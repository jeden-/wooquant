<?php
declare(strict_types=1);


namespace McpForWoo\Prompts;

use McpForWoo\Core\RegisterMcpPrompt;

/**
 * Class McpShippingTaxManagement
 *
 * Prompt for managing WooCommerce shipping zones, methods, and tax configuration.
 * Helps AI optimize logistics and tax compliance.
 *
 * @package McpForWoo\Prompts
 */
class McpShippingTaxManagement {

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
				'name'        => 'manage-shipping-tax',
				'description' => 'Configure and optimize WooCommerce shipping zones, methods, and tax settings',
				'arguments'   => array(
					array(
						'name'        => 'focus',
						'description' => 'Focus area: "shipping", "tax", "both", "audit", "optimize"',
						'required'    => true,
						'type'        => 'string',
					),
					array(
						'name'        => 'region',
						'description' => 'Specific region or zone (optional)',
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
					'text' => 'Manage shipping and tax configuration - focus: {{focus}}{{#if region}} for region: {{region}}{{/if}}.

**Available Tools:**
- wc_get_shipping_zones: List all shipping zones
- wc_get_shipping_zone: Get specific zone details
- wc_get_shipping_methods: List available methods
- wc_get_shipping_locations: Get covered locations
- wc_update_shipping_zone: Update zone configuration (write)
- wc_get_tax_classes: List tax classes
- wc_get_tax_rates: Get tax rates
- wc_get_payment_gateways: Related payment config

**SHIPPING MANAGEMENT:**

**For "shipping" focus or "audit":**

1. **Zone Analysis**
   Use wc_get_shipping_zones to review:
   - Zone coverage (countries/regions)
   - Shipping methods per zone
   - Method costs and settings
   - Gaps in coverage

2. **Identify Issues:**
   - Uncovered regions where customers exist
   - Overlapping zones causing confusion
   - Missing shipping methods
   - Incorrect pricing
   - Complex or unclear options

3. **Best Practice Review:**
   - Free shipping thresholds (recommended: 50-100% above AOV)
   - Flat rate availability for predictability
   - Local pickup options
   - International shipping clarity
   - Shipping calculator availability

4. **Cost Analysis:**
   - Compare shipping costs to order values
   - Identify loss-making shipping options
   - Evaluate free shipping impact on margins
   - Calculate optimal free shipping threshold

**For "shipping" focus with "optimize":**

1. **Recommend zone structure:**
   - Consolidate overlapping zones
   - Add zones for uncovered regions
   - Prioritize high-value customer locations
   - Consider separate zones for:
     * Local (same city/state)
     * Regional (same country)
     * International

2. **Method optimization:**
   - Offer 2-3 speed options (standard, expedited, overnight)
   - Balance cost and delivery time
   - Set competitive but profitable rates
   - Consider free shipping thresholds:
     * If AOV is $50, threshold at $75-100
     * Show "X more for free shipping" messaging

3. **Implementation** (if write enabled):
   - Use wc_update_shipping_zone to apply changes
   - Update method settings
   - Configure free shipping rules
   - Test calculation logic

**TAX MANAGEMENT:**

**For "tax" focus or "audit":**

1. **Tax Configuration Review**
   Use wc_get_tax_rates and wc_get_tax_classes:
   - Tax rates by region
   - Tax classes (standard, reduced, zero)
   - Product tax class assignments
   - Calculation method (including/excluding tax)

2. **Compliance Check:**
   - Verify rates match legal requirements
   - Check nexus compliance (US sales tax)
   - EU VAT rules if applicable
   - Digital product tax rules
   - Threshold exemptions

3. **Common Issues:**
   - Missing tax rates for selling regions
   - Incorrect rates vs current laws
   - Wrong tax class assignments
   - Display confusion (prices with/without tax)

4. **Reporting Needs:**
   - Tax collected by region
   - Tax-exempt sales
   - Tax liability estimates

**For "tax" focus with "optimize":**

1. **Recommend structure:**
   - Proper tax classes per product type
   - Accurate regional rates
   - Compliance with local laws
   - Clear customer communication

2. **Implementation tips:**
   - Use tax automation services for complex scenarios (TaxJar, Avalara)
   - Set up tax exemption for business customers
   - Configure tax display for clarity
   - Document tax settings for audits

**For "both" focus:**
Comprehensive audit and optimization of:
- Shipping zones aligned with tax jurisdictions
- Cost calculation including tax
- Checkout transparency (show all costs early)
- Legal compliance (shipping + tax)
- Customer experience optimization

**CHECKOUT OPTIMIZATION:**

Consider the customer journey:
1. **Transparency**: Show shipping costs early, before checkout
2. **Simplicity**: Offer clear, limited shipping options (3 max)
3. **Value**: Free shipping threshold visible and achievable
4. **Trust**: Accurate delivery estimates
5. **Clarity**: All costs (product + shipping + tax) clear before final purchase

**RECOMMENDATIONS FORMAT:**
For each issue found, provide:
- 🔴 Critical: Compliance issues, checkout blockers
- 🟡 Important: Cost optimization, coverage gaps
- 🟢 Enhancement: UX improvements, nice-to-haves

Include:
- Current state
- Recommended change
- Expected impact
- Implementation priority
- Implementation method (if write operations enabled)',
				),
			),
		);
	}
}

