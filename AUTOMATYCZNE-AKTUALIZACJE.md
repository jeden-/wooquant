# System Automatycznych Aktualizacji Wtyczki

## 🔴 OBECNY STAN

**NIE** - wtyczka **obecnie nie obsługuje** automatycznych aktualizacji.

Po zainstalowaniu wtyczki w innych sklepach WordPress, nie będą one automatycznie otrzymywać aktualizacji z GitHub.

---

## ✅ ROZWIĄZANIA - 3 Opcje

### Opcja 1: WordPress.org (Zalecana dla publicznych wtyczek)

**Jak to działa:**
- Wtyczka jest publikowana w oficjalnym repozytorium WordPress.org
- System WordPress automatycznie sprawdza dostępność aktualizacji
- Użytkownicy widzą powiadomienie: "Dostępna aktualizacja"
- Aktualizacja jednym kliknięciem w WordPress Admin

**Zalety:**
- ✅ Zero konfiguracji po stronie użytkownika
- ✅ Zaufane przez WordPress
- ✅ Automatyczne powiadomienia
- ✅ Widoczność w katalogu wtyczek

**Wady:**
- ❌ Proces review (może trwać 2-14 dni)
- ❌ Wymogi jakości kodu
- ❌ Publiczne repozytorium (nie dla prywatnych wtyczek)

**Kto może używać:**
Wtyczki open-source, publiczne projekty

---

### Opcja 2: GitHub Updater Plugin (Zalecana dla prywatnych wtyczek)

**Jak to działa:**
1. Użytkownik instaluje wtyczkę "GitHub Updater" w WordPress
2. Nasza wtyczka dodaje specjalne nagłówki w pliku głównym
3. GitHub Updater automatycznie sprawdza GitHub Releases
4. Aktualizacje pobierane z GitHub

**Implementacja:**

#### Krok 1: Dodaj nagłówki do `mcp-for-woocommerce.php`

```php
/**
 * Plugin name:       WooQuant
 * GitHub Plugin URI: jeden-/wooquant
 * GitHub Branch:     main
 * Version:           1.2.0
 * Requires at least: 6.4
 * Requires PHP:      8.0
 */
```

#### Krok 2: Użytkownicy instalują GitHub Updater

```bash
# W WordPress Admin → Wtyczki → Dodaj nową
# Wyszukaj: "GitHub Updater"
# Zainstaluj i aktywuj
```

#### Krok 3: Publikuj releases na GitHub

```bash
# Gdy robisz nową wersję:
git tag v1.2.0
git push origin v1.2.0

# Na GitHub → Releases → Create new release
# Załącz plik .zip z wtyczką
```

**Zalety:**
- ✅ Działa z prywatnymi i publicznymi repo
- ✅ Bezpośrednia integracja z GitHub
- ✅ Prosta konfiguracja
- ✅ Wspiera GitHub tokens dla prywatnych repo

**Wady:**
- ❌ Wymaga dodatkowej wtyczki (GitHub Updater)
- ❌ Użytkownik musi ją zainstalować
- ❌ Dodatkowa zależność

**Instalacja dla użytkowników:**
- https://github.com/afragen/github-updater

---

### Opcja 3: Plugin Update Checker (Własny system)

**Jak to działa:**
- Dodajemy bibliotekę do `composer.json`
- Wtyczka sprawdza GitHub releases bezpośrednio
- Zero dodatkowych wtyczek dla użytkownika

**Implementacja:**

#### Krok 1: Dodaj bibliotekę do composer.json

```json
{
  "require": {
    "firebase/php-jwt": "^6.11",
    "yahnis-elsts/plugin-update-checker": "^5.0"
  }
}
```

#### Krok 2: Zainstaluj

```bash
composer require yahnis-elsts/plugin-update-checker
composer install --no-dev
```

#### Krok 3: Dodaj kod do `mcp-for-woocommerce.php`

