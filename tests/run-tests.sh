#!/bin/bash
# Skrypt do uruchamiania testów pluginu MCP for WooCommerce

set -e

PLUGIN_DIR="$(cd "$(dirname "$0")/.." && pwd)"
cd "$PLUGIN_DIR"

echo "=== MCP for WooCommerce - Uruchamianie testów ==="
echo ""

# Sprawdź czy PHPUnit jest dostępne
if [ ! -f "vendor/bin/phpunit" ]; then
    echo "BŁĄD: PHPUnit nie jest zainstalowany."
    echo "Uruchom: composer install"
    exit 1
fi

# Sprawdź czy jest dostępne środowisko testowe WordPress
if [ -z "$WP_TESTS_DIR" ]; then
    echo "UWAGA: Zmienna środowiskowa WP_TESTS_DIR nie jest ustawiona."
    echo "Ustaw ją na ścieżkę do WordPress test library, np:"
    echo "  export WP_TESTS_DIR=/path/to/wordpress-tests-lib"
    echo ""
    echo "Albo użyj domyślnej lokalizacji: /tmp/wordpress-tests-lib"
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
    echo "BŁĄD: Nie znaleziono WordPress test library w: $WP_TESTS_DIR"
    echo "Zainstaluj WordPress test library używając:"
    echo "  bin/install-wp-tests.sh"
    exit 1
fi

echo "Używam WP_TESTS_DIR: $WP_TESTS_DIR"
echo ""

# Uruchom testy
echo "=== Uruchamianie wszystkich testów ==="
echo ""

vendor/bin/phpunit "$@"

