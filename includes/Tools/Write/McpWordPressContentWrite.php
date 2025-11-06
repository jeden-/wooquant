<?php
declare(strict_types=1);

namespace McpForWoo\Tools\Write;

use McpForWoo\Core\RegisterMcpTool;

/**
 * Class McpWordPressContentWrite
 *
 * Provides WordPress write operations for posts, pages, and media.
 */
class McpWordPressContentWrite {

	public function __construct() {
		add_action( 'mcpfowo_init', array( $this, 'register_tools' ) );
	}

	public function register_tools(): void {
		$settings = get_option( 'mcpfowo_settings', array() );
		if ( empty( $settings['enable_write_operations'] ) ) {
			return;
		}

		// Create Post
		new RegisterMcpTool(
			array(
				'name'        => 'wp_create_post',
				'description' => __(  'Create a new WordPress post with content, categories, tags, and featured image.', 'mcp-for-woocommerce' ),
				'type'        => 'create',
				'callback'    => array( $this, 'create_post' ),
				'permission_callback' => array( $this, 'check_edit_posts_permission' ),
				'annotations' => array(
					'title'           => 'Create Post',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'title' => array(
							'type'        => 'string',
							'description' => __(  'Post title (required)', 'mcp-for-woocommerce' ),
						),
						'content' => array(
							'type'        => 'string',
							'description' => __(  'Post content', 'mcp-for-woocommerce' ),
						),
						'excerpt' => array(
							'type'        => 'string',
							'description' => __(  'Post excerpt', 'mcp-for-woocommerce' ),
						),
						'status' => array(
							'type'        => 'string',
							'enum'        => array( 'draft', 'publish', 'pending', 'private' ),
							'description' => __(  'Post status', 'mcp-for-woocommerce' ),
							'default'     => 'draft',
						),
						'categories' => array(
							'type'        => 'array',
							'description' => __(  'Category IDs', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'integer' ),
						),
						'tags' => array(
							'type'        => 'array',
							'description' => __(  'Tag IDs or names', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'string' ),
						),
						'featured_image_id' => array(
							'type'        => 'integer',
							'description' => __(  'Featured image attachment ID', 'mcp-for-woocommerce' ),
						),
						'author_id' => array(
							'type'        => 'integer',
							'description' => __(  'Author user ID', 'mcp-for-woocommerce' ),
						),
					),
					'required' => array( 'title' ),
				),
			)
		);

