<?php
declare(strict_types=1);

namespace McpForWoo\Tools\Write;

use McpForWoo\Core\RegisterMcpTool;

/**
 * Class McpWooTagsWrite
 *
 * Provides WooCommerce write operations for product tags.
 * Handles creation, updating, and deletion of product tags with full validation.
 */
class McpWooTagsWrite {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'mcpfowo_init', array( $this, 'register_tools' ) );
	}

	/**
	 * Register tag write tools
	 *
	 * @return void
	 */
	public function register_tools(): void {
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		$settings = get_option( 'mcpfowo_settings', array() );
		if ( empty( $settings['enable_write_operations'] ) ) {
			return;
		}

		// Create Tag
		new RegisterMcpTool(
			array(
				'name'        => 'wc_create_tag',
				'description' => __(  'Create a new WooCommerce product tag with name, slug, and description.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'create_tag' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Create Product Tag',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => false,
					'openWorldHint'   => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'name' => array(
							'type'        => 'string',
							'description' => __(  'Tag name (required)', 'mcp-for-woocommerce' ),
						),
						'slug' => array(
							'type'        => 'string',
							'description' => __(  'Tag slug (URL-friendly name). Auto-generated from name if not provided.', 'mcp-for-woocommerce' ),
						),
						'description' => array(
							'type'        => 'string',
							'description' => __(  'Tag description', 'mcp-for-woocommerce' ),
						),
					),
					'required' => array( 'name' ),
				),
			)
		);

		// Update Tag
		new RegisterMcpTool(
			array(
				'name'        => 'wc_update_tag',
				'description' => __(  'Update an existing WooCommerce product tag. Can update name, slug, and description.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'update_tag' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Update Product Tag',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
					'openWorldHint'   => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array(
							'type'        => 'integer',
							'description' => __(  'Tag ID to update (required)', 'mcp-for-woocommerce' ),
						),
						'name' => array(
							'type'        => 'string',
							'description' => __(  'Tag name', 'mcp-for-woocommerce' ),
						),
						'slug' => array(
							'type'        => 'string',
							'description' => __(  'Tag slug', 'mcp-for-woocommerce' ),
						),
						'description' => array(
							'type'        => 'string',
							'description' => __(  'Tag description', 'mcp-for-woocommerce' ),
						),
					),
					'required' => array( 'id' ),
				),
			)
		);

		// Delete Tag
		new RegisterMcpTool(
			array(
				'name'        => 'wc_delete_tag',
				'description' => __(  'Delete a WooCommerce product tag. DESTRUCTIVE OPERATION - products will lose this tag.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'delete_tag' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Delete Product Tag',
					'readOnlyHint'    => false,
					'destructiveHint' => true,
					'idempotentHint'  => false,
					'openWorldHint'   => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array(
							'type'        => 'integer',
							'description' => __(  'Tag ID to delete (required)', 'mcp-for-woocommerce' ),
						),
						'force' => array(
							'type'        => 'boolean',
							'description' => __(  'Whether to force delete even if tag is used by products', 'mcp-for-woocommerce' ),
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
	 * Create a new product tag
	 *
	 * @param array $data Tag data.
	 * @return array Response data.
	 */
	public function create_tag( array $data ): array {
		try {
			// Validate required fields
			if ( empty( $data['name'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Tag name is required',
					'code'    => 'missing_required_field',
				);
			}

			// Prepare arguments for wp_insert_term
			$args = array(
				'description' => $data['description'] ?? '',
			);

			if ( ! empty( $data['slug'] ) ) {
				$args['slug'] = $data['slug'];
			}

			// Create the tag
			$result = wp_insert_term( $data['name'], 'product_tag', $args );

			if ( is_wp_error( $result ) ) {
				return array(
					'success' => false,
					'error'   => $result->get_error_message(),
					'code'    => 'tag_creation_failed',
				);
			}

			$tag_id = $result['term_id'];

			// Log the action
			do_action( 'mcpfowo_tag_created', $tag_id, $data );

			$tag = get_term( $tag_id, 'product_tag' );

			return array(
				'success' => true,
				'tag_id'  => $tag_id,
				'name'    => $tag->name,
				'slug'    => $tag->slug,
				'link'    => get_term_link( $tag_id, 'product_tag' ),
				'message' => sprintf( 'Tag "%s" created successfully', $tag->name ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'tag_creation_exception',
			);
		}
	}

	/**
	 * Update an existing product tag
	 *
	 * @param array $data Tag data with ID.
	 * @return array Response data.
	 */
	public function update_tag( array $data ): array {
		try {
			// Validate tag ID
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Tag ID is required',
					'code'    => 'missing_tag_id',
				);
			}

			$tag = get_term( $data['id'], 'product_tag' );

			if ( is_wp_error( $tag ) || ! $tag ) {
				return array(
					'success' => false,
					'error'   => 'Tag not found',
					'code'    => 'tag_not_found',
				);
			}

			// Prepare update arguments
			$args = array();

			if ( isset( $data['name'] ) ) {
				$args['name'] = $data['name'];
			}

			if ( isset( $data['slug'] ) ) {
				$args['slug'] = $data['slug'];
			}

			if ( isset( $data['description'] ) ) {
				$args['description'] = $data['description'];
			}

			// Update tag if there are changes
			if ( ! empty( $args ) ) {
				$result = wp_update_term( $data['id'], 'product_tag', $args );

				if ( is_wp_error( $result ) ) {
					return array(
						'success' => false,
						'error'   => $result->get_error_message(),
						'code'    => 'tag_update_failed',
					);
				}
			}

			// Log the action
			do_action( 'mcpfowo_tag_updated', $data['id'], $data );

			$updated_tag = get_term( $data['id'], 'product_tag' );

			return array(
				'success' => true,
				'tag_id'  => $data['id'],
				'name'    => $updated_tag->name,
				'slug'    => $updated_tag->slug,
				'link'    => get_term_link( $data['id'], 'product_tag' ),
				'message' => sprintf( 'Tag "%s" updated successfully', $updated_tag->name ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'tag_update_exception',
			);
		}
	}

	/**
	 * Delete a product tag
	 *
	 * @param array $data Tag data with ID.
	 * @return array Response data.
	 */
	public function delete_tag( array $data ): array {
		try {
			// Validate tag ID
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Tag ID is required',
					'code'    => 'missing_tag_id',
				);
			}

			$tag = get_term( $data['id'], 'product_tag' );

			if ( is_wp_error( $tag ) || ! $tag ) {
				return array(
					'success' => false,
					'error'   => 'Tag not found',
					'code'    => 'tag_not_found',
				);
			}

			$tag_name = $tag->name;
			$force = $data['force'] ?? false;

			// Check if tag is used by products
			$product_count = $tag->count;
			if ( $product_count > 0 && ! $force ) {
				return array(
					'success' => false,
					'error'   => sprintf( 'Tag is used by %d products. Use force=true to delete anyway.', $product_count ),
					'code'    => 'tag_in_use',
					'product_count' => $product_count,
				);
			}

			// Delete the tag
			$result = wp_delete_term( $data['id'], 'product_tag' );

			if ( is_wp_error( $result ) || $result === false ) {
				return array(
					'success' => false,
					'error'   => is_wp_error( $result ) ? $result->get_error_message() : 'Failed to delete tag',
					'code'    => 'tag_deletion_failed',
				);
			}

			// Log the action
			do_action( 'mcpfowo_tag_deleted', $data['id'], $tag_name );

			return array(
				'success' => true,
				'message' => sprintf( 'Tag "%s" deleted successfully', $tag_name ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'tag_deletion_exception',
			);
		}
	}
}
