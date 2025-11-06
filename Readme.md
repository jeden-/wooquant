# WooQuant (Rozszerzona wersja mcp-for-woocommerce)

Jest to rozbudowana i dostosowana wersja wtyczki **[mcp-for-woocommerce](https://github.com/iOSDevSK/mcp-for-woocommerce)** autorstwa **[iOSDevSK](https://github.com/iOSDevSK)**.

Nasza wersja (`wooquant`) wprowadza szereg kluczowych ulepszeń i modyfikacji w stosunku do oryginału, koncentrując się na internacjonalizacji, użyteczności panelu administracyjnego oraz dodaniu nowych funkcjonalności.

## Główne Zmiany i Ulepszenia

-   **Pełna Polska Lokalizacja (i18n):**
    -   Przetłumaczono cały interfejs panelu administracyjnego (React) na język polski.
    -   Dodano tłumaczenia dla wszystkich dynamicznie generowanych narzędzi i opisów.
    -   Zaimplementowano poprawne ładowanie plików językowych (`.po`, `.mo`, `.json`) zarówno po stronie PHP, jak i JavaScript.

-   **Rozbudowany Panel Administracyjny:**
    -   Dodano nową zakładkę "Uprawnienia Użytkowników" do zarządzania dostępem do MCP dla poszczególnych użytkowników i ról WordPressa.
    -   Wprowadzono filtrowanie oraz wyszukiwanie na liście dostępnych narzędzi, co znacząco ułatwia zarządzanie przy dużej ich liczbie.
    -   Poprawiono interfejs użytkownika, aby był bardziej intuicyjny.

-   **Poprawki Błędów i Stabilności:**
    -   Rozwiązano liczne błędy składni w plikach PHP definiujących narzędzia.
    -   Zapewniono poprawne budowanie zasobów frontendowych (`npm run build`) poprzez naprawę błędów w kodzie React.
    -   Wdrożono mechanizm "cache busting" dla skryptów JS, aby przeglądarka zawsze ładowała najnowszą wersję panelu po aktualizacji.

-   **Dokumentacja i Publikacja:**
    -   Stworzono szczegółową dokumentację dotyczącą podłączania wtyczki do IDE (Cursor) oraz konfiguracji dla wielu stron.
    -   Przygotowano kompletny zestaw plików (`LICENSE`, `CHANGELOG.md`, `.gitignore`) oraz instrukcje do publikacji projektu na GitHubie.

## Licencja

Podobnie jak oryginał, ten projekt jest udostępniany na licencji **GPL-2.0-or-later**. Zgodnie z jej warunkami, zachowujemy informację o oryginalnym autorze i udostępniamy nasze modyfikacje na tej samej licencji.

## Podziękowania

Specjalne podziękowania dla **iOSDevSK** za stworzenie potężnego narzędzia dla społeczności WooCommerce i udostępnienie go na licencji open-source, co umożliwiło nam dalszy rozwój i dostosowanie wtyczki do naszych potrzeb.
