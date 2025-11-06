# WooQuant - MCP dla WooCommerce (Wersja Rozszerzona)

[![Licencja: GPL v2+](https://img.shields.io/badge/Licencja-GPL%20v2%2B-blue.svg)](https://www.gnu.org/licenses/gpl-2.0)
[![WordPress](https://img.shields.io/badge/WordPress-6.4%2B-blue.svg)](https://wordpress.org/)
[![WooCommerce](https://img.shields.io/badge/WooCommerce-Wymagany-purple.svg)](https://woocommerce.com/)

**Zarządzanie WooCommerce i WordPressem za pomocą AI przez Model Context Protocol (MCP)**

Połącz swój sklep WooCommerce i stronę WordPress z asystentami AI jak Claude Desktop i Cursor IDE. Zarządzaj produktami, zamówieniami, klientami, treścią i więcej używając naturalnego języka.

---

## 🌟 Czym jest WooQuant?

WooQuant to **rozszerzona wersja** oryginalnej wtyczki [mcp-for-woocommerce](https://github.com/iOSDevSK/mcp-for-woocommerce) autorstwa [iOSDevSK](https://github.com/iOSDevSK).

Ta wersja społecznościowa dodaje:
- ✅ **Pełną internacjonalizację** (angielski + polski, więcej języków mile widzianych!)
- ✅ **Zaawansowany panel admin** z zarządzaniem uprawnieniami użytkowników
- ✅ **16 inteligentnych promptów AI** dla typowych zadań e-commerce
- ✅ **6 zasobów wiedzy** pomagających asystentom AI
- ✅ **99 narzędzi** (36 odczytu + 63 zapisu/akcji) pokrywających wszystkie operacje WooCommerce i WordPress
- ✅ **Wzmocnione bezpieczeństwo** z szczegółowymi uprawnieniami i kontrolą operacji zapisu

---

## 🚀 Szybki Start

### 1. Zainstaluj wtyczkę

Pobierz i zainstaluj WooQuant na swojej stronie WordPress z aktywnym WooCommerce.

### 2. Włącz MCP w ustawieniach

Przejdź do: **Panel WordPress → MCP dla WooCommerce → Ustawienia**

1. Włącz **"Włącz funkcjonalność MCP"**
2. Skonfiguruj uwierzytelnianie JWT lub wyłącz dla rozwoju lokalnego
3. *(Opcjonalnie)* Włącz **"Włącz operacje zapisu"** aby AI mogło tworzyć/modyfikować dane

### 3. Połącz swojego klienta AI

#### Dla Claude Desktop:
```json
{
  "mcpServers": {
    "woocommerce": {
      "url": "https://twoja-strona.pl/wp-json/mcpfowo/v1/mcp",
      "headers": {
        "Authorization": "Bearer TWOJ_TOKEN_JWT_TUTAJ"
      }
    }
  }
}
```

#### Dla Cursor IDE:
Dodaj w ustawieniach Cursor → Serwery MCP:
```json
{
  "woocommerce-mojsklep": {
    "url": "https://twoja-strona.pl/wp-json/mcpfowo/v1/mcp",
    "headers": {
      "Authorization": "Bearer TWOJ_TOKEN_JWT_TUTAJ"
    }
  }
}
```

📚 **Pełna instrukcja:** Zobacz [QUICK-START.pl.md](QUICK-START.pl.md)

---

## 🎯 Co możesz zrobić?

### Zarządzanie E-commerce
- 🛍️ **Szukaj produktów** inteligentnie ("znajdź czerwone sukienki poniżej 200 zł")
- 📦 **Analizuj zamówienia** i wyniki sprzedaży
- 👥 **Segmentuj klientów** (VIP, zagrożeni odejściem, nowi)
- 📊 **Generuj raporty biznesowe** z analizami
- 🏷️ **Zarządzaj kuponami** i promocjami
- 📦 **Monitoruj magazyn** i alerty niskiego stanu
- 🚚 **Konfiguruj wysyłkę** - strefy i metody

### Zarządzanie treścią i stroną
- ✍️ **Twórz treści** (posty, strony) z SEO
- 🔍 **Analizuj SEO** dla lepszych pozycji
- 🖼️ **Zarządzaj biblioteką mediów** (upload, organizacja, optymalizacja)
- 📋 **Buduj menu** zgodnie z najlepszymi praktykami UX
- 👤 **Zarządzaj użytkownikami** i uprawnieniami

### Obsługa klienta
- 💬 **Odpowiadaj na pytania klientów** o zamówienia i produkty
- 🔎 **Sprawdzaj status zamówień** i śledzenie przesyłek
- 📧 **Dostarczaj informacje o produktach** natychmiast

### Operacje na danych
- 📤 **Import/Export** produktów i zamówień (CSV)
- 💾 **Backup i przywracanie** danych bezpiecznie
- 🔄 **Migracja danych** między stronami

---

## 📋 Co jest w środku?

### 99 Narzędzi
- **36 narzędzi odczytu:** Pobieraj produkty, zamówienia, klientów, analitykę
- **63 narzędzia zapisu/akcji:** Twórz, aktualizuj, usuwaj dane (wymaga uprawnień)

### 16 Promptów AI
Gotowe przepływy pracy dla:
- Wyszukiwania produktów, zarządzania magazynem
- Analizy sprzedaży, segmentacji klientów
- Raportów biznesowych, analizy SEO
- Tworzenia treści, zarządzania mediami
- I więcej...

### 6 Zasobów wiedzy
Przewodniki kontekstowe pomagające AI zrozumieć Twój sklep:
- Strategie wyszukiwania WooCommerce
- Konfiguracja strony
- Informacje o wtyczkach i motywie
- Role i uprawnienia użytkowników

📚 **Pełna dokumentacja:**
- [TOOLS-LIST.pl.md](TOOLS-LIST.pl.md) - Kompletna lista wszystkich 99 narzędzi
- [PROMPTS-LIST.pl.md](PROMPTS-LIST.pl.md) - Przewodnik po wszystkich 16 promptach AI
- [QUICK-START.pl.md](QUICK-START.pl.md) - Instrukcja krok po kroku

---

## 🔒 Bezpieczeństwo i uprawnienia

### Wbudowane funkcje bezpieczeństwa
- ✅ **Uwierzytelnianie JWT** dla bezpiecznego dostępu API
- ✅ **Uprawnienia użytkowników i ról** - Kontroluj kto może używać MCP
- ✅ **Przełącznik operacji zapisu** - Domyślnie tylko odczyt
- ✅ **Uprawnienia WordPress** - Respektuje istniejące uprawnienia
- ✅ **Przypomnienia o backupie** - AI sugeruje kopie przed destrukcyjnymi operacjami

### Zalecana konfiguracja
1. **Zacznij z trybem tylko do odczytu** (Operacje zapisu WYŁ)
2. **Testuj bezpieczne operacje** (wyszukiwanie, przeglądanie)
3. **Włącz zapis gdy będziesz gotowy** dla pełnej funkcjonalności
4. **Ogranicz dostęp do MCP** tylko dla administratorów (w zakładce Uprawnienia użytkowników)

---

## 🌍 Internacjonalizacja

WooQuant jest gotowy do tłumaczenia!

**Obecnie dostępne:**
- 🇬🇧 Angielski (domyślny)
- 🇵🇱 Polski (100% przetłumaczone)

**Chcesz dodać tłumaczenie?**  
Zapraszamy do współpracy! Zobacz [CONTRIBUTING.md](CONTRIBUTING.md) dla wskazówek.

---

## 📦 Wymagania

- **WordPress:** 6.4 lub wyższy
- **WooCommerce:** Najnowsza wersja wymagana
- **PHP:** 8.0 lub wyższy
- **Klient AI:** Claude Desktop, Cursor IDE lub dowolny klient kompatybilny z MCP

---

## 🤝 Autorzy i licencja

### Oryginalny autor
- **Filip Dvoran (iOSDevSK)** - [Oryginalna wtyczka mcp-for-woocommerce](https://github.com/iOSDevSK/mcp-for-woocommerce)

### Wersja rozszerzona
- **@jeden- i współtwórcy** - Ulepszenia WooQuant

### Licencja
Ta wtyczka jest licencjonowana na **GPL-2.0-or-later**, tak samo jak oryginał.

```
Oryginalna praca Copyright (C) 2024 Filip Dvoran (iOSDevSK)
Rozszerzona praca Copyright (C) 2025 @jeden- i współtwórcy

Ten program jest wolnym oprogramowaniem; możesz go redystrybuować
i/lub modyfikować zgodnie z warunkami Powszechnej Licencji Publicznej GNU
opublikowanej przez Free Software Foundation; albo wersji 2 tej Licencji,
albo (według twojego wyboru) dowolnej późniejszej wersji.
```

**Specjalne podziękowania** dla iOSDevSK za stworzenie fundamentów tego potężnego narzędzia i udostępnienie go społeczności open-source! 🙏

---

## 📞 Wsparcie i współpraca

- 🐛 **Zgłoś błąd:** [GitHub Issues](https://github.com/jeden-/wooquant/issues)
- 💡 **Propozycje funkcji:** [GitHub Discussions](https://github.com/jeden-/wooquant/discussions)
- 🤝 **Współtwórz:** Pull requesty mile widziane! Zobacz [CONTRIBUTING.md](CONTRIBUTING.md)
- 📖 **Dokumentacja:** Sprawdź folder `/docs` lub wiki

---

## 📝 Historia zmian

Zobacz [CHANGELOG.md](CHANGELOG.md) dla szczegółowej historii wersji.

**Aktualna wersja:** 1.1.9

---

## ⚠️ Zastrzeżenie

Ta wtyczka jest projektem społecznościowym i **nie jest powiązana z Automattic ani WooCommerce**.

**Używaj na własną odpowiedzialność.** Zawsze rób backup strony przed włączeniem operacji zapisu. Testuj najpierw w środowisku staging.

---

Stworzone z ❤️ dla społeczności WordPress i WooCommerce

**[🌟 Daj gwiazdkę na GitHub](https://github.com/jeden-/wooquant)** jeśli uznasz to za przydatne!


