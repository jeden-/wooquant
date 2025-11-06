# WooQuant - MCP for WooCommerce (Extended)

[![License: GPL v2+](https://img.shields.io/badge/License-GPL%20v2%2B-blue.svg)](https://www.gnu.org/licenses/gpl-2.0)
[![WordPress](https://img.shields.io/badge/WordPress-6.4%2B-blue.svg)](https://wordpress.org/)
[![WooCommerce](https://img.shields.io/badge/WooCommerce-Required-purple.svg)](https://woocommerce.com/)

**AI-powered WooCommerce & WordPress management through Model Context Protocol (MCP)**

Connect your WooCommerce store and WordPress site with AI assistants like Claude Desktop and Cursor IDE. Manage products, orders, customers, content, and more using natural language.

---

## 🌟 What is WooQuant?

WooQuant is an **extended version** of the original [mcp-for-woocommerce](https://github.com/iOSDevSK/mcp-for-woocommerce) plugin by [iOSDevSK](https://github.com/iOSDevSK).

This community-enhanced version adds:
- ✅ **Full internationalization** (English + Polish, more languages welcome!)
- ✅ **Advanced admin panel** with user permissions management
- ✅ **16 intelligent AI prompts** for common e-commerce tasks
- ✅ **6 knowledge resources** to guide AI assistants
- ✅ **99 tools** (36 read + 63 write/action) covering all WooCommerce & WordPress operations
- ✅ **Enhanced security** with granular permissions and write operation controls

---

## 🚀 Quick Start

### 1. Install the Plugin

Download and install WooQuant on your WordPress site with WooCommerce active.

### 2. Enable MCP in Settings

Navigate to: **WordPress Admin → MCP for WooCommerce → Settings**

1. Toggle **"Enable MCP Functionality"** ON
2. Configure JWT authentication or disable for local development
3. *(Optional)* Enable **"Write Operations"** to allow AI to create/modify data

### 3. Connect Your AI Client

#### For Claude Desktop:
```json
{
  "mcpServers": {
    "woocommerce": {
      "url": "https://your-site.com/wp-json/wp/v2/wpmcp/streamable",
      "headers": {
        "Authorization": "Bearer YOUR_JWT_TOKEN_HERE"
      }
    }
  }
}
```

#### For Cursor IDE:
Add to your Cursor settings → MCP Servers:
```json
{
  "woocommerce-mystore": {
    "url": "https://your-site.com/wp-json/wp/v2/wpmcp/streamable",
    "headers": {
      "Authorization": "Bearer YOUR_JWT_TOKEN_HERE"
    }
  }
}
```

📚 **Full setup guide:** See [QUICK-START.md](QUICK-START.md)

---

## 🎯 What Can You Do?

### E-Commerce Management
- 🛍️ **Search products** intelligently ("find red dresses under $200")
- 📦 **Analyze orders** and sales performance
- 👥 **Segment customers** (VIP, at-risk, new)
- 📊 **Generate business reports** with insights
- 🏷️ **Manage coupons** and promotions
- 📦 **Monitor inventory** and low stock alerts
- 🚚 **Configure shipping** zones and methods

### Content & Site Management
- ✍️ **Create content** (blog posts, pages) with SEO
- 🔍 **Analyze SEO** for better rankings
- 🖼️ **Manage media** library (upload, organize, optimize)
- 📋 **Build menus** with UX best practices
- 👤 **Manage users** and permissions

### Customer Support
- 💬 **Answer customer queries** about orders and products
- 🔎 **Check order status** and tracking
- 📧 **Provide product information** instantly

### Data Operations
- 📤 **Import/Export** products and orders (CSV)
- 💾 **Backup & Restore** your data safely
- 🔄 **Migrate data** between sites

---

## 📋 What's Included?

### 99 Tools
- **36 Read Tools:** Get products, orders, customers, analytics
- **63 Write/Action Tools:** Create, update, delete data (requires permission)

### 16 AI Prompts
Pre-built workflows for:
- Product search, inventory management
- Sales analysis, customer segmentation
- Business reports, SEO analysis
- Content creation, media management
- And more...

### 6 Knowledge Resources
Contextual guides that help AI understand your store:
- WooCommerce search strategies
- Site configuration
- Plugin and theme info
- User roles and permissions

📚 **Full documentation:**
- [TOOLS-LIST.md](TOOLS-LIST.md) - Complete list of all 99 tools
- [PROMPTS-LIST.md](PROMPTS-LIST.md) - Guide to all 16 AI prompts
- [QUICK-START.md](QUICK-START.md) - Step-by-step setup guide

---

## 🔒 Security & Permissions

### Built-in Safety Features
- ✅ **JWT Authentication** for secure API access
- ✅ **User & Role Permissions** - Control who can use MCP
- ✅ **Write Operations Toggle** - Keep read-only by default
- ✅ **WordPress Capabilities** - Respects your existing permissions
- ✅ **Backup Reminders** - AI prompts suggest backups before destructive operations

### Recommended Setup
1. **Start with read-only** (Write Operations OFF)
2. **Test with safe operations** (searching, viewing)
3. **Enable writes when ready** for full functionality
4. **Limit MCP access** to administrators only (in User Permissions tab)

---

## 🌍 Internationalization

WooQuant is translation-ready!

**Currently available:**
- 🇬🇧 English (default)
- 🇵🇱 Polish (100% translated)

**Want to contribute a translation?**  
We welcome community translations! See [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.

---

## 📦 Requirements

- **WordPress:** 6.4 or higher
- **WooCommerce:** Latest version required
- **PHP:** 8.0 or higher
- **AI Client:** Claude Desktop, Cursor IDE, or any MCP-compatible client

---

## 🤝 Credits & License

### Original Author
- **Filip Dvoran (iOSDevSK)** - [Original mcp-for-woocommerce plugin](https://github.com/iOSDevSK/mcp-for-woocommerce)

### Extended Version
- **@jeden- and contributors** - WooQuant enhancements

### License
This plugin is licensed under **GPL-2.0-or-later**, the same as the original.

```
Original work Copyright (C) 2024 Filip Dvoran (iOSDevSK)
Extended work Copyright (C) 2025 @jeden- and contributors

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
```

**Special thanks** to iOSDevSK for creating the foundation of this powerful tool and sharing it with the open-source community! 🙏

---

## 📞 Support & Contributing

- 🐛 **Report issues:** [GitHub Issues](https://github.com/jeden-/wooquant/issues)
- 💡 **Feature requests:** [GitHub Discussions](https://github.com/jeden-/wooquant/discussions)
- 🤝 **Contribute:** Pull requests are welcome! See [CONTRIBUTING.md](CONTRIBUTING.md)
- 📖 **Documentation:** Check the `/docs` folder or wiki

---

## 📝 Changelog

See [CHANGELOG.md](CHANGELOG.md) for detailed version history.

**Current Version:** 1.1.9

---

## ⚠️ Disclaimer

This plugin is a community project and is **not affiliated with Automattic or WooCommerce**.

**Use at your own risk.** Always backup your site before enabling write operations. Test in a staging environment first.

---

Made with ❤️ for the WordPress and WooCommerce community

**[🌟 Star us on GitHub](https://github.com/jeden-/wooquant)** if you find this useful!
