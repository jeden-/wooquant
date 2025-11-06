<?php
declare(strict_types=1);

namespace McpForWoo\Tools\Write;

use McpForWoo\Core\RegisterMcpTool;

/**
 * Class McpWordPressMediaWrite
 *
 * Provides WordPress write operations for media files.
 */
class McpWordPressMediaWrite {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'mcpfowo_init', array( $this, 'register_tools' ) );
	}

	/**
	 * Register media write tools
	 */
	public function register_tools(): void {
		$settings = get_option( 'mcpfowo_settings', array() );
		if ( empty( $settings['enable_write_operations'] ) ) {
			return;
		}

		// Upload Image
		new RegisterMcpTool(
			array(
				'name'        => 'wp_upload_image',
				'description' => __(  'Upload an image file to WordPress media library. Accepts base64 encoded image data or URL.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'upload_image' ),
				'permission_callback' => array( $this, 'check_upload_files_permission' ),
				'annotations' => array(
					'title'           => 'Upload Image',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'file' => array(
							'type'        => 'string',
							'description' => __(  'Base64 encoded image data or image URL (required)', 'mcp-for-woocommerce' ),
						),
						'filename' => array(
							'type'        => 'string',
							'description' => __(  'Filename for the image', 'mcp-for-woocommerce' ),
						),
						'title' => array(
							'type'        => 'string',
							'description' => __(  'Image title', 'mcp-for-woocommerce' ),
						),
						'alt_text' => array(
							'type'        => 'string',
							'description' => __(  'Alt text for the image', 'mcp-for-woocommerce' ),
						),
						'caption' => array(
							'type'        => 'string',
							'description' => __(  'Image caption', 'mcp-for-woocommerce' ),
						),
						'description' => array(
							'type'        => 'string',
							'description' => __(  'Image description', 'mcp-for-woocommerce' ),
						),
						'post_id' => array(
							'type'        => 'integer',
							'description' => __(  'Post ID to attach image to', 'mcp-for-woocommerce' ),
						),
					),
					'required' => array( 'file' ),
				),
			)
		);

		// Upload File
		new RegisterMcpTool(
			array(
				'name'        => 'wp_upload_file',
				'description' => __(  'Upload a file to WordPress media library. Accepts base64 encoded file data or URL.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'upload_file' ),
				'permission_callback' => array( $this, 'check_upload_files_permission' ),
				'annotations' => array(
					'title'           => 'Upload File',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'file' => array(
							'type'        => 'string',
							'description' => __(  'Base64 encoded file data or file URL (required)', 'mcp-for-woocommerce' ),
						),
						'filename' => array(
							'type'        => 'string',
							'description' => __(  'Filename for the file', 'mcp-for-woocommerce' ),
						),
						'title' => array(
							'type'        => 'string',
							'description' => __(  'File title', 'mcp-for-woocommerce' ),
						),
						'post_id' => array(
							'type'        => 'integer',
							'description' => __(  'Post ID to attach file to', 'mcp-for-woocommerce' ),
						),
					),
					'required' => array( 'file' ),
				),
			)
		);

		// Delete Media
		new RegisterMcpTool(
			array(
				'name'        => 'wp_delete_media',
				'description' => __(  'Delete a media file from WordPress. DESTRUCTIVE OPERATION.', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'delete_media' ),
				'permission_callback' => array( $this, 'check_delete_media_permission' ),
				'annotations' => array(
					'title'           => 'Delete Media',
					'readOnlyHint'    => false,
					'destructiveHint' => true,
					'idempotentHint'  => false,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array(
							'type'        => 'integer',
							'description' => __(  'Media attachment ID to delete (required)', 'mcp-for-woocommerce' ),
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

		// Update Media Metadata
		new RegisterMcpTool(
			array(
				'name'        => 'wp_update_media_metadata',
				'description' => __(  'Update media metadata (title, alt text, caption, description).', 'mcp-for-woocommerce' ),
				'type'        => 'write',
				'callback'    => array( $this, 'update_media_metadata' ),
				'permission_callback' => array( $this, 'check_edit_media_permission' ),
				'annotations' => array(
					'title'           => 'Update Media Metadata',
					'readOnlyHint'    => false,
					'destructiveHint' => false,
					'idempotentHint'  => true,
				),
				'inputSchema' => array(
					'type'       => 'object',
					'properties' => array(
						'id' => array(
							'type'        => 'integer',
							'description' => __(  'Media attachment ID (required)', 'mcp-for-woocommerce' ),
						),
						'title' => array(
							'type'        => 'string',
							'description' => __(  'Media title', 'mcp-for-woocommerce' ),
						),
						'alt_text' => array(
							'type'        => 'string',
							'description' => __(  'Alt text', 'mcp-for-woocommerce' ),
						),
						'caption' => array(
							'type'        => 'string',
							'description' => __(  'Caption', 'mcp-for-woocommerce' ),
						),
						'description' => array(
							'type'        => 'string',
							'description' => __(  'Description', 'mcp-for-woocommerce' ),
						),
					),
					'required' => array( 'id' ),
				),
			)
		);
	}

	/**
	 * Check if user can upload files
	 */
	public function check_upload_files_permission(): bool {
		return current_user_can( 'upload_files' );
	}

	/**
	 * Check if user can delete media
	 */
	public function check_delete_media_permission(): bool {
		return current_user_can( 'delete_posts' );
	}

	/**
	 * Check if user can edit media
	 */
	public function check_edit_media_permission(): bool {
		return current_user_can( 'edit_posts' );
	}

	/**
	 * Upload an image
	 */
	public function upload_image( array $data ): array {
		return $this->upload_file( $data, true );
	}

	/**
	 * Upload a file
	 */
	public function upload_file( array $data, bool $is_image = false ): array {
		try {
			if ( empty( $data['file'] ) ) {
				return array(
					'success' => false,
					'error'   => 'File data or URL is required',
					'code'    => 'missing_required_field',
				);
			}

			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';

			$file_data = null;
			$filename = $data['filename'] ?? 'upload';
			$mime_type = null;

			// Check if it's a URL or base64 data
			if ( filter_var( $data['file'], FILTER_VALIDATE_URL ) ) {
				// Download from URL
				$tmp = download_url( $data['file'] );

				if ( is_wp_error( $tmp ) ) {
					return array(
						'success' => false,
						'error'   => $tmp->get_error_message(),
						'code'    => 'download_failed',
					);
				}

				$file_array = array(
					'name'     => basename( $data['file'] ),
					'tmp_name' => $tmp,
				);

				$attachment_id = media_handle_sideload( $file_array, $data['post_id'] ?? 0 );

				if ( is_wp_error( $attachment_id ) ) {
					@unlink( $tmp );
					return array(
						'success' => false,
						'error'   => $attachment_id->get_error_message(),
						'code'    => 'upload_failed',
					);
				}
			} else {
				// Base64 data
				$base64_data = $data['file'];

				// Remove data URI prefix if present
				if ( strpos( $base64_data, 'data:' ) === 0 ) {
					preg_match( '/data:([^;]+);base64,/', $base64_data, $matches );
					if ( ! empty( $matches[1] ) ) {
						$mime_type = $matches[1];
					}
					$base64_data = preg_replace( '/^data:.*?;base64,/', '', $base64_data );
				}

				$file_data = base64_decode( $base64_data, true );

				if ( false === $file_data ) {
					return array(
						'success' => false,
						'error'   => 'Invalid base64 data',
						'code'    => 'invalid_base64',
					);
				}

				// Determine MIME type if not provided
				if ( ! $mime_type ) {
					$finfo = finfo_open( FILEINFO_MIME_TYPE );
					$mime_type = finfo_buffer( $finfo, $file_data );
					finfo_close( $finfo );
				}

				// Get extension from MIME type
				$extension = $this->get_extension_from_mime_type( $mime_type );
				if ( ! $extension ) {
					return array(
						'success' => false,
						'error'   => 'Unsupported file type',
						'code'    => 'unsupported_file_type',
					);
				}

				// Create temporary file
				$tmp_file = wp_tempnam();
				file_put_contents( $tmp_file, $file_data );

				$file_array = array(
					'name'     => $filename . '.' . $extension,
					'tmp_name' => $tmp_file,
				);

				$attachment_id = media_handle_sideload( $file_array, $data['post_id'] ?? 0 );

				if ( is_wp_error( $attachment_id ) ) {
					@unlink( $tmp_file );
					return array(
						'success' => false,
						'error'   => $attachment_id->get_error_message(),
						'code'    => 'upload_failed',
					);
				}
			}

			// Update metadata
			if ( ! empty( $data['title'] ) ) {
				wp_update_post( array(
					'ID'         => $attachment_id,
					'post_title' => sanitize_text_field( $data['title'] ),
				) );
			}

			if ( ! empty( $data['alt_text'] ) ) {
				update_post_meta( $attachment_id, '_wp_attachment_image_alt', sanitize_text_field( $data['alt_text'] ) );
			}

			if ( ! empty( $data['caption'] ) ) {
				wp_update_post( array(
					'ID'         => $attachment_id,
					'post_excerpt' => sanitize_textarea_field( $data['caption'] ),
				) );
			}

			if ( ! empty( $data['description'] ) ) {
				wp_update_post( array(
					'ID'           => $attachment_id,
					'post_content' => wp_kses_post( $data['description'] ),
				) );
			}

			do_action( 'mcpfowo_media_uploaded', $attachment_id, $data );

			$attachment = get_post( $attachment_id );

			return array(
				'success'       => true,
				'media_id'      => $attachment_id,
				'url'           => wp_get_attachment_url( $attachment_id ),
				'title'         => $attachment->post_title,
				'mime_type'     => $attachment->post_mime_type,
				'message'       => sprintf( 'Media "%s" uploaded successfully', $attachment->post_title ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'upload_exception',
			);
		}
	}

	/**
	 * Delete media
	 */
	public function delete_media( array $data ): array {
		try {
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Media ID is required',
					'code'    => 'missing_media_id',
				);
			}

			$attachment = get_post( $data['id'] );

			if ( ! $attachment || $attachment->post_type !== 'attachment' ) {
				return array(
					'success' => false,
					'error'   => 'Media not found',
					'code'    => 'media_not_found',
				);
			}

			$force = $data['force'] ?? false;
			$title = $attachment->post_title;

			$result = wp_delete_attachment( $data['id'], $force );

			if ( ! $result ) {
				return array(
					'success' => false,
					'error'   => 'Failed to delete media',
					'code'    => 'media_deletion_failed',
				);
			}

			do_action( 'mcpfowo_media_deleted', $data['id'], $title );

			return array(
				'success' => true,
				'message' => sprintf( 'Media "%s" deleted successfully', $title ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'media_deletion_exception',
			);
		}
	}

	/**
	 * Update media metadata
	 */
	public function update_media_metadata( array $data ): array {
		try {
			if ( empty( $data['id'] ) ) {
				return array(
					'success' => false,
					'error'   => 'Media ID is required',
					'code'    => 'missing_media_id',
				);
			}

			$attachment = get_post( $data['id'] );

			if ( ! $attachment || $attachment->post_type !== 'attachment' ) {
				return array(
					'success' => false,
					'error'   => 'Media not found',
					'code'    => 'media_not_found',
				);
			}

			$post_data = array( 'ID' => $data['id'] );

			if ( isset( $data['title'] ) ) {
				$post_data['post_title'] = sanitize_text_field( $data['title'] );
			}

			if ( isset( $data['caption'] ) ) {
				$post_data['post_excerpt'] = sanitize_textarea_field( $data['caption'] );
			}

			if ( isset( $data['description'] ) ) {
				$post_data['post_content'] = wp_kses_post( $data['description'] );
			}

			if ( ! empty( $post_data ) && count( $post_data ) > 1 ) {
				wp_update_post( $post_data );
			}

			if ( isset( $data['alt_text'] ) ) {
				update_post_meta( $data['id'], '_wp_attachment_image_alt', sanitize_text_field( $data['alt_text'] ) );
			}

			do_action( 'mcpfowo_media_metadata_updated', $data['id'], $data );

			$updated_attachment = get_post( $data['id'] );

			return array(
				'success'   => true,
				'media_id' => $data['id'],
				'title'    => $updated_attachment->post_title,
				'url'      => wp_get_attachment_url( $data['id'] ),
				'message'  => sprintf( 'Media metadata updated successfully', $updated_attachment->post_title ),
			);

		} catch ( \Exception $e ) {
			return array(
				'success' => false,
				'error'   => $e->getMessage(),
				'code'    => 'media_update_exception',
			);
		}
	}

	/**
	 * Get file extension from MIME type
	 */
	private function get_extension_from_mime_type( string $mime_type ): ?string {
		$mime_map = array(
			'image/jpeg'                    => 'jpg',
			'image/png'                     => 'png',
			'image/gif'                     => 'gif',
			'image/webp'                    => 'webp',
			'image/svg+xml'                 => 'svg',
			'application/pdf'                => 'pdf',
			'application/msword'            => 'doc',
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
			'application/vnd.ms-excel'      => 'xls',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
			'text/plain'                    => 'txt',
			'text/csv'                      => 'csv',
			'text/html'                     => 'html',
			'application/json'              => 'json',
			'audio/mpeg'                    => 'mp3',
			'audio/wav'                     => 'wav',
			'video/mp4'                     => 'mp4',
			'video/webm'                    => 'webm',
		);

		return $mime_map[ $mime_type ] ?? null;
	}
}

