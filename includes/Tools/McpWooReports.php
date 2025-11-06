<?php
declare(strict_types=1);

namespace McpForWoo\Tools;

use McpForWoo\Core\RegisterMcpTool;

/**
 * Class McpWooReports
 *
 * Provides WooCommerce reports for sales, stock, and customers.
 */
class McpWooReports {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'mcpfowo_init', array( $this, 'register_tools' ) );
	}

	/**
	 * Register report tools
	 */
	public function register_tools(): void {
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		// Sales Report
		new RegisterMcpTool(
			array(
				'name'        => 'wc_get_sales_report',
				'description' => __(  'Get detailed WooCommerce sales report with totals, order counts, average order value, and period breakdown.', 'mcp-for-woocommerce' ),
				'type'        => 'read',
				'callback'    => array( $this, 'get_sales_report' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'         => 'Get Sales Report',
					'readOnlyHint'  => true,
					'openWorldHint' => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'period' => array(
							'type'        => 'string',
							'enum'        => array( 'today', 'yesterday', 'last_7_days', 'last_30_days', 'last_90_days', 'last_year', 'custom' ),
							'description' => __(  'Time period for the report', 'mcp-for-woocommerce' ),
							'default'     => 'last_30_days',
						),
						'date_from' => array(
							'type'        => 'string',
							'format'      => 'date',
							'description' => __(  'Start date (required if period is "custom")', 'mcp-for-woocommerce' ),
						),
						'date_to' => array(
							'type'        => 'string',
							'format'      => 'date',
							'description' => __(  'End date (required if period is "custom")', 'mcp-for-woocommerce' ),
						),
						'status' => array(
							'type'        => 'array',
							'description' => __(  'Order statuses to include', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'string' ),
							'default'     => array( 'completed', 'processing' ),
						),
					),
				),
			)
		);

		// Stock Report
		new RegisterMcpTool(
			array(
				'name'        => 'wc_get_stock_report',
				'description' => __(  'Get WooCommerce stock status report with low stock alerts, out of stock items, and stock levels.', 'mcp-for-woocommerce' ),
				'type'        => 'read',
				'callback'    => array( $this, 'get_stock_report' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'         => 'Get Stock Report',
					'readOnlyHint'  => true,
					'openWorldHint' => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'stock_status' => array(
							'type'        => 'string',
							'enum'        => array( 'all', 'instock', 'outofstock', 'onbackorder', 'low_stock' ),
							'description' => __(  'Filter by stock status', 'mcp-for-woocommerce' ),
							'default'     => 'all',
						),
						'low_stock_threshold' => array(
							'type'        => 'integer',
							'description' => __(  'Threshold for low stock alert', 'mcp-for-woocommerce' ),
							'default'     => 10,
						),
					),
				),
			)
		);

		// Customer Report
		new RegisterMcpTool(
			array(
				'name'        => 'wc_get_customer_report',
				'description' => __(  'Get WooCommerce customer report with purchase statistics, lifetime value, and order history.', 'mcp-for-woocommerce' ),
				'type'        => 'read',
				'callback'    => array( $this, 'get_customer_report' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'         => 'Get Customer Report',
					'readOnlyHint'  => true,
					'openWorldHint' => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'customer_id' => array(
							'type'        => 'integer',
							'description' => __(  'Specific customer ID (optional, for single customer report)', 'mcp-for-woocommerce' ),
						),
						'limit' => array(
							'type'        => 'integer',
							'description' => __(  'Number of customers to include in report', 'mcp-for-woocommerce' ),
							'default'     => 100,
						),
						'orderby' => array(
							'type'        => 'string',
							'enum'        => array( 'total_spent', 'order_count', 'last_order_date' ),
							'description' => __(  'Order customers by', 'mcp-for-woocommerce' ),
							'default'     => 'total_spent',
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
	 * Get sales report
	 *
	 * @param array $data Report parameters.
	 * @return array Report data.
	 */
	public function get_sales_report( array $data ): array {
		try {
			$period = $data['period'] ?? 'last_30_days';
			$status = $data['status'] ?? array( 'completed', 'processing' );

			// Calculate date range
			$date_range = $this->get_date_range( $period, $data['date_from'] ?? null, $data['date_to'] ?? null );

			// Get orders
			$orders = wc_get_orders(
				array(
					'limit'        => -1,
					'status'       => $status,
					'date_created' => $date_range['from'] . '...' . $date_range['to'],
					'return'       => 'objects',
				)
			);

			$total_sales = 0;
			$total_orders = 0;
			$total_items = 0;
			$refunds = 0;

			foreach ( $orders as $order ) {
				$total_sales += $order->get_total();
				$total_orders++;
				$total_items += $order->get_item_count();

				// Calculate refunds
				$refund_total = $order->get_total_refunded();
				if ( $refund_total > 0 ) {
					$refunds += $refund_total;
				}
			}

			$net_sales = $total_sales - $refunds;
			$average_order_value = $total_orders > 0 ? $net_sales / $total_orders : 0;

			// Get top products
			$top_products = $this->get_top_products( $date_range, $status );

			return array(
				'success' => true,
				'period' => $period,
				'date_range' => array(
					'from' => $date_range['from'],
					'to' => $date_range['to'],
				),
				'totals' => array(
					'total_sales' => number_format( $total_sales, 2, '.', '' ),
					'net_sales' => number_format( $net_sales, 2, '.', '' ),
					'refunds' => number_format( $refunds, 2, '.', '' ),
					'total_orders' => $total_orders,
					'total_items' => $total_items,
					'average_order_value' => number_format( $average_order_value, 2, '.', '' ),
				),
				'top_products' => $top_products,
				'message' => sprintf( 'Sales report generated for period: %s', $period ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'sales_report_exception',
			);
		}
	}

	/**
	 * Get stock report
	 *
	 * @param array $data Report parameters.
	 * @return array Report data.
	 */
	public function get_stock_report( array $data ): array {
		try {
			$stock_status_filter = $data['stock_status'] ?? 'all';
			$low_stock_threshold = intval( $data['low_stock_threshold'] ?? 10 );

			$args = array(
				'limit'  => -1,
				'status' => 'publish',
				'return' => 'objects',
			);

			if ( 'all' !== $stock_status_filter && 'low_stock' !== $stock_status_filter ) {
				$args['stock_status'] = $stock_status_filter;
			}

			$products = wc_get_products( $args );

			$report = array(
				'instock' => array(),
				'outofstock' => array(),
				'onbackorder' => array(),
				'low_stock' => array(),
				'totals' => array(
					'total_products' => 0,
					'instock_count' => 0,
					'outofstock_count' => 0,
					'onbackorder_count' => 0,
					'low_stock_count' => 0,
				),
			);

			foreach ( $products as $product ) {
				$stock_status = $product->get_stock_status();
				$stock_quantity = $product->get_stock_quantity();
				$manage_stock = $product->get_manage_stock();

				$product_data = array(
					'id' => $product->get_id(),
					'name' => $product->get_name(),
					'sku' => $product->get_sku(),
					'stock_status' => $stock_status,
					'stock_quantity' => $manage_stock ? $stock_quantity : null,
					'price' => $product->get_price(),
				);

				$report['totals']['total_products']++;

				if ( 'instock' === $stock_status ) {
					$report['instock'][] = $product_data;
					$report['totals']['instock_count']++;

					if ( $manage_stock && $stock_quantity !== null && $stock_quantity <= $low_stock_threshold ) {
						$report['low_stock'][] = $product_data;
						$report['totals']['low_stock_count']++;
					}
				} elseif ( 'outofstock' === $stock_status ) {
					$report['outofstock'][] = $product_data;
					$report['totals']['outofstock_count']++;
				} elseif ( 'onbackorder' === $stock_status ) {
					$report['onbackorder'][] = $product_data;
					$report['totals']['onbackorder_count']++;
				}
			}

			// Filter by stock_status if specified
			if ( 'low_stock' === $stock_status_filter ) {
				$report['instock'] = array();
				$report['outofstock'] = array();
				$report['onbackorder'] = array();
			} elseif ( 'instock' === $stock_status_filter ) {
				$report['outofstock'] = array();
				$report['onbackorder'] = array();
				$report['low_stock'] = array();
			} elseif ( 'outofstock' === $stock_status_filter ) {
				$report['instock'] = array();
				$report['onbackorder'] = array();
				$report['low_stock'] = array();
			} elseif ( 'onbackorder' === $stock_status_filter ) {
				$report['instock'] = array();
				$report['outofstock'] = array();
				$report['low_stock'] = array();
			}

			return array(
				'success' => true,
				'stock_status_filter' => $stock_status_filter,
				'low_stock_threshold' => $low_stock_threshold,
				'report' => $report,
				'message' => sprintf( 'Stock report generated with %d products', $report['totals']['total_products'] ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'stock_report_exception',
			);
		}
	}

	/**
	 * Get customer report
	 *
	 * @param array $data Report parameters.
	 * @return array Report data.
	 */
	public function get_customer_report( array $data ): array {
		try {
			if ( ! empty( $data['customer_id'] ) ) {
				return $this->get_single_customer_report( intval( $data['customer_id'] ) );
			}

			$limit = intval( $data['limit'] ?? 100 );
			$orderby = $data['orderby'] ?? 'total_spent';

			// Get all customers with orders
			$customers_query = new \WP_User_Query(
				array(
					'role' => 'customer',
					'number' => $limit,
					'orderby' => 'registered',
					'order' => 'DESC',
				)
			);

			$customers_data = array();
			$customers = $customers_query->get_results();

			foreach ( $customers as $customer ) {
				$customer_id = $customer->ID;
				$customer_data = $this->get_customer_statistics( $customer_id );

				if ( $customer_data['order_count'] > 0 ) {
					$customers_data[] = $customer_data;
				}
			}

			// Sort by orderby parameter
			usort(
				$customers_data,
				function( $a, $b ) use ( $orderby ) {
					switch ( $orderby ) {
						case 'total_spent':
							return $b['total_spent'] <=> $a['total_spent'];
						case 'order_count':
							return $b['order_count'] <=> $a['order_count'];
						case 'last_order_date':
							return strtotime( $b['last_order_date'] ?? '' ) <=> strtotime( $a['last_order_date'] ?? '' );
						default:
							return 0;
					}
				}
			);

			// Limit results
			$customers_data = array_slice( $customers_data, 0, $limit );

			// Calculate totals
			$total_customers = count( $customers_data );
			$total_revenue = array_sum( array_column( $customers_data, 'total_spent' ) );
			$total_orders = array_sum( array_column( $customers_data, 'order_count' ) );
			$average_order_value = $total_orders > 0 ? $total_revenue / $total_orders : 0;

			return array(
				'success' => true,
				'totals' => array(
					'total_customers' => $total_customers,
					'total_revenue' => number_format( $total_revenue, 2, '.', '' ),
					'total_orders' => $total_orders,
					'average_order_value' => number_format( $average_order_value, 2, '.', '' ),
				),
				'customers' => $customers_data,
				'message' => sprintf( 'Customer report generated for %d customers', $total_customers ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'customer_report_exception',
			);
		}
	}

	/**
	 * Get single customer report
	 *
	 * @param int $customer_id Customer ID.
	 * @return array Customer report data.
	 */
	private function get_single_customer_report( int $customer_id ): array {
		$customer = new \WC_Customer( $customer_id );

		if ( ! $customer->get_id() ) {
			return array(
				'success' => false,
				'error'   => 'Customer not found',
				'code'    => 'customer_not_found',
			);
		}

		$customer_data = $this->get_customer_statistics( $customer_id );

		// Get order history
		$orders = wc_get_orders(
			array(
				'customer_id' => $customer_id,
				'limit'      => -1,
				'orderby'    => 'date',
				'order'      => 'DESC',
				'return'     => 'ids',
			)
		);

		$order_history = array();
		foreach ( $orders as $order_id ) {
			$order = wc_get_order( $order_id );
			if ( $order ) {
				$order_history[] = array(
					'order_id' => $order_id,
					'order_number' => $order->get_order_number(),
					'date' => $order->get_date_created()->date( 'Y-m-d H:i:s' ),
					'status' => $order->get_status(),
					'total' => $order->get_total(),
					'item_count' => $order->get_item_count(),
				);
			}
		}

		$customer_data['order_history'] = $order_history;

		return array(
			'success' => true,
			'customer' => $customer_data,
			'message' => sprintf( 'Customer report generated for customer ID %d', $customer_id ),
		);
	}

	/**
	 * Get customer statistics
	 *
	 * @param int $customer_id Customer ID.
	 * @return array Customer statistics.
	 */
	private function get_customer_statistics( int $customer_id ): array {
		$customer = new \WC_Customer( $customer_id );

		$orders = wc_get_orders(
			array(
				'customer_id' => $customer_id,
				'limit'      => -1,
				'status'     => array( 'wc-completed', 'wc-processing' ),
				'return'     => 'objects',
			)
		);

		$total_spent = 0;
		$order_count = count( $orders );
		$last_order_date = null;

		foreach ( $orders as $order ) {
			$total_spent += $order->get_total();
			$order_date = $order->get_date_created();
			if ( ! $last_order_date || $order_date > $last_order_date ) {
				$last_order_date = $order_date;
			}
		}

		return array(
			'customer_id' => $customer_id,
			'email' => $customer->get_email(),
			'first_name' => $customer->get_first_name(),
			'last_name' => $customer->get_last_name(),
			'order_count' => $order_count,
			'total_spent' => floatval( $total_spent ),
			'average_order_value' => $order_count > 0 ? floatval( $total_spent / $order_count ) : 0,
			'last_order_date' => $last_order_date ? $last_order_date->date( 'Y-m-d H:i:s' ) : null,
		);
	}

	/**
	 * Get date range from period
	 *
	 * @param string $period Period string.
	 * @param string|null $date_from Custom start date.
	 * @param string|null $date_to Custom end date.
	 * @return array Date range.
	 */
	private function get_date_range( string $period, ?string $date_from = null, ?string $date_to = null ): array {
		$now = current_time( 'timestamp' );
		$to = date( 'Y-m-d', $now );

		switch ( $period ) {
			case 'today':
				$from = date( 'Y-m-d', $now );
				break;
			case 'yesterday':
				$from = date( 'Y-m-d', strtotime( '-1 day', $now ) );
				$to = $from;
				break;
			case 'last_7_days':
				$from = date( 'Y-m-d', strtotime( '-7 days', $now ) );
				break;
			case 'last_30_days':
				$from = date( 'Y-m-d', strtotime( '-30 days', $now ) );
				break;
			case 'last_90_days':
				$from = date( 'Y-m-d', strtotime( '-90 days', $now ) );
				break;
			case 'last_year':
				$from = date( 'Y-m-d', strtotime( '-1 year', $now ) );
				break;
			case 'custom':
				$from = $date_from ? date( 'Y-m-d', strtotime( $date_from ) ) : date( 'Y-m-d', strtotime( '-30 days', $now ) );
				$to = $date_to ? date( 'Y-m-d', strtotime( $date_to ) ) : $to;
				break;
			default:
				$from = date( 'Y-m-d', strtotime( '-30 days', $now ) );
		}

		return array(
			'from' => $from,
			'to' => $to,
		);
	}

	/**
	 * Get top products for period
	 *
	 * @param array $date_range Date range.
	 * @param array $status Order statuses.
	 * @return array Top products.
	 */
	private function get_top_products( array $date_range, array $status ): array {
		$orders = wc_get_orders(
			array(
				'limit'        => -1,
				'status'       => $status,
				'date_created' => $date_range['from'] . '...' . $date_range['to'],
				'return'       => 'objects',
			)
		);

		$products_sold = array();

		foreach ( $orders as $order ) {
			foreach ( $order->get_items() as $item ) {
				$product_id = $item->get_product_id();
				if ( ! isset( $products_sold[ $product_id ] ) ) {
					$product = wc_get_product( $product_id );
					$products_sold[ $product_id ] = array(
						'product_id' => $product_id,
						'name' => $product ? $product->get_name() : 'Unknown',
						'quantity' => 0,
						'revenue' => 0,
					);
				}
				$products_sold[ $product_id ]['quantity'] += $item->get_quantity();
				$products_sold[ $product_id ]['revenue'] += $item->get_total();
			}
		}

		// Sort by revenue
		usort(
			$products_sold,
			function( $a, $b ) {
				return $b['revenue'] <=> $a['revenue'];
			}
		);

		return array_slice( $products_sold, 0, 10 );
	}
}

