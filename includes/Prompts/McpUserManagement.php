<?php
declare(strict_types=1);


namespace McpForWoo\Prompts;

use McpForWoo\Core\RegisterMcpPrompt;

/**
 * Class McpUserManagement
 *
 * Prompt for managing WordPress users, roles, and permissions.
 * Helps AI create, audit, and optimize user access and security.
 *
 * @package McpForWoo\Prompts
 */
class McpUserManagement {

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
				'name'        => 'manage-users',
				'description' => __( 'Manage WordPress users, roles, permissions, and security', 'mcp-for-woocommerce' ),
				'arguments'   => array(
					array(
						'name'        => 'task',
						'description' => __( 'Task: "create", "audit", "security_review", "role_optimization", "bulk_update"', 'mcp-for-woocommerce' ),
						'required'    => true,
						'type'        => 'string',
					),
					array(
						'name'        => 'details',
						'description' => __( 'Task-specific details (user info, role, criteria)', 'mcp-for-woocommerce' ),
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
					'text' => 'Manage WordPress users - task: {{task}}{{#if details}} - {{details}}{{/if}}.

**Available Tools:**
- wp_create_user: Create new user account
- wp_update_user: Update user information
- wp_delete_user: Remove user (careful!)
- wp_change_user_role: Change user role/permissions
- WordPress://user-info resource: Get user information

**WordPress User Roles:**
- **Administrator**: Full site control (use sparingly!)
- **Shop Manager**: WooCommerce management (products, orders)
- **Editor**: Publish and manage content (posts, pages)
- **Author**: Write and publish own posts
- **Contributor**: Write but not publish posts
- **Subscriber**: Basic access (comments, profile)
- **Customer**: WooCommerce customer account

**For "create" task:**

1. **Gather Requirements:**
   - User purpose and responsibilities
   - Required access level
   - Department/team
   - Temporary or permanent access

2. **Determine Appropriate Role:**
   - Principle of least privilege (minimum needed)
   - Consider custom roles if standard roles too broad
   - Temporary elevated access if needed for project

3. **Create User:**
   Use wp_create_user with:
   - Professional username (firstname.lastname or role-based)
   - Strong password (suggest: 16+ chars, mixed case, numbers, symbols)
   - Work email address
   - First and last name
   - Appropriate role

4. **Security Setup:**
   - Enforce 2FA (recommend to user)
   - Set password change requirement
   - Configure session timeout if possible
   - Document access granted

5. **Onboarding:**
   - Provide credentials securely
   - Document responsibilities
   - List accessible areas
   - Set review date for access audit

**For "audit" task:**

1. **User Inventory:**
   - Total users by role
   - Active vs inactive users
   - Last login dates
   - User creation dates

2. **Access Review:**
   - **Administrator accounts**: Should be minimal (1-2)
   - **Shop Managers**: Should be limited and known
   - **Content roles**: Appropriate to team size
   - **Customers**: Normal e-commerce accounts

3. **Identify Issues:**
   🔴 **Critical:**
   - Multiple unknown administrators
   - Generic admin accounts (admin, administrator)
   - Never-logged-in administrators
   - Suspicious user creation patterns
   
   🟡 **Important:**
   - Inactive accounts (no login > 90 days)
   - Over-privileged users (too much access)
   - Missing 2FA on admin accounts
   - Shared accounts
   
   🟢 **Maintenance:**
   - Outdated user information
   - Inconsistent username formats
   - Customer accounts needing cleanup

4. **Security Metrics:**
   - Admin-to-total-user ratio (should be < 5%)
   - Average account age
   - Dormant account count
   - Password age (if available)

5. **Recommendations:**
   - Accounts to remove
   - Roles to downgrade
   - Security improvements
   - Policy updates needed

**For "security_review" task:**

1. **Administrator Audit:**
   - List all admin accounts
   - Verify each is necessary and authorized
   - Check last activity
   - Recommend removals/downgrades

2. **Permission Analysis:**
   - Users with excessive permissions
   - Custom roles review
   - Plugin-granted capabilities
   - WooCommerce manager access

3. **Security Posture:**
   - Enforce strong passwords
   - Recommend 2FA implementation
   - Check for default usernames (admin, test)
   - Review user enumeration protection
   - Session management settings

4. **Best Practices Check:**
   - Unique usernames (no "admin")
   - Role-based access control
   - Regular access reviews scheduled
   - Offboarding process exists
   - User creation documented

5. **Incident Response:**
   - Look for signs of compromise
   - Unusual user creation activity
   - Unexpected role changes
   - Suspicious login patterns

**For "role_optimization" task:**

1. **Current Role Distribution:**
   - Count users per role
   - Analyze appropriateness
   - Identify role creep (accumulated excess permissions)

2. **Role Efficiency:**
   - Are standard roles sufficient?
   - Need for custom roles?
   - Permission overlaps
   - Gap in needed permissions

3. **Recommendations:**
   - Consolidate roles if possible
   - Create custom roles if needed
   - Downgrade over-privileged users
   - Standardize role assignment process

4. **Implementation** (if write enabled):
   - Use wp_change_user_role for adjustments
   - Document role changes
   - Notify affected users
   - Monitor for access issues

**For "bulk_update" task:**

⚠️ **AFFECTS MULTIPLE USERS**

1. **Define Scope:**
   - Which users affected
   - What changes to make
   - Validation criteria
   - Rollback plan

2. **Common Bulk Operations:**
   - Role changes (seasonal staff, project end)
   - Cleanup inactive accounts
   - Update email domains (company change)
   - Password reset enforcement
   - Permission adjustments

3. **Safety Checks:**
   - Preview changes before applying
   - Never bulk-modify all admins
   - Backup user database first
   - Test on 1-2 users first
   - Keep one admin account unchanged

4. **Execution:**
   - Process in batches
   - Verify each change
   - Document all modifications
   - Test affected user access
   - Notify users of changes

5. **Post-Update:**
   - Verify system stability
   - Check for locked-out users
   - Monitor support requests
   - Document completion

**SECURITY BEST PRACTICES:**

1. **Account Creation:**
   - Use real names in usernames
   - Work email addresses only
   - Strong, unique passwords
   - Minimum necessary permissions
   - Document creation reason

2. **Account Maintenance:**
   - Regular access audits (quarterly)
   - Remove dormant accounts (90+ days)
   - Review role appropriateness
   - Update contact information
   - Monitor for compromises

3. **Administrator Protection:**
   - Limit to 1-2 accounts
   - Never use "admin" username
   - Require 2FA
   - Restrict admin login locations if possible
   - Log all admin actions

4. **Offboarding Process:**
   - Immediate account deactivation on departure
   - Review and revoke all access
   - Transfer content ownership if needed
   - Document access removal
   - Monitor for suspicious activity

5. **Customer Account Management:**
   - Separate from staff accounts
   - Regular cleanup of test/spam accounts
   - GDPR compliance (data removal requests)
   - Merge duplicate customer accounts',
				),
			),
		);
	}
}







