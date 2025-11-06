<?php
declare(strict_types=1);

namespace McpForWoo\Tools\Write;

use McpForWoo\Core\RegisterMcpTool;

/**
 * Class McpWooAttributesWrite
 * Provides WooCommerce write operations for product attributes.
 */
class McpWooAttributesWrite {

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

		// Create Attribute
		new RegisterMcpTool(
			array(
				'name'        => 'wc_create_attribute',
				'description' => __(  'Create a new global product attribute (e.g., Color, Size).', 'mcp-for-woocommerce' ),
				'type'        => 'create',
				'callback'    => array( $this, 'create_attribute' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Create Attribute',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'name' => array( 'type' => 'string', 'description' => __( 'Attribute name (required)', 'mcp-for-woocommerce' ) ),
						'slug' => array( 'type' => 'string', 'description' => __( 'Attribute slug', 'mcp-for-woocommerce' ) ),
						'type' => array( 'type' => 'string', 'enum' => array( 'select', 'text' ), 'description' => __( 'Attribute type', 'mcp-for-woocommerce' ), 'default' => 'select' ),
						'order_by' => array( 'type' => 'string', 'enum' => array( 'menu_order', 'name', 'name_num', 'id' ), 'description' => __( 'Sort order', 'mcp-for-woocommerce' ), 'default' => 'menu_order' ),
						'has_archives' => array( 'type' => 'boolean', 'description' => __( 'Enable archives', 'mcp-for-woocommerce' ), 'default' => false ),
					),
					'required' => array( 'name' ),
				),
			)
		);

		// Update Attribute
		new RegisterMcpTool(
			array(
				'name'        => 'wc_update_attribute',
				'description' => __(  'Update an existing product attribute.', 'mcp-for-woocommerce' ),
				'type'        => 'update',
				'callback'    => array( $this, 'update_attribute' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Update Attribute',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array( 'type' => 'integer', 'description' => __( 'Attribute ID (required)', 'mcp-for-woocommerce' ) ),
						'name' => array( 'type' => 'string', 'description' => __( 'Attribute name', 'mcp-for-woocommerce' ) ),
						'slug' => array( 'type' => 'string', 'description' => __( 'Attribute slug', 'mcp-for-woocommerce' ) ),
						'type' => array( 'type' => 'string', 'enum' => array( 'select', 'text' ) ),
						'order_by' => array( 'type' => 'string', 'enum' => array( 'menu_order', 'name', 'name_num', 'id' ) ),
						'has_archives' => array( 'type' => 'boolean' ),
					),
					'required' => array( 'id' ),
				),
			)
		);

		// Delete Attribute
		new RegisterMcpTool(
			array(
				'name'        => 'wc_delete_attribute',
				'description' => __(  'Delete a product attribute. DESTRUCTIVE OPERATION.', 'mcp-for-woocommerce' ),
				'type'        => 'delete',
				'callback'    => array( $this, 'delete_attribute' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Delete Attribute',
					'readOnlyHint'    => false,
					'destructiveHint' => true,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array( 'id' => array( 'type' => 'integer', 'description' => __( 'Attribute ID (required)', 'mcp-for-woocommerce' ) ) ),
					'required' => array( 'id' ),
				),
			)
		);

		// Add Attribute Terms
		new RegisterMcpTool(
			array(
				'name'        => 'wc_add_attribute_terms',
				'description' => __(  'Add terms (values) to a product attribute (e.g., add Red, Blue, Green to Color attribute).', 'mcp-for-woocommerce' ),
				'type'        => 'create',
				'callback'    => array( $this, 'add_attribute_terms' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Add Attribute Terms',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'attribute_id' => array( 'type' => 'integer', 'description' => __( 'Attribute ID (required)', 'mcp-for-woocommerce' ) ),
						'terms' => array(
							'type'        => 'array',
							'description' => __(  'Array of term names to add (required)', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'string' ),
						),
					),
					'required' => array( 'attribute_id', 'terms' ),
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
	 * Create a new attribute
	 *
	 * @param array $data Attribute data.
	 * @return array Response data.
	 */
	public function create_attribute( array $data ): array {
		try {
			if ( empty( $data['name'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Attribute name is required',
					'code'    => 'missing_required_field',
				);
			}

			$args = array(
				'name'         => $data['name'],
				'slug'        => $data['slug'] ?? sanitize_title( $data['name'] ),
				'type'        => $data['type'] ?? 'select',
				'order_by'    => $data['order_by'] ?? 'menu_order',
				'has_archives' => $data['has_archives'] ?? false,
			);

			$attribute_id = wc_create_attribute( $args );

			if ( is_wp_error( $attribute_id ) ) {
				return array(
					'success' => false,
					'error'   => $attribute_id->get_error_message(),
					'code'    => 'attribute_creation_failed',
				);
			}

			do_action( 'mcpfowo_attribute_created', $attribute_id, $data );

			$attribute = wc_get_attribute( $attribute_id );

			return array(
				'success'      => true,
				'attribute_id' => $attribute_id,
				'name'         => $attribute->name,
				'slug'         => $attribute->slug,
				'message'      => sprintf( 'Attribute "%s" created successfully', $attribute->name ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'attribute_creation_exception',
			);
		}
	}

	/**
	 * Update an existing attribute
	 *
	 * @param array $data Attribute data with ID.
	 * @return array Response data.
	 */
	public function update_attribute( array $data ): array {
		try {
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Attribute ID is required',
					'code'    => 'missing_attribute_id',
				);
			}

			$attribute = wc_get_attribute( $data['id'] );

			if ( ! $attribute ) {
				return array(
					'success' => false,
					'error'   => 'Attribute not found',
					'code'    => 'attribute_not_found',
				);
			}

			$args = array();

			if ( isset( $data['name'] ) ) {
				$args['name'] = $data['name'];
			}

			if ( isset( $data['slug'] ) ) {
				$args['slug'] = $data['slug'];
			}

			if ( isset( $data['type'] ) ) {
				$args['type'] = $data['type'];
			}

			if ( isset( $data['order_by'] ) ) {
				$args['order_by'] = $data['order_by'];
			}

			if ( isset( $data['has_archives'] ) ) {
				$args['has_archives'] = $data['has_archives'];
			}

			if ( empty( $args ) ) {
				return array(
					'success' => false,
					'error'   => 'No fields to update',
					'code'    => 'no_fields_to_update',
				);
			}

			$result = wc_update_attribute( $data['id'], $args );

			if ( is_wp_error( $result ) ) {
				return array(
					'success' => false,
					'error'   => $result->get_error_message(),
					'code'    => 'attribute_update_failed',
				);
			}

			do_action( 'mcpfowo_attribute_updated', $data['id'], $data );

			$updated_attribute = wc_get_attribute( $data['id'] );

			return array(
				'success'      => true,
				'attribute_id' => $data['id'],
				'name'         => $updated_attribute->name,
				'slug'         => $updated_attribute->slug,
				'message'      => sprintf( 'Attribute "%s" updated successfully', $updated_attribute->name ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'attribute_update_exception',
			);
		}
	}

	/**
	 * Delete an attribute
	 *
	 * @param array $data Attribute data with ID.
	 * @return array Response data.
	 */
	public function delete_attribute( array $data ): array {
		try {
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Attribute ID is required',
					'code'    => 'missing_attribute_id',
				);
			}

			$attribute = wc_get_attribute( $data['id'] );

			if ( ! $attribute ) {
				return array(
					'success' => false,
					'error'   => 'Attribute not found',
					'code'    => 'attribute_not_found',
				);
			}

			$attribute_name = $attribute->name;

			$result = wc_delete_attribute( $data['id'] );

			if ( is_wp_error( $result ) || ! $result ) {
				return array(
					'success' => false,
					'error'   => is_wp_error( $result ) ? $result->get_error_message() : 'Failed to delete attribute',
					'code'    => 'attribute_deletion_failed',
				);
			}

			do_action( 'mcpfowo_attribute_deleted', $data['id'], $attribute_name );

			return array(
				'success' => true,
				'message' => sprintf( 'Attribute "%s" deleted successfully', $attribute_name ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'attribute_deletion_exception',
			);
		}
	}

	/**
	 * Add terms to an attribute
	 *
	 * @param array $data Data with attribute_id and terms.
	 * @return array Response data.
	 */
	public function add_attribute_terms( array $data ): array {
		try {
			if ( empty( $data['attribute_id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Attribute ID is required',
					'code'    => 'missing_attribute_id',
				);
			}

			if ( empty( $data['terms'] ) || ! is_array( $data['terms'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Terms array is required',
					'code'    => 'missing_terms',
				);
			}

			$attribute = wc_get_attribute( $data['attribute_id'] );

			if ( ! $attribute ) {
				return array(
					'success' => false,
					'error'   => 'Attribute not found',
					'code'    => 'attribute_not_found',
				);
			}

			$taxonomy = wc_attribute_taxonomy_name_by_id( $data['attribute_id'] );

			if ( ! $taxonomy ) {
				return array(
					'success' => false,
					'error'   => 'Failed to get attribute taxonomy',
					'code'    => 'taxonomy_not_found',
				);
			}

			$created = array();
			$skipped = array();
			$failed = array();

			foreach ( $data['terms'] as $term_name ) {
				if ( empty( $term_name ) ) {
					$skipped[] = $term_name;
					continue;
				}

				$result = wp_insert_term( $term_name, $taxonomy );

				if ( is_wp_error( $result ) ) {
					if ( $result->get_error_code() === 'term_exists' ) {
						$skipped[] = $term_name;
					} else {
						$failed[] = array(
							'term'  => $term_name,
							'error' => $result->get_error_message(),
						);
					}
				} else {
					$created[] = array(
						'id'   => $result['term_id'],
						'name' => $term_name,
					);
				}
			}

			do_action( 'mcpfowo_attribute_terms_added', $data['attribute_id'], $data['terms'] );

			return array(
				'success' => true,
				'created' => $created,
				'skipped' => $skipped,
				'failed'  => $failed,
				'message' => sprintf(
					'Created %d terms, skipped %d, failed %d',
					count( $created ),
					count( $skipped ),
					count( $failed )
				),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'     => 'attribute_terms_exception',
			);
		}
	}
}
