<?php
declare(strict_types=1);

namespace McpForWoo\Tools\Write;

use McpForWoo\Core\RegisterMcpTool;

/**
 * Class McpWordPressMenuWrite
 *
 * Provides WordPress write operations for navigation menus.
 */
class McpWordPressMenuWrite {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'mcpfowo_init', array( $this, 'register_tools' ) );
	}

	/**
	 * Register menu write tools
	 */
	public function register_tools(): void {
		$settings = get_option( 'mcpfowo_settings', array() );
		if ( empty( $settings['enable_write_operations'] ) ) {
			return;
		}

		// Create Menu
		new RegisterMcpTool(
			array(
				'name'        => 'wp_create_menu',
				'description' => __(  'Create a new WordPress navigation menu.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'create_menu' ),
				'permission_callback' => array( $this, 'check_edit_theme_options_permission' ),
				'annotations' => array(
					'title'           => 'Create Menu',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'name' => array(
							'type'        => 'string',
							'description' => __(  'Menu name (required)', 'mcp-for-woocommerce' ),
						),
					),
					'required' => array( 'name' ),
				),
			)
		);

		// Add Menu Item
		new RegisterMcpTool(
			array(
				'name'        => 'wp_add_menu_item',
				'description' => __(  'Add a menu item to a WordPress navigation menu.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'add_menu_item' ),
				'permission_callback' => array( $this, 'check_edit_theme_options_permission' ),
				'annotations' => array(
					'title'           => 'Add Menu Item',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'menu_id' => array(
							'type'        => 'integer',
							'description' => __(  'Menu ID (required)', 'mcp-for-woocommerce' ),
						),
						'title' => array(
							'type'        => 'string',
							'description' => __(  'Menu item title (required)', 'mcp-for-woocommerce' ),
						),
						'url' => array(
							'type'        => 'string',
							'description' => __(  'URL for the menu item', 'mcp-for-woocommerce' ),
						),
						'object_id' => array(
							'type'        => 'integer',
							'description' => __(  'Post, page, or custom post type ID', 'mcp-for-woocommerce' ),
						),
						'object' => array(
							'type'        => 'string',
							'enum'        => array( 'post', 'page', 'custom', 'category', 'post_tag' ),
							'description' => __(  'Object type', 'mcp-for-woocommerce' ),
							'default'     => 'custom',
						),
						'parent_id' => array(
							'type'        => 'integer',
							'description' => __(  'Parent menu item ID (for submenu items)', 'mcp-for-woocommerce' ),
						),
						'position' => array(
							'type'        => 'integer',
							'description' => __(  'Menu order position', 'mcp-for-woocommerce' ),
						),
					),
					'required' => array( 'menu_id', 'title' ),
				),
			)
		);

		// Update Menu
		new RegisterMcpTool(
			array(
				'name'        => 'wp_update_menu',
				'description' => __(  'Update a WordPress navigation menu (rename or change menu items order).', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'update_menu' ),
				'permission_callback' => array( $this, 'check_edit_theme_options_permission' ),
				'annotations' => array(
					'title'           => 'Update Menu',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'menu_id' => array(
							'type'        => 'integer',
							'description' => __(  'Menu ID (required)', 'mcp-for-woocommerce' ),
						),
						'name' => array(
							'type'        => 'string',
							'description' => __(  'New menu name', 'mcp-for-woocommerce' ),
						),
						'items' => array(
							'type'        => 'array',
							'description' => __(  'Array of menu item IDs in desired order', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'integer' ),
						),
					),
					'required' => array( 'menu_id' ),
				),
			)
		);

		// Delete Menu
		new RegisterMcpTool(
			array(
				'name'        => 'wp_delete_menu',
				'description' => __(  'Delete a WordPress navigation menu. DESTRUCTIVE OPERATION.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'delete_menu' ),
				'permission_callback' => array( $this, 'check_edit_theme_options_permission' ),
				'annotations' => array(
					'title'           => 'Delete Menu',
					'readOnlyHint'    => false,
					'destructiveHint' => true,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'menu_id' => array(
							'type'        => 'integer',
							'description' => __(  'Menu ID to delete (required)', 'mcp-for-woocommerce' ),
						),
					),
					'required' => array( 'menu_id' ),
				),
			)
		);
	}

	/**
	 * Check if user can edit theme options
	 */
	public function check_edit_theme_options_permission(): bool {
		return current_user_can( 'edit_theme_options' );
	}

	/**
	 * Create a new menu
	 */
	public function create_menu( array $data ): array {
		try {
			if ( empty( $data['name'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Menu name is required',
					'code'    => 'missing_required_field',
				);
			}

			$menu_id = wp_create_nav_menu( sanitize_text_field( $data['name'] ) );

			if ( is_wp_error( $menu_id ) ) {
				return array(
					'success' => false,
					'error'   => $menu_id->get_error_message(),
					'code'    => 'menu_creation_failed',
				);
			}

			do_action( 'mcpfowo_menu_created', $menu_id, $data );

			$menu = wp_get_nav_menu_object( $menu_id );

			return array(
				'success' => true,
				'menu_id' => $menu_id,
				'name'    => $menu->name,
				'message' => sprintf( 'Menu "%s" created successfully', $menu->name ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'menu_creation_exception',
			);
		}
	}

	/**
	 * Add a menu item
	 */
	public function add_menu_item( array $data ): array {
		try {
			if ( empty( $data['menu_id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Menu ID is required',
					'code'    => 'missing_menu_id',
				);
			}

			if ( empty( $data['title'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Menu item title is required',
					'code'    => 'missing_required_field',
				);
			}

			$menu = wp_get_nav_menu_object( $data['menu_id'] );

			if ( ! $menu ) {
				return array(
					'success' => false,
					'error'   => 'Menu not found',
					'code'    => 'menu_not_found',
				);
			}

			$menu_item_data = array(
				'menu-item-title'  => sanitize_text_field( $data['title'] ),
				'menu-item-status' => 'publish',
			);

			$object = $data['object'] ?? 'custom';

			if ( $object === 'custom' ) {
				if ( empty( $data['url'] ) ) {
					return array(
						'success' => false,
						'error'   => 'URL is required for custom menu items',
						'code'    => 'missing_url',
					);
				}
				$menu_item_data['menu-item-type']      = 'custom';
				$menu_item_data['menu-item-url']        = esc_url_raw( $data['url'] );
			} else {
				if ( empty( $data['object_id'] ) ) {
					return array(
						'success' => false,
						'error'   => 'Object ID is required',
						'code'    => 'missing_object_id',
					);
				}
				$menu_item_data['menu-item-type']      = $object;
				$menu_item_data['menu-item-object-id'] = intval( $data['object_id'] );
				$menu_item_data['menu-item-object']    = $object;
			}

			if ( ! empty( $data['parent_id'] ) ) {
				$menu_item_data['menu-item-parent-id'] = intval( $data['parent_id'] );
			}

			if ( isset( $data['position'] ) ) {
				$menu_item_data['menu-item-position'] = intval( $data['position'] );
			}

			$menu_item_id = wp_update_nav_menu_item( $data['menu_id'], 0, $menu_item_data );

			if ( is_wp_error( $menu_item_id ) ) {
				return array(
					'success' => false,
					'error'   => $menu_item_id->get_error_message(),
					'code'    => 'menu_item_creation_failed',
				);
			}

			do_action( 'mcpfowo_menu_item_added', $menu_item_id, $data );

			$menu_item = wp_setup_nav_menu_item( get_post( $menu_item_id ) );

			return array(
				'success'      => true,
				'menu_item_id' => $menu_item_id,
				'title'        => $menu_item->title,
				'url'          => $menu_item->url,
				'message'      => sprintf( 'Menu item "%s" added successfully', $menu_item->title ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'menu_item_creation_exception',
			);
		}
	}

	/**
	 * Update a menu
	 */
	public function update_menu( array $data ): array {
		try {
			if ( empty( $data['menu_id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Menu ID is required',
					'code'    => 'missing_menu_id',
				);
			}

			$menu = wp_get_nav_menu_object( $data['menu_id'] );

			if ( ! $menu ) {
				return array(
					'success' => false,
					'error'   => 'Menu not found',
					'code'    => 'menu_not_found',
				);
			}

			// Update menu name if provided
			if ( ! empty( $data['name'] ) ) {
				wp_update_nav_menu_object( $data['menu_id'], array( 'menu-name' => sanitize_text_field( $data['name'] ) ) );
			}

			// Update menu items order if provided
			if ( ! empty( $data['items'] ) && is_array( $data['items'] ) ) {
				$position = 1;
				foreach ( $data['items'] as $item_id ) {
					wp_update_nav_menu_item( $data['menu_id'], $item_id, array( 'menu-item-position' => $position ) );
					$position++;
				}
			}

			do_action( 'mcpfowo_menu_updated', $data['menu_id'], $data );

			$updated_menu = wp_get_nav_menu_object( $data['menu_id'] );

			return array(
				'success' => true,
				'menu_id' => $data['menu_id'],
				'name'    => $updated_menu->name,
				'message' => sprintf( 'Menu "%s" updated successfully', $updated_menu->name ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'menu_update_exception',
			);
		}
	}

	/**
	 * Delete a menu
	 */
	public function delete_menu( array $data ): array {
		try {
			if ( empty( $data['menu_id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Menu ID is required',
					'code'    => 'missing_menu_id',
				);
			}

			$menu = wp_get_nav_menu_object( $data['menu_id'] );

			if ( ! $menu ) {
				return array(
					'success' => false,
					'error'   => 'Menu not found',
					'code'    => 'menu_not_found',
				);
			}

			$menu_name = $menu->name;

			$result = wp_delete_nav_menu( $data['menu_id'] );

			if ( is_wp_error( $result ) || ! $result ) {
				return array(
					'success' => false,
					'error'   => is_wp_error( $result ) ? $result->get_error_message() : 'Failed to delete menu',
					'code'    => 'menu_deletion_failed',
				);
			}

			do_action( 'mcpfowo_menu_deleted', $data['menu_id'], $menu_name );

			return array(
				'success' => true,
				'message' => sprintf( 'Menu "%s" deleted successfully', $menu_name ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'menu_deletion_exception',
			);
		}
	}
}

