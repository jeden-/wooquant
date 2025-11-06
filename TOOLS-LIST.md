# Complete List of MCP Tools - WooQuant

This document contains a complete list of all 99 MCP tools available in the WooQuant plugin (extended version of mcp-for-woocommerce).

**For Polish version:** See [TOOLS-LIST.pl.md](TOOLS-LIST.pl.md)

---

## Read-Only Tools - 36 tools

These tools only retrieve information and never modify your data. Safe to use anytime!

### WooCommerce Products

1. **wc_products_search** - Main product search tool (universal for all store types)
2. **wc_get_product** - Get product by ID
3. **wc_get_product_variations** - Get all product variations
4. **wc_get_product_variation** - Get specific variation by ID
5. **wc_intelligent_search** - Advanced intelligent product search
6. **wc_analyze_search_intent** - Analyze user search intent
7. **wc_analyze_search_intent_helper** - Helper for search intent analysis
8. **wc_get_products_by_brand** - Products by brand
9. **wc_get_products_by_category** - Products by category
10. **wc_get_products_by_attributes** - Products by attributes
11. **wc_get_products_filtered** - Products with multiple filters
12. **wc_get_product_detailed** - Detailed product information

### Categories, Tags & Attributes

13. **wc_get_categories** - List product categories
14. **wc_get_tags** - List product tags
15. **wc_get_product_attributes** - Product attribute definitions
16. **wc_get_product_attribute** - Get attribute by ID
17. **wc_get_attribute_terms** - Attribute terms (e.g., Red, Blue for Color)

### Orders

18. **wc_get_orders** - List WooCommerce orders
19. **wc_get_order** - Get order by ID

### Reviews

20. **wc_get_product_reviews** - List product reviews
21. **wc_get_product_review** - Get review by ID

### Shipping & Payment

22. **wc_get_shipping_zones** - Shipping zones
23. **wc_get_shipping_zone** - Get shipping zone by ID
24. **wc_get_shipping_methods** - Shipping methods
25. **wc_get_shipping_locations** - Shipping locations
26. **wc_get_payment_gateways** - Payment gateways
27. **wc_get_payment_gateway** - Get payment gateway by ID

### Tax & System

28. **wc_get_tax_classes** - Tax classes
29. **wc_get_tax_rates** - Tax rates
30. **wc_get_system_status** - WooCommerce system status
31. **wc_get_system_tools** - System tools

### WordPress Content

32. **wordpress_posts_list** - List WordPress posts
33. **wordpress_posts_get** - Get post by ID
34. **wordpress_pages_list** - List WordPress pages
35. **wordpress_pages_get** - Get page by ID

### Other

36. **wordpress_site_info** - WordPress site information

---

## Write/Action Tools - 63 tools

**WARNING:** Write tools can create, modify, or delete data. Only available when "Enable Write Operations" is turned ON in plugin settings.

### WooCommerce Products (4 tools)

1. **wc_create_product** - Create new product
2. **wc_update_product** - Update existing product
3. **wc_delete_product** - Delete product
4. **wc_bulk_update_products** - Bulk update products

### WooCommerce Orders (3 tools)

5. **wc_create_order** - Create new order
6. **wc_update_order_status** - Update order status
7. **wc_add_order_note** - Add note to order

### Product Categories (4 tools)

8. **wc_create_category** - Create category
9. **wc_update_category** - Update category
10. **wc_delete_category** - Delete category
11. **wc_reorder_categories** - Reorder categories

### Product Tags (3 tools)

12. **wc_create_tag** - Create tag
13. **wc_update_tag** - Update tag
14. **wc_delete_tag** - Delete tag

### Product Attributes (4 tools)

15. **wc_create_attribute** - Create global attribute
16. **wc_update_attribute** - Update attribute
17. **wc_delete_attribute** - Delete attribute
18. **wc_add_attribute_terms** - Add terms to attribute

### Customers (3 tools)

19. **wc_create_customer** - Create customer
20. **wc_update_customer** - Update customer
21. **wc_delete_customer** - Delete customer

### Coupons (3 tools)

22. **wc_create_coupon** - Create coupon
23. **wc_update_coupon** - Update coupon
24. **wc_delete_coupon** - Delete coupon

### Reviews (4 tools)

25. **wc_create_review** - Create review
26. **wc_update_review** - Update review
27. **wc_delete_review** - Delete review
28. **wc_approve_review** - Approve review

### Bulk Operations (4 tools)

29. **wc_bulk_create_products** - Bulk create products
30. **wc_bulk_delete_products** - Bulk delete products
31. **wc_bulk_update_prices** - Bulk update prices
32. **wc_bulk_update_stock** - Bulk update stock levels

### Import/Export (4 tools)

33. **wc_import_products_csv** - Import products from CSV
34. **wc_export_products_csv** - Export products to CSV
35. **wc_import_orders_csv** - Import orders from CSV
36. **wc_export_orders_csv** - Export orders to CSV

