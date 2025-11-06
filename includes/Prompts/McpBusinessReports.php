<?php
declare(strict_types=1);


namespace McpForWoo\Prompts;

use McpForWoo\Core\RegisterMcpPrompt;

/**
 * Class McpBusinessReports
 *
 * Prompt for generating comprehensive business reports and insights.
 * Helps AI create executive summaries, KPI dashboards, and strategic recommendations.
 *
 * @package McpForWoo\Prompts
 */
class McpBusinessReports {

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
				'name'        => 'generate-business-report',
				'description' => 'Generate comprehensive business reports with insights and recommendations',
				'arguments'   => array(
					array(
						'name'        => 'report_type',
						'description' => 'Report type: "executive_summary", "sales_performance", "inventory_status", "customer_insights", "marketing_effectiveness", "operational_health"',
						'required'    => true,
						'type'        => 'string',
					),
					array(
						'name'        => 'time_period',
						'description' => 'Time period: "today", "last_7_days", "last_30_days", "this_month", "last_month", "this_quarter", "this_year"',
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
					'text' => 'Generate business report: {{report_type}}{{#if time_period}} for period: {{time_period}}{{/if}}.

**Available Data Sources:**
- wc_get_orders: Order data and revenue
- wc_products_search: Product inventory and performance
- wc_get_product_reviews: Customer feedback
- wc_get_system_status: Technical health
- wordpress_posts_list: Content performance

**Report Structure:**

**For "executive_summary":**
Create high-level business overview:

1. **Key Metrics Dashboard**
   - Total revenue (current vs previous period)
   - Order count and trends
   - Average order value
   - Conversion indicators
   - Customer acquisition/retention

2. **Performance Highlights**
   - Top achievements
   - Significant improvements
   - Notable challenges
   - Quick wins identified

3. **Critical Issues** (if any)
   - Low stock alerts
   - Pending orders requiring attention
   - Technical issues
   - Customer service needs

4. **Strategic Recommendations**
   - Top 3-5 action items
   - Prioritized by impact and effort
   - Clear next steps

5. **Comparative Analysis**
   - Period-over-period trends
   - Goal tracking (if targets known)

**For "sales_performance":**
Deep-dive into revenue and sales:

1. **Revenue Analysis**
   - Total revenue by day/week
   - Revenue trends and patterns
   - Peak sales periods
   - Revenue by product category

2. **Order Analysis**
   - Total orders and status breakdown
   - Average order value trends
   - Order size distribution
   - Payment method breakdown

3. **Product Performance**
   - Best-selling products (top 10-20)
   - Revenue per product
   - Units sold
   - Product mix analysis

4. **Customer Behavior**
   - New vs returning customer sales
   - Order frequency
   - Customer lifetime value indicators

5. **Insights & Opportunities**
   - What\'s working well
   - Underperforming areas
   - Growth opportunities
   - Pricing optimization suggestions

**For "inventory_status":**
Stock and inventory health:

1. **Stock Overview**
   - Total products in inventory
   - Total inventory value
   - Low stock items (action required)
   - Out of stock items (urgent)

2. **Stock Performance**
   - Fast-moving items
   - Slow-moving items
   - Dead stock candidates
   - Optimal reorder points

3. **Category Analysis**
   - Stock levels by category
   - Category performance vs inventory

4. **Recommendations**
   - Reorder priorities
   - Overstock reduction strategies
   - Inventory optimization tips

**For "customer_insights":**
Customer behavior and segmentation:

1. **Customer Base Overview**
   - Total customers
   - New customers (period)
   - Active vs inactive
   - Geographic distribution

2. **Behavior Patterns**
   - Purchase frequency
   - Average customer value
   - Product preferences
   - Shopping patterns

3. **Segmentation**
   - VIP customers
   - At-risk customers
   - High-potential prospects
   - One-time buyers

4. **Engagement Metrics**
   - Repeat purchase rate
   - Customer retention
   - Review participation

5. **Action Plan**
   - Retention strategies
   - Re-engagement campaigns
   - Personalization opportunities

**For "marketing_effectiveness":**
Marketing channel and campaign performance:

1. **Traffic Sources**
   - Order attribution (if data available)
   - Coupon usage analysis
   - Promotional impact

2. **Conversion Metrics**
   - Sales per traffic source
   - ROI on promotions
   - Coupon effectiveness

3. **Content Performance**
   - Blog post engagement (WordPress posts)
   - Product page effectiveness
   - Review rates

4. **Recommendations**
   - Channel optimization
   - Campaign improvements
   - Content strategy

**For "operational_health":**
System and operational status:

1. **Technical Health**
   - System status (wc_get_system_status)
   - Performance indicators
   - Critical errors or warnings

2. **Order Processing**
   - Pending orders
   - Processing delays
   - Fulfillment efficiency

3. **Customer Service**
   - Order notes and issues
   - Review sentiment
   - Common complaints

4. **Operational Metrics**
   - Order processing time
   - Stock accuracy
   - Payment success rate

5. **Action Items**
   - Urgent technical fixes
   - Process improvements
   - Training needs

**Report Format:**
- Clear executive summary at top
- Visual data organization (tables, lists)
- Highlight critical numbers
- Use comparisons (vs previous period, vs target)
- Actionable recommendations
- Priority indicators (🔴 urgent, 🟡 important, 🟢 nice-to-have)',
				),
			),
		);
	}
}


