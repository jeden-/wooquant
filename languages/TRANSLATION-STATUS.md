# Status tłumaczeń - MCP for WooCommerce

## Ostatnia aktualizacja: 2025-01-04

## Dostępne języki

| Język | Kod | Status | Komunikaty | Kompletność | Ostatnia aktualizacja |
|-------|-----|--------|------------|-------------|----------------------|
| Polski | pl_PL | ✅ Aktywny | 128/128 | 100% | 2025-01-04 |
| Angielski | en_US | ✅ Domyślny | - | 100% | - |

## Szczegóły: Polski (pl_PL)

### Pokrycie tłumaczenia

| Kategoria | Komunikaty | Status |
|-----------|------------|--------|
| Interfejs administracyjny | 45 | ✅ 100% |
| Uwierzytelnienie JWT | 12 | ✅ 100% |
| Narzędzia WooCommerce | 18 | ✅ 100% |
| Narzędzia WordPress | 15 | ✅ 100% |
| Komunikaty błędów | 15 | ✅ 100% |
| Ogólne (przyciski, akcje) | 23 | ✅ 100% |
| **Suma** | **128** | ✅ **100%** |

### Jakość tłumaczenia

✅ Wszystkie teksty przetłumaczone  
✅ Spójność terminologii  
✅ Poprawna gramatyka i ortografia  
✅ Naturalne brzmienie po polsku  
✅ Zachowane formaty (placeholdery %s, %d)  
✅ Zachowane znaki formatowania

### Testy

✅ Kompilacja pliku .po do .mo  
✅ Weryfikacja składni gettext  
✅ Test ładowania w WordPress  
✅ Brak konfliktów z innymi pluginami  

### Pliki

- **Źródłowy:** `mcp-for-woocommerce-pl_PL.po` (12 KB)
- **Skompilowany:** `mcp-for-woocommerce-pl_PL.mo` (10 KB)
- **Dokumentacja:** `README.md`, `TRANSLATION-GUIDE.md`

## Jak dodać nowe tłumaczenie

Chcesz dodać język (np. niemiecki, francuski)?

### 1. Skopiuj szablon

```bash
cd languages
cp mcp-for-woocommerce-pl_PL.po mcp-for-woocommerce-LOCALE.po
# Np.: mcp-for-woocommerce-de_DE.po dla niemieckiego
```

### 2. Edytuj nagłówek

Zmień w pliku:
```po
"Language: de_DE\n"
"Language-Team: Deutsch\n"
```

### 3. Przetłumacz

Zamień wszystkie `msgstr ""` na odpowiednie tłumaczenia.

### 4. Skompiluj

```bash
msgfmt -o mcp-for-woocommerce-de_DE.mo mcp-for-woocommerce-de_DE.po
```

### 5. Testuj

Zmień język WordPress na nowy język i sprawdź tłumaczenia.

### 6. Zgłoś

Wyślij Pull Request na GitHub!

## Narzędzia do tłumaczenia

### Zalecane edytory

- **Poedit** (https://poedit.net/) - Profesjonalny edytor .po
- **Loco Translate** - Plugin WordPress
- **GlotPress** - System WordPress.org

### Walidacja

```bash
# Sprawdź składnię
msgfmt -c -v mcp-for-woocommerce-LOCALE.po

# Zobacz statystyki
msgfmt --statistics mcp-for-woocommerce-LOCALE.po
```

## Współtworzenie

### Jak pomóc?

1. **Popraw istniejące tłumaczenie**
   - Znajdź błąd lub nieścisłość
   - Otwórz issue z opisem
   - Lub wyślij Pull Request z poprawką

2. **Dodaj nowy język**
   - Postępuj zgodnie z instrukcją powyżej
   - Wyślij Pull Request
   - Zostań maintainerem języka!

3. **Aktualizuj przy nowych wersjach**
   - Plugin się rozwija
   - Nowe funkcje = nowe teksty
   - Pomóż utrzymać tłumaczenia aktualne

### Gdzie zgłaszać?

- **GitHub Issues:** https://github.com/iOSDevSK/mcp-for-woocommerce/issues
- **Pull Requests:** https://github.com/iOSDevSK/mcp-for-woocommerce/pulls

## Roadmap tłumaczeń

### W trakcie
- ✅ Polski (pl_PL) - Kompletne

### Planowane
- ⏳ Niemiecki (de_DE)
- ⏳ Francuski (fr_FR)
- ⏳ Hiszpański (es_ES)
- ⏳ Włoski (it_IT)

### Chcesz dodać swój język?
Skontaktuj się przez GitHub Issues!

## Kontakt

**Pytania dotyczące tłumaczeń?**
- GitHub: https://github.com/iOSDevSK/mcp-for-woocommerce
- Issues: https://github.com/iOSDevSK/mcp-for-woocommerce/issues

---

Ostatnia aktualizacja: 2025-01-04  
Plugin Version: 1.1.9  
Translation Version: 1.0

