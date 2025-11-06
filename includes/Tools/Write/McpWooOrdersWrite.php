<?php
declare(strict_types=1);

namespace McpForWoo\Tools\Write;

use McpForWoo\Core\RegisterMcpTool;

/**
 * Class McpWooOrdersWrite
 *
 * Provides WooCommerce write operations for orders.
 */
class McpWooOrdersWrite {

	public function __construct() {
		add_action( 'mcpfowo_init', array( $this, 'register_tools' ) );
	}

	public function register_tools(): void {
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		$settings = get_option( 'mcpfowo_settings', array() );
		if ( empty( $settings['enable_write_operations'] ) ) {
			return;
		}

		// Create Order
		new RegisterMcpTool(
			array(
				'name'        => 'wc_create_order',
				'description' => __(  'Create a new WooCommerce order with customer information, products, and payment details.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'create_order' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Create Order',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'customer_id' => array(
							'type'        => 'integer',
							'description' => __(  'Customer user ID (0 for guest)', 'mcp-for-woocommerce' ),
						),
						'status' => array(
							'type'        => 'string',
							'enum'        => array( 'pending', 'processing', 'on-hold', 'completed', 'cancelled', 'refunded', 'failed' ),
							'description' => __(  'Order status (default: pending)', 'mcp-for-woocommerce' ),
							'default'     => 'pending',
						),
						'line_items' => array(
							'type'        => 'array',
							'description' => __(  'Array of products to add to order', 'mcp-for-woocommerce' ),
							'items'       => array(
								'type'       => 'object',
								'properties' => array(
									'product_id' => array( 'type' => 'integer' ),
									'quantity'   => array( 'type' => 'integer' ),
									'variation_id' => array( 'type' => 'integer' ),
								),
								'required' => array( 'product_id', 'quantity' ),
							),
						),
						'billing' => array(
							'type'        => 'object',
							'description' => __(  'Billing address', 'mcp-for-woocommerce' ),
							'properties'  => array(
								'first_name' => array( 'type' => 'string' ),
								'last_name'  => array( 'type' => 'string' ),
								'email'      => array( 'type' => 'string' ),
								'phone'      => array( 'type' => 'string' ),
								'address_1'  => array( 'type' => 'string' ),
								'city'       => array( 'type' => 'string' ),
								'postcode'   => array( 'type' => 'string' ),
								'country'    => array( 'type' => 'string' ),
							),
						),
						'shipping' => array(
							'type'        => 'object',
							'description' => __(  'Shipping address', 'mcp-for-woocommerce' ),
							'properties'  => array(
								'first_name' => array( 'type' => 'string' ),
								'last_name'  => array( 'type' => 'string' ),
								'address_1'  => array( 'type' => 'string' ),
								'city'       => array( 'type' => 'string' ),
								'postcode'   => array( 'type' => 'string' ),
								'country'    => array( 'type' => 'string' ),
							),
						),
						'payment_method' => array(
							'type'        => 'string',
							'description' => __(  'Payment method ID', 'mcp-for-woocommerce' ),
						),
					),
					'required' => array( 'line_items' ),
				),
			)
		);