		// Update Post
		new RegisterMcpTool(
			array(
				'name'        => 'wp_update_post',
				'description' => __(  'Update an existing WordPress post.', 'mcp-for-woocommerce' ),
				'type'        => 'update',
				'callback'    => array( $this, 'update_post' ),
				'permission_callback' => array( $this, 'check_edit_posts_permission' ),
				'annotations' => array(
					'title'           => 'Update Post',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array(
							'type'        => 'integer',
							'description' => __(  'Post ID (required)', 'mcp-for-woocommerce' ),
						),
						'title' => array(
							'type'        => 'string',
							'description' => __(  'Post title', 'mcp-for-woocommerce' ),
						),
						'content' => array(
							'type'        => 'string',
							'description' => __(  'Post content', 'mcp-for-woocommerce' ),
						),
						'excerpt' => array(
							'type'        => 'string',
							'description' => __(  'Post excerpt', 'mcp-for-woocommerce' ),
						),
						'status' => array(
							'type'        => 'string',
							'enum'        => array( 'draft', 'publish', 'pending', 'private' ),
							'description' => __(  'Post status', 'mcp-for-woocommerce' ),
						),
						'categories' => array(
							'type'        => 'array',
							'description' => __(  'Category IDs', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'integer' ),
						),
						'tags' => array(
							'type'        => 'array',
							'description' => __(  'Tag IDs or names', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'string' ),
						),
						'featured_image_id' => array(
							'type'        => 'integer',
							'description' => __(  'Featured image attachment ID', 'mcp-for-woocommerce' ),
						),
					),
					'required' => array( 'id' ),
				),
			)
		);

		// Delete Post
		new RegisterMcpTool(
			array(
				'name'        => 'wp_delete_post',
				'description' => __(  'Delete a WordPress post. DESTRUCTIVE OPERATION.', 'mcp-for-woocommerce' ),
				'type'        => 'delete',
				'callback'    => array( $this, 'delete_post' ),
				'permission_callback' => array( $this, 'check_delete_posts_permission' ),
				'annotations' => array(
					'title'           => 'Delete Post',
					'readOnlyHint'    => false,
					'destructiveHint' => true,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array(
							'type'        => 'integer',
							'description' => __(  'Post ID to delete (required)', 'mcp-for-woocommerce' ),
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

		// Publish Post
		new RegisterMcpTool(
			array(
				'name'        => 'wp_publish_post',
				'description' => __(  'Publish a WordPress post (change status to publish).', 'mcp-for-woocommerce' ),
				'type'        => 'update',
				'callback'    => array( $this, 'publish_post' ),
				'permission_callback' => array( $this, 'check_publish_posts_permission' ),
				'annotations' => array(
					'title'           => 'Publish Post',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array(
							'type'        => 'integer',
							'description' => __(  'Post ID to publish (required)', 'mcp-for-woocommerce' ),
						),
					),
					'required' => array( 'id' ),
				),
			)
		);

		// Create Page
		new RegisterMcpTool(
			array(
				'name'        => 'wp_create_page',
				'description' => __(  'Create a new WordPress page with content, featured image, and parent page.', 'mcp-for-woocommerce' ),
				'type'        => 'create',
				'callback'    => array( $this, 'create_page' ),
				'permission_callback' => array( $this, 'check_edit_pages_permission' ),
				'annotations' => array(
					'title'           => 'Create Page',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'title' => array(
							'type'        => 'string',
							'description' => __(  'Page title (required)', 'mcp-for-woocommerce' ),
						),
						'content' => array(
							'type'        => 'string',
							'description' => __(  'Page content', 'mcp-for-woocommerce' ),
						),
						'excerpt' => array(
							'type'        => 'string',
							'description' => __(  'Page excerpt', 'mcp-for-woocommerce' ),
						),
						'status' => array(
							'type'        => 'string',
							'enum'        => array( 'draft', 'publish', 'pending', 'private' ),
							'description' => __(  'Page status', 'mcp-for-woocommerce' ),
							'default'     => 'draft',
						),
						'parent_id' => array(
							'type'        => 'integer',
							'description' => __(  'Parent page ID', 'mcp-for-woocommerce' ),
						),
						'featured_image_id' => array(
							'type'        => 'integer',
							'description' => __(  'Featured image attachment ID', 'mcp-for-woocommerce' ),
						),
						'author_id' => array(
							'type'        => 'integer',
							'description' => __(  'Author user ID', 'mcp-for-woocommerce' ),
						),
						'menu_order' => array(
							'type'        => 'integer',
							'description' => __(  'Page order', 'mcp-for-woocommerce' ),
							'default'     => 0,
						),
					),
					'required' => array( 'title' ),
				),
			)
		);

		// Update Page
		new RegisterMcpTool(
			array(
				'name'        => 'wp_update_page',
				'description' => __(  'Update an existing WordPress page.', 'mcp-for-woocommerce' ),
				'type'        => 'update',
				'callback'    => array( $this, 'update_page' ),
				'permission_callback' => array( $this, 'check_edit_pages_permission' ),
				'annotations' => array(
					'title'           => 'Update Page',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array(
							'type'        => 'integer',
							'description' => __(  'Page ID (required)', 'mcp-for-woocommerce' ),
						),
						'title' => array(
							'type'        => 'string',
							'description' => __(  'Page title', 'mcp-for-woocommerce' ),
						),
						'content' => array(
							'type'        => 'string',
							'description' => __(  'Page content', 'mcp-for-woocommerce' ),
						),
						'excerpt' => array(
							'type'        => 'string',
							'description' => __(  'Page excerpt', 'mcp-for-woocommerce' ),
						),
						'status' => array(
							'type'        => 'string',
							'enum'        => array( 'draft', 'publish', 'pending', 'private' ),
							'description' => __(  'Page status', 'mcp-for-woocommerce' ),
						),
						'parent_id' => array(
							'type'        => 'integer',
							'description' => __(  'Parent page ID', 'mcp-for-woocommerce' ),
						),
						'featured_image_id' => array(
							'type'        => 'integer',
							'description' => __(  'Featured image attachment ID', 'mcp-for-woocommerce' ),
						),
						'menu_order' => array(
							'type'        => 'integer',
							'description' => __(  'Page order', 'mcp-for-woocommerce' ),
						),
					),
					'required' => array( 'id' ),
				),
			)
		);

		// Delete Page
		new RegisterMcpTool(
			array(
				'name'        => 'wp_delete_page',
				'description' => __(  'Delete a WordPress page. DESTRUCTIVE OPERATION.', 'mcp-for-woocommerce' ),
				'type'        => 'delete',
				'callback'    => array( $this, 'delete_page' ),
				'permission_callback' => array( $this, 'check_delete_pages_permission' ),
				'annotations' => array(
					'title'           => 'Delete Page',
					'readOnlyHint'    => false,
					'destructiveHint' => true,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array(
							'type'        => 'integer',
							'description' => __(  'Page ID to delete (required)', 'mcp-for-woocommerce' ),
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
	 * Check if user can edit posts
	 */
	public function check_edit_posts_permission(): bool {
		return current_user_can( 'edit_posts' );
	}

	/**
	 * Check if user can delete posts
	 */
	public function check_delete_posts_permission(): bool {
		return current_user_can( 'delete_posts' );
	}

	/**
	 * Check if user can publish posts
	 */
	public function check_publish_posts_permission(): bool {
		return current_user_can( 'publish_posts' );
	}

	/**
	 * Check if user can edit pages
	 */
	public function check_edit_pages_permission(): bool {
		return current_user_can( 'edit_pages' );
	}

	/**
	 * Check if user can delete pages
	 */
	public function check_delete_pages_permission(): bool {
		return current_user_can( 'delete_pages' );
	}

	/**
	 * Create a new post
	 */
	public function create_post( array $data ): array {
		try {
			if ( empty( $data['title'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Post title is required',
					'code'    => 'missing_required_field',
				);
			}

			$post_data = array(
				'post_title'   => sanitize_text_field( $data['title'] ),
				'post_content' => isset( $data['content'] ) ? wp_kses_post( $data['content'] ) : '',
				'post_status'  => $data['status'] ?? 'draft',
				'post_type'    => 'post',
			);

			if ( ! empty( $data['excerpt'] ) ) {
				$post_data['post_excerpt'] = sanitize_textarea_field( $data['excerpt'] );
			}

			if ( ! empty( $data['author_id'] ) ) {
				$post_data['post_author'] = intval( $data['author_id'] );
			}

			$post_id = wp_insert_post( $post_data, true );

			if ( is_wp_error( $post_id ) ) {
				return array(
					'success' => false,
					'error'   => $post_id->get_error_message(),
					'code'    => 'post_creation_failed',
				);
			}

			// Set categories
			if ( ! empty( $data['categories'] ) ) {
				wp_set_post_categories( $post_id, $data['categories'] );
			}

			// Set tags
			if ( ! empty( $data['tags'] ) ) {
				wp_set_post_tags( $post_id, $data['tags'] );
			}

			// Set featured image
			if ( ! empty( $data['featured_image_id'] ) ) {
				set_post_thumbnail( $post_id, $data['featured_image_id'] );
			}

			do_action( 'mcpfowo_post_created', $post_id, $data );

			$post = get_post( $post_id );

			return array(
				'success' => true,
				'post_id' => $post_id,
				'title'   => $post->post_title,
				'status'  => $post->post_status,
				'link'    => get_permalink( $post_id ),
				'message' => sprintf( 'Post "%s" created successfully', $post->post_title ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'post_creation_exception',
			);
		}
	}

	/**
	 * Update an existing post
	 */
	public function update_post( array $data ): array {
		try {
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Post ID is required',
					'code'    => 'missing_post_id',
				);
			}

			$post = get_post( $data['id'] );

			if ( ! $post || $post->post_type !== 'post' ) {
				return array(
					'success' => false,
					'error'   => 'Post not found',
					'code'    => 'post_not_found',
				);
			}

			$post_data = array( 'ID' => $data['id'] );

			if ( isset( $data['title'] ) ) {
				$post_data['post_title'] = sanitize_text_field( $data['title'] );
			}

			if ( isset( $data['content'] ) ) {
				$post_data['post_content'] = wp_kses_post( $data['content'] );
			}

			if ( isset( $data['excerpt'] ) ) {
				$post_data['post_excerpt'] = sanitize_textarea_field( $data['excerpt'] );
			}

			if ( isset( $data['status'] ) ) {
				$post_data['post_status'] = $data['status'];
			}

			$post_id = wp_update_post( $post_data, true );

			if ( is_wp_error( $post_id ) ) {
				return array(
					'success' => false,
					'error'   => $post_id->get_error_message(),
					'code'    => 'post_update_failed',
				);
			}

			// Update categories
			if ( isset( $data['categories'] ) ) {
				wp_set_post_categories( $post_id, $data['categories'] );
			}

			// Update tags
			if ( isset( $data['tags'] ) ) {
				wp_set_post_tags( $post_id, $data['tags'] );
			}

			// Update featured image
			if ( isset( $data['featured_image_id'] ) ) {
				if ( $data['featured_image_id'] > 0 ) {
					set_post_thumbnail( $post_id, $data['featured_image_id'] );
				} else {
					delete_post_thumbnail( $post_id );
				}
			}

			do_action( 'mcpfowo_post_updated', $post_id, $data );

			$updated_post = get_post( $post_id );

			return array(
				'success' => true,
				'post_id' => $post_id,
				'title'   => $updated_post->post_title,
				'status'  => $updated_post->post_status,
				'link'    => get_permalink( $post_id ),
				'message' => sprintf( 'Post "%s" updated successfully', $updated_post->post_title ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'post_update_exception',
			);
		}
	}

	/**
	 * Delete a post
	 */
	public function delete_post( array $data ): array {
		try {
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Post ID is required',
					'code'    => 'missing_post_id',
				);
			}

			$post = get_post( $data['id'] );

			if ( ! $post || $post->post_type !== 'post' ) {
				return array(
					'success' => false,
					'error'   => 'Post not found',
					'code'    => 'post_not_found',
				);
			}

			$post_title = $post->post_title;
			$force = $data['force'] ?? false;

			$result = wp_delete_post( $data['id'], $force );

			if ( ! $result ) {
				return array(
					'success' => false,
					'error'   => 'Failed to delete post',
					'code'    => 'post_deletion_failed',
				);
			}

			do_action( 'mcpfowo_post_deleted', $data['id'], $post_title );

			return array(
				'success' => true,
				'message' => sprintf( 'Post "%s" deleted successfully', $post_title ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'post_deletion_exception',
			);
		}
	}

	/**
	 * Publish a post
	 */
	public function publish_post( array $data ): array {
		try {
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Post ID is required',
					'code'    => 'missing_post_id',
				);
			}

			$post = get_post( $data['id'] );

			if ( ! $post || $post->post_type !== 'post' ) {
				return array(
					'success' => false,
					'error'   => 'Post not found',
					'code'    => 'post_not_found',
				);
			}

			$post_data = array(
				'ID'          => $data['id'],
				'post_status' => 'publish',
			);

			$post_id = wp_update_post( $post_data, true );

			if ( is_wp_error( $post_id ) ) {
				return array(
					'success' => false,
					'error'   => $post_id->get_error_message(),
					'code'    => 'post_publish_failed',
				);
			}

			do_action( 'mcpfowo_post_published', $post_id );

			$published_post = get_post( $post_id );

			return array(
				'success' => true,
				'post_id' => $post_id,
				'title'   => $published_post->post_title,
				'status'  => $published_post->post_status,
				'link'    => get_permalink( $post_id ),
				'message' => sprintf( 'Post "%s" published successfully', $published_post->post_title ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'post_publish_exception',
			);
		}
	}

	/**
	 * Create a new page
	 */
	public function create_page( array $data ): array {
		try {
			if ( empty( $data['title'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Page title is required',
					'code'    => 'missing_required_field',
				);
			}

			$post_data = array(
				'post_title'   => sanitize_text_field( $data['title'] ),
				'post_content' => isset( $data['content'] ) ? wp_kses_post( $data['content'] ) : '',
				'post_status'  => $data['status'] ?? 'draft',
				'post_type'    => 'page',
			);

			if ( ! empty( $data['excerpt'] ) ) {
				$post_data['post_excerpt'] = sanitize_textarea_field( $data['excerpt'] );
			}

			if ( ! empty( $data['parent_id'] ) ) {
				$post_data['post_parent'] = intval( $data['parent_id'] );
			}

			if ( ! empty( $data['author_id'] ) ) {
				$post_data['post_author'] = intval( $data['author_id'] );
			}

			if ( isset( $data['menu_order'] ) ) {
				$post_data['menu_order'] = intval( $data['menu_order'] );
			}

			$page_id = wp_insert_post( $post_data, true );

			if ( is_wp_error( $page_id ) ) {
				return array(
					'success' => false,
					'error'   => $page_id->get_error_message(),
					'code'    => 'page_creation_failed',
				);
			}

			// Set featured image
			if ( ! empty( $data['featured_image_id'] ) ) {
				set_post_thumbnail( $page_id, $data['featured_image_id'] );
			}

			do_action( 'mcpfowo_page_created', $page_id, $data );

			$page = get_post( $page_id );

			return array(
				'success' => true,
				'page_id' => $page_id,
				'title'   => $page->post_title,
				'status'  => $page->post_status,
				'link'    => get_permalink( $page_id ),
				'message' => sprintf( 'Page "%s" created successfully', $page->post_title ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'page_creation_exception',
			);
		}
	}

	/**
	 * Update an existing page
	 */
	public function update_page( array $data ): array {
		try {
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Page ID is required',
					'code'    => 'missing_page_id',
				);
			}

			$page = get_post( $data['id'] );

			if ( ! $page || $page->post_type !== 'page' ) {
				return array(
					'success' => false,
					'error'   => 'Page not found',
					'code'    => 'page_not_found',
				);
			}

			$post_data = array( 'ID' => $data['id'] );

			if ( isset( $data['title'] ) ) {
				$post_data['post_title'] = sanitize_text_field( $data['title'] );
			}

			if ( isset( $data['content'] ) ) {
				$post_data['post_content'] = wp_kses_post( $data['content'] );
			}

			if ( isset( $data['excerpt'] ) ) {
				$post_data['post_excerpt'] = sanitize_textarea_field( $data['excerpt'] );
			}

			if ( isset( $data['status'] ) ) {
				$post_data['post_status'] = $data['status'];
			}

			if ( isset( $data['parent_id'] ) ) {
				$post_data['post_parent'] = intval( $data['parent_id'] );
			}

			if ( isset( $data['menu_order'] ) ) {
				$post_data['menu_order'] = intval( $data['menu_order'] );
			}

			$page_id = wp_update_post( $post_data, true );

			if ( is_wp_error( $page_id ) ) {
				return array(
					'success' => false,
					'error'   => $page_id->get_error_message(),
					'code'    => 'page_update_failed',
				);
			}

			// Update featured image
			if ( isset( $data['featured_image_id'] ) ) {
				if ( $data['featured_image_id'] > 0 ) {
					set_post_thumbnail( $page_id, $data['featured_image_id'] );
				} else {
					delete_post_thumbnail( $page_id );
				}
			}

			do_action( 'mcpfowo_page_updated', $page_id, $data );

			$updated_page = get_post( $page_id );

			return array(
				'success' => true,
				'page_id' => $page_id,
				'title'   => $updated_page->post_title,
				'status'  => $updated_page->post_status,
				'link'    => get_permalink( $page_id ),
				'message' => sprintf( 'Page "%s" updated successfully', $updated_page->post_title ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'page_update_exception',
			);
		}
	}

	/**
	 * Delete a page
	 */
	public function delete_page( array $data ): array {
		try {
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Page ID is required',
					'code'    => 'missing_page_id',
				);
			}

			$page = get_post( $data['id'] );

			if ( ! $page || $page->post_type !== 'page' ) {
				return array(
					'success' => false,
					'error'   => 'Page not found',
					'code'    => 'page_not_found',
				);
			}

			$page_title = $page->post_title;
			$force = $data['force'] ?? false;

			$result = wp_delete_post( $data['id'], $force );

			if ( ! $result ) {
				return array(
					'success' => false,
					'error'   => 'Failed to delete page',
					'code'    => 'page_deletion_failed',
				);
			}

			do_action( 'mcpfowo_page_deleted', $data['id'], $page_title );

			return array(
				'success' => true,
				'message' => sprintf( 'Page "%s" deleted successfully', $page_title ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'page_deletion_exception',
			);
		}
	}
}
