<?php
declare(strict_types=1);


namespace McpForWoo\Prompts;

use McpForWoo\Core\RegisterMcpPrompt;

/**
 * Class McpMediaManagement
 *
 * Prompt for managing WordPress media library.
 * Helps AI upload, organize, optimize, and manage images and files.
 *
 * @package McpForWoo\Prompts
 */
class McpMediaManagement {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'mcpfowo_init', array( $this, 'register_prompt' ) );
	}

	/**
	 * Register the prompt.
	 *
	 * @return void
	 */
	public function register_prompt(): void {
		new RegisterMcpPrompt(
			array(
				'name'        => 'manage-media',
				'description' => 'Upload, organize, optimize, and manage WordPress media library',
				'arguments'   => array(
					array(
						'name'        => 'task',
						'description' => 'Task: "upload", "organize", "optimize", "audit", "cleanup", "batch_upload"',
						'required'    => true,
						'type'        => 'string',
					),
					array(
						'name'        => 'details',
						'description' => 'Task-specific details (file path, organization criteria, etc.)',
						'required'    => false,
						'type'        => 'string',
					),
				),
			),
			$this->messages()
		);
	}

	/**
	 * Get the messages for the prompt.
	 *
	 * @return array
	 */
	public function messages(): array {
		return array(
			array(
				'role'    => 'user',
				'content' => array(
					'type' => 'text',
					'text' => 'Manage media library - task: {{task}}{{#if details}} - {{details}}{{/if}}.

**Available Media Tools:**
- wp_upload_image: Upload single image
- wp_upload_file: Upload any file type
- wp_delete_media: Remove media files
- wp_update_media_metadata: Update titles, alt text, descriptions

**For "upload" task:**
1. Validate file(s) exist and are accessible
2. Check file types are allowed (images: jpg, png, gif, webp; docs: pdf, doc, etc.)
3. Optimize before upload if possible:
   - Resize large images (max 2000px recommended)
   - Compress without quality loss
4. Use wp_upload_image or wp_upload_file
5. Set proper metadata:
   - Descriptive title
   - SEO-friendly alt text
   - Caption and description
6. Return media ID and URL for reference

**For "batch_upload" task:**
1. Process multiple files sequentially
2. Apply consistent naming convention
3. Set metadata for all files
4. Create organized structure
5. Report success/failure for each file

**For "organize" task:**
1. Audit current media library structure
2. Suggest organization strategy:
   - By date (year/month)
   - By type (products, blog, pages)
   - By category/collection
3. Update media metadata for better searchability
4. Create naming conventions
5. Implement organization if write operations enabled

**For "optimize" task:**
1. Identify optimization opportunities:
   - Oversized images (> 2MB)
   - Missing alt text (bad for SEO/accessibility)
   - Poor file naming (IMG_1234.jpg)
   - Unused file formats
2. Recommend:
   - Image compression
   - Format conversion (e.g., to WebP)
   - Proper alt text for all images
   - Descriptive file names
3. Implement optimizations if write operations enabled

**For "audit" task:**
1. Media library statistics:
   - Total files and storage used
   - Files by type (images, videos, docs)
   - Largest files
   - Upload date distribution
2. Identify issues:
   - Missing alt text count
   - Broken/orphaned files
   - Unused media (not attached to any content)
   - Duplicate files
3. SEO audit:
   - Images without alt text
   - Poor file naming
   - Missing captions
4. Security audit:
   - Suspicious file types
   - Unusual file sizes

**For "cleanup" task:**
⚠️ **POTENTIALLY DESTRUCTIVE**
1. **Backup first** - recommend full site backup
2. Identify candidates for removal:
   - Unused media (not referenced anywhere)
   - Duplicate files
   - Old temporary files
3. Show preview of what will be deleted
4. Get explicit confirmation
5. Use wp_delete_media selectively
6. Report freed storage space

**Best Practices:**
- Always set alt text for images (accessibility + SEO)
- Use descriptive file names (product-blue-widget.jpg not IMG_001.jpg)
- Optimize images before upload (compress, resize)
- Regular audits to prevent bloat
- Keep backups before bulk deletions',
				),
			),
		);
	}
}


