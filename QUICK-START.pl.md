# Przewodnik Szybkiego Startu - Wtyczka WooQuant MCP

**Połącz swojego asystenta AI z WooCommerce w 5 minut!**

Ten przewodnik pomoże Ci skonfigurować wtyczkę WooQuant MCP i połączyć ją z Claude Desktop lub Cursor IDE.

---

## Krok 1: Instalacja i Aktywacja

1. Prześlij folder `mcp-for-woocommerce` do `/wp-content/plugins/`
2. Aktywuj **"WooQuant - MCP for WooCommerce"** w wtyczkach WordPress
3. Upewnij się, że **WooCommerce jest zainstalowany i aktywny**

---

## Krok 2: Konfiguracja Wtyczki

1. Przejdź do **WordPress Admin → MCP for WooCommerce**
2. Kliknij zakładkę **"Ustawienia"**
3. Przełącz **"Włącz funkcjonalność MCP"** na ON

### Wybierz Tryb Uwierzytelniania:

#### Opcja A: Uwierzytelnianie JWT (Zalecane dla Produkcji)
- Pozostaw "Włącz uwierzytelnianie JWT" włączone
- Kliknij **"Wygeneruj nowy token"**
- **Skopiuj i zapisz** wygenerowany token JWT (będziesz go potrzebować w Kroku 3)

#### Opcja B: Brak Uwierzytelniania (Tylko dla Lokalnego Rozwoju)
- Przełącz "Włącz uwierzytelnianie JWT" na OFF
- Zostanie wygenerowany lokalny plik proxy
- ⚠️ **OSTRZEŻENIE:** Używaj tego tylko na lokalnych/stronach deweloperskich!

4. Kliknij **"Zapisz ustawienia"**

---

## Krok 3: Połącz Swojego Klienta AI

### Dla Claude Desktop

1. Otwórz plik konfiguracyjny Claude Desktop:
   - **Mac:** `~/Library/Application Support/Claude/claude_desktop_config.json`
   - **Windows:** `%APPDATA%\Claude\claude_desktop_config.json`

2. Dodaj swoją witrynę WooCommerce:

```json
{
  "mcpServers": {
    "woocommerce": {
      "url": "{{twoja-witryna.com}}/wp-json/wp/v2/wpmcp/streamable",
      "headers": {
        "Authorization": "Bearer TWOJ_TOKEN_JWT_TUTAJ"
      }
    }
  }
}
```

3. **Zastąp:**
   - `{{twoja-witryna.com}}` rzeczywistym adresem URL Twojej witryny (np. `https://mojsklep.com`)
   - `TWOJ_TOKEN_JWT_TUTAJ` tokenem wygenerowanym w Kroku 2

4. **Zapisz plik** i **uruchom ponownie Claude Desktop**

5. Powinieneś zobaczyć "WooCommerce" w menu MCP (ikona 🔌)

### Dla Cursor IDE

1. Otwórz Ustawienia Cursor (Cmd+, lub Ctrl+,)
2. Przejdź do **Features → Model Context Protocol**
3. Kliknij **"Add MCP Server"**
4. Dodaj tę konfigurację:

```json
{
  "woocommerce-mojsklep": {
    "url": "{{twoja-witryna.com}}/wp-json/wp/v2/wpmcp/streamable",
    "headers": {
      "Authorization": "Bearer TWOJ_TOKEN_JWT_TUTAJ"
    }
  }
}
```

5. **Zastąp** symbole zastępcze jak powyżej
6. **Zapisz** i uruchom ponownie Cursor

---

## Krok 4: Przetestuj Połączenie

### W Claude Desktop:
Spróbuj zapytać:
```
Pokaż mi moje 5 najnowszych produktów z mojego sklepu WooCommerce
```

### W Cursor IDE:
Spróbuj zapytać:
```
Wyszukaj produkty w promocji w moim sklepie
```

Jeśli AI odpowiada z Twoimi rzeczywistymi produktami, **wszystko gotowe!** 🎉

---

## Krok 5: Włącz Operacje Zapisu (Opcjonalne)

Domyślnie wtyczka jest **tylko do odczytu** dla bezpieczeństwa. Jeśli chcesz, aby AI tworzyło lub modyfikowało dane:

1. Przejdź do **MCP for WooCommerce → Ustawienia**
2. Przełącz **"Włącz operacje zapisu"** na ON
3. Kliknij **"Zapisz ustawienia"**
4. Strona odświeży się, aby załadować narzędzia zapisu

⚠️ **Ważne:** Operacje zapisu pozwalają AI na:
- Tworzenie, aktualizację lub usuwanie produktów
- Modyfikację zamówień i klientów
- Przesyłanie plików
- Zmianę ustawień

**Włącz to tylko jeśli:**
- Ufasz swojemu asystentowi AI
- Rozumiesz ryzyko
- Masz aktualne kopie zapasowe
- Najpierw przetestowałeś w środowisku testowym

---

## Konfiguracja Wiele Witryn

Chcesz połączyć wiele witryn WooCommerce? Łatwe!