```php
<?php
// Na końcu pliku, przed init_mcpfowo()

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

/**
 * Initialize automatic updates from GitHub
 */
function init_mcpfowo_updater() {
    $updateChecker = PucFactory::buildUpdateChecker(
        'https://github.com/jeden-/wooquant',
        __FILE__,
        'mcp-for-woocommerce'
    );

    // Opcjonalnie: Użyj konkretnej gałęzi
    $updateChecker->setBranch('main');

    // Opcjonalnie: Użyj GitHub Personal Access Token dla prywatnych repo
    // $updateChecker->setAuthentication('your-github-token-here');
}

add_action('plugins_loaded', 'init_mcpfowo_updater');
```

**Zalety:**
- ✅ Zero dodatkowych wtyczek dla użytkownika
- ✅ Pełna kontrola nad procesem aktualizacji
- ✅ Działa od razu po instalacji
- ✅ Wspiera prywatne repo (z tokenem)

**Wady:**
- ❌ Dodaje 400 KB do rozmiaru wtyczki (vendor/)
- ❌ Wymaga publicznych GitHub releases
- ❌ Dla prywatnych repo potrzebny token

---

## 🎯 REKOMENDACJA

### Dla **WooQuant (Twoja wtyczka):**

**Opcja 3: Plugin Update Checker** ✅

