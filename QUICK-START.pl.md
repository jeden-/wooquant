# Przewodnik Szybkiego Startu - Wtyczka WooQuant MCP

**Połącz swojego asystenta AI z WooCommerce w 5 minut!**

Ten przewodnik pom

oże Ci skonfigurować wtyczkę WooQuant MCP i połączyć ją z Claude Desktop lub Cursor IDE.

---

## Krok 1: Instalacja i aktywacja

1. Wgraj folder `mcp-for-woocommerce` do `/wp-content/plugins/`
2. Aktywuj **"WooQuant - MCP for WooCommerce"** w wtyczkach WordPress
3. Upewnij się, że **WooCommerce jest zainstalowany i aktywny**

---

## Krok 2: Konfiguracja wtyczki

1. Przejdź do **Panel WordPress → MCP dla WooCommerce**
2. Kliknij zakładkę **"Ustawienia"**
3. Włącz **"Włącz funkcjonalność MCP"**

### Wybierz tryb uwierzytelniania:

#### Opcja A: Uwierzytelnianie JWT (Zalecane dla produkcji)
- Zostaw "Włącz uwierzytelnianie JWT" jako WŁ
- Kliknij **"Generuj nowy token"**
- **Skopiuj i zapisz** wygenerowany token JWT (będzie potrzebny w Kroku 3)

#### Opcja B: Bez uwierzytelniania (Tylko dla rozwoju lokalnego)
- Wyłącz "Włącz uwierzytelnianie JWT"
- Zostanie wygenerowany lokalny plik proxy
- ⚠️ **UWAGA:** Używaj tego tylko na lokalnych/deweloperskich stronach!

4. Kliknij **"Zapisz ustawienia"**

---

## Krok 3: Połącz swojego klienta AI

### Dla Claude Desktop

1. Otwórz plik konfiguracyjny Claude Desktop:
   - **Mac:** `~/Library/Application Support/Claude/claude_desktop_config.json`
   - **Windows:** `%APPDATA%\Claude\claude_desktop_config.json`

2. Dodaj swoją stronę WooCommerce:

```json
{
  "mcpServers": {
    "woocommerce": {
      "url": "{{your-website.com}}/wp-json/mcpfowo/v1/mcp",
      "headers": {
        "Authorization": "Bearer TWOJ_TOKEN_JWT_TUTAJ"
      }
    }
  }
}
```

3. **Zamień:**
   - `{{your-website.com}}` na faktyczny adres URL twojej strony (np. `https://mojsklep.pl`)
   - `TWOJ_TOKEN_JWT_TUTAJ` na token wygenerowany w Kroku 2

4. **Zapisz plik** i **zrestartuj Claude Desktop**

5. Powinieneś zobaczyć "WooCommerce" w menu MCP (ikona 🔌)

### Dla Cursor IDE

1. Otwórz Ustawienia Cursor (Cmd+, lub Ctrl+,)
2. Przejdź do **Features → Model Context Protocol**
3. Kliknij **"Add MCP Server"**
4. Dodaj tę konfigurację:

```json
{
  "woocommerce-mojsklep": {
    "url": "{{your-website.com}}/wp-json/mcpfowo/v1/mcp",
    "headers": {
      "Authorization": "Bearer TWOJ_TOKEN_JWT_TUTAJ"
    }
  }
}
```

5. **Zamień** placeholdery jak powyżej
6. **Zapisz** i zrestartuj Cursor

---

## Krok 4: Przetestuj połączenie

### W Claude Desktop:
Spróbuj zapytać:
```
Pokaż mi moje 5 najnowszych produktów ze sklepu WooCommerce
```

### W Cursor IDE:
Spróbuj zapytać:
```
Wyszukaj produkty na wyprzedaży w moim sklepie
```

Jeśli AI odpowie z rzeczywistymi produktami, **wszystko działa!** 🎉

---

## Krok 5: Włącz operacje zapisu (Opcjonalnie)

Domyślnie wtyczka działa w trybie **tylko do odczytu** dla bezpieczeństwa. Jeśli chcesz, aby AI mogło tworzyć lub modyfikować dane:

1. Przejdź do **MCP dla WooCommerce → Ustawienia**
2. Włącz **"Włącz operacje zapisu"**
3. Kliknij **"Zapisz ustawienia"**
4. Strona odświeży się aby załadować narzędzia zapisu

⚠️ **Ważne:** Operacje zapisu pozwalają AI:
- Tworzyć, aktualizować lub usuwać produkty
- Modyfikować zamówienia i klientów
- Wgrywać pliki
- Zmieniać ustawienia

**Włączaj to tylko jeśli:**
- Ufasz swojemu asystentowi AI
- Rozumiesz ryzyko
- Masz aktualne kopie zapasowe
- Przetestowałeś w środowisku staging

---

## Konfiguracja wielu stron

Chcesz połączyć wiele sklepów WooCommerce? Łatwe!

