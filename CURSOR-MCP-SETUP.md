# Konfiguracja MCP dla Cursor IDE

## Szybka konfiguracja

### Opcja 1: Przez interfejs Cursor (Zalecane)

1. Otwórz Cursor IDE
2. Przejdź do: **Settings** (Cmd+, lub Ctrl+,)
3. Przejdź do: **Features → Model Context Protocol**
4. Kliknij **"Add MCP Server"** lub **"Edit MCP Settings"**
5. Dodaj następującą konfigurację:

```json
{
  "mcpServers": {
    "wooquant-local": {
      "command": "npx",
      "args": ["-y", "@automattic/mcp-wordpress-remote@latest"],
      "env": {
        "WP_API_URL": "http://wooquant.local",
        "JWT_TOKEN": "YOUR_JWT_TOKEN_HERE"
      }
    }
  }
}
```

**Gdzie znaleźć JWT_TOKEN:**
1. Przejdź do: WordPress Admin → WooQuant → Authentication Tokens
2. Kliknij "Generate New Token"
3. Wybierz "Never expire" dla łatwiejszej konfiguracji
4. Skopiuj wygenerowany token
5. Wklej go zamiast `YOUR_JWT_TOKEN_HERE`

6. **Zapisz** i **zrestartuj Cursor**

### Opcja 2: Ręczna edycja pliku konfiguracyjnego

#### macOS:
```bash
# Edytuj plik konfiguracyjny Cursor
nano ~/.cursor/mcp.json
```

Lub:
```bash
nano ~/Library/Application\ Support/Cursor/User/globalStorage/rooveterinaryinc.roo-cline/settings/cline_mcp_settings.json
```

#### Windows:
```
%APPDATA%\Cursor\User\globalStorage\rooveterinaryinc.roo-cline\settings\cline_mcp_settings.json
```

#### Linux:
```
~/.config/Cursor/User/globalStorage/rooveterinaryinc.roo-cline/settings/cline_mcp_settings.json
```

## Szczegóły konfiguracji

- **Nazwa serwera:** `wooquant-local` (możesz zmienić na dowolną nazwę)
- **Command:** `npx` - uruchamia adapter MCP dla WordPress
- **Args:** `["-y", "@automattic/mcp-wordpress-remote@latest"]` - używa najnowszej wersji adaptera
- **WP_API_URL:** URL Twojego WordPressa (np. `http://wooquant.local`)
- **JWT_TOKEN:** Token wygenerowany w WordPress Admin → WooQuant → Authentication Tokens

### Dlaczego używamy npx zamiast bezpośredniego URL?

Pakiet `@automattic/mcp-wordpress-remote` działa jako adapter/proxy między Cursor MCP a WordPress REST API, zapewniając:
- ✅ Stabilne połączenie i poprawną komunikację
- ✅ Automatyczne wykrywanie dostępnych narzędzi
- ✅ Kompatybilność z różnymi wersjami Cursor
- ✅ Lepszą obsługę błędów i reconnection

## Weryfikacja połączenia

Po skonfigurowaniu, sprawdź czy połączenie działa:

1. Otwórz Cursor
2. W konsoli AI spróbuj zapytać:
   ```
   Pokaż mi produkty z mojego sklepu WooCommerce
   ```
3. Jeśli AI odpowiada z rzeczywistymi produktami, połączenie działa poprawnie!

## Rozwiązywanie problemów

### "Nie można połączyć się z serwerem"
- ✅ Sprawdź czy WordPress jest uruchomiony i dostępny pod `http://wooquant.local`
- ✅ Sprawdź czy MCP jest włączone w WordPress Admin → WooQuant → Settings
- ✅ Sprawdź czy token JWT nie wygasł (tokeny mają czas wygaśnięcia)

### "Uwierzytelnianie nie powiodło się"
- ✅ Wygeneruj nowy token JWT w WordPress Admin → WooQuant → Authentication Tokens
- ✅ Skopiuj token dokładnie (bez dodatkowych spacji)
- ✅ Upewnij się, że token jest w formacie: `Bearer TOKEN`

### "Endpoint nie odpowiada"
- ✅ Sprawdź czy permalinks WordPress są włączone
- ✅ Sprawdź czy REST API WordPress działa: `http://wooquant.local/wp-json/`
- ✅ Sprawdź czy endpoint jest dostępny: `http://wooquant.local/wp-json/wp/v2/wpmcp/streamable`

## Ważne uwagi

⚠️ **Token JWT:** 
- Tokeny mogą mieć czas wygaśnięcia lub być ustawione na "Never expire"
- **Zalecamy "Never expire"** dla środowiska lokalnego (Local by Flywheel)
- W środowisku produkcyjnym używaj tokenów z wygaśnięciem dla bezpieczeństwa
- Jeśli token wygaśnie: wygeneruj nowy w WordPress Admin → WooQuant → Authentication Tokens

⚠️ **Bezpieczeństwo:** 
- Nigdy nie udostępniaj swojego tokenu JWT publicznie
- Tokeny zapewniają pełen dostęp do Twojego sklepu WooCommerce
- Dla środowiska produkcyjnego używaj tokenów z ograniczonym czasem życia
- Regularnie sprawdzaj i usuwaj nieużywane tokeny

## Przykładowe zapytania do AI

Po skonfigurowaniu możesz używać takich zapytań:

- "Pokaż mi wszystkie produkty w promocji"
- "Znajdź produkty z niskim stanem magazynowym"
- "Pokaż najnowsze zamówienia"
- "Przeanalizuj sprzedaż w tym miesiącu"
- "Wyszukaj produkty w kategorii Elektronika"






