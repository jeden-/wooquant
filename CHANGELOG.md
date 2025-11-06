# Changelog

Wszystkie istotne zmiany w projekcie WooQuant będą dokumentowane w tym pliku.

Format bazuje na [Keep a Changelog](https://keepachangelog.com/pl/1.0.0/),
a projekt stosuje [Semantic Versioning](https://semver.org/lang/pl/).

## [Unreleased]

## [1.2.0] - 2025-01-05

### 🎉 Pierwsza publiczna wersja WooQuant (Extended)

Ta wersja znacząco rozszerza oryginalny plugin [MCP for WooCommerce v1.0.0](https://github.com/iOSDevSK/mcp-for-woocommerce) autorstwa @iOSDevSK.

### ✨ Dodane

#### Internacjonalizacja (i18n)
- **Pełne wsparcie dla języka polskiego**
  - Przetłumaczono 100% stringów w interfejsie
  - Dodano pliki `.po` i `.mo` dla tłumaczeń PHP
  - Dodano pliki `.json` dla tłumaczeń React/JavaScript
  - Dodano `load_plugin_textdomain()` dla lokalnych instalacji
  - Dodano `wp_set_script_translations()` dla React

#### System uprawnień użytkowników
- **Nowa zakładka "Uprawnienia Użytkowników"**
  - Granularna kontrola dostępu do narzędzi MCP
  - Zarządzanie uprawnieniami per rola WordPress
  - Zarządzanie uprawnieniami per indywidualny użytkownik
  - Interfejs React z dynamicznym ładowaniem uprawnień
  - Backend AJAX API (`ajax_get_user_permissions`, `ajax_save_user_permissions`)
  - Zapisywanie uprawnień w opcjach WordPress

#### Panel administracyjny
- **Rozszerzony panel "Narzędzia MCP"**
  - Filtrowanie narzędzi po typie (Read, Write, Create, Update, Delete, Action)
  - Wyszukiwanie narzędzi po nazwie i opisie
  - Dynamiczne liczniki dla każdego typu narzędzia
  - Przetłumaczone etykiety typów i statusów
  - Lepszy UX z `useMemo` dla wydajności

#### Dokumentacja
- Zaktualizowano `client-setup.md` z instrukcjami dla Cursor IDE
- Dodano szczegółowy przewodnik krok po kroku dla połączenia MCP
- Dodano przykłady konfiguracji dla wielu sklepów
- Dodano sekcję rozwiązywania problemów w języku polskim

#### Tłumaczenia narzędzi
- Dodano filtr `mcpfowo_tool_description` dla dynamicznego tłumaczenia opisów narzędzi
- Zastosowano filtr w metodzie `get_all_tools()`
- Wszystkie opisy narzędzi są teraz tłumaczone w czasie rzeczywistym

### 🐛 Naprawione

#### Błędy składniowe
- **Naprawiono 38 plików PHP z błędami składniowymi**
  - Brakujące apostrofy w kluczach tablic (`'name'`, `'description'`, `'type'`)
  - Nieprawidłowe apostrofy w środku nazw zmiennych (np. `'user'name'` → `'username'`)
  - Brakujące domeny tłumaczeniowe w funkcjach `__()`
  - Brakujące nawiasy zamykające w definicjach tablic
  - Duplikaty kluczy `'description'` w niektórych plikach

#### Tłumaczenia
- Naprawiono brakujące funkcje `load_plugin_textdomain()` i `wp_set_script_translations()`
- Usunięto duplikaty w plikach `.po` za pomocą `msguniq`
- Poprawnie wygenerowano pliki `.json` dla React (64 pliki)
- Dodano brakujące tłumaczenia dla wszystkich stringów w UI

#### Panel administracyjny
- Naprawiono wyświetlanie typów narzędzi (Read, Write, itp.)
- Naprawiono wyświetlanie statusów (Enabled, Disabled)
- Poprawiono renderowanie opisów narzędzi

### 🔄 Zmienione

#### Pliki PHP
- `includes/Admin/Settings.php` - Dodano AJAX handlers i filtry
- `includes/Core/WpMcp.php` - Dodano filtrowanie opisów narzędzi
- `mcp-for-woocommerce.php` - Dodano `load_plugin_textdomain()`

#### Komponenty React
- `src/settings/index.js` - Dodano zakładkę "Uprawnienia Użytkowników"
- `src/settings/ToolsTab.js` - Dodano filtrowanie i wyszukiwanie
- `src/settings/UserPermissionsTab.js` - NOWY komponent

#### Pliki językowe
- `languages/mcp-for-woocommerce-pl_PL.po` - Pełne tłumaczenie (1000+ stringów)
- `languages/mcp-for-woocommerce-pl_PL.mo` - Skompilowana wersja binarna
- `languages/*.json` - 64 pliki JSON dla React

### 📊 Statystyki

- **Pliki zmienione**: 45+
- **Linie kodu dodane**: ~3000+
- **Stringi przetłumaczone**: 1000+
- **Naprawione błędy składniowe**: 38 plików
- **Nowe komponenty React**: 1 (UserPermissionsTab)
- **Nowe AJAX endpoints**: 2 (get/save permissions)

### 🙏 Podziękowania

- [@iOSDevSK](https://github.com/iOSDevSK) za oryginalny plugin MCP for WooCommerce
- Społeczność WordPress i WooCommerce za wsparcie
- Automattic za pakiet `@automattic/mcp-wordpress-remote`
- Anthropic za protokół Model Context Protocol

---

## [1.0.0] - 2024-XX-XX (Oryginalny)

### Bazowa wersja autorstwa @iOSDevSK

- Podstawowa integracja MCP z WooCommerce
- Uwierzytelnianie JWT
- API REST dla narzędzi MCP
- Panel administracyjny React
- Dokumentacja w języku angielskim

Źródło: https://github.com/iOSDevSK/mcp-for-woocommerce

---

[Unreleased]: https://github.com/jeden-/wooquant/compare/v1.2.0...HEAD
[1.2.0]: https://github.com/jeden-/wooquant/releases/tag/v1.2.0
[1.0.0]: https://github.com/iOSDevSK/mcp-for-woocommerce/releases/tag/v1.0.0