### W Claude Desktop:
```json
{
  "mcpServers": {
    "woocommerce-sklep1": {
      "url": "https://sklep1.pl/wp-json/mcpfowo/v1/mcp",
      "headers": {
        "Authorization": "Bearer TOKEN_ZE_SKLEPU1"
      }
    },
    "woocommerce-sklep2": {
      "url": "https://sklep2.pl/wp-json/mcpfowo/v1/mcp",
      "headers": {
        "Authorization": "Bearer TOKEN_ZE_SKLEPU2"
      }
    }
  }
}
```

**Wskazówka:** Używaj opisowych nazw jak `woocommerce-elektronika` lub `woocommerce-moda` aby łatwo identyfikować sklepy.

---

## Co możesz teraz zrobić?

### Wypróbuj te komendy:

**Zarządzanie produktami:**
```
Znajdź wszystkie produkty z niskim stanem magazynowym
Pokaż mi najlepiej sprzedające się produkty tego miesiąca
Wyszukaj niebieskie koszulki poniżej 100 zł
```

**Analiza zamówień:**
```
Pokaż oczekujące zamówienia z ostatnich 7 dni
Analizuj wyniki sprzedaży za ten miesiąc
Jakie są najlepiej sprzedające się produkty?
```

**Obsługa klienta:**
```
Sprawdź status zamówienia #12345
Znajdź produkty w kategorii "Elektronika"
Jakie są nasze aktualne strefy wysyłki?
```

**Tworzenie treści:**
```
Stwórz wpis na bloga o naszej nowej linii produktów
Analizuj SEO dla moich stron produktowych
Wgraj i zoptymalizuj zdjęcia produktów
```

**Raporty biznesowe:**
```
Wygeneruj podsumowanie wykonawcze za ostatni miesiąc
Pokaż magazyn wymagający uzupełnienia
Segmentuj klientów według zachowań zakupowych
```

💡 **Pro Tip:** AI rozumie naturalny język, więc po prostu pytaj o to, czego potrzebujesz!

---

## Rozwiązywanie problemów

### "Nie można połączyć z serwerem MCP"
- ✅ Sprawdź czy MCP jest włączony w ustawieniach wtyczki
- ✅ Zweryfikuj poprawność adresu URL strony (dołącz `https://` lub `http://`)
- ✅ Upewnij się, że token JWT jest skopiowany poprawnie (bez dodatkowych spacji)
- ✅ Sprawdź czy WooCommerce jest aktywny

### "Uwierzytelnianie nie powiodło się"
- ✅ Wygeneruj nowy token JWT w ustawieniach wtyczki
- ✅ Zaktualizuj token w konfiguracji klienta AI
- ✅ Zrestartuj swojego klienta AI

### "Narzędzia się nie ładują"
- ✅ Odśwież panel WordPress (Cmd+Shift+R)
- ✅ Sprawdź błędy PHP w logu debug WordPress
- ✅ Tymczasowo wyłącz inne wtyczki aby sprawdzić konflikty

### Potrzebujesz więcej pomocy?
- 📖 Pełna dokumentacja: [README.pl.md](README.pl.md)
- 🔧 Referencyjna lista narzędzi: [TOOLS-LIST.pl.md](TOOLS-LIST.pl.md)
- 🤖 Przewodnik po promptach: [PROMPTS-LIST.pl.md](PROMPTS-LIST.pl.md)
- 🐛 Zgłoś problem: [GitHub Issues](https://github.com/jeden-/wooquant/issues)

---

## Najlepsze praktyki bezpieczeństwa

1. **Nigdy nie udostępniaj swoich tokenów JWT** - Są jak hasła!
2. **Używaj HTTPS** - Szczególnie ważne dla stron produkcyjnych
3. **Regularne kopie zapasowe** - Przed włączeniem operacji zapisu
4. **Testuj w staging** - Wypróbuj destrukcyjne operacje bezpiecznie najpierw
5. **Ogranicz dostęp użytkowników** - Użyj zakładki "Uprawnienia użytkowników" aby kontrolować kto może używać MCP
6. **Monitoruj aktywność** - Regularnie sprawdzaj swój sklep pod kątem nieoczekiwanych zmian

---

## Kolejne kroki

- ✅ Eksploruj zakładkę **"Narzędzia"** aby zobaczyć wszystkie 99 dostępnych funkcji
- ✅ Sprawdź zakładkę **"Prompty"** dla gotowych przepływów pracy AI
- ✅ Zobacz zakładkę **"Zasoby"** dla baz wiedzy dostępnych dla AI
- ✅ Skonfiguruj **"Uprawnienia użytkowników"** aby kontrolować dostęp
- ✅ Przeczytaj pełny **[PROMPTS-LIST.pl.md](PROMPTS-LIST.pl.md)** dla zaawansowanych przykładów użycia

---

**Miłego zarządzania WooCommerce z pomocą AI!** 🚀

*Pytania? Problemy? Chcesz współtworzyć? Odwiedź [github.com/jeden-/wooquant](https://github.com/jeden-/wooquant)*


