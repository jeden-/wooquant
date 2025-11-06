<?php
declare(strict_types=1);

namespace McpForWoo\Tools\Write;

use McpForWoo\Core\RegisterMcpTool;

/**
 * Class McpSettingsWrite
 *
 * Provides write operations for WooCommerce and WordPress settings.
 */
class McpSettingsWrite {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'mcpfowo_init', array( $this, 'register_tools' ) );
	}

	/**
	 * Register settings write tools
	 */
	public function register_tools(): void {
		$settings = get_option( 'mcpfowo_settings', array() );
		if ( empty( $settings['enable_write_operations'] ) ) {
			return;
		}

		// Update WooCommerce Settings
		new RegisterMcpTool(
			array(
				'name'        => 'wc_update_settings',
				'description' => __(  'Update WooCommerce settings. Accepts settings key-value pairs to update.', 'mcp-for-woocommerce' ),
				'type'        => 'update',
				'callback'    => array( $this, 'update_woocommerce_settings' ),
				'permission_callback' => array( $this, 'check_manage_options_permission' ),
				'annotations' => array(
					'title'           => 'Update WooCommerce Settings',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'settings' => array(
							'type'        => 'object',
							'description' => __(  'Key-value pairs of WooCommerce settings to update (required)', 'mcp-for-woocommerce' ),
							'additionalProperties' => true,
						),
					),
					'required' => array( 'settings' ),
				),
			)
		);

		// Update WordPress Settings
		new RegisterMcpTool(
			array(
				'name'        => 'wp_update_settings',
				'description' => __(  'Update WordPress general settings (site title, tagline, admin email, etc.).', 'mcp-for-woocommerce' ),
				'type'        => 'update',
				'callback'    => array( $this, 'update_wordpress_settings' ),
				'permission_callback' => array( $this, 'check_manage_options_permission' ),
				'annotations' => array(
					'title'           => 'Update WordPress Settings',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'site_title' => array( 'type' => 'string', 'description' => __( 'Site title', 'mcp-for-woocommerce' ) ),
						'tagline' => array( 'type' => 'string', 'description' => __( 'Site tagline', 'mcp-for-woocommerce' ) ),
						'admin_email' => array( 'type' => 'string', 'format' => 'email', 'description' => __( 'Administrator email', 'mcp-for-woocommerce' ) ),
						'date_format' => array( 'type' => 'string', 'description' => __( 'Date format', 'mcp-for-woocommerce' ) ),
						'time_format' => array( 'type' => 'string', 'description' => __( 'Time format', 'mcp-for-woocommerce' ) ),
						'timezone_string' => array( 'type' => 'string', 'description' => __( 'Timezone string', 'mcp-for-woocommerce' ) ),
						'start_of_week' => array( 'type' => 'integer', 'description' => __( 'Start of week (0=Sunday, 1=Monday, etc.)', 'mcp-for-woocommerce' ) ),
					),
				),
			)
		);

		// Update Shipping Zone
		if ( function_exists( 'WC' ) ) {
			new RegisterMcpTool(
				array(
					'name'        => 'wc_update_shipping_zone',
					'description' => __(  'Update WooCommerce shipping zone settings. Create or update shipping zone with methods.', 'mcp-for-woocommerce' ),
					'type'        => 'update',
					'callback'    => array( $this, 'update_shipping_zone' ),
					'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
					'annotations' => array(
						'title'           => 'Update Shipping Zone',
						'readOnlyHint'    => false,
						'destructiveHint' => false,
						'idempotentHint'  => true,
					),
					'inputSchema' => array(
						'type'       => 'object',
						'properties' => array(
							'zone_id' => array(
								'type'        => 'integer',
								'description' => __(  'Zone ID (0 to create new zone)', 'mcp-for-woocommerce' ),
								'default'     => 0,
							),
							'zone_name' => array(
								'type'        => 'string',
								'description' => __(  'Zone name (required for new zones)', 'mcp-for-woocommerce' ),
							),
							'zone_order' => array(
								'type'        => 'integer',
								'description' => __(  'Zone order', 'mcp-for-woocommerce' ),
							),
							'zone_locations' => array(
								'type'        => 'array',
								'description' => __(  'Array of location codes (e.g., ["US", "CA"])', 'mcp-for-woocommerce' ),
								'items'       => array( 'type' => 'string' ),
							),
							'shipping_methods' => array(
								'type'        => 'array',
								'description' => __(  'Array of shipping method configurations', 'mcp-for-woocommerce' ),
								'items'       => array(
									'type'       => 'object',
									'properties' => array(
										'method_id' => array( 'type' => 'string', 'description' => __( 'Method ID (e.g., "flat_rate")', 'mcp-for-woocommerce' ) ),
										'method_title' => array( 'type' => 'string', 'description' => __( 'Method title', 'mcp-for-woocommerce' ) ),
										'enabled' => array( 'type' => 'boolean', 'description' => __( 'Whether method is enabled', 'mcp-for-woocommerce' ) ),
										'settings' => array( 'type' => 'object', 'description' => __( 'Method-specific settings', 'mcp-for-woocommerce' ), 'additionalProperties' => true ),
									),
								),
							),
						),
					),
				),
			);

			// Update Payment Gateway
			new RegisterMcpTool(
				array(
					'name'        => 'wc_update_payment_gateway',
					'description' => __(  'Update WooCommerce payment gateway settings. Enable/disable and configure payment gateways.', 'mcp-for-woocommerce' ),
					'type'        => 'update',
					'callback'    => array( $this, 'update_payment_gateway' ),
					'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
					'annotations' => array(
						'title'           => 'Update Payment Gateway',
						'readOnlyHint'    => false,
						'destructiveHint' => false,
						'idempotentHint'  => true,
					),
					'inputSchema' => array(
						'type'       => 'object',
						'properties' => array(
							'gateway_id' => array(
								'type'        => 'string',
								'description' => __(  'Payment gateway ID (e.g., "bacs", "paypal", "stripe") (required)', 'mcp-for-woocommerce' ),
							),
							'enabled' => array(
								'type'        => 'boolean',
								'description' => __(  'Whether gateway is enabled', 'mcp-for-woocommerce' ),
							),
							'settings' => array(
								'type'        => 'object',
								'description' => __(  'Gateway-specific settings (title, description, etc.)', 'mcp-for-woocommerce' ),
								'additionalProperties' => true,
							),
						),
						'required' => array( 'gateway_id' ),
					),
				)
			);
		}
	}

	/**
	 * Check if user has permission to manage options
	 *
	 * @return bool
	 */
	public function check_manage_options_permission(): bool {
		return current_user_can( 'manage_options' );
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
	 * Update WooCommerce settings
	 *
	 * @param array $data Settings data.
	 * @return array Response data.
	 */
	public function update_woocommerce_settings( array $data ): array {
		try {
			if ( ! function_exists( 'WC' ) ) {
				return array(
					'success' => false,
					'error'   => 'WooCommerce is not active',
					'code'    => 'woocommerce_not_active',
				);
			}

			if ( empty( $data['settings'] ) || ! is_array( $data['settings'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Settings array is required',
					'code'    => 'missing_settings',
				);
			}

			$updated = array();
			$failed = array();

			foreach ( $data['settings'] as $key => $value ) {
				$sanitized_key = sanitize_text_field( $key );
				$sanitized_value = $this->sanitize_setting_value( $value );

				// Update WooCommerce option
				$result = update_option( 'woocommerce_' . $sanitized_key, $sanitized_value );

				if ( $result ) {
					$updated[] = $sanitized_key;
				} else {
					$failed[] = $sanitized_key;
				}
			}

			do_action( 'mcpfowo_woocommerce_settings_updated', $updated, $failed );

			return array(
				'success' => true,
				'updated' => $updated,
				'failed'  => $failed,
				'message' => sprintf( 'Updated %d setting(s)', count( $updated ) ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'settings_update_exception',
			);
		}
	}

	/**
	 * Update WordPress settings
	 *
	 * @param array $data Settings data.
	 * @return array Response data.
	 */
	public function update_wordpress_settings( array $data ): array {
		try {
			$updated = array();
			$failed = array();

			if ( isset( $data['site_title'] ) ) {
				$result = update_option( 'blogname', sanitize_text_field( $data['site_title'] ) );
				if ( $result ) {
					$updated[] = 'site_title';
				} else {
					$failed[] = 'site_title';
				}
			}

			if ( isset( $data['tagline'] ) ) {
				$result = update_option( 'blogdescription', sanitize_text_field( $data['tagline'] ) );
				if ( $result ) {
					$updated[] = 'tagline';
				} else {
					$failed[] = 'tagline';
				}
			}

			if ( isset( $data['admin_email'] ) ) {
				$email = sanitize_email( $data['admin_email'] );
				if ( is_email( $email ) ) {
					$result = update_option( 'admin_email', $email );
					if ( $result ) {
						$updated[] = 'admin_email';
					} else {
						$failed[] = 'admin_email';
					}
				} else {
					$failed[] = 'admin_email (invalid email)';
				}
			}

			if ( isset( $data['date_format'] ) ) {
				$result = update_option( 'date_format', sanitize_text_field( $data['date_format'] ) );
				if ( $result ) {
					$updated[] = 'date_format';
				} else {
					$failed[] = 'date_format';
				}
			}

			if ( isset( $data['time_format'] ) ) {
				$result = update_option( 'time_format', sanitize_text_field( $data['time_format'] ) );
				if ( $result ) {
					$updated[] = 'time_format';
				} else {
					$failed[] = 'time_format';
				}
			}

			if ( isset( $data['timezone_string'] ) ) {
				$result = update_option( 'timezone_string', sanitize_text_field( $data['timezone_string'] ) );
				if ( $result ) {
					$updated[] = 'timezone_string';
				} else {
					$failed[] = 'timezone_string';
				}
			}

			if ( isset( $data['start_of_week'] ) ) {
				$result = update_option( 'start_of_week', intval( $data['start_of_week'] ) );
				if ( $result ) {
					$updated[] = 'start_of_week';
				} else {
					$failed[] = 'start_of_week';
				}
			}

			do_action( 'mcpfowo_wordpress_settings_updated', $updated, $failed );

			return array(
				'success' => true,
				'updated' => $updated,
				'failed'  => $failed,
				'message' => sprintf( 'Updated %d setting(s)', count( $updated ) ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'settings_update_exception',
			);
		}
	}

	/**
	 * Update shipping zone
	 *
	 * @param array $data Zone data.
	 * @return array Response data.
	 */
	public function update_shipping_zone( array $data ): array {
		try {
			if ( ! function_exists( 'WC' ) ) {
				return array(
					'success' => false,
					'error'   => 'WooCommerce is not active',
					'code'    => 'woocommerce_not_active',
				);
			}

			$zone_id = intval( $data['zone_id'] ?? 0 );

			if ( $zone_id > 0 ) {
				$zone = new \WC_Shipping_Zone( $zone_id );
				if ( ! $zone->get_id() ) {
					return array(
						'success' => false,
						'error'   => 'Shipping zone not found',
						'code'    => 'zone_not_found',
					);
				}
			} else {
				// Create new zone
				if ( empty( $data['zone_name'] ) ) {
					return array(
						'success' => false,
						'error'   => 'Zone name is required for new zones',
						'code'    => 'missing_zone_name',
					);
				}
				$zone = new \WC_Shipping_Zone();
				$zone->set_zone_name( sanitize_text_field( $data['zone_name'] ) );
			}

			if ( isset( $data['zone_name'] ) && $zone_id === 0 ) {
				$zone->set_zone_name( sanitize_text_field( $data['zone_name'] ) );
			}

			if ( isset( $data['zone_order'] ) ) {
				$zone->set_zone_order( intval( $data['zone_order'] ) );
			}

			if ( ! empty( $data['zone_locations'] ) && is_array( $data['zone_locations'] ) ) {
				$locations = array();
				foreach ( $data['zone_locations'] as $location_code ) {
					$locations[] = array(
						'code' => sanitize_text_field( $location_code ),
						'type' => 'country',
					);
				}
				$zone->set_locations( $locations );
			}

			$zone->save();

			// Update shipping methods
			if ( ! empty( $data['shipping_methods'] ) && is_array( $data['shipping_methods'] ) ) {
				foreach ( $data['shipping_methods'] as $method_data ) {
					$instance_id = $zone->add_shipping_method( sanitize_text_field( $method_data['method_id'] ) );
					$method = $zone->get_shipping_method( $instance_id );

					if ( $method && isset( $method_data['settings'] ) ) {
						$method_settings = $method->get_instance_option();
						foreach ( $method_data['settings'] as $key => $value ) {
							$method_settings[ $key ] = sanitize_text_field( $value );
						}
						update_option( $method->get_instance_option_key(), $method_settings );
					}
				}
			}

			do_action( 'mcpfowo_shipping_zone_updated', $zone->get_id(), $data );

			return array(
				'success' => true,
				'zone_id' => $zone->get_id(),
				'message' => sprintf( 'Shipping zone #%d updated successfully', $zone->get_id() ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'shipping_zone_update_exception',
			);
		}
	}

	/**
	 * Update payment gateway
	 *
	 * @param array $data Gateway data.
	 * @return array Response data.
	 */
	public function update_payment_gateway( array $data ): array {
		try {
			if ( ! function_exists( 'WC' ) ) {
				return array(
					'success' => false,
					'error'   => 'WooCommerce is not active',
					'code'    => 'woocommerce_not_active',
				);
			}

			if ( empty( $data['gateway_id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Gateway ID is required',
					'code'    => 'missing_gateway_id',
				);
			}

			$gateways = WC()->payment_gateways()->payment_gateways();
			$gateway_id = sanitize_text_field( $data['gateway_id'] );

			if ( ! isset( $gateways[ $gateway_id ] ) ) {
				return array(
					'success' => false,
					'error'   => 'Payment gateway not found',
					'code'    => 'gateway_not_found',
				);
			}

			$gateway = $gateways[ $gateway_id ];

			if ( isset( $data['enabled'] ) ) {
				$gateway->enabled = $data['enabled'] ? 'yes' : 'no';
			}

			if ( ! empty( $data['settings'] ) && is_array( $data['settings'] ) ) {
				foreach ( $data['settings'] as $key => $value ) {
					$gateway->settings[ $key ] = sanitize_text_field( $value );
				}
			}

			update_option( $gateway->get_option_key(), $gateway->settings );
			do_action( 'mcpfowo_payment_gateway_updated', $gateway_id, $data );

			return array(
				'success' => true,
				'gateway_id' => $gateway_id,
				'message' => sprintf( 'Payment gateway "%s" updated successfully', $gateway_id ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'payment_gateway_update_exception',
			);
		}
	}

	/**
	 * Sanitize setting value
	 *
	 * @param mixed $value Setting value.
	 * @return mixed Sanitized value.
	 */
	private function sanitize_setting_value( $value ) {
		if ( is_array( $value ) ) {
			return array_map( array( $this, 'sanitize_setting_value' ), $value );
		} elseif ( is_string( $value ) ) {
			return sanitize_text_field( $value );
		} elseif ( is_bool( $value ) ) {
			return $value;
		} elseif ( is_numeric( $value ) ) {
			return $value;
		}
		return $value;
	}
}

