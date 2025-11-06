# Przewodnik tłumaczenia - MCP for WooCommerce

## Nowy język polski dodany! 🇵🇱

Plugin został w pełni przetłumaczony na język polski.

## Jak aktywować polski język

### Metoda 1: Przez panel administracyjny WordPress

1. Zaloguj się do panelu WordPress
2. Przejdź do **Ustawienia → Ogólne**
3. W polu **Język witryny** wybierz **Polski**
4. Kliknij **Zapisz zmiany**

Plugin automatycznie przełączy się na polski!

### Metoda 2: Przez wp-config.php

Dodaj lub zmień w pliku `wp-config.php`:

```php
define('WPLANG', 'pl_PL');
```

### Metoda 3: Dla konkretnego użytkownika

Każdy użytkownik może ustawić swój język:

1. **Profil** → **Język**
2. Wybierz **Polski**
3. Kliknij **Aktualizuj profil**

## Co zostało przetłumaczone

✅ **Interfejs administracyjny**
- Ustawienia pluginu
- Konfiguracja serwera MCP
- Uwierzytelnienie JWT
- Dokumentacja

✅ **Komunikaty**
- Komunikaty sukcesu
- Komunikaty błędów
- Ostrzeżenia bezpieczeństwa
- Komunikaty walidacji

✅ **Narzędzia MCP**
- Nazwy narzędzi WooCommerce
- Nazwy narzędzi WordPress
- Opisy narzędzi
- Parametry narzędzi

✅ **Ogólne**
- Przyciski i akcje
- Etykiety formularzy
- Komunikaty statusu
- Teksty pomocy

## Przykłady przetłumaczonych tekstów

### Przed
```
Enable MCP functionality
```

### Po
```
Włącz funkcjonalność MCP
```

### Przed
```
Token copied to clipboard!
```

### Po
```
Token skopiowany do schowka!
```

### Przed
```
Never-expiring tokens pose significant security risks...
```

### Po
```
Tokeny bez daty wygaśnięcia stanowią poważne zagrożenie bezpieczeństwa...
```

## Pliki tłumaczenia

Wszystkie pliki znajdują się w katalogu `languages/`:

- **mcp-for-woocommerce-pl_PL.po** - Edytowalny plik tłumaczenia
- **mcp-for-woocommerce-pl_PL.mo** - Skompilowany plik (używany przez WordPress)
- **README.md** - Szczegółowa dokumentacja tłumaczeń

## Aktualizacja tłumaczenia

Jeśli chcesz zmienić lub dodać tłumaczenie:

### 1. Edytuj plik .po

Otwórz `languages/mcp-for-woocommerce-pl_PL.po` w edytorze tekstowym lub Poedit.

### 2. Znajdź tekst do zmiany

```po
msgid "Original text"
msgstr "Twoje tłumaczenie"
```

### 3. Skompiluj

```bash
cd languages
msgfmt -o mcp-for-woocommerce-pl_PL.mo mcp-for-woocommerce-pl_PL.po
```

### 4. Wyczyść cache WordPress

Plugin powinien automatycznie załadować nowe tłumaczenie.

## Edytory tłumaczeń

### Poedit (zalecane)
- Pobierz: https://poedit.net/
- Graficzny edytor plików .po/.mo
- Automatyczna kompilacja

### Loco Translate (plugin WordPress)
- Instaluj jako plugin WordPress
- Edytuj tłumaczenia bezpośrednio w panelu admin
- Nie wymaga dostępu do plików

### Edytor tekstowy
- Można edytować pliki .po w dowolnym edytorze
- Pamiętaj o kompilacji do .mo!

## Zgłaszanie błędów w tłumaczeniu

Znalazłeś błąd lub masz sugestię? 

1. Otwórz issue na GitHub:
   https://github.com/iOSDevSK/mcp-for-woocommerce/issues

2. Lub edytuj plik i wyślij Pull Request

## Dodawanie nowego języka

Chcesz przetłumaczyć plugin na inny język?

1. Skopiuj plik `mcp-for-woocommerce-pl_PL.po`
2. Zmień nazwę na odpowiedni kod (np. `mcp-for-woocommerce-de_DE.po` dla niemieckiego)
3. Zmień nagłówek `Language:` w pliku
4. Przetłumacz wszystkie `msgstr`
5. Skompiluj do .mo
6. Wyślij Pull Request!

## Wsparcie

Pytania? Pomoc?
- GitHub Issues: https://github.com/iOSDevSK/mcp-for-woocommerce/issues
- Dokumentacja: Zobacz `languages/README.md`

---

**Dziękujemy za korzystanie z MCP for WooCommerce w języku polskim!** 🇵🇱

