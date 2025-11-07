# Instrukcja: Przygotowanie wtyczki do instalacji

## ❌ NIE kopiuj bezpośrednio folderu!

**Nigdy nie kopiuj** całego folderu `/wp-content/plugins/mcp-for-woocommerce` do innego sklepu!

Zawiera on:
- Pliki deweloperskie (testy, node_modules)
- Źródła niekompilowane
- Pliki konfiguracyjne Git
- Setki MB niepotrzebnych danych

## ✅ Prawidłowy sposób

### Metoda 1: Automatyczny build (Zalecana)

```bash
cd "/Users/mariusz/Local Sites/wooquant/app/public/wp-content/plugins/mcp-for-woocommerce"

# Uruchom pełny build
./build-release.sh
```

**Wynik:** Zostanie utworzony plik `mcp-for-woocommerce-{VERSION}.zip`

---

### Metoda 2: Build przez NPM/PNPM

```bash
cd "/Users/mariusz/Local Sites/wooquant/app/public/wp-content/plugins/mcp-for-woocommerce"

# Za pomocą pnpm
pnpm run plugin-zip:build

# LUB za pomocą npm
npm run plugin-zip:build
```

---

### Metoda 3: Ręczny build (jeśli skrypty nie działają)

```bash
cd "/Users/mariusz/Local Sites/wooquant/app/public/wp-content/plugins/mcp-for-woocommerce"

# Krok 1: Zainstaluj production dependencies
composer install --no-dev --optimize-autoloader

# Krok 2: Zbuduj frontend
npm run build

# Krok 3: Utwórz dystrybucję
./create-wordpress-org-compliant.sh
```

---

## 📦 Co zawiera paczka instalacyjna?

### ✅ Włączone pliki:

```
mcp-for-woocommerce/
├── mcp-for-woocommerce.php     (główny plik wtyczki)
├── readme.txt                   (opis dla WordPress.org)
├── changelog.txt                (historia zmian)
├── LICENSE                      (licencja GPL-2.0)
├── uninstall.php                (skrypt odinstalowania)
├── composer.json                (Composer config)
├── client-setup.md              (instrukcja konfiguracji)
├── includes/                    (kod PHP wtyczki)
│   ├── Admin/
│   ├── Auth/
│   ├── Core/
│   ├── Prompts/
│   ├── Resources/
│   ├── Tools/
│   └── Utils/
├── vendor/                      (Composer dependencies - TYLKO production)
│   ├── autoload.php
│   ├── firebase/php-jwt/
│   └── yoast/phpunit-polyfills/
├── build/                       (skompilowany frontend)
│   ├── index.js
│   ├── index.asset.php
│   └── style-index.css
├── languages/                   (tłumaczenia)
│   ├── mcp-for-woocommerce-pl_PL.po
│   ├── mcp-for-woocommerce-pl_PL.mo
│   └── mcp-for-woocommerce-pl_PL-*.json
└── static-files/                (pliki statyczne)
    └── openapi.json
```

**Wielkość:** ~2-5 MB

---

### ❌ Wykluczone pliki (nie trafiają do paczki):

```
❌ node_modules/           (300+ MB - zależności deweloperskie)
❌ .git/                   (historia git)
❌ tests/                  (testy PHPUnit)
❌ src/                    (źródła JS - mamy build/)
❌ docs/                   (dokumentacja deweloperska)
❌ .gitignore              (konfiguracja Git)
❌ .env                    (zmienne środowiskowe)
❌ composer.lock           (lock file)
❌ package-lock.json       (lock file)
❌ *.log                   (logi)
❌ *.md (większość)        (dokumentacja dev)
❌ vendor/phpunit/         (testy - tylko production deps)
```

---

## 🔧 Instalacja w innym sklepie WordPress

### Sposób 1: Przez WordPress Admin (Zalecany)

1. Zaloguj się do WordPress Admin
2. Przejdź do: **Wtyczki → Dodaj nową**
3. Kliknij: **Wyślij wtyczkę**
4. Wybierz plik: `mcp-for-woocommerce-{VERSION}.zip`
5. Kliknij: **Zainstaluj teraz**
6. Po instalacji kliknij: **Aktywuj**

