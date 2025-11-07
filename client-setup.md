# Przewodnik Konfiguracyjny MCP for WooCommerce

## 🚀 Przegląd

Wtyczka **MCP for WooCommerce** integruje Twój sklep WooCommerce z protokołem **Model Context Protocol (MCP)**, umożliwiając asystentom AI bezpieczny dostęp do danych i interakcję z Twoim sklepem.

### Kluczowe Funkcje (Wersja 1.1.9 - Zmodyfikowana)

- ✅ **Pełne Wsparcie dla Języka Polskiego**: Zarówno panel administracyjny, jak i wszystkie komunikaty są w pełni przetłumaczone.
- ✅ **Zaktualizowany Panel Administracyjny**: Nowoczesny interfejs oparty na React, umożliwiający pełne zarządzanie wtyczką.
- ✅ **Uwierzytelnianie JWT**: Bezpieczny dostęp do danych za pomocą tokenów.
- ✅ **Poprawiony System Testów**: Zapewniona stabilność i jakość kodu dzięki testom jednostkowym PHPUnit.
- ✅ **Dostęp Tylko do Odczytu**: Gwarancja bezpieczeństwa – AI nie może modyfikować danych w Twoim sklepie.

---

## ⚙️ Panel Administracyjny

Panel znajdziesz w menu WordPressa: `Ustawienia` → `MCP dla WooCommerce`.

### Zakładka: Ustawienia MCP

Główna sekcja konfiguracyjna.

- **Włącz funkcjonalność MCP**: Globalny przełącznik aktywujący lub dezaktywujący całą wtyczkę.
- **Wymagaj uwierzytelniania JWT**:
  - **Włączone (Zalecane)**: Wszystkie zapytania do MCP muszą zawierać ważny token JWT. Niezbędne do pełnej funkcjonalności i bezpieczeństwa.
  - **Wyłączone**: Dostęp do danych jest możliwy bez uwierzytelniania (tylko w trybie do odczytu).

### Zakładka: Uwierzytelnienie

Zarządzanie tokenami dostępowymi.

- **Generowanie Tokenów**: Stwórz nowe, bezpieczne tokeny JWT.
- **Ustawienia Ważności**: Określ, jak długo token ma być aktywny (od 1 godziny do opcji "nigdy nie wygasa").
- **Lista Aktywnych Tokenów**: Przeglądaj i unieważniaj aktywne tokeny.

> **Wskazówka Bezpieczeństwa**: Używaj tokenów o jak najkrótszym czasie życia. Unieważniaj nieużywane tokeny.

### Zakładka: Narzędzia MCP

Zarządzaj narzędziami, które udostępniasz asystentom AI. Możesz tu włączać i wyłączać poszczególne funkcje, takie jak wyszukiwanie produktów, sprawdzanie kategorii czy odczytywanie recenzji.

### Pozostałe Zakładki

- **Dokumentacja**: Ta strona, którą właśnie czytasz.
- **Resources**: Lista dostępnych zasobów systemowych.
- **Prompts**: Lista dostępnych podpowiedzi dla AI.

---

## 🔌 Konfiguracja Klientów MCP

Aby połączyć się z serwerem MCP Twojego sklepu, użyj poniższych konfiguracji. Pamiętaj, aby zastąpić `{{your-website.com}}` adresem Twojej strony (np. `http://wooquant.local`) oraz `your-jwt-token-here` wygenerowanym tokenem.

### Cursor IDE

Dodaj do pliku `.cursorrules` w głównym katalogu projektu lub w ustawieniach Cursor (`Settings` → `Cursor Settings` → `Features` → `MCP Servers`):

