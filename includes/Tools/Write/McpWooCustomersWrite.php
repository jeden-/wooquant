<?php
declare(strict_types=1);

namespace McpForWoo\Tools\Write;

use McpForWoo\Core\RegisterMcpTool;
use WC_Customer;

/**
 * Class McpWooCustomersWrite
 *
 * Provides WooCommerce write operations for customers.
 */
class McpWooCustomersWrite {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'mcpfowo_init', array( $this, 'register_tools' ) );
	}

	/**
	 * Register customer write tools
	 */
	public function register_tools(): void {
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		$settings = get_option( 'mcpfowo_settings', array() );
		if ( empty( $settings['enable_write_operations'] ) ) {
			return;
		}

		// Create Customer
		new RegisterMcpTool(
			array(
				'name'        => 'wc_create_customer',
				'description' => __(  'Create a new WooCommerce customer with billing and shipping addresses.', 'mcp-for-woocommerce' ),
				'type'        => 'create',
				'callback'    => array( $this, 'create_customer' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Create Customer',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'email' => array(
							'type'        => 'string',
							'description' => __(  'Customer email (required)', 'mcp-for-woocommerce' ),
						),
						'username' => array(
							'type'        => 'string',
							'description' => __(  'Customer username', 'mcp-for-woocommerce' ),
						),
						'password' => array(
							'type'        => 'string',
							'description' => __(  'Customer password', 'mcp-for-woocommerce' ),
						),
						'first_name' => array(
							'type'        => 'string',
							'description' => __(  'Customer first name', 'mcp-for-woocommerce' ),
						),
						'last_name' => array(
							'type'        => 'string',
							'description' => __(  'Customer last name', 'mcp-for-woocommerce' ),
						),
						'billing' => array(
							'type'        => 'object',
							'description' => __(  'Billing address', 'mcp-for-woocommerce' ),
							'properties'  => array(
								'first_name' => array( 'type' => 'string' ),
								'last_name'  => array( 'type' => 'string' ),
								'company'    => array( 'type' => 'string' ),
								'address_1'  => array( 'type' => 'string' ),
								'address_2'  => array( 'type' => 'string' ),
								'city'       => array( 'type' => 'string' ),
								'state'      => array( 'type' => 'string' ),
								'postcode'   => array( 'type' => 'string' ),
								'country'    => array( 'type' => 'string' ),
								'email'      => array( 'type' => 'string' ),
								'phone'      => array( 'type' => 'string' ),
							),
						),
						'shipping' => array(
							'type'        => 'object',
							'description' => __(  'Shipping address', 'mcp-for-woocommerce' ),
							'properties'  => array(
								'first_name' => array( 'type' => 'string' ),
								'last_name'  => array( 'type' => 'string' ),
								'company'    => array( 'type' => 'string' ),
								'address_1'  => array( 'type' => 'string' ),
								'address_2'  => array( 'type' => 'string' ),
								'city'       => array( 'type' => 'string' ),
								'state'      => array( 'type' => 'string' ),
								'postcode'   => array( 'type' => 'string' ),
								'country'    => array( 'type' => 'string' ),
							),
						),
					),
					'required' => array( 'email' ),
				),
			)
		);

		// Update Customer
		new RegisterMcpTool(
			array(
				'name'        => 'wc_update_customer',
				'description' => __(  'Update an existing WooCommerce customer.', 'mcp-for-woocommerce' ),
				'type'        => 'update',
				'callback'    => array( $this, 'update_customer' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Update Customer',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array(
							'type'        => 'integer',
							'description' => __(  'Customer ID (required)', 'mcp-for-woocommerce' ),
						),
						'email' => array(
							'type'        => 'string',
							'description' => __(  'Customer email', 'mcp-for-woocommerce' ),
						),
						'first_name' => array(
							'type'        => 'string',
							'description' => __(  'Customer first name', 'mcp-for-woocommerce' ),
						),
						'last_name' => array(
							'type'        => 'string',
							'description' => __(  'Customer last name', 'mcp-for-woocommerce' ),
						),
						'billing' => array(
							'type'        => 'object',
							'description' => __(  'Billing address', 'mcp-for-woocommerce' ),
							'properties'  => array(
								'first_name' => array( 'type' => 'string' ),
								'last_name'  => array( 'type' => 'string' ),
								'company'    => array( 'type' => 'string' ),
								'address_1'  => array( 'type' => 'string' ),
								'address_2'  => array( 'type' => 'string' ),
								'city'       => array( 'type' => 'string' ),
								'state'      => array( 'type' => 'string' ),
								'postcode'   => array( 'type' => 'string' ),
								'country'    => array( 'type' => 'string' ),
								'email'      => array( 'type' => 'string' ),
								'phone'      => array( 'type' => 'string' ),
							),
						),
						'shipping' => array(
							'type'        => 'object',
							'description' => __(  'Shipping address', 'mcp-for-woocommerce' ),
							'properties'  => array(
								'first_name' => array( 'type' => 'string' ),
								'last_name'  => array( 'type' => 'string' ),
								'company'    => array( 'type' => 'string' ),
								'address_1'  => array( 'type' => 'string' ),
								'address_2'  => array( 'type' => 'string' ),
								'city'       => array( 'type' => 'string' ),
								'state'      => array( 'type' => 'string' ),
								'postcode'   => array( 'type' => 'string' ),
								'country'    => array( 'type' => 'string' ),
							),
						),
					),
					'required' => array( 'id' ),
				),
			)
		);

		// Delete Customer
		new RegisterMcpTool(
			array(
				'name'        => 'wc_delete_customer',
				'description' => __(  'Delete a WooCommerce customer. DESTRUCTIVE OPERATION - also removes associated WordPress user.', 'mcp-for-woocommerce' ),
				'type'        => 'delete',
				'callback'    => array( $this, 'delete_customer' ),
				'permission_callback' => array( $this, 'check_delete_users_permission' ),
				'annotations' => array(
					'title'           => 'Delete Customer',
					'readOnlyHint'    => false,
					'destructiveHint' => true,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array(
							'type'        => 'integer',
							'description' => __(  'Customer ID to delete (required)', 'mcp-for-woocommerce' ),
						),
						'reassign' => array(
							'type'        => 'integer',
							'description' => __(  'User ID to reassign orders to (optional)', 'mcp-for-woocommerce' ),
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
	 * Check if user can delete users
	 *
	 * @return bool
	 */
	public function check_delete_users_permission(): bool {
		return current_user_can( 'delete_users' );
	}

	/**
	 * Create a new customer
	 *
	 * @param array $data Customer data.
	 * @return array Response data.
	 */
	public function create_customer( array $data ): array {
		try {
			if ( empty( $data['email'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Customer email is required',
					'code'    => 'missing_required_field',
				);
			}

			// Check if email already exists
			if ( email_exists( $data['email'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Email already exists',
					'code'    => 'email_exists',
				);
			}

			// Create WordPress user if needed
			$user_id = null;
			if ( ! empty( $data['username'] ) || ! empty( $data['password'] ) ) {
				$username = $data['username'] ?? sanitize_user( $data['email'], true );
				
				if ( username_exists( $username ) ) {
					return array(
						'success' => false,
						'error'   => 'Username already exists',
						'code'    => 'username_exists',
					);
				}

				$user_data = array(
					'user_login' => $username,
					'user_email' => sanitize_email( $data['email'] ),
					'user_pass'  => ! empty( $data['password'] ) ? $data['password'] : wp_generate_password(),
					'role'       => 'customer',
				);

				if ( ! empty( $data['first_name'] ) ) {
					$user_data['first_name'] = sanitize_text_field( $data['first_name'] );
				}

				if ( ! empty( $data['last_name'] ) ) {
					$user_data['last_name'] = sanitize_text_field( $data['last_name'] );
				}

				$user_id = wp_insert_user( $user_data );

				if ( is_wp_error( $user_id ) ) {
					return array(
						'success' => false,
						'error'   => $user_id->get_error_message(),
						'code'    => 'user_creation_failed',
					);
				}
			}

			// Create customer
			$customer = new WC_Customer();
			
			if ( $user_id ) {
				$customer->set_id( $user_id );
			}

			$customer->set_email( sanitize_email( $data['email'] ) );

			if ( ! empty( $data['first_name'] ) ) {
				$customer->set_first_name( sanitize_text_field( $data['first_name'] ) );
			}

			if ( ! empty( $data['last_name'] ) ) {
				$customer->set_last_name( sanitize_text_field( $data['last_name'] ) );
			}

			if ( ! empty( $data['billing'] ) ) {
				$customer->set_billing_address( $data['billing']['address_1'] ?? '' );
				$customer->set_billing_address_2( $data['billing']['address_2'] ?? '' );
				$customer->set_billing_city( $data['billing']['city'] ?? '' );
				$customer->set_billing_state( $data['billing']['state'] ?? '' );
				$customer->set_billing_postcode( $data['billing']['postcode'] ?? '' );
				$customer->set_billing_country( $data['billing']['country'] ?? '' );
				$customer->set_billing_company( $data['billing']['company'] ?? '' );
				$customer->set_billing_phone( $data['billing']['phone'] ?? '' );
				$customer->set_billing_email( $data['billing']['email'] ?? $data['email'] );
				$customer->set_billing_first_name( $data['billing']['first_name'] ?? $data['first_name'] ?? '' );
				$customer->set_billing_last_name( $data['billing']['last_name'] ?? $data['last_name'] ?? '' );
			}

			if ( ! empty( $data['shipping'] ) ) {
				$customer->set_shipping_address( $data['shipping']['address_1'] ?? '' );
				$customer->set_shipping_address_2( $data['shipping']['address_2'] ?? '' );
				$customer->set_shipping_city( $data['shipping']['city'] ?? '' );
				$customer->set_shipping_state( $data['shipping']['state'] ?? '' );
				$customer->set_shipping_postcode( $data['shipping']['postcode'] ?? '' );
				$customer->set_shipping_country( $data['shipping']['country'] ?? '' );
				$customer->set_shipping_company( $data['shipping']['company'] ?? '' );
				$customer->set_shipping_first_name( $data['shipping']['first_name'] ?? $data['first_name'] ?? '' );
				$customer->set_shipping_last_name( $data['shipping']['last_name'] ?? $data['last_name'] ?? '' );
			}

			$customer->save();

			do_action( 'mcpfowo_customer_created', $customer->get_id(), $data );

			return array(
				'success'     => true,
				'customer_id' => $customer->get_id(),
				'email'       => $customer->get_email(),
				'name'        => $customer->get_first_name() . ' ' . $customer->get_last_name(),
				'message'     => sprintf( 'Customer "%s" created successfully', $customer->get_email() ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'customer_creation_exception',
			);
		}
	}

	/**
	 * Update an existing customer
	 *
	 * @param array $data Customer data with ID.
	 * @return array Response data.
	 */
	public function update_customer( array $data ): array {
		try {
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Customer ID is required',
					'code'    => 'missing_customer_id',
				);
			}

			$customer = new WC_Customer( $data['id'] );

			if ( ! $customer->get_id() ) {
				return array(
					'success' => false,
					'error'   => 'Customer not found',
					'code'    => 'customer_not_found',
				);
			}

			if ( isset( $data['email'] ) ) {
				$customer->set_email( sanitize_email( $data['email'] ) );
			}

			if ( isset( $data['first_name'] ) ) {
				$customer->set_first_name( sanitize_text_field( $data['first_name'] ) );
			}

			if ( isset( $data['last_name'] ) ) {
				$customer->set_last_name( sanitize_text_field( $data['last_name'] ) );
			}

			if ( isset( $data['billing'] ) ) {
				if ( isset( $data['billing']['address_1'] ) ) {
					$customer->set_billing_address( $data['billing']['address_1'] );
				}
				if ( isset( $data['billing']['address_2'] ) ) {
					$customer->set_billing_address_2( $data['billing']['address_2'] );
				}
				if ( isset( $data['billing']['city'] ) ) {
					$customer->set_billing_city( $data['billing']['city'] );
				}
				if ( isset( $data['billing']['state'] ) ) {
					$customer->set_billing_state( $data['billing']['state'] );
				}
				if ( isset( $data['billing']['postcode'] ) ) {
					$customer->set_billing_postcode( $data['billing']['postcode'] );
				}
				if ( isset( $data['billing']['country'] ) ) {
					$customer->set_billing_country( $data['billing']['country'] );
				}
				if ( isset( $data['billing']['company'] ) ) {
					$customer->set_billing_company( $data['billing']['company'] );
				}
				if ( isset( $data['billing']['phone'] ) ) {
					$customer->set_billing_phone( $data['billing']['phone'] );
				}
				if ( isset( $data['billing']['email'] ) ) {
					$customer->set_billing_email( $data['billing']['email'] );
				}
				if ( isset( $data['billing']['first_name'] ) ) {
					$customer->set_billing_first_name( $data['billing']['first_name'] );
				}
				if ( isset( $data['billing']['last_name'] ) ) {
					$customer->set_billing_last_name( $data['billing']['last_name'] );
				}
			}

			if ( isset( $data['shipping'] ) ) {
				if ( isset( $data['shipping']['address_1'] ) ) {
					$customer->set_shipping_address( $data['shipping']['address_1'] );
				}
				if ( isset( $data['shipping']['address_2'] ) ) {
					$customer->set_shipping_address_2( $data['shipping']['address_2'] );
				}
				if ( isset( $data['shipping']['city'] ) ) {
					$customer->set_shipping_city( $data['shipping']['city'] );
				}
				if ( isset( $data['shipping']['state'] ) ) {
					$customer->set_shipping_state( $data['shipping']['state'] );
				}
				if ( isset( $data['shipping']['postcode'] ) ) {
					$customer->set_shipping_postcode( $data['shipping']['postcode'] );
				}
				if ( isset( $data['shipping']['country'] ) ) {
					$customer->set_shipping_country( $data['shipping']['country'] );
				}
				if ( isset( $data['shipping']['company'] ) ) {
					$customer->set_shipping_company( $data['shipping']['company'] );
				}
				if ( isset( $data['shipping']['first_name'] ) ) {
					$customer->set_shipping_first_name( $data['shipping']['first_name'] );
				}
				if ( isset( $data['shipping']['last_name'] ) ) {
					$customer->set_shipping_last_name( $data['shipping']['last_name'] );
				}
			}

			$customer->save();

			do_action( 'mcpfowo_customer_updated', $customer->get_id(), $data );

			return array(
				'success'     => true,
				'customer_id' => $customer->get_id(),
				'email'       => $customer->get_email(),
				'name'        => $customer->get_first_name() . ' ' . $customer->get_last_name(),
				'message'     => sprintf( 'Customer "%s" updated successfully', $customer->get_email() ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'customer_update_exception',
			);
		}
	}

	/**
	 * Delete a customer
	 *
	 * @param array $data Customer data with ID.
	 * @return array Response data.
	 */
	public function delete_customer( array $data ): array {
		try {
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Customer ID is required',
					'code'    => 'missing_customer_id',
				);
			}

			$customer = new WC_Customer( $data['id'] );

			if ( ! $customer->get_id() ) {
				return array(
					'success' => false,
					'error'   => 'Customer not found',
					'code'    => 'customer_not_found',
				);
			}

			$customer_email = $customer->get_email();
			$user_id = $customer->get_id();

			// Delete customer data
			$customer->delete();

			// Delete WordPress user if reassign is not specified
			if ( empty( $data['reassign'] ) ) {
				wp_delete_user( $user_id );
			} else {
				wp_delete_user( $user_id, $data['reassign'] );
			}

			do_action( 'mcpfowo_customer_deleted', $user_id, $customer_email );

			return array(
				'success' => true,
				'message' => sprintf( 'Customer "%s" deleted successfully', $customer_email ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'customer_deletion_exception',
			);
		}
	}
}

