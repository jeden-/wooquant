<?php
declare(strict_types=1);

namespace McpForWoo\Tools\Write;

use McpForWoo\Core\RegisterMcpTool;
use WC_Coupon;

/**
 * Class McpWooCouponsWrite
 *
 * Provides WooCommerce write operations for coupons.
 */
class McpWooCouponsWrite {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'mcpfowo_init', array( $this, 'register_tools' ) );
	}

	/**
	 * Register coupon write tools
	 */
	public function register_tools(): void {
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		$settings = get_option( 'mcpfowo_settings', array() );
		if ( empty( $settings['enable_write_operations'] ) ) {
			return;
		}

		// Create Coupon
		new RegisterMcpTool(
			array(
				'name'        => 'wc_create_coupon',
				'description' => __(  'Create a new WooCommerce coupon with discount rules, usage limits, and restrictions.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'create_coupon' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Create Coupon',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'code' => array(
							'type'        => 'string',
							'description' => __(  'Coupon code (required)', 'mcp-for-woocommerce' ),
						),
						'discount_type' => array(
							'type'        => 'string',
							'enum'        => array( 'percent', 'fixed_cart', 'fixed_product' ),
							'description' => __(  'Type of discount', 'mcp-for-woocommerce' ),
							'default'     => 'fixed_cart',
						),
						'amount' => array(
							'type'        => 'string',
							'description' => __(  'Discount amount (required)', 'mcp-for-woocommerce' ),
						),
						'individual_use' => array(
							'type'        => 'boolean',
							'description' => __(  'If true, coupon cannot be used with other coupons', 'mcp-for-woocommerce' ),
							'default'     => false,
						),
						'product_ids' => array(
							'type'        => 'array',
							'description' => __(  'Product IDs that the coupon applies to', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'integer' ),
						),
						'excluded_product_ids' => array(
							'type'        => 'array',
							'description' => __(  'Product IDs that the coupon does not apply to', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'integer' ),
						),
						'usage_limit' => array(
							'type'        => 'integer',
							'description' => __(  'How many times the coupon can be used in total', 'mcp-for-woocommerce' ),
						),
						'usage_limit_per_user' => array(
							'type'        => 'integer',
							'description' => __(  'How many times the coupon can be used per user', 'mcp-for-woocommerce' ),
						),
						'limit_usage_to_x_items' => array(
							'type'        => 'integer',
							'description' => __(  'Max number of items in cart the coupon can apply to', 'mcp-for-woocommerce' ),
						),
						'free_shipping' => array(
							'type'        => 'boolean',
							'description' => __(  'If true, enables free shipping', 'mcp-for-woocommerce' ),
							'default'     => false,
						),
						'product_categories' => array(
							'type'        => 'array',
							'description' => __(  'Category IDs that the coupon applies to', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'integer' ),
						),
						'excluded_product_categories' => array(
							'type'        => 'array',
							'description' => __(  'Category IDs that the coupon does not apply to', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'integer' ),
						),
						'exclude_sale_items' => array(
							'type'        => 'boolean',
							'description' => __(  'If true, sale items are excluded from coupon', 'mcp-for-woocommerce' ),
							'default'     => false,
						),
						'minimum_amount' => array(
							'type'        => 'string',
							'description' => __(  'Minimum order amount required to use coupon', 'mcp-for-woocommerce' ),
						),
						'maximum_amount' => array(
							'type'        => 'string',
							'description' => __(  'Maximum order amount allowed to use coupon', 'mcp-for-woocommerce' ),
						),
						'email_restrictions' => array(
							'type'        => 'array',
							'description' => __(  'Email addresses that can use the coupon', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'string' ),
						),
						'date_expires' => array(
							'type'        => 'string',
							'description' => __(  'Coupon expiration date (YYYY-MM-DD format)', 'mcp-for-woocommerce' ),
						),
					),
					'required' => array( 'code', 'amount' ),
				),
			)
		);

		// Update Coupon
		new RegisterMcpTool(
			array(
				'name'        => 'wc_update_coupon',
				'description' => __(  'Update an existing WooCommerce coupon.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'update_coupon' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Update Coupon',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array(
							'type'        => 'integer',
							'description' => __(  'Coupon ID to update (required)', 'mcp-for-woocommerce' ),
						),
						'code' => array(
							'type'        => 'string',
							'description' => __(  'Coupon code', 'mcp-for-woocommerce' ),
						),
						'discount_type' => array(
							'type'        => 'string',
							'enum'        => array( 'percent', 'fixed_cart', 'fixed_product' ),
							'description' => __(  'Type of discount', 'mcp-for-woocommerce' ),
						),
						'amount' => array(
							'type'        => 'string',
							'description' => __(  'Discount amount', 'mcp-for-woocommerce' ),
						),
						'individual_use' => array(
							'type'        => 'boolean',
							'description' => __(  'If true, coupon cannot be used with other coupons', 'mcp-for-woocommerce' ),
						),
						'product_ids' => array(
							'type'        => 'array',
							'description' => __(  'Product IDs that the coupon applies to', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'integer' ),
						),
						'excluded_product_ids' => array(
							'type'        => 'array',
							'description' => __(  'Product IDs that the coupon does not apply to', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'integer' ),
						),
						'usage_limit' => array(
							'type'        => 'integer',
							'description' => __(  'How many times the coupon can be used in total', 'mcp-for-woocommerce' ),
						),
						'usage_limit_per_user' => array(
							'type'        => 'integer',
							'description' => __(  'How many times the coupon can be used per user', 'mcp-for-woocommerce' ),
						),
						'limit_usage_to_x_items' => array(
							'type'        => 'integer',
							'description' => __(  'Max number of items in cart the coupon can apply to', 'mcp-for-woocommerce' ),
						),
						'free_shipping' => array(
							'type'        => 'boolean',
							'description' => __(  'If true, enables free shipping', 'mcp-for-woocommerce' ),
						),
						'product_categories' => array(
							'type'        => 'array',
							'description' => __(  'Category IDs that the coupon applies to', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'integer' ),
						),
						'excluded_product_categories' => array(
							'type'        => 'array',
							'description' => __(  'Category IDs that the coupon does not apply to', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'integer' ),
						),
						'exclude_sale_items' => array(
							'type'        => 'boolean',
							'description' => __(  'If true, sale items are excluded from coupon', 'mcp-for-woocommerce' ),
						),
						'minimum_amount' => array(
							'type'        => 'string',
							'description' => __(  'Minimum order amount required to use coupon', 'mcp-for-woocommerce' ),
						),
						'maximum_amount' => array(
							'type'        => 'string',
							'description' => __(  'Maximum order amount allowed to use coupon', 'mcp-for-woocommerce' ),
						),
						'email_restrictions' => array(
							'type'        => 'array',
							'description' => __(  'Email addresses that can use the coupon', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'string' ),
						),
						'date_expires' => array(
							'type'        => 'string',
							'description' => __(  'Coupon expiration date (YYYY-MM-DD format)', 'mcp-for-woocommerce' ),
						),
					),
					'required' => array( 'id' ),
				),
			)
		);

		// Delete Coupon
		new RegisterMcpTool(
			array(
				'name'        => 'wc_delete_coupon',
				'description' => __(  'Delete a WooCommerce coupon. DESTRUCTIVE OPERATION.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'delete_coupon' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Delete Coupon',
					'readOnlyHint'    => false,
					'destructiveHint' => true,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array(
							'type'        => 'integer',
							'description' => __(  'Coupon ID to delete (required)', 'mcp-for-woocommerce' ),
						),
						'force' => array(
							'type'        => 'boolean',
							'description' => __(  'Whether to force delete permanently', 'mcp-for-woocommerce' ),
							'default'     => false,
						),
					),
					'required' => array( 'id' ),
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
	 * Create a new coupon
	 *
	 * @param array $data Coupon data.
	 * @return array Response data.
	 */
	public function create_coupon( array $data ): array {
		try {
			if ( empty( $data['code'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Coupon code is required',
					'code'    => 'missing_required_field',
				);
			}

			if ( empty( $data['amount'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Discount amount is required',
					'code'    => 'missing_required_field',
				);
			}

			$coupon = new WC_Coupon();
			$coupon->set_code( $data['code'] );
			$coupon->set_discount_type( $data['discount_type'] ?? 'fixed_cart' );
			$coupon->set_amount( $data['amount'] );

			if ( isset( $data['individual_use'] ) ) {
				$coupon->set_individual_use( $data['individual_use'] );
			}

			if ( ! empty( $data['product_ids'] ) ) {
				$coupon->set_product_ids( $data['product_ids'] );
			}

			if ( ! empty( $data['excluded_product_ids'] ) ) {
				$coupon->set_excluded_product_ids( $data['excluded_product_ids'] );
			}

			if ( isset( $data['usage_limit'] ) ) {
				$coupon->set_usage_limit( $data['usage_limit'] );
			}

			if ( isset( $data['usage_limit_per_user'] ) ) {
				$coupon->set_usage_limit_per_user( $data['usage_limit_per_user'] );
			}

			if ( isset( $data['limit_usage_to_x_items'] ) ) {
				$coupon->set_limit_usage_to_x_items( $data['limit_usage_to_x_items'] );
			}

			if ( isset( $data['free_shipping'] ) ) {
				$coupon->set_free_shipping( $data['free_shipping'] );
			}

			if ( ! empty( $data['product_categories'] ) ) {
				$coupon->set_product_categories( $data['product_categories'] );
			}

			if ( ! empty( $data['excluded_product_categories'] ) ) {
				$coupon->set_excluded_product_categories( $data['excluded_product_categories'] );
			}

			if ( isset( $data['exclude_sale_items'] ) ) {
				$coupon->set_exclude_sale_items( $data['exclude_sale_items'] );
			}

			if ( ! empty( $data['minimum_amount'] ) ) {
				$coupon->set_minimum_amount( $data['minimum_amount'] );
			}

			if ( ! empty( $data['maximum_amount'] ) ) {
				$coupon->set_maximum_amount( $data['maximum_amount'] );
			}

			if ( ! empty( $data['email_restrictions'] ) ) {
				$coupon->set_email_restrictions( $data['email_restrictions'] );
			}

			if ( ! empty( $data['date_expires'] ) ) {
				$coupon->set_date_expires( strtotime( $data['date_expires'] ) );
			}

			$coupon_id = $coupon->save();

			if ( ! $coupon_id ) {
				return array(
					'success' => false,
					'error'   => 'Failed to create coupon',
					'code'    => 'coupon_creation_failed',
				);
			}

			do_action( 'mcpfowo_coupon_created', $coupon_id, $data );

			$saved_coupon = new WC_Coupon( $coupon_id );

			return array(
				'success'   => true,
				'coupon_id' => $coupon_id,
				'code'      => $saved_coupon->get_code(),
				'amount'    => $saved_coupon->get_amount(),
				'type'      => $saved_coupon->get_discount_type(),
				'message'   => sprintf( 'Coupon "%s" created successfully', $saved_coupon->get_code() ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'coupon_creation_exception',
			);
		}
	}

	/**
	 * Update an existing coupon
	 *
	 * @param array $data Coupon data with ID.
	 * @return array Response data.
	 */
	public function update_coupon( array $data ): array {
		try {
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Coupon ID is required',
					'code'    => 'missing_coupon_id',
				);
			}

			$coupon = new WC_Coupon( $data['id'] );

			if ( ! $coupon->get_id() ) {
				return array(
					'success' => false,
					'error'   => 'Coupon not found',
					'code'    => 'coupon_not_found',
				);
			}

			if ( isset( $data['code'] ) ) {
				$coupon->set_code( $data['code'] );
			}

			if ( isset( $data['discount_type'] ) ) {
				$coupon->set_discount_type( $data['discount_type'] );
			}

			if ( isset( $data['amount'] ) ) {
				$coupon->set_amount( $data['amount'] );
			}

			if ( isset( $data['individual_use'] ) ) {
				$coupon->set_individual_use( $data['individual_use'] );
			}

			if ( isset( $data['product_ids'] ) ) {
				$coupon->set_product_ids( $data['product_ids'] );
			}

			if ( isset( $data['excluded_product_ids'] ) ) {
				$coupon->set_excluded_product_ids( $data['excluded_product_ids'] );
			}

			if ( isset( $data['usage_limit'] ) ) {
				$coupon->set_usage_limit( $data['usage_limit'] );
			}

			if ( isset( $data['usage_limit_per_user'] ) ) {
				$coupon->set_usage_limit_per_user( $data['usage_limit_per_user'] );
			}

			if ( isset( $data['limit_usage_to_x_items'] ) ) {
				$coupon->set_limit_usage_to_x_items( $data['limit_usage_to_x_items'] );
			}

			if ( isset( $data['free_shipping'] ) ) {
				$coupon->set_free_shipping( $data['free_shipping'] );
			}

			if ( isset( $data['product_categories'] ) ) {
				$coupon->set_product_categories( $data['product_categories'] );
			}

			if ( isset( $data['excluded_product_categories'] ) ) {
				$coupon->set_excluded_product_categories( $data['excluded_product_categories'] );
			}

			if ( isset( $data['exclude_sale_items'] ) ) {
				$coupon->set_exclude_sale_items( $data['exclude_sale_items'] );
			}

			if ( isset( $data['minimum_amount'] ) ) {
				$coupon->set_minimum_amount( $data['minimum_amount'] );
			}

			if ( isset( $data['maximum_amount'] ) ) {
				$coupon->set_maximum_amount( $data['maximum_amount'] );
			}

			if ( isset( $data['email_restrictions'] ) ) {
				$coupon->set_email_restrictions( $data['email_restrictions'] );
			}

			if ( isset( $data['date_expires'] ) ) {
				if ( ! empty( $data['date_expires'] ) ) {
					$coupon->set_date_expires( strtotime( $data['date_expires'] ) );
				} else {
					$coupon->set_date_expires( null );
				}
			}

			$coupon->save();

			do_action( 'mcpfowo_coupon_updated', $data['id'], $data );

			return array(
				'success'   => true,
				'coupon_id' => $data['id'],
				'code'      => $coupon->get_code(),
				'message'   => sprintf( 'Coupon "%s" updated successfully', $coupon->get_code() ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'coupon_update_exception',
			);
		}
	}

	/**
	 * Delete a coupon
	 *
	 * @param array $data Coupon data with ID.
	 * @return array Response data.
	 */
	public function delete_coupon( array $data ): array {
		try {
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Coupon ID is required',
					'code'    => 'missing_coupon_id',
				);
			}

			$coupon = new WC_Coupon( $data['id'] );

			if ( ! $coupon->get_id() ) {
				return array(
					'success' => false,
					'error'   => 'Coupon not found',
					'code'    => 'coupon_not_found',
				);
			}

			$coupon_code = $coupon->get_code();
			$force = $data['force'] ?? false;

			if ( $force ) {
				wp_delete_post( $data['id'], true );
			} else {
				wp_trash_post( $data['id'] );
			}

			do_action( 'mcpfowo_coupon_deleted', $data['id'], $coupon_code );

			return array(
				'success' => true,
				'message' => sprintf( 'Coupon "%s" deleted successfully', $coupon_code ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'coupon_deletion_exception',
			);
		}
	}
}
