<?php
declare(strict_types=1);


namespace McpForWoo\Prompts;

use McpForWoo\Core\RegisterMcpPrompt;

/**
 * Class McpCustomerAnalysis
 *
 * Prompt for analyzing and segmenting WooCommerce customers.
 * Helps AI understand customer behavior, create segments, and personalize marketing.
 *
 * @package McpForWoo\Prompts
 */
class McpCustomerAnalysis {

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
				'name'        => 'analyze-customers',
				'description' => 'Analyze customer behavior, create segments, and personalize marketing',
				'arguments'   => array(
					array(
						'name'        => 'analysis_type',
						'description' => 'Type: "segmentation", "lifetime_value", "churn_risk", "purchase_patterns", "loyalty_analysis"',
						'required'    => true,
						'type'        => 'string',
					),
					array(
						'name'        => 'segment',
						'description' => 'Specific customer segment to focus on (optional)',
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
					'text' => 'Analyze customers with type: {{analysis_type}}{{#if segment}} focusing on segment: {{segment}}{{/if}}.

**Customer Analysis Workflow:**

1. **Data Collection:**
   - Use wc_get_orders to retrieve order history
   - Calculate customer metrics:
     - Total orders per customer
     - Average order value
     - Total revenue per customer
     - Purchase frequency
     - Last purchase date
     - Product preferences

2. **For "segmentation":**
   Create customer segments:
   - **VIP Customers**: High LTV, frequent buyers
   - **At-Risk**: Previously active, now inactive
   - **New Customers**: First purchase < 30 days
   - **Bargain Hunters**: Only buy on sale/with coupons
   - **High-Value Prospects**: Few orders but high AOV
   - **Loyal Fans**: Regular purchases, full-price buyers
   
   For each segment provide:
   - Segment size and characteristics
   - Recommended marketing approach
   - Personalized offers/messaging
   - Retention strategies

3. **For "lifetime_value":**
   - Calculate CLV for customers
   - Identify top 20% revenue generators
   - Predict future value based on behavior
   - Recommend VIP treatment strategies

4. **For "churn_risk":**
   - Identify customers who haven\'t ordered recently
   - Calculate days since last purchase
   - Compare to historical frequency
   - Create win-back campaigns
   - Suggest re-engagement offers

5. **For "purchase_patterns":**
   - Identify buying cycles
   - Product affinities and cross-sell opportunities
   - Seasonal purchasing trends
   - Category preferences
   - Suggest personalized recommendations

6. **For "loyalty_analysis":**
   - Identify repeat purchase rate
   - Calculate retention metrics
   - Analyze customer journey stages
   - Recommend loyalty program structure

**Deliverables:**
- Customer segment lists
- Actionable marketing recommendations
- Personalized campaign ideas
- ROI projections
- If write operations enabled: Create targeted customer groups',
				),
			),
		);
	}
}

