# Raport Testów Pluginu MCP for WooCommerce

## Data testowania
2025-01-XX

## Struktura testów

### Pliki testowe

#### Testy transportu
- `McpStdioTransportTest.php` - Testy transportu STDIO (`/wp/v2/wpmcp`)
- `McpStreamableTransportTest.php` - Testy transportu Streamable (`/wp/v2/wpmcp/streamable`)
- `McpTransportIntegrationTest.php` - Testy integracyjne obu transportów
- `McpTransportTestBase.php` - Klasa bazowa dla testów transportu

#### Testy autentykacji
- `JwtAuthTest.php` - Testy autentykacji JWT (620+ linii kodu)

#### Testy narzędzi WooCommerce
- `Tools/McpWooProductsTest.php` - Testy narzędzi produktów WooCommerce
- `Tools/McpWooOrdersTest.php` - Testy narzędzi zamówień WooCommerce
- `Tools/McpWooReportsTest.php` - Testy raportów WooCommerce
- `Tools/McpWooShippingTest.php` - Testy opcji dostawy
- `Tools/McpWooAttributesTest.php` - Testy atrybutów produktów
- `Tools/McpWooIntelligentSearchTest.php` - Testy inteligentnego wyszukiwania

#### Testy narzędzi WordPress
- `Tools/McpPostsToolsTest.php` - Testy narzędzi postów
- `Tools/McpPagesToolsTest.php` - Testy narzędzi stron
- `Tools/McpMediaToolsTest.php` - Testy narzędzi mediów
- `Tools/McpUsersToolsTest.php` - Testy narzędzi użytkowników
- `Tools/McpSettingsToolsTest.php` - Testy narzędzi ustawień
- `Tools/McpCustomPostTypeTest.php` - Testy niestandardowych typów postów
- `Tools/McpSiteInfoTest.php` - Testy informacji o stronie

#### Inne testy
- `McpToolsRegistrationTest.php` - Testy rejestracji narzędzi MCP
- `WpFeaturesApiAdapterTest.php` - Testy adaptera Features API

## Konfiguracja

### Plik konfiguracyjny PHPUnit
Utworzono plik `phpunit.xml` z następującą konfiguracją:
- Bootstrap: `tests/bootstrap.php`
- Katalog testów: `tests/phpunit`
- Katalog źródłowy: `includes`
- Środowisko: WP_TESTS_DIR, WP_TESTS_PHPUNIT_POLYFILLS_PATH

### Bootstrap testów
Plik `tests/bootstrap.php`:
- Wymaga zmiennej środowiskowej `WP_TESTS_DIR` (domyślnie `/tmp/wordpress-tests-lib`)
- Automatycznie aktywuje WooCommerce jeśli jest dostępne
- Konfiguruje ustawienia MCP przed testami
- Ładuje plugin ręcznie przez `_manually_load_plugin()`

