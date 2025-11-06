#!/bin/bash
# Skrypt do uruchamiania testów w środowisku Local by Flywheel
# Używa PHP z Local przez docker lub bezpośrednio

set -e

PLUGIN_DIR="$(cd "$(dirname "$0")/.." && pwd)"
cd "$PLUGIN_DIR"

echo "=== MCP for WooCommerce - Uruchamianie testów (Local by Flywheel) ==="
echo ""

# Spróbuj znaleźć PHP w różnych lokalizacjach Local
PHP_BIN=""
LOCAL_PHP_PATHS=(
    "/Applications/Local.app/Contents/Resources/extraResources/bin/php"
    "$HOME/.local/share/local-litespeed/bin/php"
    "/usr/local/bin/php"
    "/usr/bin/php"
)

for php_path in "${LOCAL_PHP_PATHS[@]}"; do
    if [ -f "$php_path" ] && [ -x "$php_path" ]; then
        PHP_BIN="$php_path"
        echo "Znaleziono PHP: $PHP_BIN"
        break
    fi
done

# Jeśli nie znaleziono, spróbuj przez docker (Local może używać Dockera)
if [ -z "$PHP_BIN" ]; then
    echo "Szukanie PHP przez Docker..."
    if command -v docker &> /dev/null; then
        # Sprawdź czy jest kontener Local
        CONTAINER=$(docker ps --format '{{.Names}}' | grep -i "wooquant\|local" | head -1)
        if [ -n "$CONTAINER" ]; then
            echo "Znaleziono kontener: $CONTAINER"
            echo "Uruchamianie testów w kontenerze..."
            docker exec "$CONTAINER" bash -c "cd /app/public/wp-content/plugins/mcp-for-woocommerce && vendor/bin/phpunit"
            exit $?
        fi
    fi
fi

# Jeśli nadal nie ma PHP, użyj domyślnego
if [ -z "$PHP_BIN" ]; then
    PHP_BIN="php"
    echo "Używam domyślnego PHP z PATH"
fi

# Sprawdź czy PHPUnit jest dostępne
if [ ! -f "vendor/bin/phpunit" ]; then
    echo "BŁĄD: PHPUnit nie jest zainstalowany."
    echo "Instalowanie zależności..."
    $PHP_BIN "$(which composer)" install --no-interaction || {
        echo "Nie można zainstalować zależności. Uruchom ręcznie:"
        echo "  composer install"
        exit 1
    }
fi

# Sprawdź czy jest dostępne środowisko testowe WordPress
if [ -z "$WP_TESTS_DIR" ]; then
    echo "UWAGA: Zmienna środowiskowa WP_TESTS_DIR nie jest ustawiona."
    echo "Ustaw ją na ścieżkę do WordPress test library."
    echo ""
    echo "Dla Local by Flywheel, możesz użyć:"
    echo "  export WP_TESTS_DIR=/tmp/wordpress-tests-lib"
    echo ""
    read -p "Czy chcesz kontynuować z domyślną lokalizacją? (y/n) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
    export WP_TESTS_DIR="/tmp/wordpress-tests-lib"
fi

# Sprawdź czy plik bootstrap istnieje
if [ ! -f "$WP_TESTS_DIR/includes/functions.php" ]; then
    echo "UWAGA: Nie znaleziono WordPress test library w: $WP_TESTS_DIR"
    echo "Testy mogą nie działać poprawnie bez WordPress test library."
    echo ""
    read -p "Czy chcesz kontynuować mimo to? (y/n) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

echo "Używam PHP: $PHP_BIN"
echo "Używam WP_TESTS_DIR: $WP_TESTS_DIR"
echo ""

# Uruchom testy
echo "=== Uruchamianie testów ==="
echo ""

$PHP_BIN vendor/bin/phpunit "$@"

