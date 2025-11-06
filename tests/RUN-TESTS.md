# Jak uruchomić testy w Local by Flywheel

## Metoda 1: Przez terminal Local (zalecane)

1. Otwórz terminal w Local by Flywheel (ikona terminala przy stronie)
2. Przejdź do katalogu pluginu:
   ```bash
   cd wp-content/plugins/mcp-for-woocommerce
   ```
3. Zainstaluj zależności (jeśli jeszcze nie):
   ```bash
   composer install
   ```
4. Uruchom testy:
   ```bash
   vendor/bin/phpunit
   ```

## Metoda 2: Przez skrypt pomocniczy

```bash
cd wp-content/plugins/mcp-for-woocommerce
./tests/run-tests-local.sh
```

## Metoda 3: Przez Docker (jeśli Local używa Dockera)

```bash
# Znajdź kontener Local
docker ps | grep wooquant

# Uruchom testy w kontenerze
docker exec -it <container-name> bash -c "cd /app/public/wp-content/plugins/mcp-for-woocommerce && vendor/bin/phpunit"
```

## Metoda 4: Przez WP-CLI (jeśli dostępne)

```bash
wp test-phpunit --plugin=mcp-for-woocommerce
```

## Konfiguracja WordPress Test Library

Jeśli nie masz jeszcze WordPress Test Library:

1. Pobierz skrypt instalacyjny z WordPress:
   ```bash
   curl -O https://raw.githubusercontent.com/WordPress/wordpress-develop/trunk/tests/phpunit/includes/install-wp-tests.sh
   ```

2. Uruchom instalację:
   ```bash
   bash install-wp-tests.sh db_name db_user db_pass localhost latest
   ```

   Lub ustaw zmienną środowiskową:
   ```bash
   export WP_TESTS_DIR=/tmp/wordpress-tests-lib
   ```

## Uruchamianie konkretnych testów

### Wszystkie testy
```bash
vendor/bin/phpunit
```

### Konkretna klasa
```bash
vendor/bin/phpunit tests/phpunit/JwtAuthTest.php
```

### Konkretna metoda
```bash
vendor/bin/phpunit tests/phpunit/JwtAuthTest.php --filter test_generate_token_with_valid_credentials
```

### Testy WooCommerce
```bash
vendor/bin/phpunit tests/phpunit/Tools/ --filter="McpWoo"
```

### Testy transportu
```bash
vendor/bin/phpunit tests/phpunit/ --filter="Mcp.*Transport"
```

## Rozwiązywanie problemów

### Błąd: "PHP not found"
- Użyj terminala w Local by Flywheel (ma dostęp do PHP)
- Lub zidentyfikuj ścieżkę do PHP w Local i użyj pełnej ścieżki

### Błąd: "PHPUnit not found"
- Uruchom `composer install`
- Sprawdź czy `vendor/bin/phpunit` istnieje

### Błąd: "WordPress test library not found"
- Zainstaluj WordPress test library (patrz wyżej)
- Ustaw zmienną `WP_TESTS_DIR`

### Błąd: "Class not found"
- Uruchom `composer dump-autoload`
- Sprawdź czy namespace w `composer.json` jest poprawny

## Alternatywa: Uruchomienie bez WordPress Test Library

Możesz uruchomić podstawowe testy składni bez pełnego środowiska:

```bash
# Test składni wszystkich plików
find tests/phpunit -name "*.php" -exec php -l {} \;

# Test podstawowej struktury
php tests/test-runner.php
```

