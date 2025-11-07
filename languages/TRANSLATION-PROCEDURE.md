# Procedura tłumaczenia WooQuant - Kompletny Przewodnik

## ✅ Czy edycja pliku `.po` rozwiązuje problemy?

**TAK**, ale trzeba wykonać pełną procedurę składającą się z 4 kroków:

1. ✅ **Edycja pliku `.po`** - dodanie tłumaczeń
2. ✅ **Kompilacja pliku `.mo`** - dla tłumaczeń PHP (backend)
3. ✅ **Aktualizacja pliku `.json`** - dla tłumaczeń JavaScript (frontend)
4. ✅ **Kopiowanie JSON z hashem wersji** - WordPress wymaga tego dla tłumaczeń JS

## 📋 Pełna procedura tłumaczenia

### Metoda 1: Automatyczna (zalecana)

```bash
cd wp-content/plugins/mcp-for-woocommerce/languages
./update-translations.sh
```

Skrypt automatycznie:
- Kompiluje plik `.mo`
- Generuje plik `.json`
- Kopiuje JSON z hashem wersji

### Metoda 2: Ręczna

#### Krok 1: Edycja pliku `.po`

Edytuj `mcp-for-woocommerce-pl_PL.po` i znajdź wpisy bez tłumaczeń:

```po
msgid "English text"
msgstr ""  # ← Dodaj tutaj tłumaczenie
```

Dodaj tłumaczenie:

```po
msgid "English text"
msgstr "Polski tekst"
```

#### Krok 2: Kompilacja pliku `.mo`

```bash
cd wp-content/plugins/mcp-for-woocommerce/languages
msgfmt mcp-for-woocommerce-pl_PL.po -o mcp-for-woocommerce-pl_PL.mo
```

**Dlaczego?** WordPress używa plików `.mo` do tłumaczeń PHP (backend).

#### Krok 3: Generowanie pliku `.json`

**Opcja A: Z WP CLI (zalecane)**
```bash
wp i18n make-json mcp-for-woocommerce-pl_PL.po --no-purge
```

**Opcja B: Ręcznie (jeśli brak WP CLI)**
Zaktualizuj plik `mcp-for-woocommerce-pl_PL.json` dodając wpisy w formacie:
```json
{
  "domain": "messages",
  "locale_data": {
    "messages": {
      "English text": [null, "Polski tekst"]
    }
  }
}
```

**Dlaczego?** WordPress używa plików `.json` do tłumaczeń JavaScript/React (frontend).

#### Krok 4: Kopiowanie JSON z hashem wersji

```bash
# Sprawdź hash w pliku asset
cat build/index.asset.php | grep version

# Skopiuj JSON z hashem (przykład)
cp mcp-for-woocommerce-pl_PL.json mcp-for-woocommerce-pl_PL-e38ec5a49f598f8c2e6f.json
```

**Dlaczego?** WordPress szuka pliku JSON z hashem wersji w nazwie dla tłumaczeń JavaScript.

## 🔍 Sprawdzanie stanu tłumaczeń

### Ile tłumaczeń brakuje?

```bash
cd wp-content/plugins/mcp-for-woocommerce/languages
grep -c '^msgstr ""$' mcp-for-woocommerce-pl_PL.po
```

### Znajdź wszystkie brakujące tłumaczenia

```bash
grep -B 1 '^msgstr ""$' mcp-for-woocommerce-pl_PL.po | grep '^msgid'
```

## 📁 Struktura plików tłumaczeń

```
languages/
├── mcp-for-woocommerce.pot          # Szablon tłumaczeń (nie edytować)
├── mcp-for-woocommerce-pl_PL.po     # Plik tłumaczeń polskich (EDYTOWAĆ)
├── mcp-for-woocommerce-pl_PL.mo    # Skompilowany plik (GENEROWAĆ)
├── mcp-for-woocommerce-pl_PL.json  # JSON dla JavaScript (GENEROWAĆ)
└── mcp-for-woocommerce-pl_PL-*.json # JSON z hashem wersji (KOPIOWAĆ)
```

## ⚠️ Ważne uwagi

1. **Zawsze kompiluj `.mo` po edycji `.po`** - WordPress nie używa `.po` bezpośrednio
2. **Zawsze aktualizuj JSON** - Tłumaczenia JavaScript wymagają JSON
3. **Zawsze kopiuj JSON z hashem** - WordPress szuka pliku z hashem wersji
4. **Odśwież przeglądarkę** - Użyj Cmd+Shift+R (Mac) lub Ctrl+Shift+R (Windows/Linux)

## 🐛 Rozwiązywanie problemów

### Tłumaczenia nie działają po edycji `.po`

1. Sprawdź czy skompilowałeś `.mo`: `ls -lah *.mo`
2. Sprawdź czy zaktualizowałeś JSON: `ls -lah *.json`
3. Sprawdź czy skopiowałeś JSON z hashem: `ls -lah *-e38ec5a49f598f8c2e6f.json`
4. Wyczyść cache przeglądarki: Cmd+Shift+R / Ctrl+Shift+R

### Brakuje narzędzia `msgfmt`

```bash
# macOS
brew install gettext

# Linux
sudo apt-get install gettext
```

### Brakuje WP CLI

Użyj metody ręcznej (Krok 3, Opcja B) lub zainstaluj WP CLI:
```bash
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
sudo mv wp-cli.phar /usr/local/bin/wp
```

## 📊 Obecny stan tłumaczeń

- **Wszystkie wpisy:** ~2400+
- **Przetłumaczone:** ~2184
- **Brakuje:** 216

## 🎯 Najczęstsze miejsca bez tłumaczeń

1. Opisy narzędzi (Tools) - ✅ **NAPRAWIONE**
2. Opisy zasobów (Resources) - ✅ **NAPRAWIONE**
3. Opisy promptów (Prompts) - ✅ **NAPRAWIONE**
4. Opisy parametrów narzędzi - ⚠️ **Częściowo**
5. Komunikaty błędów - ⚠️ **Częściowo**

