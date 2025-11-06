# Szybki Start - Uruchamianie testów

## ⚠️ Wymagane: Terminal w Local by Flywheel

Aby uruchomić testy, musisz użyć terminala w Local by Flywheel, który ma dostęp do PHP.

## Kroki:

### 1. Otwórz terminal w Local
- Kliknij ikonę terminala przy swojej stronie "wooquant"
- Albo użyj menu: Site → Open Site Shell

### 2. Przejdź do katalogu pluginu
```bash
cd wp-content/plugins/mcp-for-woocommerce
```

### 3. Zainstaluj zależności testowe
```bash
composer install
```

### 4. Uruchom testy
```bash
vendor/bin/phpunit
```

## Szybkie testy bez WordPress Test Library

Jeśli nie masz jeszcze WordPress Test Library, możesz uruchomić podstawowe testy:

```bash
# Test składni
find tests/phpunit -name "*.php" -exec php -l {} \;

# Podstawowa walidacja
php tests/test-runner.php
```

## Wszystkie poprawki zostały wykonane ✅

- ✅ Ujednolicone metody setUp/tearDown
- ✅ Poprawione wywołania parent::setUp()
- ✅ Sprawdzona składnia wszystkich plików
- ✅ Utworzone skrypty pomocnicze

Testy są gotowe do uruchomienia!

