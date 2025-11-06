# Complete List of MCP Prompts - WooQuant

This document provides a comprehensive guide to all 16 MCP prompts available in the WooQuant plugin, utilizing the full functionality of 99 tools.

**For Polish version:** See [PROMPTS-LIST.pl.md](PROMPTS-LIST.pl.md)

---

## WordPress Prompts (6)

### 1. **get-site-info** - Site Information
**Description:** Retrieves detailed WordPress site information  
**Arguments:**
- `info_type` (optional): Type of information (general, plugins, theme, users, settings)

**Example Usage:**
```
Use the get-site-info prompt to get complete plugin information
```

**What it does:**
- Gathers site, plugin, and theme data
- User roles and permissions
- WordPress configuration
- Helpful for troubleshooting

---

### 2. **create-content** - Content Creation
**Description:** Creates and optimizes WordPress content (posts, pages)  
**Arguments:**
- `content_type` (required): "post" or "page"
- `topic` (required): Topic or title for the content
- `tone` (optional): Writing tone (professional, casual, friendly, formal)

**Example Usage:**
```
Use the create-content prompt to create a post about "How to choose the perfect product" in a friendly tone
```

**What it does:**
- Analyzes existing content
- Creates well-structured content
- SEO optimization (keywords, meta descriptions)
- Suggests featured images
- If write operations enabled: creates content automatically

---

### 3. **analyze-seo** - SEO Analysis
**Description:** Analyzes and optimizes SEO for content and products  
**Arguments:**
- `target` (required): "site", "products", "posts", "pages" or specific URL/ID
- `focus_keyword` (optional): Target keyword for optimization

**Example Usage:**
```
Use the analyze-seo prompt for products with focus_keyword "running shoes"
```

**What it does:**
- Content analysis (titles, meta, headers, keywords)
- Technical analysis (URLs, links, images, alt text)
- WooCommerce-specific (product schema, reviews)
- Prioritized recommendations
- Actionable improvement steps

---

### 4. **manage-media** - Media Management
**Description:** Upload, organize, optimize media library  
**Arguments:**
- `task` (required): "upload", "organize", "optimize", "audit", "cleanup", "batch_upload"
- `details` (optional): Task-specific details (file path, criteria)

**Example Usage:**
```
Use the manage-media prompt with task "audit" to check media library
```

**What it does:**
- **Upload**: Upload images with optimization and SEO
- **Organize**: Organize media structure
- **Optimize**: Compression, alt text, file names
- **Audit**: Statistics, issues, unused files
- **Cleanup**: Remove duplicates and unused files (CAUTION!)

---

### 5. **manage-menus** - Menu Management
**Description:** Creates and optimizes navigation menus  
**Arguments:**
- `action` (required): "create", "optimize", "analyze", "restructure"
- `menu_location` (optional): "primary", "footer", "mobile", "sidebar"

**Example Usage:**
```
Use the manage-menus prompt with action "optimize" for primary menu
```

**What it does:**
- **Create**: Creates professional menu structures (e-commerce, content site)
- **Optimize**: Applies UX best practices (max 5-7 top-level items)
- **Analyze**: Navigation and usability assessment
- **Restructure**: Redesigns menu for better UX

---

### 6. **manage-users** - User Management
**Description:** Manages users, roles, and security  
**Arguments:**
- `task` (required): "create", "audit", "security_review", "role_optimization", "bulk_update"
- `details` (optional): Task-specific details

**Example Usage:**
```
Use the manage-users prompt with task "security_review" to conduct security audit
```

**What it does:**
- **Create**: Creates accounts with appropriate permissions (least privilege)
- **Audit**: User inventory, identifies issues
- **Security Review**: Administrator audit, 2FA, security
- **Role Optimization**: Optimizes role assignments
- **Bulk Update**: Mass changes (CAUTION!)

---

## WooCommerce Prompts (8)

### 7. **search-products** - Product Search
**Description:** Intelligent product search with fallbacks  
**Arguments:**
- `query` (required): Search query (e.g., "cheapest laptops on sale", "newest shoes")

**Example Usage:**
```
Use the search-products prompt with query "red dresses under $200"
```