```json
{
	"mcpServers": {
		"wooquant-shop": {
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

**Dla wielu sklepów:**

```json
{
	"mcpServers": {
		"wooquant-local": {
			"command": "npx",
			"args": ["-y", "@automattic/mcp-wordpress-remote@latest"],
			"env": {
				"WP_API_URL": "http://wooquant.local",
				"JWT_TOKEN": "token-dla-wooquant"
			}
		},
		"sklep-produkcyjny": {
			"command": "npx",
			"args": ["-y", "@automattic/mcp-wordpress-remote@latest"],
			"env": {
				"WP_API_URL": "https://twojsklep.pl",
				"JWT_TOKEN": "token-dla-sklepu-produkcyjnego"
			}
		},
		"sklep-testowy": {
			"command": "npx",
			"args": ["-y", "@automattic/mcp-wordpress-remote@latest"],
			"env": {
				"WP_API_URL": "https://test.twojsklep.pl",
				"JWT_TOKEN": "token-dla-sklepu-testowego"
			}
		}
	}
}
```

### VS Code (Rozszerzenie MCP)

Dodaj w ustawieniach VS Code lub w pliku `.vscode/mcp.json`:

```json
{
	"mcpServers": {
		"wooquant-shop": {
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

> **Uwaga**: VS Code może wymagać zainstalowania rozszerzenia obsługującego MCP. Konfiguracja jest identyczna jak dla Cursor IDE.

### Claude Desktop

Dodaj do pliku `claude_desktop_config.json`:

```json
{
	"mcpServers": {
		"mcp-for-woocommerce": {
			"command": "npx",
			"args": [ "-y", "@automattic/mcp-wordpress-remote@latest" ],
			"env": {
				"WP_API_URL": "{{your-website.com}}",
				"JWT_TOKEN": "your-jwt-token-here"
			}
		}
	}
}
```
---

## 📝 Krok po kroku: Jak połączyć Cursor z WooCommerce

### Dla jednego sklepu:

1. **W WordPress:**
   - Przejdź do `Ustawienia` → `MCP dla WooCommerce`
   - Włącz "Funkcjonalność MCP"
   - Włącz "Wymagaj uwierzytelniania JWT"
   - Przejdź do zakładki "Uwierzytelnienie"
   - Kliknij "Generuj Token"
   - **Skopiuj wygenerowany token** (zachowaj go bezpiecznie!)

2. **W Cursor:**
   - Otwórz ustawienia: `Settings` → `Cursor Settings` → `Features` → `MCP Servers`
   - Lub stwórz plik `.cursorrules` w katalogu projektu
   - Dodaj konfigurację:
```json
{
     "mcpServers": {
       "moj-sklep": {
         "command": "npx",
         "args": ["-y", "@automattic/mcp-wordpress-remote@latest"],
         "env": {
           "WP_API_URL": "http://wooquant.local",
           "JWT_TOKEN": "TUTAJ-WKLEJ-SKOPIOWANY-TOKEN"
			}
		}
	}
}
```

3. **Zrestartuj Cursor**

4. **Gotowe!** Cursor może teraz komunikować się z Twoim sklepem WooCommerce.

### Dla wielu sklepów (Local, Testowy, Produkcyjny):

**TAK - musisz dodać każdy sklep osobno**, ale możesz to zrobić w jednej konfiguracji:

1. Wygeneruj osobny token JWT dla każdego sklepu
2. Dodaj wszystkie sklepy do jednej konfiguracji:

```json
{
	"mcpServers": {
    "wooquant-local": {
      "command": "npx",
      "args": ["-y", "@automattic/mcp-wordpress-remote@latest"],
      "env": {
        "WP_API_URL": "http://wooquant.local",
        "JWT_TOKEN": "token-z-local-site"
      }
    },
    "wooquant-test": {
      "command": "npx",
      "args": ["-y", "@automattic/mcp-wordpress-remote@latest"],
      "env": {
        "WP_API_URL": "https://test.wooquant.com",
        "JWT_TOKEN": "token-z-test-site"
      }
    },
    "wooquant-prod": {
      "command": "npx",
      "args": ["-y", "@automattic/mcp-wordpress-remote@latest"],
			"env": {
        "WP_API_URL": "https://wooquant.com",
        "JWT_TOKEN": "token-z-prod-site"
			}
		}
	}
}
```

Cursor automatycznie rozpozna wszystkie 3 serwery i będzie mógł się z nimi łączyć!

---
## 💡 Rozwiązywanie Problemów

- **Brak tłumaczeń lub stary wygląd panelu**: Wykonaj "twarde odświeżenie" przeglądarki (`Cmd/Ctrl + Shift + R`), aby wyczyścić pamięć podręczną.
- **Błędy uwierzytelniania**: Upewnij się, że token JWT jest poprawnie skopiowany i nie wygasł.
- **Problemy z połączeniem**: Sprawdź, czy Twoja strona WordPress jest dostępna i czy nie blokuje jej zapora sieciowa (firewall).

W razie dalszych problemów, zgłoś je w [repozytorium GitHub](https://github.com/iOSDevSK/mcp-for-woocommerce/issues).
