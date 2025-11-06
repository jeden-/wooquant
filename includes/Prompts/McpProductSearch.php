<?php
declare(strict_types=1);


namespace McpForWoo\Prompts;

use McpForWoo\Core\RegisterMcpPrompt;

/**
 * Class McpProductSearch
 *
 * Prompt for intelligent WooCommerce product search.
 * Helps AI understand how to search for products effectively.
 *
 * @package McpForWoo\Prompts
 */
class McpProductSearch {

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
				'name'        => 'search-products',
				'description' => 'Search for WooCommerce products intelligently with filters and sorting',
				'arguments'   => array(
					array(
						'name'        => 'query',
						'description' => 'The product search query (e.g., "cheapest laptops on sale", "newest shoes")',
						'required'    => true,
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
					'text' => 'Search for products matching: "{{query}}". 

CRITICAL WORKFLOW:
1. First, read the woocommerce-search-guide resource to understand search strategies
2. Get available categories using wc_get_categories
3. Analyze the search intent using wc_analyze_search_intent
4. Execute intelligent search with wc_products_search using these strategies:
   - Stage 1: Search with all filters (price, category, sale status)
   - Stage 2: If no results, remove promotional filters (sale)
   - Stage 3: If still no results, try broader categories
   - Stage 4: If still no results, do general text search
   - Stage 5: If all fails, show available categories

ALWAYS include product permalinks in results. NEVER return empty results without trying all fallback strategies.',
				),
			),
		);
	}
}

