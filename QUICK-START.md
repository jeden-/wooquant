# Quick Start Guide - WooQuant MCP Plugin

**Get your AI assistant connected to WooCommerce in 5 minutes!**

This guide will help you set up the WooQuant MCP plugin and connect it to Claude Desktop or Cursor IDE.

---

## Step 1: Install & Activate

1. Upload the `mcp-for-woocommerce` folder to `/wp-content/plugins/`
2. Activate **"WooQuant - MCP for WooCommerce"** in WordPress plugins
3. Make sure **WooCommerce is installed and active**

---

## Step 2: Configure the Plugin

1. Go to **WordPress Admin → MCP for WooCommerce**
2. Click on the **"Settings"** tab
3. Toggle **"Enable MCP Functionality"** to ON

### Choose Your Authentication Mode:

#### Option A: JWT Authentication (Recommended for Production)
- Keep "Enable JWT Authentication" ON
- Click **"Generate New Token"**
- **Copy and save** the generated JWT token (you'll need it in Step 3)

#### Option B: No Authentication (For Local Development Only)
- Toggle "Enable JWT Authentication" OFF
- A local proxy file will be generated
- ⚠️ **WARNING:** Only use this on local/development sites!

4. Click **"Save Settings"**

---

## Step 3: Connect Your AI Client

### For Claude Desktop

1. Open Claude Desktop configuration file:
   - **Mac:** `~/Library/Application Support/Claude/claude_desktop_config.json`
   - **Windows:** `%APPDATA%\Claude\claude_desktop_config.json`

2. Add your WooCommerce site:

```json
{
  "mcpServers": {
    "woocommerce": {
      "url": "{{your-website.com}}/wp-json/mcpfowo/v1/mcp",
      "headers": {
        "Authorization": "Bearer YOUR_JWT_TOKEN_HERE"
      }
    }
  }
}
```

3. **Replace:**
   - `{{your-website.com}}` with your actual site URL (e.g., `https://mystore.com`)
   - `YOUR_JWT_TOKEN_HERE` with the token you generated in Step 2

4. **Save the file** and **restart Claude Desktop**

5. You should see "WooCommerce" in the MCP menu (🔌 icon)

### For Cursor IDE

1. Open Cursor Settings (Cmd+, or Ctrl+,)
2. Go to **Features → Model Context Protocol**
3. Click **"Add MCP Server"**
4. Add this configuration:

```json
{
  "woocommerce-mystore": {
    "url": "{{your-website.com}}/wp-json/mcpfowo/v1/mcp",
    "headers": {
      "Authorization": "Bearer YOUR_JWT_TOKEN_HERE"
    }
  }
}
```

5. **Replace** the placeholders as above
6. **Save** and restart Cursor

---

## Step 4: Test the Connection

### In Claude Desktop:
Try asking:
```
Show me my 5 newest products from my WooCommerce store
```

### In Cursor IDE:
Try asking:
```
Search for products on sale in my store
```

If the AI responds with your actual products, **you're all set!** 🎉

---

## Step 5: Enable Write Operations (Optional)

By default, the plugin is **read-only** for safety. If you want AI to create or modify data:

1. Go to **MCP for WooCommerce → Settings**
2. Toggle **"Enable Write Operations"** to ON
3. Click **"Save Settings"**
4. The page will refresh to load write tools

⚠️ **Important:** Write operations allow AI to:
- Create, update, or delete products
- Modify orders and customers
- Upload files
- Change settings

**Only enable this if:**
- You trust your AI assistant
- You understand the risks
- You have recent backups
- You've tested in a staging environment first

---

## Multiple Sites Setup

Want to connect multiple WooCommerce sites? Easy!

### In Claude Desktop:
```json
{
  "mcpServers": {
    "woocommerce-store1": {
      "url": "https://store1.com/wp-json/mcpfowo/v1/mcp",
      "headers": {
        "Authorization": "Bearer TOKEN_FROM_STORE1"
      }
    },
    "woocommerce-store2": {
      "url": "https://store2.com/wp-json/mcpfowo/v1/mcp",
      "headers": {
        "Authorization": "Bearer TOKEN_FROM_STORE2"
      }
    }
  }
}
```

**Tip:** Use descriptive names like `woocommerce-electronics-shop` or `woocommerce-fashion-boutique` to easily identify stores.

---

## What Can You Do Now?

### Try These Commands:

**Product Management:**
```
Find all products with low stock
Show me best-selling products this month
Search for blue t-shirts under $30
```

**Order Analysis:**
```
Show pending orders from last 7 days
Analyze sales performance for this month
What are the top-selling products?
```

**Customer Support:**
```
Check status of order #12345
Find products in the "Electronics" category
What are our current shipping zones?
```

**Content Creation:**
```
Create a blog post about our new product line
Analyze SEO for my product pages
Upload and optimize product images
```

**Business Reports:**
```
Generate an executive summary for last month
Show inventory that needs reordering
Segment customers by purchase behavior
```

💡 **Pro Tip:** The AI understands natural language, so just ask what you need!

---

## Troubleshooting

### "Cannot connect to MCP server"
- ✅ Check that MCP is enabled in plugin settings
- ✅ Verify your site URL is correct (include `https://` or `http://`)
- ✅ Make sure JWT token is copied correctly (no extra spaces)
- ✅ Check that WooCommerce is active

### "Authentication failed"
- ✅ Generate a new JWT token in plugin settings
- ✅ Update the token in your AI client config
- ✅ Restart your AI client

### "Tools not loading"
- ✅ Refresh WordPress admin panel (Cmd+Shift+R)
- ✅ Check for PHP errors in WordPress debug log
- ✅ Disable other plugins temporarily to check for conflicts

### Need More Help?
- 📖 See full documentation: [README.md](README.md)
- 🔧 Tool reference: [TOOLS-LIST.md](TOOLS-LIST.md)
- 🤖 Prompt guide: [PROMPTS-LIST.md](PROMPTS-LIST.md)
- 🐛 Report issues: [GitHub Issues](https://github.com/jeden-/wooquant/issues)

---

## Security Best Practices

1. **Never share your JWT tokens** - They're like passwords!
2. **Use HTTPS** - Especially important for production sites
3. **Regular backups** - Before enabling write operations
4. **Test in staging** - Try destructive operations safely first
5. **Limit user access** - Use the "User Permissions" tab to control who can use MCP
6. **Monitor activity** - Check your store regularly for unexpected changes

---

## Next Steps

- ✅ Explore the **"Tools"** tab to see all 99 available functions
- ✅ Check the **"Prompts"** tab for pre-built AI workflows
- ✅ View the **"Resources"** tab for knowledge bases AI can access
- ✅ Configure **"User Permissions"** to control access
- ✅ Read the full **[PROMPTS-LIST.md](PROMPTS-LIST.md)** for advanced usage examples

---

**Happy AI-powered WooCommerce management!** 🚀

*Questions? Issues? Contributions? Visit [github.com/jeden-/wooquant](https://github.com/jeden-/wooquant)*


