# WooQuant - MCP for WooCommerce (Extended Version)

[![WordPress Plugin Version](https://img.shields.io/badge/WordPress-6.0%2B-blue)](https://wordpress.org/)
[![WooCommerce Version](https://img.shields.io/badge/WooCommerce-8.0%2B-purple)](https://woocommerce.com/)
[![License](https://img.shields.io/badge/license-GPL--2.0%2B-green)](LICENSE)

> **🔔 Uwaga**: Ten projekt jest rozszerzoną wersją oryginalnego pluginu [MCP for WooCommerce](https://github.com/iOSDevSK/mcp-for-woocommerce) autorstwa [@iOSDevSK](https://github.com/iOSDevSK).

## 🎯 O projekcie

**WooQuant** to znacząco rozbudowana wersja pluginu MCP for WooCommerce, która dodaje:

- ✅ **Pełne wsparcie dla języka polskiego** - interfejs i wszystkie komunikaty w 100% przetłumaczone
- ✅ **Zarządzanie uprawnieniami użytkowników** - granularna kontrola dostępu na poziomie ról i indywidualnych użytkowników
- ✅ **Rozbudowany panel administracyjny** - nowoczesny interfejs React z wieloma nowymi funkcjami
- ✅ **Poprawiony system testów** - stabilne testy jednostkowe PHPUnit
- ✅ **Rozszerzone narzędzia MCP** - dodatkowe funkcje dla AI
- ✅ **Zaktualizowana dokumentacja** - pełna instrukcja w języku polskim

## 🏗️ Pochodzenie projektu

Ten projekt powstał jako rozszerzenie oryginalnego pluginu:

**Oryginalny projekt:**
- Nazwa: MCP for WooCommerce
- Autor: [@iOSDevSK](https://github.com/iOSDevSK)
- Repozytorium: https://github.com/iOSDevSK/mcp-for-woocommerce
- Wersja bazowa: 1.0.0
- Licencja: GPL-2.0+

**Nasza wersja (WooQuant):**
- Wersja: 1.2.0 (Extended)
- Główni kontrybutorzy: [@jeden-](https://github.com/jeden-)
- Repozytorium: https://github.com/jeden-/wooquant
- Licencja: GPL-2.0+ (zgodnie z oryginałem)

## 🆕 Najważniejsze zmiany vs. oryginał

### Dodane funkcjonalności:

1. **Internacjonalizacja (i18n)**
   - Pełne tłumaczenie na język polski
   - Pliki PO/MO i JSON dla React
   - Wsparcie dla wielojęzyczności

2. **System uprawnień**
   - Zarządzanie uprawnieniami per użytkownik
   - Kontrola dostępu per rola
   - AJAX API do zarządzania uprawnieniami

3. **Panel administracyjny**
   - Nowa zakładka "Uprawnienia Użytkowników"
   - Rozszerzony panel "Narzędzia MCP"
   - Filtrowanie i wyszukiwanie narzędzi

4. **Poprawki błędów**
   - Naprawiono 38+ plików z błędami składniowymi
   - Dodano brakujące funkcje tłumaczeniowe
   - Stabilizacja testów jednostkowych

### Zmienione pliki:

```
includes/Admin/Settings.php          - Dodano AJAX handlers dla uprawnień
includes/Core/WpMcp.php              - Dodano filtry tłumaczeń
src/settings/UserPermissionsTab.js   - NOWY: Panel uprawnień
src/settings/ToolsTab.js             - Rozszerzony: Filtrowanie i wyszukiwanie
languages/                           - NOWY: Pełne tłumaczenie PL
client-setup.md                      - Zaktualizowana dokumentacja
```

## 📦 Instalacja

### Wymagania:
- WordPress 6.0+
- WooCommerce 8.0+
- PHP 8.0+
- Node.js 18+ (do budowania frontendu)

### Kroki instalacji:

```bash
# 1. Sklonuj repozytorium
git clone https://github.com/jeden-/wooquant.git

# 2. Przejdź do katalogu
cd wooquant

# 3. Zainstaluj zależności PHP
composer install

# 4. Zainstaluj zależności Node.js
npm install

# 5. Zbuduj frontend
npm run build

# 6. Skopiuj do katalogu pluginów WordPress
cp -r . /path/to/wordpress/wp-content/plugins/mcp-for-woocommerce/

# 7. Aktywuj plugin w panelu WordPress
```

## 🚀 Konfiguracja

Szczegółowa dokumentacja konfiguracji znajduje się w pliku [client-setup.md](client-setup.md).

### Szybki start:

1. **Włącz plugin** w WordPress
2. Przejdź do `Ustawienia` → `MCP dla WooCommerce`
3. Włącz "Funkcjonalność MCP"
4. Włącz "Wymagaj uwierzytelniania JWT"
5. Wygeneruj token JWT w zakładce "Uwierzytelnienie"
6. Skonfiguruj Cursor/Claude Desktop/VS Code

### Przykładowa konfiguracja dla Cursor:

```json
{
  "mcpServers": {
    "wooquant": {
      "command": "npx",
      "args": ["-y", "@automattic/mcp-wordpress-remote@latest"],
      "env": {
        "WP_API_URL": "http://wooquant.local",
        "JWT_TOKEN": "your-jwt-token-here"
      }
    }
  }
}
```

## 🧪 Testowanie

```bash
# Testy jednostkowe PHP
composer test

# Sprawdzenie składni PHP
find includes -name "*.php" -exec php -l {} \;

# Budowanie frontendu
npm run build

# Generowanie tłumaczeń
npm run i18n
```

## 📖 Dokumentacja

- [Instrukcja konfiguracji klientów MCP](client-setup.md)
- [Przewodnik tłumaczenia](TRANSLATION-GUIDE.md)
- [Przewodnik testowania](tests/TESTING-GUIDE.md)
- [Changelog PL](languages/CHANGELOG-PL.md)

## 🤝 Wkład w projekt

Zachęcamy do współpracy! Jeśli chcesz dodać nowe funkcje lub poprawić istniejące:

1. Fork repozytorium
2. Stwórz branch z funkcją (`git checkout -b feature/AmazingFeature`)
3. Commit zmian (`git commit -m 'Add some AmazingFeature'`)
4. Push do brancha (`git push origin feature/AmazingFeature`)
5. Otwórz Pull Request

### Zasady wkładu:

- Kod musi przejść testy PHPUnit
- Nowe funkcje wymagają testów
- Zachowaj istniejący styl kodu
- Dodaj polskie tłumaczenia dla nowych stringów

## 📝 Licencja

Ten projekt jest licencjonowany na podstawie GPL-2.0+ - zgodnie z [oryginalnym projektem](https://github.com/iOSDevSK/mcp-for-woocommerce).

```
Copyright (C) 2024 - Original work by @iOSDevSK
Copyright (C) 2025 - Extended work by @jeden- and contributors

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
```

## 🙏 Podziękowania

- **[@iOSDevSK](https://github.com/iOSDevSK)** - za stworzenie oryginalnego pluginu MCP for WooCommerce
- Społeczność WordPress i WooCommerce
- Automattic - za pakiet [@automattic/mcp-wordpress-remote](https://www.npmjs.com/package/@automattic/mcp-wordpress-remote)
- Anthropic - za protokół Model Context Protocol (MCP)

## 📧 Kontakt

- GitHub Issues: https://github.com/jeden-/wooquant/issues
- Oryginalny projekt: https://github.com/iOSDevSK/mcp-for-woocommerce

## 🔗 Linki

- [WordPress Plugin Directory](https://wordpress.org/plugins/)
- [WooCommerce](https://woocommerce.com/)
- [Model Context Protocol](https://modelcontextprotocol.io/)
- [Cursor IDE](https://cursor.sh/)
- [Claude AI](https://claude.ai/)

---

**Zbudowane z ❤️ dla społeczności WordPress i AI**




