<?php
declare(strict_types=1);

namespace McpForWoo\Tools\Write;

use McpForWoo\Core\RegisterMcpTool;
use WC_Product_Simple;

/**
 * Class McpWooImportExport
 *
 * Provides WooCommerce import and export operations for products and orders.
 */
class McpWooImportExport {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'mcpfowo_init', array( $this, 'register_tools' ) );
	}

	/**
	 * Register import/export tools
	 */
	public function register_tools(): void {
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		$settings = get_option( 'mcpfowo_settings', array() );
		if ( empty( $settings['enable_write_operations'] ) ) {
			return;
		}

		// Import Products CSV
		new RegisterMcpTool(
			array(
				'name'        => 'wc_import_products_csv',
				'description' => __(  'Import WooCommerce products from CSV file. CSV should contain product data (name, SKU, price, stock, etc.).', 'mcp-for-woocommerce' ),
				'type'        => 'action',
				'callback'    => array( $this, 'import_products_csv' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Import Products CSV',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'csv_data' => array(
							'type'        => 'string',
							'description' => __(  'CSV content as string (required)', 'mcp-for-woocommerce' ),
						),
						'has_headers' => array(
							'type'        => 'boolean',
							'description' => __(  'Whether CSV has header row', 'mcp-for-woocommerce' ),
							'default'     => true,
						),
					),
					'required' => array( 'csv_data' ),
				),
			)
		);

		// Export Products CSV
		new RegisterMcpTool(
			array(
				'name'        => 'wc_export_products_csv',
				'description' => __(  'Export WooCommerce products to CSV format. Returns CSV content as string.', 'mcp-for-woocommerce' ),
				'type'        => 'action',
				'callback'    => array( $this, 'export_products_csv' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Export Products CSV',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'product_ids' => array(
							'type'        => 'array',
							'description' => __(  'Array of product IDs to export (optional, empty for all)', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'integer' ),
						),
					),
				),
			)
		);

		// Import Orders CSV
		new RegisterMcpTool(
			array(
				'name'        => 'wc_import_orders_csv',
				'description' => __(  'Import WooCommerce orders from CSV file. CSV should contain order data (customer, products, amounts, etc.).', 'mcp-for-woocommerce' ),
				'type'        => 'action',
				'callback'    => array( $this, 'import_orders_csv' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Import Orders CSV',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'csv_data' => array(
							'type'        => 'string',
							'description' => __(  'CSV content as string (required)', 'mcp-for-woocommerce' ),
						),
						'has_headers' => array(
							'type'        => 'boolean',
							'description' => __(  'Whether CSV has header row', 'mcp-for-woocommerce' ),
							'default'     => true,
						),
					),
					'required' => array( 'csv_data' ),
				),
			)
		);

		// Export Orders CSV
		new RegisterMcpTool(
			array(
				'name'        => 'wc_export_orders_csv',
				'description' => __(  'Export WooCommerce orders to CSV format. Returns CSV content as string.', 'mcp-for-woocommerce' ),
				'type'        => 'action',
				'callback'    => array( $this, 'export_orders_csv' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Export Orders CSV',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'order_ids' => array(
							'type'        => 'array',
							'description' => __(  'Array of order IDs to export (optional, empty for all)', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'integer' ),
						),
					),
				),
			)
		);
	}

	/**
	 * Check if user has permission to manage WooCommerce
	 *
	 * @return bool
	 */
	public function check_manage_woocommerce_permission(): bool {
		return current_user_can( 'manage_woocommerce' ) || current_user_can( 'manage_options' );
	}

	/**
	 * Import products from CSV
	 *
	 * @param array $data Import data.
	 * @return array Response data.
	 */
	public function import_products_csv( array $data ): array {
		try {
			if ( empty( $data['csv_data'] ) ) {
				return array(
					'success' => false,
					'error'   => 'CSV data is required',
					'code'    => 'missing_csv_data',
				);
			}

			$has_headers = $data['has_headers'] ?? true;
			$csv_lines = explode( "\n", $data['csv_data'] );
			$csv_lines = array_filter( $csv_lines, 'trim' );

			if ( empty( $csv_lines ) ) {
				return array(
					'success' => false,
					'error'   => 'CSV file is empty',
					'code'    => 'empty_csv',
				);
			}

			$headers = null;
			if ( $has_headers ) {
				$headers = str_getcsv( array_shift( $csv_lines ) );
			}

			$results = array(
				'success' => true,
				'imported' => 0,
				'failed' => 0,
				'product_ids' => array(),
				'messages' => array(),
			);

			foreach ( $csv_lines as $line_index => $line ) {
				$row = str_getcsv( $line );
				if ( empty( $row ) ) {
					continue;
				}

				$product_data = array();
				if ( $headers ) {
					foreach ( $headers as $index => $header ) {
						$header = strtolower( trim( $header ) );
						if ( isset( $row[ $index ] ) ) {
							$product_data[ $header ] = trim( $row[ $index ] );
						}
					}
				} else {
					// Assume standard order: name, sku, price, stock, description
					$product_data['name'] = $row[0] ?? '';
					$product_data['sku'] = $row[1] ?? '';
					$product_data['regular_price'] = $row[2] ?? '';
					$product_data['stock_quantity'] = $row[3] ?? '';
					$product_data['description'] = $row[4] ?? '';
				}

				if ( empty( $product_data['name'] ) ) {
					$results['failed']++;
					$results['messages'][] = sprintf( 'Row %d: Missing product name', $line_index + 1 );
					continue;
				}

				// Create product
				$product = new WC_Product_Simple();
				$product->set_name( sanitize_text_field( $product_data['name'] ) );
				$product->set_status( sanitize_text_field( $product_data['status'] ?? 'publish' ) );

				if ( ! empty( $product_data['sku'] ) ) {
					$product->set_sku( sanitize_text_field( $product_data['sku'] ) );
				}

				if ( ! empty( $product_data['regular_price'] ) ) {
					$product->set_regular_price( sanitize_text_field( $product_data['regular_price'] ) );
				}

				if ( ! empty( $product_data['sale_price'] ) ) {
					$product->set_sale_price( sanitize_text_field( $product_data['sale_price'] ) );
				}

				if ( ! empty( $product_data['description'] ) ) {
					$product->set_description( sanitize_textarea_field( $product_data['description'] ) );
				}

				if ( ! empty( $product_data['short_description'] ) ) {
					$product->set_short_description( sanitize_textarea_field( $product_data['short_description'] ) );
				}

				if ( isset( $product_data['stock_quantity'] ) && $product_data['stock_quantity'] !== '' ) {
					$product->set_manage_stock( true );
					$product->set_stock_quantity( intval( $product_data['stock_quantity'] ) );
				}

				if ( ! empty( $product_data['stock_status'] ) ) {
					$product->set_stock_status( sanitize_text_field( $product_data['stock_status'] ) );
				}

				$product_id = $product->save();

				if ( $product_id ) {
					$results['imported']++;
					$results['product_ids'][] = $product_id;
					$results['messages'][] = sprintf( 'Row %d: Product "%s" imported (ID: %d)', $line_index + 1, $product_data['name'], $product_id );
				} else {
					$results['failed']++;
					$results['messages'][] = sprintf( 'Row %d: Failed to import product "%s"', $line_index + 1, $product_data['name'] );
				}
			}

			do_action( 'mcpfowo_products_imported', $results );

			return $results;

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'import_exception',
			);
		}
	}

	/**
	 * Export products to CSV
	 *
	 * @param array $data Export data.
	 * @return array Response data with CSV content.
	 */
	public function export_products_csv( array $data ): array {
		try {
			$product_ids = $data['product_ids'] ?? array();

			if ( empty( $product_ids ) ) {
				// Get all products
				$args = array(
					'limit'  => -1,
					'status' => 'any',
					'return' => 'ids',
				);
				$product_ids = wc_get_products( $args );
			}

			$headers = array(
				'ID',
				'Name',
				'SKU',
				'Regular Price',
				'Sale Price',
				'Stock Quantity',
				'Stock Status',
				'Description',
				'Short Description',
				'Status',
			);

			$csv_rows = array();
			$csv_rows[] = $headers;

			foreach ( $product_ids as $product_id ) {
				$product = wc_get_product( $product_id );
				if ( ! $product ) {
					continue;
				}

				$row = array(
					$product->get_id(),
					$product->get_name(),
					$product->get_sku(),
					$product->get_regular_price(),
					$product->get_sale_price(),
					$product->get_stock_quantity(),
					$product->get_stock_status(),
					$product->get_description(),
					$product->get_short_description(),
					$product->get_status(),
				);

				$csv_rows[] = $row;
			}

			// Convert to CSV string
			$csv_content = '';
			foreach ( $csv_rows as $row ) {
				$csv_content .= $this->array_to_csv_line( $row ) . "\n";
			}

			return array(
				'success' => true,
				'csv_content' => $csv_content,
				'products_count' => count( $product_ids ),
				'message' => sprintf( 'Exported %d products to CSV', count( $product_ids ) ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'export_exception',
			);
		}
	}

	/**
	 * Import orders from CSV
	 *
	 * @param array $data Import data.
	 * @return array Response data.
	 */
	public function import_orders_csv( array $data ): array {
		try {
			if ( empty( $data['csv_data'] ) ) {
				return array(
					'success' => false,
					'error'   => 'CSV data is required',
					'code'    => 'missing_csv_data',
				);
			}

			$has_headers = $data['has_headers'] ?? true;
			$csv_lines = explode( "\n", $data['csv_data'] );
			$csv_lines = array_filter( $csv_lines, 'trim' );

			if ( empty( $csv_lines ) ) {
				return array(
					'success' => false,
					'error'   => 'CSV file is empty',
					'code'    => 'empty_csv',
				);
			}

			$headers = null;
			if ( $has_headers ) {
				$headers = str_getcsv( array_shift( $csv_lines ) );
			}

			$results = array(
				'success' => true,
				'imported' => 0,
				'failed' => 0,
				'order_ids' => array(),
				'messages' => array(),
			);

			foreach ( $csv_lines as $line_index => $line ) {
				$row = str_getcsv( $line );
				if ( empty( $row ) ) {
					continue;
				}

				$order_data = array();
				if ( $headers ) {
					foreach ( $headers as $index => $header ) {
						$header = strtolower( trim( $header ) );
						if ( isset( $row[ $index ] ) ) {
							$order_data[ $header ] = trim( $row[ $index ] );
						}
					}
				} else {
					// Assume standard order: customer_id, product_id, quantity, status
					$order_data['customer_id'] = $row[0] ?? '';
					$order_data['product_id'] = $row[1] ?? '';
					$order_data['quantity'] = $row[2] ?? 1;
					$order_data['status'] = $row[3] ?? 'pending';
				}

				if ( empty( $order_data['product_id'] ) ) {
					$results['failed']++;
					$results['messages'][] = sprintf( 'Row %d: Missing product ID', $line_index + 1 );
					continue;
				}

				// Create order
				$order = wc_create_order();

				if ( ! empty( $order_data['customer_id'] ) ) {
					$order->set_customer_id( intval( $order_data['customer_id'] ) );
				}

				$product = wc_get_product( intval( $order_data['product_id'] ) );
				if ( $product ) {
					$quantity = intval( $order_data['quantity'] ?? 1 );
					$order->add_product( $product, $quantity );
				}

				$order->set_status( sanitize_text_field( $order_data['status'] ?? 'pending' ) );
				$order->calculate_totals();
				$order->save();

				$order_id = $order->get_id();

				if ( $order_id ) {
					$results['imported']++;
					$results['order_ids'][] = $order_id;
					$results['messages'][] = sprintf( 'Row %d: Order #%d imported', $line_index + 1, $order_id );
				} else {
					$results['failed']++;
					$results['messages'][] = sprintf( 'Row %d: Failed to import order', $line_index + 1 );
				}
			}

			do_action( 'mcpfowo_orders_imported', $results );

			return $results;

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'import_exception',
			);
		}
	}

	/**
	 * Export orders to CSV
	 *
	 * @param array $data Export data.
	 * @return array Response data with CSV content.
	 */
	public function export_orders_csv( array $data ): array {
		try {
			$order_ids = $data['order_ids'] ?? array();

			if ( empty( $order_ids ) ) {
				// Get all orders
				$args = array(
					'limit'  => -1,
					'status' => 'any',
					'return' => 'ids',
				);
				$order_ids = wc_get_orders( $args );
			}

			$headers = array(
				'ID',
				'Order Number',
				'Customer ID',
				'Customer Email',
				'Status',
				'Total',
				'Date Created',
				'Payment Method',
			);

			$csv_rows = array();
			$csv_rows[] = $headers;

			foreach ( $order_ids as $order_id ) {
				$order = wc_get_order( $order_id );
				if ( ! $order ) {
					continue;
				}

				$row = array(
					$order->get_id(),
					$order->get_order_number(),
					$order->get_customer_id(),
					$order->get_billing_email(),
					$order->get_status(),
					$order->get_total(),
					$order->get_date_created()->date( 'Y-m-d H:i:s' ),
					$order->get_payment_method(),
				);

				$csv_rows[] = $row;
			}

			// Convert to CSV string
			$csv_content = '';
			foreach ( $csv_rows as $row ) {
				$csv_content .= $this->array_to_csv_line( $row ) . "\n";
			}

			return array(
				'success' => true,
				'csv_content' => $csv_content,
				'orders_count' => count( $order_ids ),
				'message' => sprintf( 'Exported %d orders to CSV', count( $order_ids ) ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'export_exception',
			);
		}
	}

	/**
	 * Convert array to CSV line
	 *
	 * @param array $row Data row.
	 * @return string CSV line.
	 */
	private function array_to_csv_line( array $row ): string {
		$output = fopen( 'php://temp', 'r+' );
		fputcsv( $output, $row );
		rewind( $output );
		$csv_line = stream_get_contents( $output );
		fclose( $output );
		return trim( $csv_line );
	}
}

