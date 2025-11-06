# 📤 Instrukcja publikacji na GitHub

## ✅ Checklist przed publikacją

- [ ] Sprawdź licencję oryginalnego projektu
- [ ] Przygotuj README z informacją o pochodzeniu
- [ ] Dodaj plik LICENSE
- [ ] Stwórz CHANGELOG
- [ ] Dodaj .gitignore
- [ ] Przetestuj lokalnie
- [ ] Zbuduj produkcyjną wersję

## 🔍 Krok 1: Sprawdź licencję oryginalnego projektu

Oryginalny projekt: https://github.com/iOSDevSK/mcp-for-woocommerce

Pluginy WordPress są zazwyczaj na licencji **GPL-2.0+**, która pozwala na:
- ✅ Używanie kodu
- ✅ Modyfikowanie
- ✅ Redystrybucję
- ✅ Komercyjne wykorzystanie

**Pod warunkiem:**
- ⚠️ Zachowania informacji o oryginalnym autorze
- ⚠️ Użycia tej samej licencji (GPL-2.0+)
- ⚠️ Udostępnienia kodu źródłowego

## 📝 Krok 2: Przygotuj dokumentację

### README.md
Powinien zawierać:
- Wyraźną informację o bazowaniu na oryginalnym projekcie
- Link do oryginalnego repozytorium
- Informację o autorze oryginału
- Listę Twoich zmian i ulepszeń
- Podziękowania dla oryginalnego autora

✅ **Stworzone**: `README-GIT.md` (zmień nazwę na `README.md`)

### LICENSE
Używaj tej samej licencji co oryginał:
- GPL-2.0+ dla pluginów WordPress
- Dodaj informację o oryginalnym autorze
- Dodaj informację o swoich zmianach

✅ **Stworzone**: `LICENSE`

### CHANGELOG.md
Dokumentuj wszystkie zmiany:
- Co dodałeś
- Co naprawiłeś
- Co zmieniłeś
- Link do oryginalnej wersji

✅ **Stworzone**: `CHANGELOG.md`

## 🧹 Krok 3: Przygotuj kod do publikacji

### A. Sprawdź składnię

```bash
cd "/Users/mariusz/Local Sites/wooquant/app/public/wp-content/plugins/mcp-for-woocommerce"

# Sprawdź wszystkie pliki PHP
find includes -name "*.php" -exec php -l {} \; | grep -i "error"

# Jeśli nic nie zwróci = wszystko OK
```

### B. Zbuduj produkcyjną wersję

```bash
# Zainstaluj zależności
npm install
composer install --no-dev

# Zbuduj frontend
npm run build

# Wygeneruj tłumaczenia
npm run i18n
```

### C. Dodaj .gitignore

✅ **Stworzone**: `.gitignore`

Upewnij się, że ignorujesz:
- `node_modules/`
- `vendor/`
- `.env`
- Pliki `.mo` (opcjonalnie)
- IDE configs

## 🚀 Krok 4: Inicjalizacja Git i publikacja

### Opcja A: Pierwsze wrzucenie (Twój przypadek)

```bash
# 1. Przejdź do katalogu pluginu
cd "/Users/mariusz/Local Sites/wooquant/app/public/wp-content/plugins/mcp-for-woocommerce"

# 2. Zmień nazwę README-GIT.md na README.md
mv README-GIT.md README.md

# 3. Inicjalizuj Git
git init

# 4. Dodaj remote (Twoje repozytorium)
git remote add origin https://github.com/jeden-/wooquant.git

# 5. Dodaj wszystkie pliki
git add .

# 6. Pierwszy commit
git commit -m "Initial commit: WooQuant v1.2.0 - Extended MCP for WooCommerce

Based on https://github.com/iOSDevSK/mcp-for-woocommerce v1.0.0
Original author: @iOSDevSK

Major additions:
- Full Polish language support (i18n)
- User permissions management system
- Extended admin panel with filtering
- 38+ PHP syntax fixes
- Updated documentation

See CHANGELOG.md for full list of changes."

# 7. Stwórz branch main (jeśli jeszcze nie istnieje)
git branch -M main

# 8. Wypchnij na GitHub
git push -u origin main
```

### Opcja B: Jeśli już istnieje lokalne repo

```bash
# Sprawdź status
git status

# Dodaj zmiany
git add .

# Commit
git commit -m "Your commit message"

# Push
git push origin main
```

## 🏷️ Krok 5: Stwórz tag dla wersji

```bash
# Stwórz tag dla wersji 1.2.0
git tag -a v1.2.0 -m "WooQuant v1.2.0 - Extended MCP for WooCommerce

First public release with:
- Full Polish language support
- User permissions management
- Extended admin panel
- 38+ PHP syntax fixes

Based on MCP for WooCommerce v1.0.0 by @iOSDevSK"

# Wypchnij tag na GitHub
git push origin v1.2.0
```

## 📋 Krok 6: Uzupełnij GitHub

### A. Edytuj opis repozytorium

Na stronie https://github.com/jeden-/wooquant kliknij "Edit" i dodaj:

**Description:**
```
Extended version of MCP for WooCommerce with Polish language support, user permissions, and enhanced admin panel. Based on @iOSDevSK's original work.
```