		// Update Order Status
		new RegisterMcpTool(
			array(
				'name'        => 'wc_update_order_status',
				'description' => __(  'Update WooCommerce order status. Can add order notes and trigger customer notifications.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'update_order_status' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Update Order Status',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'order_id' => array(
							'type'        => 'integer',
							'description' => __(  'Order ID (required)', 'mcp-for-woocommerce' ),
						),
						'status' => array(
							'type'        => 'string',
							'enum'        => array( 'pending', 'processing', 'on-hold', 'completed', 'cancelled', 'refunded', 'failed' ),
							'description' => __(  'New order status (required)', 'mcp-for-woocommerce' ),
						),
						'note' => array(
							'type'        => 'string',
							'description' => __(  'Optional order note to add', 'mcp-for-woocommerce' ),
						),
						'customer_note' => array(
							'type'        => 'boolean',
							'description' => __(  'Whether to notify customer (default: false)', 'mcp-for-woocommerce' ),
							'default'     => false,
						),
					),
					'required' => array( 'order_id', 'status' ),
				),
			)
		);

		// Add Order Note
		new RegisterMcpTool(
			array(
				'name'        => 'wc_add_order_note',
				'description' => __(  'Add a note to an existing WooCommerce order.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'add_order_note' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Add Order Note',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'order_id' => array(
							'type'        => 'integer',
							'description' => __(  'Order ID (required)', 'mcp-for-woocommerce' ),
						),
						'note' => array(
							'type'        => 'string',
							'description' => __(  'Note content (required)', 'mcp-for-woocommerce' ),
						),
						'is_customer_note' => array(
							'type'        => 'boolean',
							'description' => __(  'Whether note is for customer (default: false)', 'mcp-for-woocommerce' ),
							'default'     => false,
						),
					),
					'required' => array( 'order_id', 'note' ),
				),
			)
		);
	}

	public function check_manage_woocommerce_permission(): bool {
		return current_user_can( 'manage_woocommerce' ) || current_user_can( 'manage_options' );
	}

	public function create_order( array $data ): array {
		try {
			if ( empty( $data['line_items'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Line items are required',
					'code'    => 'missing_line_items',
				);
			}

			$order = wc_create_order();

			// Set customer
			if ( ! empty( $data['customer_id'] ) ) {
				$order->set_customer_id( $data['customer_id'] );
			}

			// Add line items
			foreach ( $data['line_items'] as $item ) {
				$product = wc_get_product( $item['product_id'] );
				if ( $product ) {
					$order->add_product( $product, $item['quantity'], array(
						'variation_id' => $item['variation_id'] ?? 0,
					) );
				}
			}

			// Set addresses
			if ( ! empty( $data['billing'] ) ) {
				$order->set_address( $data['billing'], 'billing' );
			}

			if ( ! empty( $data['shipping'] ) ) {
				$order->set_address( $data['shipping'], 'shipping' );
			}

			// Set payment method
			if ( ! empty( $data['payment_method'] ) ) {
				$order->set_payment_method( $data['payment_method'] );
			}

			// Set status
			$order->set_status( $data['status'] ?? 'pending' );

			// Calculate totals
			$order->calculate_totals();

			// Save order
			$order->save();

			do_action( 'mcpfowo_order_created', $order->get_id(), $data );

			return array(
				'success'  => true,
				'order_id' => $order->get_id(),
				'message'  => sprintf( 'Order #%d created successfully', $order->get_id() ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'order_creation_failed',
			);
		}
	}

	public function update_order_status( array $data ): array {
		try {
			if ( empty( $data['order_id'] ) || empty( $data['status'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Order ID and status are required',
					'code'    => 'missing_required_fields',
				);
			}

			$order = wc_get_order( $data['order_id'] );

			if ( ! $order ) {
				return array(
					'success' => false,
					'error'   => 'Order not found',
					'code'    => 'order_not_found',
				);
			}

			$old_status = $order->get_status();
			$order->update_status( $data['status'], $data['note'] ?? '', $data['customer_note'] ?? false );

			do_action( 'mcpfowo_order_status_updated', $order->get_id(), $old_status, $data['status'] );

			return array(
				'success'    => true,
				'order_id'   => $order->get_id(),
				'old_status' => $old_status,
				'new_status' => $data['status'],
				'message'    => sprintf( 'Order #%d status updated from %s to %s', $order->get_id(), $old_status, $data['status'] ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'order_status_update_failed',
			);
		}
	}

	public function add_order_note( array $data ): array {
		try {
			if ( empty( $data['order_id'] ) || empty( $data['note'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Order ID and note are required',
					'code'    => 'missing_required_fields',
				);
			}

			$order = wc_get_order( $data['order_id'] );

			if ( ! $order ) {
				return array(
					'success' => false,
					'error'   => 'Order not found',
					'code'    => 'order_not_found',
				);
			}

			$note_id = $order->add_order_note(
				$data['note'],
				$data['is_customer_note'] ?? false
			);

			do_action( 'mcpfowo_order_note_added', $order->get_id(), $note_id );

			return array(
				'success' => true,
				'order_id' => $order->get_id(),
				'note_id' => $note_id,
				'message' => 'Note added successfully',
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'note_addition_failed',
			);
		}
	}
}