### Composer
- Zależności testowe: PHPUnit ^9.0, yoast/phpunit-polyfills ^1.0
- Namespace: `McpForWoo\` dla kodu produkcyjnego
- Namespace testów: `McpForWoo\Tests\`

## Problemy znalezione

### 1. Niezgodność nazw metod setUp/tearDown
- Niektóre klasy używają `setUp()`/`tearDown()` (JwtAuthTest, McpStdioTransportTest, McpTransportTestBase)
- Inne używają `set_up()`/`tear_down()` (większość testów w katalogu Tools/)
- **Status**: Wymaga ujednolicenia - PHPUnit 9+ preferuje `setUp()`/`tearDown()`, ale Yoast Polyfills wspierają `set_up()`/`tear_down()`

### 2. Poprawione namespace w composer.json
- **Było**: `Automattic\WordpressMcp\` (błędne)
- **Jest**: `McpForWoo\` (poprawne)
- Status: Poprawione

### 3. Poprawiona ścieżka w bootstrap.php
- **Było**: `wordpress-mcp.php` (nieistniejący plik)
- **Jest**: `mcp-for-woocommerce.php` (poprawny plik główny)
- Status: Poprawione

## Pokrycie testami

### Testowane komponenty

#### Transport
- ✅ STDIO transport endpoint
- ✅ Streamable transport endpoint
- ✅ Autentykacja JWT
- ✅ Autentykacja Application Password (tylko STDIO)
- ✅ Walidacja nagłówków HTTP
- ✅ Format odpowiedzi (WordPress vs JSON-RPC 2.0)
- ✅ Obsługa błędów
- ✅ Batch requests (Streamable)
- ✅ Notifications (Streamable)

#### Autentykacja JWT
- ✅ Generowanie tokenów
- ✅ Walidacja tokenów
- ✅ Wygaśnięcie tokenów
- ✅ Revocation tokenów
- ✅ Lista tokenów
- ✅ Różne formaty nagłówków Authorization

#### Narzędzia WooCommerce
- ✅ Produkty (wc_products_search, wc_products_list)
- ✅ Zamówienia (wc_orders_search, wc_orders_list)
- ✅ Raporty (wc_reports_sales, wc_reports_top_sellers)
- ✅ Opcje dostawy
- ✅ Atrybuty produktów
- ✅ Inteligentne wyszukiwanie

#### Narzędzia WordPress
- ✅ Posty
- ✅ Strony
- ✅ Media
- ✅ Użytkownicy
- ✅ Ustawienia
- ✅ Niestandardowe typy postów
- ✅ Informacje o stronie

## Wymagania do uruchomienia testów

1. **Środowisko WordPress**
   - WordPress test library (instalacja przez `bin/install-wp-tests.sh`)
   - Zmienna środowiskowa `WP_TESTS_DIR`

2. **Zależności**
   - PHP 8.0+
   - Composer (zainstalowane zależności: `composer install`)
   - WooCommerce (opcjonalnie, dla testów WooCommerce)

3. **Konfiguracja**
   - Plik `phpunit.xml` (utworzony)
   - Poprawiony `composer.json` (namespace)
   - Poprawiony `tests/bootstrap.php` (ścieżka do głównego pliku)

## Uruchomienie testów

### Wszystkie testy
```bash
vendor/bin/phpunit
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

## Narzędzie pomocnicze

Utworzono prosty skrypt testowy `tests/test-runner.php` do podstawowej walidacji bez pełnego środowiska PHPUnit:
- Sprawdza istnienie plików
- Weryfikuje autoloader
- Sprawdza ładowanie klas
- Weryfikuje stałe pluginu
- Sprawdza składnię PHP

Uruchomienie:
```bash
php tests/test-runner.php
```

## Rekomendacje

1. **Ujednolicenie nazw metod setUp/tearDown**
   - Zdecydować się na jedną konwencję
   - Zaktualizować wszystkie klasy testowe

2. **Dodanie testów integracyjnych**
   - Testy pełnego przepływu MCP
   - Testy end-to-end z rzeczywistym klientem MCP

3. **Testy wydajnościowe**
   - Testy z dużymi ilościami danych
   - Testy obciążeniowe

4. **Testy bezpieczeństwa**
   - Testy SQL injection
   - Testy XSS
   - Testy CSRF

5. **Dokumentacja**
   - Dodać przykłady użycia
   - Dodać scenariusze testowe

## Statystyki

- Liczba klas testowych: ~20
- Szacowana liczba metod testowych: 100+
- Pokrycie: Transport, Autentykacja, Narzędzia WooCommerce, Narzędzia WordPress
- Framework: PHPUnit 9+ z Yoast PHPUnit Polyfills

## Status

✅ **Konfiguracja testów poprawiona i gotowa do uruchomienia**

Wszystkie wymagane pliki konfiguracyjne zostały utworzone/poprawione:
- `phpunit.xml` - utworzony
- `composer.json` - poprawiony namespace
- `tests/bootstrap.php` - poprawiona ścieżka do głównego pliku

Testy wymagają środowiska WordPress test library do pełnego uruchomienia.