**Topics (Tags):**
- `wordpress`
- `woocommerce`
- `mcp`
- `model-context-protocol`
- `ai`
- `cursor`
- `claude`
- `php`
- `react`
- `i18n`
- `polish`

**Website:**
```
https://github.com/iOSDevSK/mcp-for-woocommerce
```

### B. Stwórz Release

1. Przejdź do zakładki "Releases"
2. Kliknij "Create a new release"
3. Wybierz tag `v1.2.0`
4. Tytuł: `WooQuant v1.2.0 - Extended MCP for WooCommerce`
5. Opis:

```markdown
## 🎉 First Public Release

Extended version of [MCP for WooCommerce](https://github.com/iOSDevSK/mcp-for-woocommerce) v1.0.0 by [@iOSDevSK](https://github.com/iOSDevSK).

### ✨ Major Additions

- ✅ **Full Polish language support** - 100% translated UI and messages
- ✅ **User permissions management** - Granular access control per role/user
- ✅ **Extended admin panel** - Filtering, searching, modern React UI
- ✅ **38+ PHP syntax fixes** - Stable, production-ready code
- ✅ **Updated documentation** - Full Polish setup guide

### 📦 Installation

See [README.md](https://github.com/jeden-/wooquant#installation) for installation instructions.

### 🙏 Credits

Original work: [@iOSDevSK](https://github.com/iOSDevSK)
Extended by: [@jeden-](https://github.com/jeden-)

### 📝 Full Changelog

See [CHANGELOG.md](https://github.com/jeden-/wooquant/blob/main/CHANGELOG.md)
```

## ⚖️ Krok 7: Etyka open source

### Dobre praktyki:

1. **Zawsze linkuj do oryginału**
   - W README
   - W opisie repozytorium
   - W release notes

2. **Informuj oryginalnego autora** (opcjonalnie, ale mile widziane)
   - Stwórz Issue w oryginalnym repo
   - Lub wyślij wiadomość
   - Treść: "Hi! I've created an extended version of your plugin with Polish language support and additional features. Would you like to check it out? [link]"

3. **Rozważ Pull Request do oryginału**
   - Jeśli Twoje zmiany mogą być przydatne dla wszystkich
   - Szczególnie poprawki błędów

4. **Zachowaj licencję**
   - Użyj tej samej (GPL-2.0+)
   - Dodaj informację o obu autorach

## 🔗 Krok 8: Promowanie projektu

### README w GitHub powinien zawierać:

- ✅ Badge'e (WordPress version, WooCommerce version, License)
- ✅ Wyraźne oznaczenie jako "Extended version"
- ✅ Link do oryginału
- ✅ Porównanie zmian (Added, Fixed, Changed)
- ✅ Instrukcje instalacji
- ✅ Dokumentację
- ✅ Podziękowania

### Opcjonalnie możesz:

- Stworzyć stronę GitHub Pages z dokumentacją
- Dodać screenshoty w README
- Nagrać demo video
- Napisać blog post o zmianach

## 📊 Podsumowanie

✅ **Stworzone pliki:**
- `README-GIT.md` → zmień na `README.md`
- `LICENSE`
- `CHANGELOG.md`
- `.gitignore`
- Ten przewodnik: `PUBLISHING-TO-GITHUB.md`

✅ **Gotowe do wykonania:**
1. Zmień nazwę `README-GIT.md` → `README.md`
2. Zainicjuj Git
3. Dodaj remote
4. Commit & push
5. Stwórz tag
6. Stwórz release na GitHub
7. (Opcjonalnie) Poinformuj oryginalnego autora

## 🎯 Przykładowe komendy - gotowe do skopiowania

```bash
# Przejdź do katalogu
cd "/Users/mariusz/Local Sites/wooquant/app/public/wp-content/plugins/mcp-for-woocommerce"

# Zmień nazwę README
mv README-GIT.md README.md

# Git setup
git init
git remote add origin https://github.com/jeden-/wooquant.git
git add .
git commit -m "Initial commit: WooQuant v1.2.0 - Extended MCP for WooCommerce"
git branch -M main
git push -u origin main

# Tag version
git tag -a v1.2.0 -m "WooQuant v1.2.0 - First public release"
git push origin v1.2.0

echo "✅ Gotowe! Sprawdź: https://github.com/jeden-/wooquant"
```

## ❓ FAQ

**Q: Czy mogę użyć kodu z GPL bez zgody autora?**
A: Tak, to jest właśnie cel GPL - wolność modyfikacji i dystrybucji.

**Q: Czy muszę informować autora o moich zmianach?**
A: Nie musisz, ale jest to dobra praktyka i mile widziane w społeczności.

**Q: Czy mogę zmienić nazwę pluginu?**
A: Tak, ale musisz jasno zaznaczyć, że bazujesz na oryginalnym projekcie.

**Q: Czy mogę zarabiać na zmodyfikowanym pluginie?**
A: Tak, GPL pozwala na komercyjne wykorzystanie, ale musisz udostępnić kod źródłowy.

**Q: Co jeśli autor ma problem z moją wersją?**
A: GPL chroni Twoje prawo do modyfikacji. Ale zachowuj profesjonalizm i szacunek.

---

**Powodzenia z publikacją!** 🚀




