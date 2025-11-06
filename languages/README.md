# Tłumaczenia MCP for WooCommerce

## Dostępne języki

- **Polski (pl_PL)** - Pełne tłumaczenie ✅
- **Angielski (en_US)** - Domyślny język

## Pliki tłumaczeń

### Polski
- `mcp-for-woocommerce-pl_PL.po` - Plik tłumaczenia (edytowalny)
- `mcp-for-woocommerce-pl_PL.mo` - Skompilowany plik tłumaczenia (używany przez WordPress)

### Szablon
- `woo-mcp.pot` - Szablon tłumaczenia (stary)

## Jak używać tłumaczeń

WordPress automatycznie wykryje i użyje odpowiedniego języka na podstawie ustawień witryny.

### Zmiana języka witryny

1. Przejdź do **Ustawienia → Ogólne**
2. Wybierz **Polski** z listy "Język witryny"
3. Kliknij **Zapisz zmiany**

Plugin automatycznie przełączy się na polski.

## Aktualizacja tłumaczeń

Jeśli dodano nowe teksty do pluginu:

### 1. Zaktualizuj plik .po

Edytuj plik `mcp-for-woocommerce-pl_PL.po` i dodaj nowe tłumaczenia.

### 2. Skompiluj plik .mo

```bash
cd wp-content/plugins/mcp-for-woocommerce/languages
msgfmt -o mcp-for-woocommerce-pl_PL.mo mcp-for-woocommerce-pl_PL.po
```

### 3. Wyczyść cache

W WordPressie może być konieczne wyczyszczenie cache, aby zobaczyć zmiany.

## Tworzenie nowego tłumaczenia

Aby dodać nowe tłumaczenie (np. niemieckie):

### 1. Skopiuj plik

```bash
cp mcp-for-woocommerce-pl_PL.po mcp-for-woocommerce-de_DE.po
```

### 2. Edytuj nagłówek

Zmień informacje w nagłówku pliku:
- `Language: de_DE`
- `Language-Team: Deutsch`

### 3. Przetłumacz teksty

Edytuj wartości `msgstr` na odpowiednie tłumaczenia.

### 4. Skompiluj

```bash
msgfmt -o mcp-for-woocommerce-de_DE.mo mcp-for-woocommerce-de_DE.po
```

## Struktura pliku .po

```po
msgid "Original English text"
msgstr "Przetłumaczony tekst"
```

### Przykłady

```po
msgid "Save Settings"
msgstr "Zapisz ustawienia"

# Z placeholderami
msgid "Tool %1$s has been %2$s."
msgstr "Narzędzie %1$s zostało %2$s."

# Liczba mnoga
msgid "1 product"
msgid_plural "%d products"
msgstr[0] "%d produkt"
msgstr[1] "%d produkty"
msgstr[2] "%d produktów"
```

## Testowanie tłumaczeń

### 1. Sprawdź składnię

```bash
msgfmt -c -v -o /dev/null mcp-for-woocommerce-pl_PL.po
```

### 2. Włącz język w WordPress

```php
// W wp-config.php
define('WPLANG', 'pl_PL');
```

### 3. Sprawdź interfejs

Przejdź do ustawień pluginu i sprawdź, czy teksty są po polsku.

## Licencje tłumaczeń

Wszystkie tłumaczenia są dystrybuowane na tej samej licencji co plugin (GPL-2.0-or-later).

## Współtworzenie

Jeśli znalazłeś błąd w tłumaczeniu lub chcesz je ulepszyć:

1. Otwórz issue na GitHub
2. Lub wyślij Pull Request z poprawkami

## Narzędzia do tłumaczenia

### Edytory .po

- **Poedit** - https://poedit.net/ (Windows, Mac, Linux)
- **Loco Translate** - Plugin WordPress do edycji tłumaczeń online
- **GlotPress** - System tłumaczeń WordPress.org

### Generowanie plików .pot

```bash
# Z WP-CLI
wp i18n make-pot . languages/mcp-for-woocommerce.pot

# Lub ręcznie
find . -name "*.php" -exec xgettext --from-code=UTF-8 -o languages/mcp-for-woocommerce.pot {} +
```

## Statystyki

**Polskie tłumaczenie:**
- 150+ przetłumaczonych tekstów
- Pokrycie: 100% interfejsu administracyjnego
- Status: Kompletne ✅

## Kontakt

Pytania dotyczące tłumaczeń? Otwórz issue na:
https://github.com/iOSDevSK/mcp-for-woocommerce/issues

