<?php

declare(strict_types=1);


namespace McpForWoo\Tools;

use McpForWoo\Core\RegisterMcpTool;

/**
 * Class for managing MCP Posts Tools functionality.
 */
class McpPostsTools {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'mcpfowo_init', array( $this, 'register_tools' ) );
	}

	/**
	 * Register the tools.
	 */
	public function register_tools(): void {
		new RegisterMcpTool(
			array(
				'name'        => 'wp_posts_search',
				'description' => __( 'Search and filter WordPress posts with pagination', 'mcp-for-woocommerce' ),
				'type'        => 'read',
				'rest_alias'  => array(
					'route'  => '/wp/v2/posts',
					'method' => 'GET',
				),
				'annotations' => array(
					'title'         => 'Search Posts',
					'readOnlyHint'  => true,
					'openWorldHint' => false,
				),
			)
		);

		new RegisterMcpTool(
			array(
				'name'        => 'wp_get_post',
				'description' => __( 'Get a WordPress post by ID', 'mcp-for-woocommerce' ),
				'type'        => 'read',
				'rest_alias'  => array(
					'route'  => '/wp/v2/posts/(?P<id>[\d]+)',
					'method' => 'GET',
				),
				'annotations' => array(
					'title'         => 'Get Post',
					'readOnlyHint'  => true,
					'openWorldHint' => false,
				),
			)
		);

		// Removed wp_add_post, wp_update_post, wp_delete_post for security reasons

		// list all categories.
		new RegisterMcpTool(
			array(
				'name'        => 'wp_list_categories',
				'description' => __( 'List all WordPress post categories', 'mcp-for-woocommerce' ),
				'type'        => 'read',
				'rest_alias'  => array(
					'route'  => '/wp/v2/categories',
					'method' => 'GET',
				),
				'annotations' => array(
					'title'         => 'List Categories',
					'readOnlyHint'  => true,
					'openWorldHint' => false,
				),
			)
		);

		// Removed wp_add_category, wp_update_category, wp_delete_category for security reasons

		// list all tags.
		new RegisterMcpTool(
			array(
				'name'        => 'wp_list_tags',
				'description' => __( 'List all WordPress post tags', 'mcp-for-woocommerce' ),
				'type'        => 'read',
				'rest_alias'  => array(
					'route'  => '/wp/v2/tags',
					'method' => 'GET',
				),
				'annotations' => array(
					'title'         => 'List Tags',
					'readOnlyHint'  => true,
					'openWorldHint' => false,
				),
			)
		);

		// Removed wp_add_tag, wp_update_tag, wp_delete_tag for security reasons
	}
}