**Dlaczego:**
1. Zero konfiguracji dla użytkowników
2. Repo jest publiczne (https://github.com/jeden-/wooquant)
3. Już używasz Composer
4. Kontrolujesz cały proces

**Dla WordPress.org:**
Jeśli chcesz publikować publicznie, to dodatkowa opcja (możesz mieć obie).

---

## 🚀 IMPLEMENTACJA - Krok po kroku

### Dla Plugin Update Checker (Opcja 3)

#### 1. Dodaj bibliotekę

```bash
cd "/Users/mariusz/Local Sites/wooquant/app/public/wp-content/plugins/mcp-for-woocommerce"

composer require yahnis-elsts/plugin-update-checker
```

#### 2. Zmodyfikuj `mcp-for-woocommerce.php`

Dodaj przed linią `add_action('plugins_loaded', 'init_mcpfowo');`:

```php
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

/**
 * Initialize automatic updates from GitHub
 */
function init_mcpfowo_updater() {
    require_once MCPFOWO_PATH . 'vendor/autoload.php';
    
    $updateChecker = PucFactory::buildUpdateChecker(
        'https://github.com/jeden-/wooquant',
        MCPFOWO_PLUGIN_FILE,
        'mcp-for-woocommerce'
    );

    $updateChecker->setBranch('main');
    
    // Włącz szczegółowe logi (usuń po testach)
    // $updateChecker->getDebugBarExtension();
}

add_action('init', 'init_mcpfowo_updater');
```

#### 3. Zaktualizuj wersję wtyczki

W `mcp-for-woocommerce.php` zmień:

```php
/**
 * Version:           1.2.1
 */
```

I w stałej:

```php
define( 'MCPFOWO_VERSION', '1.2.1' );
```

#### 4. Commit i push

```bash
git add .
git commit -m "feat: add automatic updates from GitHub"
git push origin main
```

#### 5. Utwórz GitHub Release

```bash
# Taguj wersję
git tag v1.2.1
git push origin v1.2.1

# Lub przez GitHub UI:
# 1. Przejdź do: https://github.com/jeden-/wooquant/releases
# 2. Kliknij "Create a new release"
# 3. Tag: v1.2.1
# 4. Title: Version 1.2.1 - Automatic Updates
# 5. Description: Added automatic update system
# 6. Załącz plik: mcp-for-woocommerce-1.2.1.zip (zbudowany przez ./build-release.sh)
# 7. Publish release
```

#### 6. Testowanie

Na innej instalacji WordPress:

```
1. Zainstaluj wtyczkę wersji 1.2.0
2. Aktywuj
3. Przejdź do: WordPress Admin → Pulpit → Aktualizacje
4. Powinieneś zobaczyć: "WooQuant 1.2.1 is available"
5. Kliknij "Aktualizuj teraz"
```

---

## 📦 Struktura GitHub Release

Każdy release musi zawierać:

```
Release v1.2.1
├── Tag: v1.2.1
├── Title: Version 1.2.1 - Feature Name
├── Description: (changelog)
└── Assets:
    └── mcp-for-woocommerce-1.2.1.zip  ← WAŻNE!
```

**KRYTYCZNE:** Plik ZIP musi być załączony do każdego release!

---

## 🔄 Proces aktualizacji dla użytkowników

### Przed (bez auto-update):

1. Developer: Wypuszcza nową wersję
2. Admin: Pobiera ZIP ręcznie
3. Admin: Dezaktywuje wtyczkę
4. Admin: Usuwa starą wersję
5. Admin: Instaluje nową wersję z ZIP
6. Admin: Aktywuje wtyczkę

**Czas:** 5-10 minut + ryzyko błędu

### Po (z auto-update):

1. Developer: Wypuszcza nową wersję + GitHub release
2. WordPress: Automatycznie wykrywa aktualizację
3. Admin: Widzi powiadomienie "Dostępna aktualizacja"
4. Admin: Klika "Aktualizuj teraz"

**Czas:** 30 sekund ✅

---

## 🧪 Testowanie systemu aktualizacji

### Test 1: Sprawdź czy wtyczka wykrywa aktualizacje

```php
// Dodaj tymczasowo do functions.php testowej instalacji
add_action('admin_init', function() {
    delete_site_transient('update_plugins');
    wp_update_plugins();
    var_dump(get_site_transient('update_plugins'));
});
```

### Test 2: Wymuś sprawdzenie aktualizacji

W WordPress Admin:
- Pulpit → Aktualizacje
- Kliknij "Sprawdź ponownie"

### Test 3: Debug logi

Włącz w `wp-config.php`:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

Sprawdź logi: `wp-content/debug.log`

---

## ⚠️ WAŻNE UWAGI

### Numerowanie wersji

Używaj **Semantic Versioning**:
- `1.0.0` → `1.0.1` (bugfix)
- `1.0.1` → `1.1.0` (nowa funkcja)
- `1.1.0` → `2.0.0` (breaking changes)

### GitHub Releases

**ZAWSZE** załączaj plik ZIP do release!
```bash
# Zbuduj przed utworzeniem release
./build-release.sh

# Powstanie: mcp-for-woocommerce-1.2.1.zip
# Załącz ten plik do GitHub release
```

### Kompatybilność wsteczna

- Testuj aktualizacje na staging environment
- Zachowaj backwards compatibility
- Dokumentuj breaking changes w changelog

### Rollback

Użytkownicy mogą wrócić do starszej wersji:
1. Dezaktywacja wtyczki
2. Usunięcie
3. Instalacja starszej wersji z GitHub releases

---

## 📋 Checklist dla każdego release

- [ ] Zaktualizowana wersja w `mcp-for-woocommerce.php`
- [ ] Zaktualizowana `MCPFOWO_VERSION`
- [ ] Zaktualizowany `changelog.txt`
- [ ] Zaktualizowany `readme.txt`
- [ ] Testy przechodzą
- [ ] Zbudowana paczka ZIP (`./build-release.sh`)
- [ ] Commit i push do GitHub
- [ ] Utworzony tag Git (`git tag v1.2.1`)
- [ ] Push tagu (`git push origin v1.2.1`)
- [ ] Utworzony GitHub Release
- [ ] Załączony ZIP do release
- [ ] Przetestowana aktualizacja na testowej instalacji

---

## 🔗 Linki i zasoby

- **Plugin Update Checker:** https://github.com/YahnisElsts/plugin-update-checker
- **GitHub Updater:** https://github.com/afragen/github-updater
- **WordPress.org Publishing:** https://developer.wordpress.org/plugins/wordpress-org/
- **Semantic Versioning:** https://semver.org/

---

## 💡 Pytania?

**Q: Czy użytkownicy muszą coś instalować?**  
A: Opcja 3 (Plugin Update Checker) - NIE. System działa od razu.

**Q: Czy działa z prywatnymi repo?**  
A: TAK, ale potrzebujesz GitHub Personal Access Token.

**Q: Jak często sprawdzane są aktualizacje?**  
A: WordPress sprawdza co 12 godzin automatycznie.

**Q: Czy mogę wymusić aktualizację?**  
A: Użytkownik decyduje kiedy aktualizować (widzi tylko powiadomienie).

**Q: Co jeśli aktualizacja się nie powiedzie?**  
A: WordPress automatycznie przywraca poprzednią wersję.

