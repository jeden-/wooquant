# Przewodnik testowania pluginu MCP for WooCommerce

## Wymagania

1. **PHP 8.0+**
2. **Composer** - do instalacji zależności
3. **WordPress Test Library** - środowisko testowe WordPress
4. **PHPUnit 9.0+** - framework testowy

## Instalacja środowiska testowego

### 1. Zainstaluj zależności Composer

```bash
cd wp-content/plugins/mcp-for-woocommerce
composer install
```

### 2. Zainstaluj WordPress Test Library

Skopiuj i dostosuj skrypt `bin/install-wp-tests.sh` z WordPress core, lub użyj:

```bash
# Utwórz katalog dla testów
mkdir -p /tmp/wordpress-tests-lib

# Pobierz WordPress test library
# (możesz użyć skryptu z WordPress core)
```

Lub ustaw zmienną środowiskową:

```bash
export WP_TESTS_DIR=/path/to/wordpress-tests-lib
```

## Uruchamianie testów

### Wszystkie testy

```bash
vendor/bin/phpunit
```

Lub użyj skryptu pomocniczego:

```bash
./tests/run-tests.sh
```

### Konkretna klasa testowa

```bash
vendor/bin/phpunit tests/phpunit/JwtAuthTest.php
```

### Konkretna metoda testowa

```bash
vendor/bin/phpunit tests/phpunit/JwtAuthTest.php --filter test_generate_token_with_valid_credentials
```

### Testy transportu

```bash
vendor/bin/phpunit tests/phpunit/ --filter="Mcp.*Transport"
```

### Testy WooCommerce

```bash
vendor/bin/phpunit tests/phpunit/Tools/ --filter="McpWoo"
```

## Struktura testów

### Testy transportu
- `McpStdioTransportTest.php` - Testy transportu STDIO
- `McpStreamableTransportTest.php` - Testy transportu Streamable
- `McpTransportIntegrationTest.php` - Testy integracyjne
- `McpTransportTestBase.php` - Klasa bazowa

### Testy autentykacji
- `JwtAuthTest.php` - Testy JWT (24+ metod testowych)

### Testy narzędzi WooCommerce
- `Tools/McpWooProductsTest.php` - Produkty
- `Tools/McpWooOrdersTest.php` - Zamówienia
- `Tools/McpWooReportsTest.php` - Raporty
- `Tools/McpWooShippingTest.php` - Dostawa
- `Tools/McpWooAttributesTest.php` - Atrybuty
- `Tools/McpWooIntelligentSearchTest.php` - Inteligentne wyszukiwanie

### Testy narzędzi WordPress
- `Tools/McpPostsToolsTest.php` - Posty
- `Tools/McpPagesToolsTest.php` - Strony
- `Tools/McpMediaToolsTest.php` - Media
- `Tools/McpUsersToolsTest.php` - Użytkownicy
- `Tools/McpSettingsToolsTest.php` - Ustawienia
- `Tools/McpCustomPostTypeTest.php` - Niestandardowe typy postów
- `Tools/McpSiteInfoTest.php` - Informacje o stronie

## Poprawki wykonane

### ✅ Ujednolicone nazwy metod setUp/tearDown
- Wszystkie klasy testowe używają teraz `setUp()` i `tearDown()` (standard PHPUnit)
- Poprawione wywołania `parent::setUp()` i `parent::tearDown()`

### ✅ Poprawione namespace w composer.json
- `Automattic\WordpressMcp\` → `McpForWoo\`

### ✅ Poprawiona ścieżka w bootstrap.php
- `wordpress-mcp.php` → `mcp-for-woocommerce.php`

### ✅ Utworzony phpunit.xml
- Konfiguracja PHPUnit z bootstrap i katalogami testów

## Rozwiązywanie problemów

### Błąd: "Could not find wp-load.php"
- Upewnij się, że WordPress jest zainstalowany
- Sprawdź ścieżkę w `tests/bootstrap.php`

### Błąd: "PHPUnit not found"
- Uruchom `composer install`
- Sprawdź czy `vendor/bin/phpunit` istnieje

### Błąd: "WordPress test library not found"
- Ustaw zmienną `WP_TESTS_DIR`
- Zainstaluj WordPress test library

### Błąd autoryzacji w testach
- Testy wymagają aktywnego WooCommerce
- Niektóre testy wymagają użytkownika administratora

## Pokrycie testami

- ✅ Transport STDIO i Streamable
- ✅ Autentykacja JWT
- ✅ Narzędzia WooCommerce (produkty, zamówienia, raporty, dostawa)
- ✅ Narzędzia WordPress (posty, strony, media, użytkownicy)
- ✅ Rejestracja narzędzi MCP
- ✅ Obsługa błędów
- ✅ Walidacja uprawnień

## Statystyki

- **20 klas testowych**
- **173+ metod testowych**
- **Pokrycie**: Transport, Autentykacja, Narzędzia WooCommerce, Narzędzia WordPress

## Następne kroki

1. Uruchom pełną suitę testów
2. Sprawdź pokrycie kodem (code coverage)
3. Dodaj testy dla nowych funkcji
4. Zintegruj z CI/CD

