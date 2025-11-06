<?php
declare(strict_types=1);


namespace McpForWoo\Prompts;

use McpForWoo\Core\RegisterMcpPrompt;

/**
 * Class McpInventoryManagement
 *
 * Prompt for managing WooCommerce inventory.
 * Helps AI understand and manage stock levels, low stock alerts, and inventory optimization.
 *
 * @package McpForWoo\Prompts
 */
class McpInventoryManagement {

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
				'name'        => 'manage-inventory',
				'description' => 'Analyze and manage WooCommerce product inventory',
				'arguments'   => array(
					array(
						'name'        => 'action',
						'description' => 'Action to perform: "check_low_stock", "check_out_of_stock", "analyze_all", "update_stock"',
						'required'    => true,
						'type'        => 'string',
					),
					array(
						'name'        => 'threshold',
						'description' => 'Low stock threshold (default: 5)',
						'required'    => false,
						'type'        => 'number',
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
					'text' => 'Perform inventory management action: {{action}}{{#if threshold}} with low stock threshold: {{threshold}}{{/if}}.

Use wc_products_search with stock_status parameter to:
- Find products with low stock (stock_status=lowstock)
- Find out of stock products (stock_status=outofstock)
- Analyze inventory levels across all products

For each product, provide:
- Product name and SKU
- Current stock level
- Stock status
- Product category
- Recent sales data (if available)
- Recommendations for restocking

If action is "update_stock", use write operations (if enabled) to update stock levels based on the analysis.',
				),
			),
		);
	}
}


