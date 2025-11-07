<?php
declare(strict_types=1);


namespace McpForWoo\Prompts;

use McpForWoo\Core\RegisterMcpPrompt;

/**
 * Class McpSeoAnalysis
 *
 * Prompt for SEO analysis and optimization.
 * Helps AI analyze and improve site SEO for products, posts, and pages.
 *
 * @package McpForWoo\Prompts
 */
class McpSeoAnalysis {

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
				'name'        => 'analyze-seo',
				'description' => __( 'Analyze and optimize SEO for WordPress content and WooCommerce products', 'mcp-for-woocommerce' ),
				'arguments'   => array(
					array(
						'name'        => 'target',
						'description' => __( 'What to analyze: "site", "products", "posts", "pages", or specific URL/ID', 'mcp-for-woocommerce' ),
						'required'    => true,
						'type'        => 'string',
					),
					array(
						'name'        => 'focus_keyword',
						'description' => __( 'Target keyword for optimization (optional)', 'mcp-for-woocommerce' ),
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
					'text' => 'Perform SEO analysis for: {{target}}{{#if focus_keyword}} focusing on keyword: "{{focus_keyword}}"{{/if}}.

Analysis should include:

1. **Content Analysis:**
   - Title optimization (length, keywords, uniqueness)
   - Meta description quality and length
   - Header structure (H1, H2, H3)
   - Content length and quality
   - Keyword usage and density
   - Internal and external links
   - Image alt text optimization

2. **Technical SEO:**
   - URL structure (permalinks)
   - Page load performance indicators
   - Mobile responsiveness
   - Schema markup opportunities

3. **WooCommerce-specific (for products):**
   - Product title optimization
   - Product description quality
   - Category and tag usage
   - Product schema markup
   - Review integration
   - Product availability and pricing display

4. **Recommendations:**
   - Prioritized list of improvements
   - Specific action items
   - Content suggestions
   - Keyword opportunities

Use available tools:
- wc_products_search for product analysis
- wordpress_posts_list / wordpress_pages_list for content analysis
- WordPress://site-info for overall site configuration

Provide actionable, prioritized recommendations.',
				),
			),
		);
	}
}







