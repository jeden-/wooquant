# Changelog

All notable changes to WooQuant will be documented in this file.

## [1.2.1] - 2025-11-07

### Added
- **Automatic Updates System**: Integrated Plugin Update Checker library
  - Wtyczka automatycznie sprawdza aktualizacje z GitHub
  - Zero konfiguracji dla użytkowników - system działa od razu
  - Obsługa GitHub Releases - aktualizacje pobierane bezpośrednio z repozytorium
  - Kompatybilność z GitHub Updater Plugin dla użytkowników preferujących to rozwiązanie
- **GitHub Integration Headers**: Added GitHub Plugin URI and Branch headers
  - Pełna kompatybilność z GitHub Updater Plugin
  - Automatyczne wykrywanie nowych wersji z GitHub Releases

### Changed
- Updated version to 1.2.1
- Added `yahnis-elsts/plugin-update-checker` ^5.0 to Composer dependencies

### Technical Details
- Plugin Update Checker monitors: https://github.com/jeden-/wooquant
- Automatic update check every 12 hours
- Updates available through WordPress Admin → Dashboard → Updates
- Supports both automatic update system (built-in) and GitHub Updater plugin (optional)

## [1.2.1-beta] - 2025-01-06

### Fixed
- **Critical**: Fixed PHP syntax errors in all 16 prompt definition files (`includes/Prompts/*.php`)
  - Removed nested quotes in `__()` translation function calls
  - Fixed `Parse error: syntax error, unexpected identifier "description"` errors
- **Translation fixes**: Corrected Polish translations in admin panel
  - "Prompts" tab now correctly displays "Prompty" instead of "Podpowiedzi"
  - "Available Prompts" now displays "Dostępne Prompty"
  - Added missing translations for:
    - "Enable Write Operations" → "Włącz operacje zapisu"
    - "Allow tools to create, update, or delete data. Use with caution." → "Zezwól narzędziom na tworzenie, aktualizację lub usuwanie danych. Używaj ostrożnie."
    - "Ready-to-use AI workflows and scenarios..." → "Gotowe do użycia przepływy pracy AI i scenariusze..."
- Fixed JSON translation files domain from "messages" to "mcp-for-woocommerce"
- Updated all JSON translation files to use correct translations
- Created missing JSON translation file for current build hash (`db0c054ea21d82558cce`)

### Changed
- Updated translation JSON files to ensure WordPress loads correct translations
- Cleaned up temporary translation files from `languages/` directory

### Technical Details
- Fixed nested quote issue: `__( ''description' => 'text'', 'domain' )` → `__( 'text', 'domain' )`
- All prompt files now use correct translation function syntax
- WordPress now correctly loads Polish translations for React components

## [1.2.0] - 2025-01-06

### Changed
- **BREAKING**: Plugin display name changed from "MCP for WooCommerce" to "WooQuant"
- Complete documentation rewrite in English (international standard)
- Simplified all documentation for beginners and non-technical users
- Updated version to 1.2.0 for major release

### Added
- Full Polish translations for all documentation:
  - README.pl.md - Complete README in Polish
  - QUICK-START.pl.md - Quick start guide in Polish
  - PROMPTS-LIST.pl.md - All 16 prompts documented in Polish
- New QUICK-START guides (EN + PL) for 5-minute setup
- CONTRIBUTING.md with translation guidelines and code of conduct
- Comprehensive PROMPTS-LIST and TOOLS-LIST documentation in English
- 8 additional AI prompts (total 16):
  - **manage-coupons** - Coupon campaigns and promotional strategies
  - **analyze-customers** - Customer segmentation, CLV, churn prediction
  - **migrate-data** - Import/export with safety workflows
  - **manage-media** - Upload, organize, optimize media library
  - **manage-menus** - Navigation and UX optimization
  - **manage-shipping-tax** - Shipping zones and tax compliance
  - **manage-users** - User roles and security management
  - **generate-business-report** - Executive summaries and KPI dashboards
- All 6 available MCP resources now initialized and visible:
  - woocommerce-search-guide
  - WordPress://site-info
  - WordPress://plugin-info
  - WordPress://theme-info
  - WordPress://user-info
  - WordPress://site-settings

### Fixed
- Polish translations now load correctly in admin panel
- All React components use translatable strings via `__()`
- Frontend build updated with proper language loading
- Plugin name consistently uses "WooQuant" everywhere

### Documentation
- English as primary language (international standard)
- Polish translations complete (100% coverage)
- User-friendly language without technical jargon
- Consistent "WooQuant" branding throughout
- Proper GPL-2.0+ licensing and credits to original author

---

## [1.1.9] - 2025-01-04

### Added
- Write operations toggle in settings
- Full tool management interface
- User permissions tab

### Fixed
- Write operations persistence
- Tool type validation
- REST API connectivity

---

## [1.1.0 - 1.1.8] - 2024-2025

### Added
- Polish localization (UI translations)
- Advanced admin panel with React
- JWT authentication system
- Tool filtering and search
- 99 tools total (36 read + 63 write)

### Fixed
- Various PHP syntax errors
- Translation loading issues
- Build system improvements

---

## [1.0.0] - 2024

- Initial fork from [mcp-for-woocommerce](https://github.com/iOSDevSK/mcp-for-woocommerce) by iOSDevSK
- Basic MCP functionality
- WooCommerce integration

---

**For detailed commit history:** See [GitHub repository](https://github.com/jeden-/wooquant)
