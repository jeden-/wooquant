<?php
declare(strict_types=1);

namespace McpForWoo\Tools\Write;

use McpForWoo\Core\RegisterMcpTool;

/**
 * Class McpWooReviewsWrite
 *
 * Provides WooCommerce write operations for product reviews.
 */
class McpWooReviewsWrite {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'mcpfowo_init', array( $this, 'register_tools' ) );
	}

	/**
	 * Register review write tools
	 */
	public function register_tools(): void {
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		$settings = get_option( 'mcpfowo_settings', array() );
		if ( empty( $settings['enable_write_operations'] ) ) {
			return;
		}

		// Create Review
		new RegisterMcpTool(
			array(
				'name'        => 'wc_create_review',
				'description' => __(  'Create a new WooCommerce product review with rating, content, and reviewer information.', 'mcp-for-woocommerce' ),
				'type'        => 'create',
				'callback'    => array( $this, 'create_review' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Create Review',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'product_id' => array(
							'type'        => 'integer',
							'description' => __(  'Product ID (required)', 'mcp-for-woocommerce' ),
						),
						'reviewer_name' => array(
							'type'        => 'string',
							'description' => __(  'Reviewer name (required)', 'mcp-for-woocommerce' ),
						),
						'reviewer_email' => array(
							'type'        => 'string',
							'description' => __(  'Reviewer email (required)', 'mcp-for-woocommerce' ),
						),
						'content' => array(
							'type'        => 'string',
							'description' => __(  'Review content (required)', 'mcp-for-woocommerce' ),
						),
						'rating' => array(
							'type'        => 'integer',
							'description' => __(  'Rating (1-5)', 'mcp-for-woocommerce' ),
							'minimum'     => 1,
							'maximum'     => 5,
						),
						'approved' => array(
							'type'        => 'boolean',
							'description' => __(  'Whether the review is approved', 'mcp-for-woocommerce' ),
							'default'     => false,
						),
					),
					'required' => array( 'product_id', 'reviewer_name', 'reviewer_email', 'content' ),
				),
			)
		);

		// Update Review
		new RegisterMcpTool(
			array(
				'name'        => 'wc_update_review',
				'description' => __(  'Update an existing WooCommerce product review.', 'mcp-for-woocommerce' ),
				'type'        => 'update',
				'callback'    => array( $this, 'update_review' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Update Review',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array(
							'type'        => 'integer',
							'description' => __(  'Review ID (required)', 'mcp-for-woocommerce' ),
						),
						'reviewer_name' => array(
							'type'        => 'string',
							'description' => __(  'Reviewer name', 'mcp-for-woocommerce' ),
						),
						'reviewer_email' => array(
							'type'        => 'string',
							'description' => __(  'Reviewer email', 'mcp-for-woocommerce' ),
						),
						'content' => array(
							'type'        => 'string',
							'description' => __(  'Review content', 'mcp-for-woocommerce' ),
						),
						'rating' => array(
							'type'        => 'integer',
							'description' => __(  'Rating (1-5)', 'mcp-for-woocommerce' ),
							'minimum'     => 1,
							'maximum'     => 5,
						),
					),
					'required' => array( 'id' ),
				),
			)
		);

		// Delete Review
		new RegisterMcpTool(
			array(
				'name'        => 'wc_delete_review',
				'description' => __(  'Delete a WooCommerce product review. DESTRUCTIVE OPERATION.', 'mcp-for-woocommerce' ),
				'type'        => 'delete',
				'callback'    => array( $this, 'delete_review' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Delete Review',
					'readOnlyHint'    => false,
					'destructiveHint' => true,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array(
							'type'        => 'integer',
							'description' => __(  'Review ID to delete (required)', 'mcp-for-woocommerce' ),
						),
					),
					'required' => array( 'id' ),
				),
			)
		);

		// Approve Review
		new RegisterMcpTool(
			array(
				'name'        => 'wc_approve_review',
				'description' => __(  'Approve a WooCommerce product review (change status to approved).', 'mcp-for-woocommerce' ),
				'type'        => 'update',
				'callback'    => array( $this, 'approve_review' ),
				'permission_callback' => array( $this, 'check_manage_woocommerce_permission' ),
				'annotations' => array(
					'title'           => 'Approve Review',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array(
							'type'        => 'integer',
							'description' => __(  'Review ID to approve (required)', 'mcp-for-woocommerce' ),
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
		return current_user_can( 'manage_woocommerce' ) || current_user_can( 'moderate_comments' );
	}

	/**
	 * Create a new review
	 *
	 * @param array $data Review data.
	 * @return array Response data.
	 */
	public function create_review( array $data ): array {
		try {
			if ( empty( $data['product_id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Product ID is required',
					'code'    => 'missing_required_field',
				);
			}

			if ( empty( $data['reviewer_name'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Reviewer name is required',
					'code'    => 'missing_required_field',
				);
			}

			if ( empty( $data['reviewer_email'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Reviewer email is required',
					'code'    => 'missing_required_field',
				);
			}

			if ( empty( $data['content'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Review content is required',
					'code'    => 'missing_required_field',
				);
			}

			// Check if product exists
			$product = wc_get_product( $data['product_id'] );
			if ( ! $product ) {
				return array(
					'success' => false,
					'error'   => 'Product not found',
					'code'    => 'product_not_found',
				);
			}

			$comment_data = array(
				'comment_post_ID'      => $data['product_id'],
				'comment_author'       => sanitize_text_field( $data['reviewer_name'] ),
				'comment_author_email' => sanitize_email( $data['reviewer_email'] ),
				'comment_content'      => sanitize_textarea_field( $data['content'] ),
				'comment_type'         => 'review',
				'comment_approved'     => $data['approved'] ?? false ? 1 : 0,
			);

			$comment_id = wp_insert_comment( $comment_data );

			if ( ! $comment_id ) {
				return array(
					'success' => false,
					'error'   => 'Failed to create review',
					'code'    => 'review_creation_failed',
				);
			}

			// Set rating if provided
			if ( ! empty( $data['rating'] ) ) {
				$rating = intval( $data['rating'] );
				if ( $rating >= 1 && $rating <= 5 ) {
					update_comment_meta( $comment_id, 'rating', $rating );
				}
			}

			// Update product rating
			if ( function_exists( 'wc_update_product_review_average_rating' ) ) {
				wc_update_product_review_average_rating( $data['product_id'] );
			}

			do_action( 'mcpfowo_review_created', $comment_id, $data );

			$review = get_comment( $comment_id );

			return array(
				'success'       => true,
				'review_id'     => $comment_id,
				'product_id'    => $data['product_id'],
				'reviewer_name' => $review->comment_author,
				'rating'        => get_comment_meta( $comment_id, 'rating', true ),
				'approved'      => $review->comment_approved === '1',
				'message'       => sprintf( 'Review created successfully for product ID %d', $data['product_id'] ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'review_creation_exception',
			);
		}
	}

	/**
	 * Update an existing review
	 *
	 * @param array $data Review data with ID.
	 * @return array Response data.
	 */
	public function update_review( array $data ): array {
		try {
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Review ID is required',
					'code'    => 'missing_review_id',
				);
			}

			$review = get_comment( $data['id'] );

			if ( ! $review || $review->comment_type !== 'review' ) {
				return array(
					'success' => false,
					'error'   => 'Review not found',
					'code'    => 'review_not_found',
				);
			}

			$comment_data = array( 'comment_ID' => $data['id'] );

			if ( isset( $data['reviewer_name'] ) ) {
				$comment_data['comment_author'] = sanitize_text_field( $data['reviewer_name'] );
			}

			if ( isset( $data['reviewer_email'] ) ) {
				$comment_data['comment_author_email'] = sanitize_email( $data['reviewer_email'] );
			}

			if ( isset( $data['content'] ) ) {
				$comment_data['comment_content'] = sanitize_textarea_field( $data['content'] );
			}

			$result = wp_update_comment( $comment_data );

			if ( ! $result ) {
				return array(
					'success' => false,
					'error'   => 'Failed to update review',
					'code'    => 'review_update_failed',
				);
			}

			// Update rating if provided
			if ( isset( $data['rating'] ) ) {
				$rating = intval( $data['rating'] );
				if ( $rating >= 1 && $rating <= 5 ) {
					update_comment_meta( $data['id'], 'rating', $rating );
				}
			}

			// Update product rating
			if ( function_exists( 'wc_update_product_review_average_rating' ) ) {
				wc_update_product_review_average_rating( $review->comment_post_ID );
			}

			do_action( 'mcpfowo_review_updated', $data['id'], $data );

			$updated_review = get_comment( $data['id'] );

			return array(
				'success'       => true,
				'review_id'     => $data['id'],
				'product_id'    => $updated_review->comment_post_ID,
				'reviewer_name' => $updated_review->comment_author,
				'rating'        => get_comment_meta( $data['id'], 'rating', true ),
				'message'       => sprintf( 'Review ID %d updated successfully', $data['id'] ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'review_update_exception',
			);
		}
	}

	/**
	 * Delete a review
	 *
	 * @param array $data Review data with ID.
	 * @return array Response data.
	 */
	public function delete_review( array $data ): array {
		try {
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Review ID is required',
					'code'    => 'missing_review_id',
				);
			}

			$review = get_comment( $data['id'] );

			if ( ! $review || $review->comment_type !== 'review' ) {
				return array(
					'success' => false,
					'error'   => 'Review not found',
					'code'    => 'review_not_found',
				);
			}

			$product_id = $review->comment_post_ID;
			$review_id = $data['id'];

			$result = wp_delete_comment( $review_id, true );

			if ( ! $result ) {
				return array(
					'success' => false,
					'error'   => 'Failed to delete review',
					'code'    => 'review_deletion_failed',
				);
			}

			// Clear product rating cache
			WC()->queue()->schedule_single( time(), 'woocommerce_product_reviews_update_average_rating', array( $product_id ), 'woocommerce' );

			do_action( 'mcpfowo_review_deleted', $review_id, $product_id );

			return array(
				'success' => true,
				'message' => sprintf( 'Review ID %d deleted successfully', $review_id ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'review_deletion_exception',
			);
		}
	}

	/**
	 * Approve a review
	 *
	 * @param array $data Review data with ID.
	 * @return array Response data.
	 */
	public function approve_review( array $data ): array {
		try {
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Review ID is required',
					'code'    => 'missing_review_id',
				);
			}

			$review = get_comment( $data['id'] );

			if ( ! $review || $review->comment_type !== 'review' ) {
				return array(
					'success' => false,
					'error'   => 'Review not found',
					'code'    => 'review_not_found',
				);
			}

			$comment_data = array(
				'comment_ID'       => $data['id'],
				'comment_approved' => 1,
			);

			$result = wp_update_comment( $comment_data );

			if ( ! $result ) {
				return array(
					'success' => false,
					'error'   => 'Failed to approve review',
					'code'    => 'review_approval_failed',
				);
			}

			// Update product rating
			if ( function_exists( 'wc_update_product_review_average_rating' ) ) {
				wc_update_product_review_average_rating( $review->comment_post_ID );
			}

			do_action( 'mcpfowo_review_approved', $data['id'] );

			$approved_review = get_comment( $data['id'] );

			return array(
				'success'    => true,
				'review_id'  => $data['id'],
				'product_id' => $approved_review->comment_post_ID,
				'approved'   => true,
				'message'    => sprintf( 'Review ID %d approved successfully', $data['id'] ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'review_approval_exception',
			);
		}
	}
}