### WordPress Posts (4 tools)

37. **wp_create_post** - Create post
38. **wp_update_post** - Update post
39. **wp_delete_post** - Delete post
40. **wp_publish_post** - Publish post

### WordPress Pages (3 tools)

41. **wp_create_page** - Create page
42. **wp_update_page** - Update page
43. **wp_delete_page** - Delete page

### WordPress Media (4 tools)

44. **wp_upload_image** - Upload image
45. **wp_upload_file** - Upload file
46. **wp_delete_media** - Delete media
47. **wp_update_media_metadata** - Update media metadata

### WordPress Users (4 tools)

48. **wp_create_user** - Create user
49. **wp_update_user** - Update user
50. **wp_delete_user** - Delete user
51. **wp_change_user_role** - Change user role

### WordPress Menus (4 tools)

52. **wp_create_menu** - Create menu
53. **wp_add_menu_item** - Add menu item
54. **wp_update_menu** - Update menu
55. **wp_delete_menu** - Delete menu

### Settings (4 tools)

56. **wc_update_settings** - Update WooCommerce settings
57. **wp_update_settings** - Update WordPress settings
58. **wc_update_shipping_zone** - Update shipping zone
59. **wc_update_payment_gateway** - Update payment gateway

### Backup & Restore (4 tools)

60. **wc_backup_products** - Backup products
61. **wc_restore_products** - Restore products from backup
62. **wp_backup_content** - Backup WordPress content
63. **wp_restore_content** - Restore content from backup

---

## Summary

- **Read Tools:** 36
- **Write Tools:** 63
- **Total:** 99 MCP tools

## Functionality Types

- **read** - Read-only (36 tools) - Safe, no modifications
- **create** - Create new items
- **update** - Modify existing items
- **delete** - Remove items
- **action** - Perform specific actions

---

## Important Notes

1. **All Write tools** require "Enable Write Operations" to be turned ON in plugin settings.
2. **Write tools require proper permissions** (e.g., `manage_woocommerce`, `edit_posts`).
3. **Some Write tools are destructive** (e.g., `wc_delete_product`, `wp_delete_user`) - use with caution!
4. **Always backup before** using destructive operations.
5. **Test in staging environment** first for bulk operations.

---

## Tool Categories by Function

### Product Management
- Search & find products (12 read tools)
- Create, update, delete products (4 write tools)
- Bulk operations (4 tools)
- Categories, tags, attributes management (11 write tools)

### Order Management
- View orders and details (2 read tools)
- Create orders, update status, add notes (3 write tools)

### Customer Management
- Customer data (included in orders)
- Create, update, delete customers (3 write tools)

### Marketing & Sales
- Coupons management (3 write tools)
- Reviews management (2 read + 4 write tools)
- Sales analytics (via AI prompts)

### Content Management
- Posts and pages (4 read + 7 write tools)
- Media library (4 write tools)
- Menus (4 write tools)

### Store Configuration
- Shipping & payment (8 read + 2 write tools)
- Tax settings (2 read tools)
- System info (2 read tools)
- General settings (2 write tools)

### Data Operations
- Import/Export CSV (4 tools)
- Backup/Restore (4 tools)

---

## How AI Uses These Tools

AI assistants like Claude and Cursor automatically use these tools when you ask questions:

**Examples:**

**You ask:** "Show me products on sale"  
**AI uses:** `wc_products_search` with sale filter

**You ask:** "Create a blog post about new products"  
**AI uses:** `wc_products_search` to find products, then `wp_create_post` to create content

**You ask:** "What's the status of order 12345?"  
**AI uses:** `wc_get_order` with ID 12345

**You ask:** "Export all products to CSV"  
**AI uses:** `wc_export_products_csv`

You don't need to remember tool names - just ask in natural language!

---

## Safety Features

### Built-in Protection:
- ✅ **Read-only by default** - Write operations must be explicitly enabled
- ✅ **WordPress capabilities** - Respects user permissions
- ✅ **Confirmation prompts** - AI asks before destructive operations
- ✅ **Backup recommendations** - AI suggests backups before risky operations
- ✅ **Audit trail** - WordPress logs all changes

### Best Practices:
1. Start with read-only mode
2. Enable writes only when needed
3. Test in staging first
4. Keep regular backups
5. Limit MCP access to trusted users only

---

## Need Help?

- 📖 **Full Documentation:** [README.md](README.md)
- 🚀 **Quick Start Guide:** [QUICK-START.md](QUICK-START.md)
- 🤖 **Prompt Guide:** [PROMPTS-LIST.md](PROMPTS-LIST.md)
- 🐛 **Report Issues:** [GitHub Issues](https://github.com/jeden-/wooquant/issues)

---

**Version:** 1.1.9  
**Last Updated:** 2025-01-06

Made with ❤️ for the WordPress & WooCommerce community