**What it does:**
- Reads search guide (woocommerce-search-guide)
- Analyzes search intent
- Multi-stage strategy (filters → categories → text search)
- ALWAYS returns product links
- Never returns empty results without trying all fallback strategies

---

### 8. **analyze-sales** - Sales Analysis
**Description:** Analyzes WooCommerce sales data  
**Arguments:**
- `time_span` (required): Time period (last_7_days, last_30_days, last_month, last_quarter, last_year)

**Example Usage:**
```
Use the analyze-sales prompt for period last_30_days
```

**What it does:**
- Total sales and trends
- Average order value
- Top products
- Sales trend analysis
- Insights and recommendations

---

### 9. **analyze-orders** - Order Analysis
**Description:** Analyzes orders with filters and insights  
**Arguments:**
- `status` (optional): Order status (pending, processing, completed, cancelled)
- `time_period` (optional): Time period (today, last_7_days, last_30_days, this_month)

**Example Usage:**
```
Use the analyze-orders prompt with status "pending" for period last_7_days
```

**What it does:**
- Order count and revenue
- Order status distribution
- Top customers and products
- Payment methods
- Concerning patterns (high cancellations)
- Action recommendations

---

### 10. **customer-support** - Customer Support
**Description:** AI-powered support for customer inquiries  
**Arguments:**
- `customer_query` (required): Customer question
- `order_id` (optional): Order ID if query is about specific order

**Example Usage:**
```
Use the customer-support prompt with query "Where is my order?" and order_id 12345
```

**What it does:**
- Checks order status
- Searches products (colors, sizes, options)
- Shipping and availability information
- Friendly, helpful responses
- Product links included

---

### 11. **manage-inventory** - Inventory Management
**Description:** Analyzes and manages inventory levels  
**Arguments:**
- `action` (required): "check_low_stock", "check_out_of_stock", "analyze_all", "update_stock"
- `threshold` (optional): Low stock threshold (default: 5)

**Example Usage:**
```
Use the manage-inventory prompt with action "check_low_stock" and threshold 10
```

**What it does:**
- Identifies low stock products
- Finds out of stock products
- Analyzes inventory levels
- Restocking recommendations
- If write enabled: updates stock levels

---

### 12. **manage-coupons** - Coupon Management
**Description:** Creates and manages promotional campaigns  
**Arguments:**
- `action` (required): "create_campaign", "analyze_performance", "optimize_existing", "seasonal_promotion"
- `details` (optional): Campaign details

**Example Usage:**
```
Use the manage-coupons prompt with action "create_campaign" for "Black Friday Sale"
```

**What it does:**
- **Create Campaign**: Designs coupon strategies (goals, targets, products)
- **Analyze Performance**: ROI metrics, redemption rate, impact on AOV
- **Optimize Existing**: Improves underperforming coupons
- **Seasonal Promotion**: Creates themed campaigns (holidays, sales)

---

### 13. **analyze-customers** - Customer Analysis
**Description:** Customer segmentation and behavior analysis  
**Arguments:**
- `analysis_type` (required): "segmentation", "lifetime_value", "churn_risk", "purchase_patterns", "loyalty_analysis"
- `segment` (optional): Specific customer segment

**Example Usage:**
```
Use the analyze-customers prompt with type "churn_risk" to find customers to reactivate
```

**What it does:**
- **Segmentation**: VIP, At-Risk, New, Bargain Hunters, Loyal Fans
- **Lifetime Value**: CLV, top 20% customers, predicted value
- **Churn Risk**: Inactive customers, win-back campaigns
- **Purchase Patterns**: Buying cycles, cross-sell, trends
- **Loyalty Analysis**: Retention metrics, loyalty program

---

### 14. **manage-shipping-tax** - Shipping & Tax
**Description:** Configures and optimizes shipping and tax settings  
**Arguments:**
- `focus` (required): "shipping", "tax", "both", "audit", "optimize"
- `region` (optional): Specific region/zone

**Example Usage:**
```
Use the manage-shipping-tax prompt with focus "shipping" and action "optimize"
```

