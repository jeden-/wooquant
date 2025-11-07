# Contributing to WooQuant

Thank you for considering contributing to WooQuant! This project thrives because of contributors like you.

## 🌍 Translation Contributions

We welcome translations to make WooQuant accessible to more people!

### Current Languages:
- 🇬🇧 English (default)
- 🇵🇱 Polish (100% translated)

### How to Add a Translation:

1. **Copy the Polish translation files as a template:**
   - `languages/mcp-for-woocommerce-pl_PL.po` → `mcp-for-woocommerce-{your_locale}.po`
   - Example: `mcp-for-woocommerce-fr_FR.po` for French

2. **Translate the strings:**
   - Open the `.po` file in a text editor or use [Poedit](https://poedit.net/)
   - Translate all `msgstr` values (keep `msgid` unchanged)
   - Save your changes

3. **Compile the translation:**
   ```bash
   msgfmt mcp-for-woocommerce-{your_locale}.po -o mcp-for-woocommerce-{your_locale}.mo
   ```

4. **Generate JSON translations** (for React admin panel):
   ```bash
   wp i18n make-json languages/mcp-for-woocommerce-{your_locale}.po --no-purge
   ```

5. **Translate documentation:**
   - Copy `README.pl.md` → `README.{your_lang}.md`
   - Copy `QUICK-START.pl.md` → `QUICK-START.{your_lang}.md`
   - Copy `PROMPTS-LIST.pl.md` → `PROMPTS-LIST.{your_lang}.md`
   - Copy `TOOLS-LIST.pl.md` → `TOOLS-LIST.{your_lang}.md`
   - Translate all content

6. **Submit a Pull Request** with your translations!

---

## 🐛 Bug Reports

Found a bug? Please help us fix it!

### Before Submitting:
- Check if the issue already exists in [GitHub Issues](https://github.com/jeden-/wooquant/issues)
- Make sure you're using the latest version
- Try disabling other plugins to rule out conflicts

### What to Include:
- **WordPress version**
- **WooCommerce version**
- **PHP version**
- **Plugin version**
- **Steps to reproduce** the issue
- **Expected behavior** vs **actual behavior**
- **Screenshots** (if applicable)
- **Error messages** from debug.log

---

## 💡 Feature Requests

Have an idea for a new feature?

1. Check [GitHub Discussions](https://github.com/jeden-/wooquant/discussions) to see if it's already been proposed
2. Create a new discussion describing:
   - What problem does it solve?
   - How would it work?
   - Who would benefit from it?
3. Get community feedback before starting development

---

## 🔧 Code Contributions

### Development Setup:

1. **Clone the repository:**
   ```bash
   git clone https://github.com/jeden-/wooquant.git
   cd wooquant
   ```

2. **Install dependencies:**
   ```bash
   npm install
   composer install
   ```

3. **Build the admin panel:**
   ```bash
   npm run build
   ```

4. **Watch for changes** (during development):
   ```bash
   npm run start
   ```

### Code Standards:

- **PHP**: Follow [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- **JavaScript**: Follow [WordPress JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/)
- **React**: Use WordPress components (`@wordpress/components`) where possible
- **Translations**: All user-facing strings must use `__()`, `_e()`, or `_n()`

### Pull Request Process:

1. **Fork the repository** and create a feature branch:
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Make your changes:**
   - Write clean, documented code
   - Follow existing code style
   - Add comments for complex logic

3. **Test your changes:**
   - Test in WordPress admin
   - Test with Claude Desktop or Cursor IDE
   - Check for PHP errors
   - Verify translations work

4. **Commit with clear messages:**
   ```bash
   git commit -m "feat: Add amazing new feature"
   ```

   Use prefixes:
   - `feat:` New feature
   - `fix:` Bug fix
   - `docs:` Documentation changes
   - `refactor:` Code refactoring
   - `test:` Adding tests
   - `chore:` Maintenance tasks

5. **Push and create Pull Request:**
   ```bash
   git push origin feature/your-feature-name
   ```
   Then open a PR on GitHub with:
   - Clear description of changes
   - Reference any related issues
   - Screenshots (if UI changes)

6. **Wait for review** - we'll review and provide feedback!

---

## 📝 Documentation Contributions

Good documentation helps everyone! You can help by:

- Improving existing documentation
- Adding examples and use cases
- Creating video tutorials
- Writing blog posts about using WooQuant
- Answering questions in GitHub Discussions

---

## 🙏 Other Ways to Contribute

- ⭐ **Star the repository** on GitHub
- 🐦 **Share on social media** to spread the word
- 💬 **Help others** in GitHub Discussions
- 📖 **Write tutorials** or blog posts
- 🎥 **Create video guides**
- 🧪 **Test beta versions** and report issues

---

## Code of Conduct

### Our Pledge

We pledge to make participation in our project a harassment-free experience for everyone, regardless of age, body size, disability, ethnicity, gender identity, experience level, nationality, personal appearance, race, religion, or sexual identity.

### Our Standards

**Positive behavior includes:**
- Being respectful and welcoming
- Accepting constructive criticism
- Focusing on what's best for the community
- Showing empathy toward others

**Unacceptable behavior includes:**
- Harassment or discriminatory language
- Personal or political attacks
- Publishing others' private information
- Any conduct that could be inappropriate in a professional setting

### Enforcement

Project maintainers are responsible for clarifying standards and will take appropriate action in response to unacceptable behavior.

---

## 📞 Questions?

- 💬 **GitHub Discussions:** [github.com/jeden-/wooquant/discussions](https://github.com/jeden-/wooquant/discussions)
- 🐛 **Issues:** [github.com/jeden-/wooquant/issues](https://github.com/jeden-/wooquant/issues)
- 📧 **Email:** (coming soon)

---

## License

By contributing to WooQuant, you agree that your contributions will be licensed under the GPL-2.0-or-later license, the same as the project.

---

Thank you for helping make WooQuant better for everyone! 🎉

Every contribution, no matter how small, makes a difference.







