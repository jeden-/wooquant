<?php
declare(strict_types=1);


namespace McpForWoo\Prompts;

use McpForWoo\Core\RegisterMcpPrompt;

/**
 * Class McpMenuManagement
 *
 * Prompt for managing WordPress navigation menus.
 * Helps AI create, update, and optimize site navigation structure.
 *
 * @package McpForWoo\Prompts
 */
class McpMenuManagement {

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
				'name'        => 'manage-menus',
				'description' => 'Create and optimize WordPress navigation menus for better UX',
				'arguments'   => array(
					array(
						'name'        => 'action',
						'description' => 'Action: "create", "optimize", "analyze", "restructure"',
						'required'    => true,
						'type'        => 'string',
					),
					array(
						'name'        => 'menu_location',
						'description' => 'Menu location: "primary", "footer", "mobile", "sidebar"',
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
					'text' => 'Manage navigation menus - action: {{action}}{{#if menu_location}} for location: {{menu_location}}{{/if}}.

**Available Menu Tools:**
- wp_create_menu: Create new menu
- wp_add_menu_item: Add items to menu
- wp_update_menu: Update existing menu
- wp_delete_menu: Remove menu

**For "create" action:**
1. Determine menu purpose and audience
2. Plan menu structure (max 7 top-level items recommended)
3. Create logical hierarchy:
   - Group related pages
   - Use clear, action-oriented labels
   - Consider user journey
   
4. Recommended menu structures:

   **E-commerce Primary Menu:**
   - Shop (with category dropdowns)
   - New Arrivals
   - Sale
   - About Us
   - Contact
   
   **Content Site Primary Menu:**
   - Home
   - Blog/Articles
   - Services/Products
   - About
   - Contact
   
   **Footer Menu:**
   - Privacy Policy
   - Terms & Conditions
   - Shipping Info
   - Returns
   - FAQ
   
5. Use wp_create_menu and wp_add_menu_item to build structure

**For "optimize" action:**
1. Analyze current menu structure
2. Apply UX best practices:
   - Limit top-level items (5-7 ideal)
   - Clear, concise labels (< 20 chars)
   - Logical grouping
   - Important items first
   - Mobile-friendly structure
   
3. Check for issues:
   - Broken links
   - Orphaned pages (not in menu)
   - Too deep nesting (> 3 levels)
   - Duplicate items
   - Generic labels ("Click here", "Page")
   
4. SEO considerations:
   - Use keywords in menu labels
   - Proper internal linking structure
   - Descriptive anchor text
   
5. Provide specific recommendations with priorities

**For "analyze" action:**
1. Review all site menus
2. Navigation analysis:
   - Menu item count per location
   - Depth of navigation
   - Link destinations
   - Label clarity
   
3. Compare against best practices:
   - Information architecture principles
   - Industry standards
   - Competitor analysis
   
4. User experience assessment:
   - Findability score
   - Consistency across site
   - Mobile usability
   
5. Report findings with actionable insights

**For "restructure" action:**
⚠️ **Changes affect site navigation**
1. Review current structure
2. Propose new organization:
   - Simplified hierarchy
   - Improved grouping
   - Better labels
   - Enhanced user flow
   
3. Show before/after comparison
4. If write operations enabled:
   - Backup current menu structure
   - Implement new structure using wp_update_menu
   - Update menu items with wp_add_menu_item
   - Test on different devices
   
5. Post-restructure verification:
   - All links working
   - Mobile menu functional
   - Proper hierarchy displayed

**Menu Best Practices:**
- **Primary Menu**: 5-7 items max, most important content
- **Footer Menu**: Legal, policies, support links
- **Mobile Menu**: Simplified, touch-friendly
- **Mega Menu**: For large e-commerce (product categories)
- Labels: Clear, concise, action-oriented
- Avoid: Generic terms, jargon, more than 3 levels deep',
				),
			),
		);
	}
}

