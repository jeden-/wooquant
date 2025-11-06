<?php
declare(strict_types=1);


namespace McpForWoo\Prompts;

use McpForWoo\Core\RegisterMcpPrompt;

/**
 * Class McpCustomerSupport
 *
 * Prompt for providing WooCommerce customer support assistance.
 * Helps AI assist with common customer queries about products, orders, and shipping.
 *
 * @package McpForWoo\Prompts
 */
class McpCustomerSupport {

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
				'name'        => 'customer-support',
				'description' => 'Provide customer support for WooCommerce store inquiries',
				'arguments'   => array(
					array(
						'name'        => 'customer_query',
						'description' => 'The customer question or issue (e.g., "Where is my order?", "Do you have this in blue?", "What are shipping options?")',
						'required'    => true,
						'type'        => 'string',
					),
					array(
						'name'        => 'order_id',
						'description' => 'Order ID if the query is about a specific order',
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
					'text' => 'Provide customer support for this query: "{{customer_query}}"{{#if order_id}} regarding order ID: {{order_id}}{{/if}}.

Available tools to help:
- wc_get_orders: Check order status and details
- wc_products_search: Find products by name, color, size, etc.
- wc_get_product_variations: Get available colors, sizes, and options
- wc_get_shipping_zones: Check shipping availability
- wc_get_categories: Browse product categories

Instructions:
1. Understand the customer question
2. Use appropriate tools to gather information
3. Provide clear, helpful, and friendly response
4. Include relevant product links when discussing products
5. If about orders, provide tracking information and status
6. If about products, show available options with prices
7. If about shipping, explain available methods and costs
8. Always be polite and solution-oriented',
				),
			),
		);
	}
}

