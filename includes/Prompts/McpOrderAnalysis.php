<?php
declare(strict_types=1);


namespace McpForWoo\Prompts;

use McpForWoo\Core\RegisterMcpPrompt;

/**
 * Class McpOrderAnalysis
 *
 * Prompt for analyzing WooCommerce orders.
 * Provides insights on order status, customer patterns, and order management.
 *
 * @package McpForWoo\Prompts
 */
class McpOrderAnalysis {

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
				'name'        => 'analyze-orders',
				'description' => 'Analyze WooCommerce orders with filtering and insights',
				'arguments'   => array(
					array(
						'name'        => 'status',
						'description' => 'Filter by order status (e.g., pending, processing, completed, cancelled)',
						'required'    => false,
						'type'        => 'string',
					),
					array(
						'name'        => 'time_period',
						'description' => 'Time period to analyze (e.g., today, last_7_days, last_30_days, this_month)',
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
					'text' => 'Analyze WooCommerce orders{{#if status}} with status: {{status}}{{/if}}{{#if time_period}} for time period: {{time_period}}{{/if}}. 

Provide analysis including:
- Total number of orders
- Total revenue
- Average order value
- Order status distribution
- Top customers
- Most ordered products
- Payment method breakdown
- Any concerning patterns (e.g., high cancellation rate, pending payments)
- Actionable recommendations for order management

Use wc_get_orders tool to retrieve order data with appropriate filters.',
				),
			),
		);
	}
}


