<?php
declare(strict_types=1);

namespace McpForWoo\Tools\Write;

use McpForWoo\Core\RegisterMcpTool;
use WC_Product_Simple;

/**
 * Class McpBackupRestore
 *
 * Provides backup and restore operations for WooCommerce products and WordPress content.
 */
class McpBackupRestore {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'mcpfowo_init', array( $this, 'register_tools' ) );
	}

	/**
	 * Register backup/restore tools
	 */
	public function register_tools(): void {
		$settings = get_option( 'mcpfowo_settings', array() );
		if ( empty( $settings['enable_write_operations'] ) ) {
			return;
		}

		// Backup Products
		if ( function_exists( 'WC' ) ) {
			new RegisterMcpTool(
				array(
					'name'        => 'wc_backup_products',
					'description' => __(  'Create a backup of WooCommerce products. Returns JSON data with all product information that can be restored later.', 'mcp-for-woocommerce' ),
					'type'        => 'write',
					'callback'    => array( $this, 'backup_products' ),
					'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
					'annotations' => array(
						'title'           => 'Backup Products',
						'readOnlyHint'    => false,
						'destructiveHint' => false,
						'idempotentHint'  => true,
					),
					'inputSchema' => array(
						'type'       => 'object',
						'properties' => array(
							'product_ids' => array(
								'type'        => 'array',
								'description' => __(  'Array of product IDs to backup (optional, empty for all products)', 'mcp-for-woocommerce' ),
								'items'       => array( 'type' => 'integer' ),
							),
							'include_metadata' => array(
								'type'        => 'boolean',
								'description' => __(  'Whether to include product metadata', 'mcp-for-woocommerce' ),
								'default'     => true,
							),
						),
					),
				)
			);

			// Restore Products
			new RegisterMcpTool(
				array(
					'name'        => 'wc_restore_products',
					'description' => __(  'Restore WooCommerce products from backup JSON data. Can create new products or update existing ones.', 'mcp-for-woocommerce' ),
					'type'        => 'write',
					'callback'    => array( $this, 'restore_products' ),
					'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
					'annotations' => array(
						'title'           => 'Restore Products',
						'readOnlyHint'    => false,
						'destructiveHint' => false,
						'idempotentHint'  => false,
					),
					'inputSchema' => array(
						'type'       => 'object',
						'properties' => array(
							'backup_data' => array(
								'type'        => 'string',
								'description' => __(  'JSON string containing backup data (required)', 'mcp-for-woocommerce' ),
							),
							'update_existing' => array(
								'type'        => 'boolean',
								'description' => __(  'Whether to update existing products by SKU', 'mcp-for-woocommerce' ),
								'default'     => true,
							),
						),
						'required' => array( 'backup_data' ),
					),
				)
			);
		}

		// Backup WordPress Content
		new RegisterMcpTool(
			array(
				'name'        => 'wp_backup_content',
				'description' => __(  'Create a backup of WordPress content (posts, pages, media). Returns JSON data with all content that can be restored later.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'backup_content' ),
				'permission_callback' => array( $this, 'check_manage_options_permission' ),
				'annotations' => array(
					'title'           => 'Backup WordPress Content',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'post_types' => array(
							'type'        => 'array',
							'description' => __(  'Post types to backup (default: post, page)', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'string' ),
							'default'     => array( 'post', 'page' ),
						),
						'include_media' => array(
							'type'        => 'boolean',
							'description' => __(  'Whether to include media metadata', 'mcp-for-woocommerce' ),
							'default'     => true,
						),
					),
				),
			)
		);

		// Restore WordPress Content
		new RegisterMcpTool(
			array(
				'name'        => 'wp_restore_content',
				'description' => __(  'Restore WordPress content (posts, pages) from backup JSON data.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'restore_content' ),
				'permission_callback' => array( $this, 'check_manage_options_permission' ),
				'annotations' => array(
					'title'           => 'Restore WordPress Content',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'backup_data' => array(
							'type'        => 'string',
							'description' => __(  'JSON string containing backup data (required)', 'mcp-for-woocommerce' ),
						),
						'update_existing' => array(
							'type'        => 'boolean',
							'description' => __(  'Whether to update existing posts/pages by title', 'mcp-for-woocommerce' ),
							'default'     => true,
						),
					),
					'required' => array( 'backup_data' ),
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
	 * Check if user has permission to manage options
	 *
	 * @return bool
	 */
	public function check_manage_options_permission(): bool {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Backup products
	 *
	 * @param array $data Backup parameters.
	 * @return array Backup data.
	 */
	public function backup_products( array $data ): array {
		try {
			if ( ! function_exists( 'WC' ) ) {
				return array(
					'success' => false,
					'error'   => 'WooCommerce is not active',
					'code'    => 'woocommerce_not_active',
				);
			}

			$product_ids = $data['product_ids'] ?? array();
			$include_metadata = $data['include_metadata'] ?? true;

			if ( empty( $product_ids ) ) {
				// Get all products
				$args = array(
					'limit'  => -1,
					'status' => 'any',
					'return' => 'ids',
				);
				$product_ids = wc_get_products( $args );
			}

			$backup = array(
				'version' => '1.0',
				'date' => current_time( 'mysql' ),
				'products' => array(),
			);

			foreach ( $product_ids as $product_id ) {
				$product = wc_get_product( $product_id );
				if ( ! $product ) {
					continue;
				}

				$product_data = array(
					'id' => $product->get_id(),
					'name' => $product->get_name(),
					'sku' => $product->get_sku(),
					'type' => $product->get_type(),
					'status' => $product->get_status(),
					'description' => $product->get_description(),
					'short_description' => $product->get_short_description(),
					'regular_price' => $product->get_regular_price(),
					'sale_price' => $product->get_sale_price(),
					'manage_stock' => $product->get_manage_stock(),
					'stock_quantity' => $product->get_stock_quantity(),
					'stock_status' => $product->get_stock_status(),
					'weight' => $product->get_weight(),
					'length' => $product->get_length(),
					'width' => $product->get_width(),
					'height' => $product->get_height(),
					'category_ids' => $product->get_category_ids(),
					'tag_ids' => $product->get_tag_ids(),
					'image_id' => $product->get_image_id(),
					'gallery_image_ids' => $product->get_gallery_image_ids(),
				);

				if ( $include_metadata ) {
					$product_data['meta_data'] = $product->get_meta_data();
				}

				$backup['products'][] = $product_data;
			}

			$backup_json = wp_json_encode( $backup, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );

			return array(
				'success' => true,
				'backup_data' => $backup_json,
				'products_count' => count( $backup['products'] ),
				'message' => sprintf( 'Backup created for %d products', count( $backup['products'] ) ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'backup_exception',
			);
		}
	}

	/**
	 * Restore products
	 *
	 * @param array $data Restore parameters.
	 * @return array Restore results.
	 */
	public function restore_products( array $data ): array {
		try {
			if ( ! function_exists( 'WC' ) ) {
				return array(
					'success' => false,
					'error'   => 'WooCommerce is not active',
					'code'    => 'woocommerce_not_active',
				);
			}

			if ( empty( $data['backup_data'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Backup data is required',
					'code'    => 'missing_backup_data',
				);
			}

			$backup = json_decode( $data['backup_data'], true );
			if ( ! $backup || ! isset( $backup['products'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Invalid backup data format',
					'code'    => 'invalid_backup_format',
				);
			}

			$update_existing = $data['update_existing'] ?? true;
			$results = array(
				'success' => true,
				'created' => 0,
				'updated' => 0,
				'failed' => 0,
				'messages' => array(),
			);

			foreach ( $backup['products'] as $product_data ) {
				try {
					$product = null;

					// Try to find existing product by SKU
					if ( $update_existing && ! empty( $product_data['sku'] ) ) {
						$existing_id = wc_get_product_id_by_sku( $product_data['sku'] );
						if ( $existing_id ) {
							$product = wc_get_product( $existing_id );
						}
					}

					// Create new product if not found
					if ( ! $product ) {
						$product = new WC_Product_Simple();
					}

					// Restore product data
					if ( isset( $product_data['name'] ) ) {
						$product->set_name( $product_data['name'] );
					}
					if ( isset( $product_data['sku'] ) ) {
						$product->set_sku( $product_data['sku'] );
					}
					if ( isset( $product_data['status'] ) ) {
						$product->set_status( $product_data['status'] );
					}
					if ( isset( $product_data['description'] ) ) {
						$product->set_description( $product_data['description'] );
					}
					if ( isset( $product_data['short_description'] ) ) {
						$product->set_short_description( $product_data['short_description'] );
					}
					if ( isset( $product_data['regular_price'] ) ) {
						$product->set_regular_price( $product_data['regular_price'] );
					}
					if ( isset( $product_data['sale_price'] ) ) {
						$product->set_sale_price( $product_data['sale_price'] );
					}
					if ( isset( $product_data['manage_stock'] ) ) {
						$product->set_manage_stock( $product_data['manage_stock'] );
					}
					if ( isset( $product_data['stock_quantity'] ) ) {
						$product->set_stock_quantity( $product_data['stock_quantity'] );
					}
					if ( isset( $product_data['stock_status'] ) ) {
						$product->set_stock_status( $product_data['stock_status'] );
					}
					if ( isset( $product_data['weight'] ) ) {
						$product->set_weight( $product_data['weight'] );
					}
					if ( isset( $product_data['length'] ) ) {
						$product->set_length( $product_data['length'] );
					}
					if ( isset( $product_data['width'] ) ) {
						$product->set_width( $product_data['width'] );
					}
					if ( isset( $product_data['height'] ) ) {
						$product->set_height( $product_data['height'] );
					}
					if ( isset( $product_data['category_ids'] ) ) {
						$product->set_category_ids( $product_data['category_ids'] );
					}
					if ( isset( $product_data['tag_ids'] ) ) {
						$product->set_tag_ids( $product_data['tag_ids'] );
					}
					if ( isset( $product_data['image_id'] ) ) {
						$product->set_image_id( $product_data['image_id'] );
					}
					if ( isset( $product_data['gallery_image_ids'] ) ) {
						$product->set_gallery_image_ids( $product_data['gallery_image_ids'] );
					}

					$product_id = $product->save();

					if ( $product_id ) {
						if ( $update_existing && isset( $product_data['id'] ) && $product->get_id() === $product_data['id'] ) {
							$results['updated']++;
							$results['messages'][] = sprintf( 'Product "%s" updated (ID: %d)', $product_data['name'], $product_id );
						} else {
							$results['created']++;
							$results['messages'][] = sprintf( 'Product "%s" created (ID: %d)', $product_data['name'], $product_id );
						}
					} else {
						$results['failed']++;
						$results['messages'][] = sprintf( 'Failed to restore product "%s"', $product_data['name'] ?? 'Unknown' );
					}

				} catch ( \Exception $e ) {
					$results['failed']++;
					$results['messages'][] = sprintf( 'Error restoring product: %s', $e->getMessage() );
				}
			}

			return $results;

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'restore_exception',
			);
		}
	}

	/**
	 * Backup WordPress content
	 *
	 * @param array $data Backup parameters.
	 * @return array Backup data.
	 */
	public function backup_content( array $data ): array {
		try {
			$post_types = $data['post_types'] ?? array( 'post', 'page' );
			$include_media = $data['include_media'] ?? true;

			$backup = array(
				'version' => '1.0',
				'date' => current_time( 'mysql' ),
				'posts' => array(),
				'pages' => array(),
			);

			foreach ( $post_types as $post_type ) {
				$posts = get_posts(
					array(
						'post_type'      => $post_type,
						'posts_per_page' => -1,
						'post_status'    => 'any',
					)
				);

				foreach ( $posts as $post ) {
					$post_data = array(
						'ID' => $post->ID,
						'post_title' => $post->post_title,
						'post_content' => $post->post_content,
						'post_excerpt' => $post->post_excerpt,
						'post_status' => $post->post_status,
						'post_type' => $post->post_type,
						'post_date' => $post->post_date,
						'post_author' => $post->post_author,
						'post_category' => wp_get_post_categories( $post->ID ),
						'tags' => wp_get_post_tags( $post->ID, array( 'fields' => 'ids' ) ),
						'featured_image' => get_post_thumbnail_id( $post->ID ),
					);

					if ( $include_media ) {
						$post_data['meta'] = get_post_meta( $post->ID );
					}

					if ( 'post' === $post_type ) {
						$backup['posts'][] = $post_data;
					} elseif ( 'page' === $post_type ) {
						$backup['pages'][] = $post_data;
					}
				}
			}

			$backup_json = wp_json_encode( $backup, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );

			return array(
				'success' => true,
				'backup_data' => $backup_json,
				'posts_count' => count( $backup['posts'] ),
				'pages_count' => count( $backup['pages'] ),
				'message' => sprintf( 'Backup created for %d posts and %d pages', count( $backup['posts'] ), count( $backup['pages'] ) ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'backup_exception',
			);
		}
	}

	/**
	 * Restore WordPress content
	 *
	 * @param array $data Restore parameters.
	 * @return array Restore results.
	 */
	public function restore_content( array $data ): array {
		try {
			if ( empty( $data['backup_data'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Backup data is required',
					'code'    => 'missing_backup_data',
				);
			}

			$backup = json_decode( $data['backup_data'], true );
			if ( ! $backup ) {
				return array(
					'success' => false,
					'error'   => 'Invalid backup data format',
					'code'    => 'invalid_backup_format',
				);
			}

			$update_existing = $data['update_existing'] ?? true;
			$results = array(
				'success' => true,
				'created' => 0,
				'updated' => 0,
				'failed' => 0,
				'messages' => array(),
			);

			// Restore posts
			if ( isset( $backup['posts'] ) ) {
				foreach ( $backup['posts'] as $post_data ) {
					$result = $this->restore_post( $post_data, $update_existing );
					if ( $result['success'] ) {
						if ( $result['updated'] ) {
							$results['updated']++;
						} else {
							$results['created']++;
						}
						$results['messages'][] = $result['message'];
					} else {
						$results['failed']++;
						$results['messages'][] = $result['error'];
					}
				}
			}

			// Restore pages
			if ( isset( $backup['pages'] ) ) {
				foreach ( $backup['pages'] as $page_data ) {
					$result = $this->restore_post( $page_data, $update_existing );
					if ( $result['success'] ) {
						if ( $result['updated'] ) {
							$results['updated']++;
						} else {
							$results['created']++;
						}
						$results['messages'][] = $result['message'];
					} else {
						$results['failed']++;
						$results['messages'][] = $result['error'];
					}
				}
			}

			return $results;

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'restore_exception',
			);
		}
	}

	/**
	 * Restore single post/page
	 *
	 * @param array $post_data Post data.
	 * @param bool $update_existing Whether to update existing.
	 * @return array Result.
	 */
	private function restore_post( array $post_data, bool $update_existing ): array {
		try {
			$post_id = null;

			// Try to find existing post by title
			if ( $update_existing && ! empty( $post_data['post_title'] ) ) {
				$existing = get_page_by_title( $post_data['post_title'], OBJECT, $post_data['post_type'] ?? 'post' );
				if ( $existing ) {
					$post_id = $existing->ID;
				}
			}

			$post_args = array(
				'post_title' => $post_data['post_title'] ?? '',
				'post_content' => $post_data['post_content'] ?? '',
				'post_excerpt' => $post_data['post_excerpt'] ?? '',
				'post_status' => $post_data['post_status'] ?? 'publish',
				'post_type' => $post_data['post_type'] ?? 'post',
				'post_author' => $post_data['post_author'] ?? 1,
			);

			if ( $post_id ) {
				$post_args['ID'] = $post_id;
			}

			$new_post_id = wp_insert_post( $post_args, true );

			if ( is_wp_error( $new_post_id ) ) {
				return array(
					'success' => false,
					'error' => $new_post_id->get_error_message(),
				);
			}

			// Set categories
			if ( ! empty( $post_data['post_category'] ) ) {
				wp_set_post_categories( $new_post_id, $post_data['post_category'] );
			}

			// Set tags
			if ( ! empty( $post_data['tags'] ) ) {
				wp_set_post_tags( $new_post_id, $post_data['tags'] );
			}

			// Set featured image
			if ( ! empty( $post_data['featured_image'] ) ) {
				set_post_thumbnail( $new_post_id, $post_data['featured_image'] );
			}

			// Restore meta
			if ( ! empty( $post_data['meta'] ) ) {
				foreach ( $post_data['meta'] as $key => $value ) {
					if ( is_array( $value ) && count( $value ) === 1 ) {
						$value = $value[0];
					}
					update_post_meta( $new_post_id, $key, $value );
				}
			}

			return array(
				'success' => true,
				'updated' => $post_id !== null,
				'message' => sprintf(
					'%s "%s" %s (ID: %d)',
					ucfirst( $post_data['post_type'] ?? 'post' ),
					$post_data['post_title'],
					$post_id ? 'updated' : 'created',
					$new_post_id
				),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error' => $e->getMessage(),
			);
		}
	}
}

