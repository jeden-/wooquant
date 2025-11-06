<?php
declare(strict_types=1);

namespace McpForWoo\Tools\Write;

use McpForWoo\Core\RegisterMcpTool;
use WC_Product;
use WC_Product_Simple;
use WC_Product_Variable;

/**
 * Class McpWooProductsWrite
 *
 * Provides WooCommerce write operations for products (Create, Update, Delete).
 * Only available with proper permissions.
 */
class McpWooProductsWrite {

	/**
	 * Constructor for McpWooProductsWrite.
	 */
	public function __construct() {
		add_action( 'mcpfowo_init', array( $this, 'register_tools' ) );
	}

	/**
	 * Registers WooCommerce write tools for products if WooCommerce is active.
	 *
	 * @return void
	 */
	public function register_tools(): void {
		// Only register if WooCommerce is active
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		// Check if write operations are enabled in settings
		$settings = get_option( 'mcpfowo_settings', array() );
		if ( empty( $settings['enable_write_operations'] ) ) {
			return;
		}

		// Create Product
		new RegisterMcpTool(
			array(
				'name'        => 'wc_create_product',
				'description' => __(  'Create a new WooCommerce product. Supports simple and variable products with full configuration including pricing, inventory, images, categories, tags, and attributes.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'create_product' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Create Product',
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
							'description' => __(  'Product name/title (required)', 'mcp-for-woocommerce' ),
						),
						'type' => array(
							'type'        => 'string',
							'enum'        => array( 'simple', 'variable', 'grouped', 'external' ),
							'description' => __(  'Product type (default: simple)', 'mcp-for-woocommerce' ),
							'default'     => 'simple',
						),
						'status' => array(
							'type'        => 'string',
							'enum'        => array( 'draft', 'pending', 'private', 'publish' ),
							'description' => __(  'Product status (default: publish)', 'mcp-for-woocommerce' ),
							'default'     => 'publish',
						),
						'description' => array(
							'type'        => 'string',
							'description' => __(  'Product long description', 'mcp-for-woocommerce' ),
						),
						'short_description' => array(
							'type'        => 'string',
							'description' => __(  'Product short description', 'mcp-for-woocommerce' ),
						),
						'sku' => array(
							'type'        => 'string',
							'description' => __(  'Product SKU (Stock Keeping Unit)', 'mcp-for-woocommerce' ),
						),
						'regular_price' => array(
							'type'        => 'string',
							'description' => __(  'Product regular price', 'mcp-for-woocommerce' ),
						),
						'sale_price' => array(
							'type'        => 'string',
							'description' => __(  'Product sale price', 'mcp-for-woocommerce' ),
						),
						'manage_stock' => array(
							'type'        => 'boolean',
							'description' => __(  'Enable stock management', 'mcp-for-woocommerce' ),
							'default'     => false,
						),
						'stock_quantity' => array(
							'type'        => 'integer',
							'description' => __(  'Stock quantity (if manage_stock is true)', 'mcp-for-woocommerce' ),
						),
						'stock_status' => array(
							'type'        => 'string',
							'enum'        => array( 'instock', 'outofstock', 'onbackorder' ),
							'description' => __(  'Stock status', 'mcp-for-woocommerce' ),
							'default'     => 'instock',
						),
						'categories' => array(
							'type'        => 'array',
							'description' => __(  'Array of category IDs', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'integer' ),
						),
						'tags' => array(
							'type'        => 'array',
							'description' => __(  'Array of tag IDs', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'integer' ),
						),
						'images' => array(
							'type'        => 'array',
							'description' => __(  'Array of image URLs or attachment IDs', 'mcp-for-woocommerce' ),
							'items'       => array(
								'type' => 'object',
								'properties' => array(
									'src' => array( 'type' => 'string' ),
									'id'  => array( 'type' => 'integer' ),
								),
							),
						),
						'attributes' => array(
							'type'        => 'array',
							'description' => __(  'Product attributes', 'mcp-for-woocommerce' ),
							'items'       => array(
								'type'       => 'object',
								'properties' => array(
									'name'      => array( 'type' => 'string' ),
									'options'   => array( 'type' => 'array', 'items' => array( 'type' => 'string' ) ),
									'visible'   => array( 'type' => 'boolean', 'default' => true ),
									'variation' => array( 'type' => 'boolean', 'default' => false ),
								),
							),
						),
						'weight' => array(
							'type'        => 'string',
							'description' => __(  'Product weight', 'mcp-for-woocommerce' ),
						),
						'dimensions' => array(
							'type'        => 'object',
							'description' => __(  'Product dimensions', 'mcp-for-woocommerce' ),
							'properties'  => array(
								'length' => array( 'type' => 'string' ),
								'width'  => array( 'type' => 'string' ),
								'height' => array( 'type' => 'string' ),
							),
						),
					),
					'required' => array( 'name' ),
				),
			)
		);

		// Update Product
		new RegisterMcpTool(
			array(
				'name'        => 'wc_update_product',
				'description' => __(  'Update an existing WooCommerce product. Can update any product field including name, description, price, stock, images, categories, etc.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'update_product' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Update Product',
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
							'description' => __(  'Product ID to update (required)', 'mcp-for-woocommerce' ),
						),
						'name' => array(
							'type'        => 'string',
							'description' => __(  'Product name/title', 'mcp-for-woocommerce' ),
						),
						'status' => array(
							'type'        => 'string',
							'enum'        => array( 'draft', 'pending', 'private', 'publish' ),
							'description' => __(  'Product status', 'mcp-for-woocommerce' ),
						),
						'description' => array(
							'type'        => 'string',
							'description' => __(  'Product long description', 'mcp-for-woocommerce' ),
						),
						'short_description' => array(
							'type'        => 'string',
							'description' => __(  'Product short description', 'mcp-for-woocommerce' ),
						),
						'regular_price' => array(
							'type'        => 'string',
							'description' => __(  'Product regular price', 'mcp-for-woocommerce' ),
						),
						'sale_price' => array(
							'type'        => 'string',
							'description' => __(  'Product sale price', 'mcp-for-woocommerce' ),
						),
						'sku' => array(
							'type'        => 'string',
							'description' => __(  'Product SKU', 'mcp-for-woocommerce' ),
						),
						'manage_stock' => array(
							'type'        => 'boolean',
							'description' => __(  'Enable stock management', 'mcp-for-woocommerce' ),
						),
						'stock_quantity' => array(
							'type'        => 'integer',
							'description' => __(  'Stock quantity', 'mcp-for-woocommerce' ),
						),
						'stock_status' => array(
							'type'        => 'string',
							'enum'        => array( 'instock', 'outofstock', 'onbackorder' ),
							'description' => __(  'Stock status', 'mcp-for-woocommerce' ),
						),
						'categories' => array(
							'type'        => 'array',
							'description' => __(  'Array of category IDs', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'integer' ),
						),
						'tags' => array(
							'type'        => 'array',
							'description' => __(  'Array of tag IDs', 'mcp-for-woocommerce' ),
							'items'       => array( 'type' => 'integer' ),
						),
					),
					'required' => array( 'id' ),
				),
			)
		);

		// Delete Product
		new RegisterMcpTool(
			array(
				'name'        => 'wc_delete_product',
				'description' => __(  'Delete a WooCommerce product. Can permanently delete or move to trash. DESTRUCTIVE OPERATION - use with caution.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'delete_product' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Delete Product',
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
							'description' => __(  'Product ID to delete (required)', 'mcp-for-woocommerce' ),
						),
						'force' => array(
							'type'        => 'boolean',
							'description' => __(  'Whether to permanently delete (true) or move to trash (false, default)', 'mcp-for-woocommerce' ),
							'default'     => false,
						),
					),
					'required' => array( 'id' ),
				),
			)
		);

		// Bulk Update Products
		new RegisterMcpTool(
			array(
				'name'        => 'wc_bulk_update_products',
				'description' => __(  'Bulk update multiple products at once. Useful for updating prices, stock, or other fields for multiple products.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'bulk_update_products' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Bulk Update Products',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
					'openWorldHint'   => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'products' => array(
							'type'        => 'array',
							'description' => __(  'Array of products to update with their IDs and fields', 'mcp-for-woocommerce' ),
							'items'       => array(
								'type'       => 'object',
								'properties' => array(
									'id'            => array( 'type' => 'integer' ),
									'regular_price' => array( 'type' => 'string' ),
									'sale_price'    => array( 'type' => 'string' ),
									'stock_status'  => array( 'type' => 'string' ),
									'stock_quantity' => array( 'type' => 'integer' ),
								),
								'required' => array( 'id' ),
							),
						),
					),
					'required' => array( 'products' ),
				),
			)
		);
	}

	/**
	 * Check if user has permission to manage WooCommerce.
	 *
	 * @return bool
	 */
	public function check_manage_woocommerce_permission(): bool {
		return current_user_can( 'manage_woocommerce' ) || current_user_can( 'manage_options' );
	}

	/**
	 * Create a new product.
	 *
	 * @param array $data Product data.
	 * @return array Response data.
	 */
	public function create_product( array $data ): array {
		try {
			// Validate required fields
			if ( empty( $data['name'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Product name is required',
					'code'    => 'missing_required_field',
				);
			}

			// Determine product type
			$product_type = $data['type'] ?? 'simple';
			
			// Create product object based on type
			switch ( $product_type ) {
				case 'variable':
					$product = new WC_Product_Variable();
					break;
				case 'simple':
				default:
					$product = new WC_Product_Simple();
					break;
			}

			// Set basic properties
			$product->set_name( $data['name'] );
			$product->set_status( $data['status'] ?? 'publish' );

			if ( ! empty( $data['description'] ) ) {
				$product->set_description( $data['description'] );
			}

			if ( ! empty( $data['short_description'] ) ) {
				$product->set_short_description( $data['short_description'] );
			}

			if ( ! empty( $data['sku'] ) ) {
				$product->set_sku( $data['sku'] );
			}

			// Set pricing
			if ( isset( $data['regular_price'] ) ) {
				$product->set_regular_price( $data['regular_price'] );
			}

			if ( isset( $data['sale_price'] ) ) {
				$product->set_sale_price( $data['sale_price'] );
			}

			// Set stock management
			if ( isset( $data['manage_stock'] ) ) {
				$product->set_manage_stock( $data['manage_stock'] );
				
				if ( $data['manage_stock'] && isset( $data['stock_quantity'] ) ) {
					$product->set_stock_quantity( $data['stock_quantity'] );
				}
			}

			if ( isset( $data['stock_status'] ) ) {
				$product->set_stock_status( $data['stock_status'] );
			}

			// Set categories
			if ( ! empty( $data['categories'] ) ) {
				$product->set_category_ids( $data['categories'] );
			}

			// Set tags
			if ( ! empty( $data['tags'] ) ) {
				$product->set_tag_ids( $data['tags'] );
			}

			// Set weight and dimensions
			if ( isset( $data['weight'] ) ) {
				$product->set_weight( $data['weight'] );
			}

			if ( ! empty( $data['dimensions'] ) ) {
				if ( isset( $data['dimensions']['length'] ) ) {
					$product->set_length( $data['dimensions']['length'] );
				}
				if ( isset( $data['dimensions']['width'] ) ) {
					$product->set_width( $data['dimensions']['width'] );
				}
				if ( isset( $data['dimensions']['height'] ) ) {
					$product->set_height( $data['dimensions']['height'] );
				}
			}

			// Set images
			if ( ! empty( $data['images'] ) ) {
				$image_ids = array();
				foreach ( $data['images'] as $image ) {
					if ( isset( $image['id'] ) ) {
						$image_ids[] = $image['id'];
					} elseif ( isset( $image['src'] ) ) {
						// Download and attach image from URL
						$attachment_id = $this->upload_image_from_url( $image['src'], $product->get_id() );
						if ( $attachment_id ) {
							$image_ids[] = $attachment_id;
						}
					}
				}
				
				if ( ! empty( $image_ids ) ) {
					$product->set_image_id( $image_ids[0] );
					if ( count( $image_ids ) > 1 ) {
						$product->set_gallery_image_ids( array_slice( $image_ids, 1 ) );
					}
				}
			}

			// Set attributes
			if ( ! empty( $data['attributes'] ) ) {
				$attributes = array();
				foreach ( $data['attributes'] as $attr_data ) {
					$attribute = new \WC_Product_Attribute();
					$attribute->set_name( $attr_data['name'] );
					$attribute->set_options( $attr_data['options'] ?? array() );
					$attribute->set_visible( $attr_data['visible'] ?? true );
					$attribute->set_variation( $attr_data['variation'] ?? false );
					$attributes[] = $attribute;
				}
				$product->set_attributes( $attributes );
			}

			// Save product
			$product_id = $product->save();

			// Log the action
			do_action( 'mcpfowo_product_created', $product_id, $data );

			return array(
				'success'    => true,
				'product_id' => $product_id,
				'permalink'  => get_permalink( $product_id ),
				'message'    => sprintf( 'Product "%s" created successfully', $product->get_name() ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'product_creation_failed',
			);
		}
	}

	/**
	 * Update an existing product.
	 *
	 * @param array $data Product data with ID.
	 * @return array Response data.
	 */
	public function update_product( array $data ): array {
		try {
			// Validate product ID
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Product ID is required',
					'code'    => 'missing_product_id',
				);
			}

			$product = wc_get_product( $data['id'] );

			if ( ! $product ) {
				return array(
					'success' => false,
					'error'   => 'Product not found',
					'code'    => 'product_not_found',
				);
			}

			// Update fields if provided
			if ( isset( $data['name'] ) ) {
				$product->set_name( $data['name'] );
			}

			if ( isset( $data['status'] ) ) {
				$product->set_status( $data['status'] );
			}

			if ( isset( $data['description'] ) ) {
				$product->set_description( $data['description'] );
			}

			if ( isset( $data['short_description'] ) ) {
				$product->set_short_description( $data['short_description'] );
			}

			if ( isset( $data['sku'] ) ) {
				$product->set_sku( $data['sku'] );
			}

			if ( isset( $data['regular_price'] ) ) {
				$product->set_regular_price( $data['regular_price'] );
			}

			if ( isset( $data['sale_price'] ) ) {
				$product->set_sale_price( $data['sale_price'] );
			}

			if ( isset( $data['manage_stock'] ) ) {
				$product->set_manage_stock( $data['manage_stock'] );
			}

			if ( isset( $data['stock_quantity'] ) ) {
				$product->set_stock_quantity( $data['stock_quantity'] );
			}

			if ( isset( $data['stock_status'] ) ) {
				$product->set_stock_status( $data['stock_status'] );
			}

			if ( isset( $data['categories'] ) ) {
				$product->set_category_ids( $data['categories'] );
			}

			if ( isset( $data['tags'] ) ) {
				$product->set_tag_ids( $data['tags'] );
			}

			// Save updated product
			$product->save();

			// Log the action
			do_action( 'mcpfowo_product_updated', $product->get_id(), $data );

			return array(
				'success'    => true,
				'product_id' => $product->get_id(),
				'permalink'  => get_permalink( $product->get_id() ),
				'message'    => sprintf( 'Product "%s" updated successfully', $product->get_name() ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'product_update_failed',
			);
		}
	}

	/**
	 * Delete a product.
	 *
	 * @param array $data Product data with ID.
	 * @return array Response data.
	 */
	public function delete_product( array $data ): array {
		try {
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Product ID is required',
					'code'    => 'missing_product_id',
				);
			}

			$product = wc_get_product( $data['id'] );

			if ( ! $product ) {
				return array(
					'success' => false,
					'error'   => 'Product not found',
					'code'    => 'product_not_found',
				);
			}

			$product_name = $product->get_name();
			$force_delete = $data['force'] ?? false;

			// Delete product
			$result = $product->delete( $force_delete );

			if ( ! $result ) {
				return array(
					'success' => false,
					'error'   => 'Failed to delete product',
					'code'    => 'product_deletion_failed',
				);
			}

			// Log the action
			do_action( 'mcpfowo_product_deleted', $data['id'], $force_delete );

			return array(
				'success' => true,
				'message' => sprintf(
					'Product "%s" %s successfully',
					$product_name,
					$force_delete ? 'permanently deleted' : 'moved to trash'
				),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'product_deletion_failed',
			);
		}
	}

	/**
	 * Bulk update products.
	 *
	 * @param array $data Bulk update data.
	 * @return array Response data.
	 */
	public function bulk_update_products( array $data ): array {
		try {
			if ( empty( $data['products'] ) || ! is_array( $data['products'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Products array is required',
					'code'    => 'missing_products_array',
				);
			}

			$results = array(
				'success'  => true,
				'updated'  => 0,
				'failed'   => 0,
				'messages' => array(),
			);

			foreach ( $data['products'] as $product_data ) {
				if ( empty( $product_data['id'] ) ) {
					$results['failed']++;
					$results['messages'][] = 'Skipped product without ID';
					continue;
				}

				$update_result = $this->update_product( $product_data );

				if ( $update_result['success'] ) {
					$results['updated']++;
					$results['messages'][] = $update_result['message'];
				} else {
					$results['failed']++;
					$results['messages'][] = 'Product ID ' . $product_data['id'] . ': ' . $update_result['error'];
				}
			}

			// Log the action
			do_action( 'mcpfowo_products_bulk_updated', $results );

			return $results;

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'bulk_update_failed',
			);
		}
	}

	/**
	 * Upload image from URL.
	 *
	 * @param string $image_url Image URL.
	 * @param int    $post_id   Post ID to attach to.
	 * @return int|false Attachment ID or false on failure.
	 */
	private function upload_image_from_url( string $image_url, int $post_id ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$tmp = download_url( $image_url );

		if ( is_wp_error( $tmp ) ) {
			return false;
		}

		$file_array = array(
			'name'     => basename( $image_url ),
			'tmp_name' => $tmp,
		);

		$id = media_handle_sideload( $file_array, $post_id );

		if ( is_wp_error( $id ) ) {
			@unlink( $file_array['tmp_name'] );
			return false;
		}

		return $id;
	}
}
