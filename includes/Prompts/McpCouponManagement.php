<?php
declare(strict_types=1);


namespace McpForWoo\Prompts;

use McpForWoo\Core\RegisterMcpPrompt;

/**
 * Class McpCouponManagement
 *
 * Prompt for managing WooCommerce coupons and promotions.
 * Helps AI create, analyze, and optimize discount strategies.
 *
 * @package McpForWoo\Prompts
 */
class McpCouponManagement {

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
				'name'        => 'manage-coupons',
				'description' => __( 'Create and manage WooCommerce coupons and promotional campaigns', 'mcp-for-woocommerce' ),
				'arguments'   => array(
					array(
						'name'        => 'action',
						'description' => __( 'Action: "create_campaign", "analyze_performance", "optimize_existing", "seasonal_promotion"', 'mcp-for-woocommerce' ),
						'required'    => true,
						'type'        => 'string',
					),
					array(
						'name'        => 'details',
						'description' => __( 'Campaign details or analysis criteria', 'mcp-for-woocommerce' ),
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
					'text' => 'Manage coupons with action: {{action}}{{#if details}} - {{details}}{{/if}}.

Available coupon management capabilities:

**For "create_campaign":**
1. Design coupon strategy based on:
   - Business goals (customer acquisition, retention, revenue boost)
   - Target audience (new customers, VIPs, cart abandoners)
   - Product focus (specific categories, bestsellers, clearance)
   
2. Create coupons using wc_create_coupon with optimal settings:
   - Discount type (percentage, fixed cart, fixed product)
   - Amount and minimum spend requirements
   - Usage restrictions (products, categories, emails)
   - Expiration dates
   - Usage limits per coupon/user

**For "analyze_performance":**
1. Retrieve orders with applied coupons
2. Calculate metrics:
   - Redemption rate
   - Average order value with/without coupon
   - Revenue impact
   - Customer acquisition cost
   - Most popular coupons
3. Provide actionable insights

**For "optimize_existing":**
1. Review current coupon configuration
2. Identify underperforming coupons
3. Suggest improvements:
   - Better discount amounts
   - Adjusted restrictions
   - Extended/shortened validity
   - Better naming/marketing
4. Implement changes if write operations enabled

**For "seasonal_promotion":**
1. Create themed campaign (holiday, back-to-school, etc.)
2. Multiple coupon tiers (new customer, VIP, flash sale)
3. Strategic timing and expiration
4. Cross-sell/upsell opportunities

Include clear coupon codes, usage instructions, and expected ROI.',
				),
			),
		);
	}
}