**What it does:**
- **Shipping Audit**: Analyzes zones, methods, costs, coverage gaps
- **Shipping Optimize**: Zone recommendations, free shipping thresholds
- **Tax Audit**: Verifies rate compliance with regulations
- **Tax Optimize**: Proper tax classes, legal compliance
- **Both**: Comprehensive checkout optimization

---

## Data & Reporting Prompts (2)

### 15. **migrate-data** - Data Migration
**Description:** Import, export, backup, restore data  
**Arguments:**
- `operation` (required): "import", "export", "backup", "restore", "migrate", "validate"
- `data_type` (required): "products", "orders", "customers", "content", "all"
- `source` (optional): Source file path

**Example Usage:**
```
Use the migrate-data prompt with operation "backup" for data_type "products"
```

**What it does:**
- **Export**: Safe export to CSV
- **Import**: ⚠️ DESTRUCTIVE - backup first, validation, test, full import
- **Backup**: Timestamped backups with verification
- **Restore**: ⚠️ VERY DESTRUCTIVE - last resort, backup before restore
- **Validate**: Checks data consistency, orphaned records

---

### 16. **generate-business-report** - Business Reports
**Description:** Comprehensive business reports and KPIs  
**Arguments:**
- `report_type` (required): "executive_summary", "sales_performance", "inventory_status", "customer_insights", "marketing_effectiveness", "operational_health"
- `time_period` (optional): Time period

**Example Usage:**
```
Use the generate-business-report prompt with type "executive_summary" for last_30_days
```

**What it does:**
- **Executive Summary**: KPI dashboard, highlights, issues, top recommendations
- **Sales Performance**: Deep sales analysis, products, customers
- **Inventory Status**: Stock status, fast/slow movers, ordering recommendations
- **Customer Insights**: Segmentation, CLV, retention, personalization
- **Marketing Effectiveness**: Campaign ROI, coupons, conversions, content performance
- **Operational Health**: Technical status, processing, customer service, metrics

---

## How to Use Prompts?

### In Cursor IDE:
```
# Direct prompt call
Use the search-products prompt with query "blue shirts"

# Or just describe what you want
Find the best-selling products this month
```

### In Claude Desktop (via MCP):
Prompts are automatically available in the MCP menu as "Prompts". AI will recognize your query and use the appropriate prompt.

### Best Practices:

1. **Start with simple queries** - AI will choose the right prompt
2. **Use prompt names** when you know what you need
3. **For write operations** - enable "Enable Write Operations" in settings
4. **For destructive operations** (import, restore, cleanup) - AI will ask for confirmation
5. **Test on small data** before bulk operations

---

## Functionality Coverage

### ✅ Full coverage of 99 tools:
- **36 Read Tools** - all covered by prompts
- **63 Write/Action Tools** - all covered by prompts + safety mechanisms

### Main Areas:
- ✅ WooCommerce Products (search, management, SEO)
- ✅ Orders & Sales (analysis, support, reports)
- ✅ Customers (segmentation, CLV, churn, support)
- ✅ Inventory (stock, reordering, optimization)
- ✅ Marketing (coupons, promotions, ROI)
- ✅ Shipping & Tax (zones, methods, compliance)
- ✅ WordPress Content (posts, pages, SEO)
- ✅ Media (upload, organization, optimization)
- ✅ Menus (navigation, UX)
- ✅ Users (roles, security, audit)
- ✅ Data (import, export, backup, restore)
- ✅ Business Reports (KPI, insights, recommendations)

---

## Security

### Read-Only Operations (Read):
- Safe, don't modify data
- Can be used without worry

### Write Operations (Write):
- Require "Enable Write Operations" to be ON
- AI always shows what will be changed
- For destructive operations: requires confirmation
- Recommends backups before critical operations

### ⚠️ Operations requiring extra caution:
- `migrate-data` (import, restore)
- `manage-media` (cleanup)
- `manage-users` (bulk_update, delete)
- Any bulk deletion

---

## Support

For issues or questions:
- GitHub: https://github.com/jeden-/wooquant
- Issues: https://github.com/jeden-/wooquant/issues

**Documentation Version:** 1.1.9  
**Last Updated:** 2025-01-06