### Sposób 2: Przez FTP/SSH

```bash
# Skopiuj ZIP na serwer
scp mcp-for-woocommerce-1.2.0.zip user@server.com:/tmp/

# Zaloguj się na serwer
ssh user@server.com

# Rozpakuj do folderu wtyczek
cd /path/to/wordpress/wp-content/plugins/
unzip /tmp/mcp-for-woocommerce-1.2.0.zip

# Usuń ZIP
rm /tmp/mcp-for-woocommerce-1.2.0.zip
```

Następnie aktywuj wtyczkę w WordPress Admin.

---

## ✅ Weryfikacja przed budowaniem

Sprawdź czy wszystko jest gotowe:

```bash
# Sprawdź wersję wtyczki
grep "Version:" mcp-for-woocommerce.php

# Sprawdź czy build/ istnieje i zawiera pliki
ls -la build/

# Sprawdź czy vendor/ istnieje
ls -la vendor/

# Sprawdź czy languages/ zawiera tłumaczenia
ls -la languages/*.mo
```

Jeśli czegoś brakuje:

```bash
# Brak vendor/
composer install --no-dev

# Brak build/
npm run build

# Brak languages/*.mo
cd languages/
msgfmt mcp-for-woocommerce-pl_PL.po -o mcp-for-woocommerce-pl_PL.mo
```

---

## 🐛 Rozwiązywanie problemów

### Problem: "command not found: pnpm"
**Rozwiązanie:** Użyj `npm` zamiast `pnpm` lub zainstaluj pnpm:
```bash
npm install -g pnpm
```

### Problem: "command not found: composer"
**Rozwiązanie:** Zainstaluj Composer z https://getcomposer.org/

### Problem: Brak vendor/ po composer install
**Rozwiązanie:** 
```bash
composer clear-cache
composer install --no-dev --optimize-autoloader
```

### Problem: Brak build/ po npm run build
**Rozwiązanie:**
```bash
# Zainstaluj zależności
npm install

# Zbuduj ponownie
npm run build
```

### Problem: "PHP Fatal error: require_once(vendor/autoload.php)"
**Przyczyna:** Brak vendor/ w paczce
**Rozwiązanie:** Zawsze uruchamiaj `composer install --no-dev` przed budowaniem

---

## 📝 Checklist przed dystrybucją

- [ ] ✅ Zaktualizowana wersja w `mcp-for-woocommerce.php`
- [ ] ✅ Zaktualizowany `changelog.txt`
- [ ] ✅ Uruchomione `composer install --no-dev`
- [ ] ✅ Uruchomione `npm run build`
- [ ] ✅ Testy przechodzą pomyślnie
- [ ] ✅ Tłumaczenia skompilowane (.mo)
- [ ] ✅ Commit i push na Git
- [ ] ✅ Utworzona paczka ZIP
- [ ] ✅ Przetestowana instalacja na czystym WordPress

---

## 🚀 Quick Start

**Najszybszy sposób:**

```bash
cd "/Users/mariusz/Local Sites/wooquant/app/public/wp-content/plugins/mcp-for-woocommerce"
./build-release.sh
```

**Plik gotowy do instalacji:**  
`mcp-for-woocommerce-{VERSION}.zip`

---

## 📚 Dodatkowe informacje

- **Wielkość paczki:** ~2-5 MB (w porównaniu do ~300+ MB folder deweloperski)
- **Format:** Standard WordPress.org ZIP
- **Struktura:** `mcp-for-woocommerce/` (jeden główny folder)
- **Zgodność:** WordPress 6.0+, WooCommerce 7.0+, PHP 7.4+

---

## 💡 Wskazówki

1. **Zawsze** używaj skryptów budowania - nie kopiuj ręcznie plików
2. **Nigdy** nie dodawaj node_modules/ do paczki
3. **Zawsze** sprawdź czy vendor/ zawiera tylko production dependencies
4. **Testuj** paczkę na czystym WordPress przed dystrybucją
5. **Dokumentuj** zmiany w changelog.txt

