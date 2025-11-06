<?php
declare(strict_types=1);

namespace McpForWoo\Tools\Write;

use McpForWoo\Core\RegisterMcpTool;
use WC_Product_Simple;

/**
 * Class McpWooBulkOperations
 *
 * Provides WooCommerce bulk operations for products.
 */
class McpWooBulkOperations {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'mcpfowo_init', array( $this, 'register_tools' ) );
	}

	/**
	 * Register bulk operation tools
	 */
	public function register_tools(): void {
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		$settings = get_option( 'mcpfowo_settings', array() );
		if ( empty( $settings['enable_write_operations'] ) ) {
			return;
		}

		// Bulk Create Products
		new RegisterMcpTool(
			array(
				'name'        => 'wc_bulk_create_products',
				'description' => __(  'Create multiple WooCommerce products at once. Accepts array of product data.', 'mcp-for-woocommerce' ),
				'type'        => 'action',
				'callback'    => array( $this, 'bulk_create_products' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Bulk Create Products',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'products' => array(
							'type'        => 'array',
							'description' => __(  'Array of product data objects (required)', 'mcp-for-woocommerce' ),
							'items'       => array(
								'type'       => 'object',
								'properties' => array(
									'name' => array( 'type' => 'string', 'description' => __( 'Product name (required)', 'mcp-for-woocommerce' ) ),
									'type' => array( 'type' => 'string', 'enum' => array( 'simple', 'variable' ), 'default' => 'simple' ),
									'status' => array( 'type' => 'string', 'enum' => array( 'draft', 'publish', 'pending' ), 'default' => 'publish' ),
									'description' => array( 'type' => 'string' ),
									'short_description' => array( 'type' => 'string' ),
									'sku' => array( 'type' => 'string' ),
									'regular_price' => array( 'type' => 'string' ),
									'sale_price' => array( 'type' => 'string' ),
									'manage_stock' => array( 'type' => 'boolean', 'default' => false ),
									'stock_quantity' => array( 'type' => 'integer' ),
									'stock_status' => array( 'type' => 'string', 'enum' => array( 'instock', 'outofstock', 'onbackorder' ) ),
									'categories' => array( 'type' => 'array', 'items' => array( 'type' => 'integer' ) ),
									'tags' => array( 'type' => 'array', 'items' => array( 'type' => 'integer' ) ),
								),
								'required' => array( 'name' ),
							),
						),
					),
					'required' => array( 'products' ),
				),
			)
		);

		// Bulk Delete Products
		new RegisterMcpTool(
			array(
				'name'        => 'wc_bulk_delete_products',
				'description' => __(  'Delete multiple WooCommerce products at once. DESTRUCTIVE OPERATION.', 'mcp-for-woocommerce' ),
				'type'        => 'action',
				'callback'    => array( $this, 'bulk_delete_products' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Bulk Delete Products',
					'readOnlyHint'    => false,
					'destructiveHint' => true,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'product_ids' => array(
							'type'        => 'array',
							'description' => __(  'Array of product IDs to delete (required)', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'integer' ),
						),
						'force' => array(
							'type'        => 'boolean',
							'description' => __(  'Whether to force delete permanently', 'mcp-for-woocommerce' ),
							'default'     => false,
						),
					),
					'required' => array( 'product_ids' ),
				),
			)
		);

		// Bulk Update Prices
		new RegisterMcpTool(
			array(
				'name'        => 'wc_bulk_update_prices',
				'description' => __(  'Update prices for multiple products at once. Supports percentage or fixed amount changes.', 'mcp-for-woocommerce' ),
				'type'        => 'action',
				'callback'    => array( $this, 'bulk_update_prices' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Bulk Update Prices',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'product_ids' => array(
							'type'        => 'array',
							'description' => __(  'Array of product IDs to update (required)', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'integer' ),
						),
						'update_type' => array(
							'type'        => 'string',
							'enum'        => array( 'percentage', 'fixed', 'set' ),
							'description' => __(  'Type of price update: percentage (increase/decrease by %), fixed (add/subtract amount), or set (set exact price)', 'mcp-for-woocommerce' ),
							'default'     => 'set',
						),
						'regular_price' => array(
							'type'        => 'string',
							'description' => __(  'New price (for set), amount to add/subtract (for fixed), or percentage (for percentage, e.g., "10" for +10%, "-5" for -5%)', 'mcp-for-woocommerce' ),
						),
						'sale_price' => array(
							'type'        => 'string',
							'description' => __(  'New sale price (for set), amount to add/subtract (for fixed), or percentage (for percentage)', 'mcp-for-woocommerce' ),
						),
					),
					'required' => array( 'product_ids' ),
				),
			)
		);

		// Bulk Update Stock
		new RegisterMcpTool(
			array(
				'name'        => 'wc_bulk_update_stock',
				'description' => __(  'Update stock quantities or stock status for multiple products at once.', 'mcp-for-woocommerce' ),
				'type'        => 'action',
				'callback'    => array( $this, 'bulk_update_stock' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Bulk Update Stock',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'product_ids' => array(
							'type'        => 'array',
							'description' => __(  'Array of product IDs to update (required)', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'integer' ),
						),
						'stock_quantity' => array(
							'type'        => 'integer',
							'description' => __(  'Stock quantity to set (or null to only update status)', 'mcp-for-woocommerce' ),
						),
						'stock_status' => array(
							'type'        => 'string',
							'enum'        => array( 'instock', 'outofstock', 'onbackorder' ),
							'description' => __(  'Stock status to set', 'mcp-for-woocommerce' ),
						),
						'manage_stock' => array(
							'type'        => 'boolean',
							'description' => __(  'Whether to enable stock management', 'mcp-for-woocommerce' ),
						),
					),
					'required' => array( 'product_ids' ),
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
	 * Bulk create products
	 *
	 * @param array $data Bulk create data.
	 * @return array Response data.
	 */
	public function bulk_create_products( array $data ): array {
		try {
			if ( empty( $data['products'] ) || ! is_array( $data['products'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Products array is required',
					'code'    => 'missing_products_array',
				);
			}

			$results = array(
				'success'  => true,
				'created' => 0,
				'failed'  => 0,
				'product_ids' => array(),
				'messages' => array(),
			);

			foreach ( $data['products'] as $product_data ) {
				if ( empty( $product_data['name'] ) ) {
					$results['failed']++;
					$results['messages'][] = 'Skipped product without name';
					continue;
				}

				$product = new WC_Product_Simple();
				$product->set_name( $product_data['name'] );
				$product->set_status( $product_data['status'] ?? 'publish' );

				if ( ! empty( $product_data['description'] ) ) {
					$product->set_description( $product_data['description'] );
				}

				if ( ! empty( $product_data['short_description'] ) ) {
					$product->set_short_description( $product_data['short_description'] );
				}

				if ( ! empty( $product_data['sku'] ) ) {
					$product->set_sku( $product_data['sku'] );
				}

				if ( isset( $product_data['regular_price'] ) ) {
					$product->set_regular_price( $product_data['regular_price'] );
				}

				if ( isset( $product_data['sale_price'] ) ) {
					$product->set_sale_price( $product_data['sale_price'] );
				}

				if ( isset( $product_data['manage_stock'] ) ) {
					$product->set_manage_stock( $product_data['manage_stock'] );
				}

				if ( isset( $product_data['stock_quantity'] ) ) {
					$product->set_stock_quantity( $product_data['stock_quantity'] );
				}

				if ( isset( $product_data['stock_status'] ) ) {
					$product->set_stock_status( $product_data['stock_status'] );
				}

				if ( ! empty( $product_data['categories'] ) ) {
					$product->set_category_ids( $product_data['categories'] );
				}

				if ( ! empty( $product_data['tags'] ) ) {
					$product->set_tag_ids( $product_data['tags'] );
				}

				$product_id = $product->save();

				if ( $product_id ) {
					$results['created']++;
					$results['product_ids'][] = $product_id;
					$results['messages'][] = sprintf( 'Product "%s" created (ID: %d)', $product_data['name'], $product_id );
				} else {
					$results['failed']++;
					$results['messages'][] = sprintf( 'Failed to create product "%s"', $product_data['name'] );
				}
			}

			do_action( 'mcpfowo_products_bulk_created', $results );

			return $results;

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'bulk_create_exception',
			);
		}
	}

	/**
	 * Bulk delete products
	 *
	 * @param array $data Bulk delete data.
	 * @return array Response data.
	 */
	public function bulk_delete_products( array $data ): array {
		try {
			if ( empty( $data['product_ids'] ) || ! is_array( $data['product_ids'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Product IDs array is required',
					'code'    => 'missing_product_ids',
				);
			}

			$results = array(
				'success' => true,
				'deleted' => 0,
				'failed'  => 0,
				'messages' => array(),
			);

			$force = $data['force'] ?? false;

			foreach ( $data['product_ids'] as $product_id ) {
				$product = wc_get_product( $product_id );

				if ( ! $product ) {
					$results['failed']++;
					$results['messages'][] = sprintf( 'Product ID %d not found', $product_id );
					continue;
				}

				$product_name = $product->get_name();
				$result = $product->delete( $force );

				if ( $result ) {
					$results['deleted']++;
					$results['messages'][] = sprintf( 'Product "%s" (ID: %d) deleted', $product_name, $product_id );
				} else {
					$results['failed']++;
					$results['messages'][] = sprintf( 'Failed to delete product ID %d', $product_id );
				}
			}

			do_action( 'mcpfowo_products_bulk_deleted', $results );

			return $results;

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'bulk_delete_exception',
			);
		}
	}

	/**
	 * Bulk update prices
	 *
	 * @param array $data Bulk price update data.
	 * @return array Response data.
	 */
	public function bulk_update_prices( array $data ): array {
		try {
			if ( empty( $data['product_ids'] ) || ! is_array( $data['product_ids'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Product IDs array is required',
					'code'    => 'missing_product_ids',
				);
			}

			$update_type = $data['update_type'] ?? 'set';
			$results = array(
				'success' => true,
				'updated' => 0,
				'failed'  => 0,
				'messages' => array(),
			);

			foreach ( $data['product_ids'] as $product_id ) {
				$product = wc_get_product( $product_id );

				if ( ! $product ) {
					$results['failed']++;
					$results['messages'][] = sprintf( 'Product ID %d not found', $product_id );
					continue;
				}

				try {
					// Update regular price
					if ( isset( $data['regular_price'] ) ) {
						$new_price = $this->calculate_new_price( $product->get_regular_price(), $data['regular_price'], $update_type );
						if ( $new_price !== false ) {
							$product->set_regular_price( $new_price );
						}
					}

					// Update sale price
					if ( isset( $data['sale_price'] ) ) {
						$new_sale_price = $this->calculate_new_price( $product->get_sale_price(), $data['sale_price'], $update_type );
						if ( $new_sale_price !== false ) {
							$product->set_sale_price( $new_sale_price );
						}
					}

					$product->save();
					$results['updated']++;
					$results['messages'][] = sprintf( 'Product ID %d prices updated', $product_id );

				} catch ( \Exception $e ) {
					$results['failed']++;
					$results['messages'][] = sprintf( 'Product ID %d: %s', $product_id, $e->getMessage() );
				}
			}

			do_action( 'mcpfowo_prices_bulk_updated', $results );

			return $results;

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'bulk_price_update_exception',
			);
		}
	}

	/**
	 * Bulk update stock
	 *
	 * @param array $data Bulk stock update data.
	 * @return array Response data.
	 */
	public function bulk_update_stock( array $data ): array {
		try {
			if ( empty( $data['product_ids'] ) || ! is_array( $data['product_ids'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Product IDs array is required',
					'code'    => 'missing_product_ids',
				);
			}

			$results = array(
				'success' => true,
				'updated' => 0,
				'failed'  => 0,
				'messages' => array(),
			);

			foreach ( $data['product_ids'] as $product_id ) {
				$product = wc_get_product( $product_id );

				if ( ! $product ) {
					$results['failed']++;
					$results['messages'][] = sprintf( 'Product ID %d not found', $product_id );
					continue;
				}

				try {
					if ( isset( $data['manage_stock'] ) ) {
						$product->set_manage_stock( $data['manage_stock'] );
					}

					if ( isset( $data['stock_quantity'] ) ) {
						$product->set_stock_quantity( $data['stock_quantity'] );
					}

					if ( isset( $data['stock_status'] ) ) {
						$product->set_stock_status( $data['stock_status'] );
					}

					$product->save();
					$results['updated']++;
					$results['messages'][] = sprintf( 'Product ID %d stock updated', $product_id );

				} catch ( \Exception $e ) {
					$results['failed']++;
					$results['messages'][] = sprintf( 'Product ID %d: %s', $product_id, $e->getMessage() );
				}
			}

			do_action( 'mcpfowo_stock_bulk_updated', $results );

			return $results;

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'bulk_stock_update_exception',
			);
		}
	}

	/**
	 * Calculate new price based on update type
	 *
	 * @param string|float $current_price Current price.
	 * @param string|float $value Update value.
	 * @param string $update_type Type of update (set, fixed, percentage).
	 * @return float|false New price or false on error.
	 */
	private function calculate_new_price( $current_price, $value, string $update_type ) {
		$current = floatval( $current_price );
		$val = floatval( $value );

		switch ( $update_type ) {
			case 'set':
				return $val;

			case 'fixed':
				return max( 0, $current + $val );

			case 'percentage':
				$percentage = $val / 100;
				return max( 0, $current * ( 1 + $percentage ) );

			default:
				return false;
		}
	}
}

