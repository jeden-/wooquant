<?php
declare(strict_types=1);


namespace McpForWoo\Prompts;

use McpForWoo\Core\RegisterMcpPrompt;

/**
 * Class McpContentCreation
 *
 * Prompt for creating and managing WordPress content.
 * Helps AI create, update, and optimize posts and pages.
 *
 * @package McpForWoo\Prompts
 */
class McpContentCreation {

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
				'name'        => 'create-content',
				'description' => 'Create or optimize WordPress content (posts, pages)',
				'arguments'   => array(
					array(
						'name'        => 'content_type',
						'description' => 'Type of content: "post" or "page"',
						'required'    => true,
						'type'        => 'string',
					),
					array(
						'name'        => 'topic',
						'description' => 'Topic or title for the content',
						'required'    => true,
						'type'        => 'string',
					),
					array(
						'name'        => 'tone',
						'description' => 'Writing tone: "professional", "casual", "friendly", "formal"',
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
					'text' => 'Create WordPress {{content_type}} content about: "{{topic}}"{{#if tone}} in {{tone}} tone{{/if}}.

Steps:
1. Research existing content using wordpress_posts_list or wordpress_pages_list
2. Review site information using WordPress://site-info resource
3. Create well-structured content with:
   - Engaging title
   - Compelling introduction
   - Well-organized sections with headings
   - Relevant keywords for SEO
   - Clear conclusion or call-to-action
   - Appropriate categories/tags

4. Format content in WordPress block editor format (if applicable)
5. Suggest featured image ideas
6. Provide meta description for SEO

If write operations are enabled, offer to create the content directly using wp_create_post or wp_create_page tools.',
				),
			),
		);
	}
}

