<?php
declare(strict_types=1);

namespace McpForWoo\Tools\Write;

use McpForWoo\Core\RegisterMcpTool;

/**
 * Class McpWooCategoriesWrite
 *
 * Provides WooCommerce write operations for product categories.
 */
class McpWooCategoriesWrite {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'mcpfowo_init', array( $this, 'register_tools' ) );
	}

	/**
	 * Register category write tools
	 */
	public function register_tools(): void {
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		$settings = get_option( 'mcpfowo_settings', array() );
		if ( empty( $settings['enable_write_operations'] ) ) {
			return;
		}

		// Create Category
		new RegisterMcpTool(
			array(
				'name'        => 'wc_create_category',
				'description' => __(  'Create a new WooCommerce product category with name, slug, description, parent category, and image.', 'mcp-for-woocommerce' ),
				'type'        => 'create',
				'callback'    => array( $this, 'create_category' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Create Category',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'name' => array(
							'type'        => 'string',
							'description' => __(  'Category name (required)', 'mcp-for-woocommerce' ),
						),
						'slug' => array(
							'type'        => 'string',
							'description' => __(  'Category slug (URL-friendly name)', 'mcp-for-woocommerce' ),
						),
						'parent' => array(
							'type'        => 'integer',
							'description' => __(  'Parent category ID (0 for top-level)', 'mcp-for-woocommerce' ),
							'default'     => 0,
						),
						'description' => array(
							'type'        => 'string',
							'description' => __(  'Category description', 'mcp-for-woocommerce' ),
						),
						'display' => array(
							'type'        => 'string',
							'enum'        => array( 'default', 'products', 'subcategories', 'both' ),
							'description' => __(  'Category display type', 'mcp-for-woocommerce' ),
							'default'     => 'default',
						),
						'image_id' => array(
							'type'        => 'integer',
							'description' => __(  'Category image attachment ID', 'mcp-for-woocommerce' ),
						),
						'menu_order' => array(
							'type'        => 'integer',
							'description' => __(  'Menu order for sorting', 'mcp-for-woocommerce' ),
							'default'     => 0,
						),
					),
					'required' => array( 'name' ),
				),
			)
		);

		// Update Category
		new RegisterMcpTool(
			array(
				'name'        => 'wc_update_category',
				'description' => __(  'Update an existing WooCommerce product category.', 'mcp-for-woocommerce' ),
				'type'        => 'update',
				'callback'    => array( $this, 'update_category' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Update Category',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array(
							'type'        => 'integer',
							'description' => __(  'Category ID to update (required)', 'mcp-for-woocommerce' ),
						),
						'name' => array(
							'type'        => 'string',
							'description' => __(  'Category name', 'mcp-for-woocommerce' ),
						),
						'slug' => array(
							'type'        => 'string',
							'description' => __(  'Category slug', 'mcp-for-woocommerce' ),
						),
						'parent' => array(
							'type'        => 'integer',
							'description' => __(  'Parent category ID', 'mcp-for-woocommerce' ),
						),
						'description' => array(
							'type'        => 'string',
							'description' => __(  'Category description', 'mcp-for-woocommerce' ),
						),
						'display' => array(
							'type' => 'string',
							'enum' => array( 'default', 'products', 'subcategories', 'both' ),
							'description' => __(  'Category display type', 'mcp-for-woocommerce' ),
						),
						'image_id' => array(
							'type'        => 'integer',
							'description' => __(  'Category image attachment ID', 'mcp-for-woocommerce' ),
						),
						'menu_order' => array(
							'type'        => 'integer',
							'description' => __(  'Menu order for sorting', 'mcp-for-woocommerce' ),
						),
					),
					'required' => array( 'id' ),
				),
			)
		);

		// Delete Category
		new RegisterMcpTool(
			array(
				'name'        => 'wc_delete_category',
				'description' => __(  'Delete a WooCommerce product category. DESTRUCTIVE OPERATION - products will be uncategorized.', 'mcp-for-woocommerce' ),
				'type'        => 'delete',
				'callback'    => array( $this, 'delete_category' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Delete Category',
					'readOnlyHint'    => false,
					'destructiveHint' => true,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array(
							'type'        => 'integer',
							'description' => __(  'Category ID to delete (required)', 'mcp-for-woocommerce' ),
						),
						'force' => array(
							'type'        => 'boolean',
							'description' => __(  'Whether to force delete even if category has products', 'mcp-for-woocommerce' ),
							'default'     => false,
						),
					),
					'required' => array( 'id' ),
				),
			)
		);

		// Reorder Categories
		new RegisterMcpTool(
			array(
				'name'        => 'wc_reorder_categories',
				'description' => __(  'Change the order of product categories for display.', 'mcp-for-woocommerce' ),
				'type'        => 'update',
				'callback'    => array( $this, 'reorder_categories' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Reorder Categories',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'categories' => array(
							'type'        => 'array',
							'description' => __(  'Array of category IDs in desired order', 'mcp-for-woocommerce' ),
							'items'       => array(
								'type'       => 'object',
								'properties' => array(
									'id'         => array( 'type' => 'integer' ),
									'menu_order' => array( 'type' => 'integer' ),
								),
								'required' => array( 'id', 'menu_order' ),
							),
						),
					),
					'required' => array( 'categories' ),
				),
			)
		);
	}

	/**
	 * Check if user has permission to manage WooCommerce
	 */
	public function check_manage_woocommerce_permission(): bool {
		return current_user_can( 'manage_woocommerce' ) || current_user_can( 'manage_options' );
	}

	/**
	 * Create a new category
	 */
	public function create_category( array $data ): array {
		try {
			if ( empty( $data['name'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Category name is required',
					'code'    => 'missing_required_field',
				);
			}

			$args = array(
				'name'        => $data['name'],
				'parent'      => $data['parent'] ?? 0,
				'description' => $data['description'] ?? '',
			);

			if ( ! empty( $data['slug'] ) ) {
				$args['slug'] = $data['slug'];
			}

			// Create the category
			$result = wp_insert_term( $args['name'], 'product_cat', $args );

			if ( is_wp_error( $result ) ) {
				return array(
					'success' => false,
					'error'   => $result->get_error_message(),
					'code'    => 'category_creation_failed',
				);
			}

			$category_id = $result['term_id'];

			// Set display type
			if ( ! empty( $data['display'] ) ) {
				update_term_meta( $category_id, 'display_type', $data['display'] );
			}

			// Set category image
			if ( ! empty( $data['image_id'] ) ) {
				update_term_meta( $category_id, 'thumbnail_id', $data['image_id'] );
			}

			// Set menu order
			if ( isset( $data['menu_order'] ) ) {
				update_term_meta( $category_id, 'order', $data['menu_order'] );
			}

			// Log the action
			do_action( 'mcpfowo_category_created', $category_id, $data );

			$category = get_term( $category_id, 'product_cat' );

			return array(
				'success'     => true,
				'category_id' => $category_id,
				'name'        => $category->name,
				'slug'        => $category->slug,
				'link'        => get_term_link( $category_id, 'product_cat' ),
				'message'     => sprintf( 'Category "%s" created successfully', $category->name ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'category_creation_exception',
			);
		}
	}

	/**
	 * Update an existing category
	 */
	public function update_category( array $data ): array {
		try {
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Category ID is required',
					'code'    => 'missing_category_id',
				);
			}

			$category = get_term( $data['id'], 'product_cat' );

			if ( is_wp_error( $category ) || ! $category ) {
				return array(
					'success' => false,
					'error'   => 'Category not found',
					'code'    => 'category_not_found',
				);
			}

			$args = array();

			if ( isset( $data['name'] ) ) {
				$args['name'] = $data['name'];
			}

			if ( isset( $data['slug'] ) ) {
				$args['slug'] = $data['slug'];
			}

			if ( isset( $data['parent'] ) ) {
				$args['parent'] = $data['parent'];
			}

			if ( isset( $data['description'] ) ) {
				$args['description'] = $data['description'];
			}

			// Update category if there are changes
			if ( ! empty( $args ) ) {
				$result = wp_update_term( $data['id'], 'product_cat', $args );

				if ( is_wp_error( $result ) ) {
					return array(
						'success' => false,
						'error'   => $result->get_error_message(),
						'code'    => 'category_update_failed',
					);
				}
			}

			// Update meta fields
			if ( isset( $data['display'] ) ) {
				update_term_meta( $data['id'], 'display_type', $data['display'] );
			}

			if ( isset( $data['image_id'] ) ) {
				update_term_meta( $data['id'], 'thumbnail_id', $data['image_id'] );
			}

			if ( isset( $data['menu_order'] ) ) {
				update_term_meta( $data['id'], 'order', $data['menu_order'] );
			}

			// Log the action
			do_action( 'mcpfowo_category_updated', $data['id'], $data );

			$updated_category = get_term( $data['id'], 'product_cat' );

			return array(
				'success'     => true,
				'category_id' => $data['id'],
				'name'        => $updated_category->name,
				'slug'        => $updated_category->slug,
				'link'        => get_term_link( $data['id'], 'product_cat' ),
				'message'     => sprintf( 'Category "%s" updated successfully', $updated_category->name ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'category_update_exception',
			);
		}
	}

	/**
	 * Delete a category
	 */
	public function delete_category( array $data ): array {
		try {
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Category ID is required',
					'code'    => 'missing_category_id',
				);
			}

			$category = get_term( $data['id'], 'product_cat' );

			if ( is_wp_error( $category ) || ! $category ) {
				return array(
					'success' => false,
					'error'   => 'Category not found',
					'code'    => 'category_not_found',
				);
			}

			$category_name = $category->name;
			$force = $data['force'] ?? false;

			// Check if category has products
			$product_count = $category->count;
			if ( $product_count > 0 && ! $force ) {
				return array(
					'success' => false,
					'error'   => sprintf( 'Category has %d products. Use force=true to delete anyway.', $product_count ),
					'code'    => 'category_has_products',
					'product_count' => $product_count,
				);
			}

			// Delete the category
			$result = wp_delete_term( $data['id'], 'product_cat' );

			if ( is_wp_error( $result ) || $result === false ) {
				return array(
					'success' => false,
					'error'   => is_wp_error( $result ) ? $result->get_error_message() : 'Failed to delete category',
					'code'    => 'category_deletion_failed',
				);
			}

			// Log the action
			do_action( 'mcpfowo_category_deleted', $data['id'], $category_name );

			return array(
				'success' => true,
				'message' => sprintf( 'Category "%s" deleted successfully', $category_name ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'category_deletion_exception',
			);
		}
	}

	/**
	 * Reorder categories
	 */
	public function reorder_categories( array $data ): array {
		try {
			if ( empty( $data['categories'] ) || ! is_array( $data['categories'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Categories array is required',
					'code'    => 'missing_categories_array',
				);
			}

			$updated = 0;
			$failed = 0;
			$messages = array();

			foreach ( $data['categories'] as $cat_data ) {
				if ( empty( $cat_data['id'] ) || ! isset( $cat_data['menu_order'] ) ) {
					$failed++;
					$messages[] = 'Skipped category without ID or menu_order';
					continue;
				}

				$result = update_term_meta( $cat_data['id'], 'order', $cat_data['menu_order'] );

				if ( $result ) {
					$updated++;
					$messages[] = sprintf( 'Category ID %d order updated to %d', $cat_data['id'], $cat_data['menu_order'] );
				} else {
					$failed++;
					$messages[] = sprintf( 'Failed to update category ID %d', $cat_data['id'] );
				}
			}

			// Log the action
			do_action( 'mcpfowo_categories_reordered', $data );

			return array(
				'success'  => true,
				'updated'  => $updated,
				'failed'   => $failed,
				'messages' => $messages,
				'message'  => sprintf( 'Reordered %d categories successfully', $updated ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'category_reorder_exception',
			);
		}
	}
}
