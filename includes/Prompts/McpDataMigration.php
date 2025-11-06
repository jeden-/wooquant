<?php
declare(strict_types=1);


namespace McpForWoo\Prompts;

use McpForWoo\Core\RegisterMcpPrompt;

/**
 * Class McpDataMigration
 *
 * Prompt for importing, exporting, and migrating WooCommerce data.
 * Helps AI manage bulk data operations safely and efficiently.
 *
 * @package McpForWoo\Prompts
 */
class McpDataMigration {

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
				'name'        => 'migrate-data',
				'description' => __( 'Import, export, backup, and migrate WooCommerce and WordPress data', 'mcp-for-woocommerce' ),
				'arguments'   => array(
					array(
						'name'        => 'operation',
						'description' => __( 'Operation: "import", "export", "backup", "restore", "migrate", "validate"', 'mcp-for-woocommerce' ),
						'required'    => true,
						'type'        => 'string',
					),
					array(
						'name'        => 'data_type',
						'description' => __( 'Data type: "products", "orders", "customers", "content", "all"', 'mcp-for-woocommerce' ),
						'required'    => true,
						'type'        => 'string',
					),
					array(
						'name'        => 'source',
						'description' => __( 'Source file path or description (for import/restore)', 'mcp-for-woocommerce' ),
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
					'text' => 'Perform data migration operation: {{operation}} for data type: {{data_type}}{{#if source}} from source: {{source}}{{/if}}.

**Available Tools:**
- wc_import_products_csv, wc_export_products_csv
- wc_import_orders_csv, wc_export_orders_csv
- wc_backup_products, wc_restore_products
- wp_backup_content, wp_restore_content
- wc_bulk_create_products, wc_bulk_update_products

**CRITICAL SAFETY WORKFLOW:**

**For "export" operations:**
1. Validate export scope and filters
2. Use appropriate export tool:
   - wc_export_products_csv for products
   - wc_export_orders_csv for orders
3. Provide clear file location
4. Include export statistics (rows, size)
5. Recommend backup verification

**For "import" operations:**
⚠️ **DESTRUCTIVE - Proceed with extreme caution**
1. **MANDATORY BACKUP FIRST**:
   - Run wc_backup_products or wp_backup_content
   - Verify backup completed successfully
   - Document backup location
   
2. **Validation**:
   - Check CSV format and headers
   - Validate required fields
   - Check for duplicate SKUs/IDs
   - Estimate impact (X new, Y updates)
   
3. **Test Import** (if possible):
   - Import 1-5 sample rows first
   - Verify data correctness
   - Check for errors
   
4. **Full Import**:
   - Use wc_import_products_csv or wc_import_orders_csv
   - Monitor progress and errors
   - Log any issues
   
5. **Post-Import Verification**:
   - Count imported items
   - Spot-check data accuracy
   - Verify product links work
   - Check images loaded
   
6. **Rollback Plan**:
   - Keep backup accessible
   - Document restore procedure

**For "backup" operations:**
1. Determine scope (full site vs specific data)
2. Create timestamped backup:
   - wc_backup_products for WooCommerce data
   - wp_backup_content for WordPress content
3. Verify backup integrity
4. Document backup location and timestamp
5. Test restore procedure periodically

**For "restore" operations:**
⚠️ **EXTREMELY DESTRUCTIVE - Last resort only**
1. **Confirm necessity** - explain why restore is needed
2. **Create current state backup first** (backup before restore!)
3. Validate restore file exists and is intact
4. Use wc_restore_products or wp_restore_content
5. Verify restored data
6. Document what was lost/changed

**For "migrate" operations:**
1. Full export from source
2. Data transformation/mapping if needed
3. Validation of transformed data
4. Backup target site
5. Import to target
6. Verification and testing

**For "validate" operations:**
1. Check data consistency
2. Find orphaned records
3. Validate product data completeness
4. Check for missing images/links
5. Provide cleanup recommendations

**ALWAYS:**
- Show clear progress and status
- Document all operations
- Provide rollback instructions
- Err on side of caution
- Get user confirmation for destructive operations',
				),
			),
		);
	}
}