### W Claude Desktop:
```json
{
  "mcpServers": {
    "woocommerce-sklep1": {
      "url": "https://sklep1.com/wp-json/wp/v2/wpmcp/streamable",
      "headers": {
        "Authorization": "Bearer TOKEN_ZE_SKLEPU1"
      }
    },
    "woocommerce-sklep2": {
      "url": "https://sklep2.com/wp-json/wp/v2/wpmcp/streamable",
      "headers": {
        "Authorization": "Bearer TOKEN_ZE_SKLEPU2"
      }
    }
  }
}
```

**Wskazówka:** Używaj opisowych nazw jak `woocommerce-sklep-elektroniczny` lub `woocommerce-boutique-modowa`, aby łatwo identyfikować sklepy.

---

## Co Możesz Teraz Zrobić?

### Wypróbuj Te Polecenia:

**Zarządzanie Produktami:**
```
Znajdź wszystkie produkty z niskim stanem magazynowym
Pokaż mi najlepiej sprzedające się produkty w tym miesiącu
Wyszukaj niebieskie koszulki poniżej 30 zł
```

**Analiza Zamówień:**
```
Pokaż oczekujące zamówienia z ostatnich 7 dni
Przeanalizuj wyniki sprzedaży w tym miesiącu
Jakie są najlepiej sprzedające się produkty?
```

**Wsparcie Klienta:**
```
Sprawdź status zamówienia #12345
Znajdź produkty w kategorii "Elektronika"
Jakie są nasze obecne strefy wysyłki?
```

**Tworzenie Treści:**
```
Utwórz wpis na blogu o naszej nowej linii produktów
Przeanalizuj SEO dla moich stron produktów
Prześlij i zoptymalizuj obrazy produktów
```

**Raporty Biznesowe:**
```
Wygeneruj podsumowanie wykonawcze za ostatni miesiąc
Pokaż zapasy wymagające uzupełnienia
Segmentuj klientów według zachowań zakupowych
```

💡 **Pro Tip:** AI rozumie język naturalny, więc po prostu zapytaj o to, czego potrzebujesz!

---

## Rozwiązywanie Problemów

### "Nie można połączyć się z serwerem MCP"
- ✅ Sprawdź, czy MCP jest włączone w ustawieniach wtyczki
- ✅ Zweryfikuj, czy adres URL Twojej witryny jest poprawny (dołącz `https://` lub `http://`)
- ✅ Upewnij się, że token JWT jest poprawnie skopiowany (bez dodatkowych spacji)
- ✅ Sprawdź, czy WooCommerce jest aktywny

### "Uwierzytelnianie nie powiodło się"
- ✅ Wygeneruj nowy token JWT w ustawieniach wtyczki
- ✅ Zaktualizuj token w konfiguracji klienta AI
- ✅ Uruchom ponownie klienta AI

### "Narzędzia się nie ładują"
- ✅ Odśwież panel administracyjny WordPress (Cmd+Shift+R)
- ✅ Sprawdź błędy PHP w dzienniku debugowania WordPress
- ✅ Tymczasowo wyłącz inne wtyczki, aby sprawdzić konflikty

### Potrzebujesz Więcej Pomocy?
- 📖 Zobacz pełną dokumentację: [README.md](README.md)
- 🔧 Referencja narzędzi: [TOOLS-LIST.md](TOOLS-LIST.md)
- 🤖 Przewodnik promptów: [PROMPTS-LIST.md](PROMPTS-LIST.md)
- 🐛 Zgłoś problemy: [GitHub Issues](https://github.com/jeden-/wooquant/issues)

---

## Najlepsze Praktyki Bezpieczeństwa

1. **Nigdy nie udostępniaj swoich tokenów JWT** - Są jak hasła!
2. **Używaj HTTPS** - Szczególnie ważne dla witryn produkcyjnych
3. **Regularne kopie zapasowe** - Przed włączeniem operacji zapisu
4. **Testuj w środowisku testowym** - Najpierw bezpiecznie wypróbuj operacje niszczące
5. **Ogranicz dostęp użytkowników** - Użyj zakładki "Uprawnienia Użytkowników", aby kontrolować, kto może używać MCP
6. **Monitoruj aktywność** - Regularnie sprawdzaj swój sklep pod kątem nieoczekiwanych zmian

---

## Następne Kroki

- ✅ Poznaj zakładkę **"Narzędzia"**, aby zobaczyć wszystkie 99 dostępnych funkcji
- ✅ Sprawdź zakładkę **"Prompty"** dla gotowych przepływów pracy AI
- ✅ Zobacz zakładkę **"Zasoby"** dla baz wiedzy, do których AI może uzyskać dostęp
- ✅ Skonfiguruj **"Uprawnienia Użytkowników"**, aby kontrolować dostęp
- ✅ Przeczytaj pełny **[PROMPTS-LIST.md](PROMPTS-LIST.md)** dla zaawansowanych przykładów użycia

---

**Szczęśliwego zarządzania WooCommerce z AI!** 🚀

*Pytania? Problemy? Wkład? Odwiedź [github.com/jeden-/wooquant](https://github.com/jeden-/wooquant)*
