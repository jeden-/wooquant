# Pełna lista promptów MCP for WooCommerce

Ten dokument zawiera kompletną listę wszystkich 16 dostępnych promptów MCP, które wykorzystują pełną funkcjonalność 99 narzędzi wtyczki WooQuant.

## Prompty WordPress (6)

### 1. **get-site-info** - Informacje o witrynie
**Opis:** Pobiera szczegółowe informacje o witrynie WordPress  
**Argumenty:**
- `info_type` (opcjonalnie): Typ informacji (general, plugins, theme, users, settings)

**Przykład użycia:**
```
Użyj promptu get-site-info aby uzyskać pełne informacje o wtyczkach
```

**Co robi:**
- Zbiera dane o witrynie, wtyczkach, motywie
- Informacje o użytkownikach i rolach
- Konfiguracja WordPress
- Przydatne do troubleshootingu

---

### 2. **create-content** - Tworzenie treści
**Opis:** Tworzy i optymalizuje treści WordPress (posty, strony)  
**Argumenty:**
- `content_type` (wymagany): "post" lub "page"
- `topic` (wymagany): Temat lub tytuł treści
- `tone` (opcjonalnie): Ton pisania (professional, casual, friendly, formal)

**Przykład użycia:**
```
Użyj promptu create-content aby stworzyć post o "Jak wybrać idealny produkt" w przyjaznym tonie
```

**Co robi:**
- Analizuje istniejące treści
- Tworzy dobrze zorganizowaną strukturę
- Optymalizacja SEO (słowa kluczowe, meta opisy)
- Sugeruje obrazy wyróżniające
- Jeśli włączone operacje zapisu: tworzy treść automatycznie

---

### 3. **analyze-seo** - Analiza SEO
**Opis:** Analizuje i optymalizuje SEO dla treści i produktów  
**Argumenty:**
- `target` (wymagany): "site", "products", "posts", "pages" lub konkretny URL/ID
- `focus_keyword` (opcjonalnie): Docelowe słowo kluczowe

**Przykład użycia:**
```
Użyj promptu analyze-seo dla produktów z focus_keyword "buty sportowe"
```

**Co robi:**
- Analiza treści (tytuły, meta, nagłówki, słowa kluczowe)
- Analiza techniczna (URL, linki, obrazy, alt text)
- Specyficzne dla WooCommerce (schema produktów, recenzje)
- Priorytetyzowane rekomendacje
- Konkretne kroki do poprawy

---

### 4. **manage-media** - Zarządzanie mediami
**Opis:** Upload, organizacja, optymalizacja biblioteki mediów  
**Argumenty:**
- `task` (wymagany): "upload", "organize", "optimize", "audit", "cleanup", "batch_upload"
- `details` (opcjonalnie): Szczegóły zadania (ścieżka pliku, kryteria)

**Przykład użycia:**
```
Użyj promptu manage-media z zadaniem "audit" aby sprawdzić bibliotekę mediów
```

**Co robi:**
- **Upload**: Wgrywa obrazy z optymalizacją i SEO
- **Organize**: Porządkuje strukturę mediów
- **Optimize**: Kompresja, alt text, nazwy plików
- **Audit**: Statystyki, problemy, niewykorzystane pliki
- **Cleanup**: Usuwa duplikaty i nieużywane pliki (OSTROŻNIE!)

---

### 5. **manage-menus** - Zarządzanie menu
**Opis:** Tworzy i optymalizuje menu nawigacyjne  
**Argumenty:**
- `action` (wymagany): "create", "optimize", "analyze", "restructure"
- `menu_location` (opcjonalnie): "primary", "footer", "mobile", "sidebar"

**Przykład użycia:**
```
Użyj promptu manage-menus z akcją "optimize" dla menu primary
```

**Co robi:**
- **Create**: Tworzy profesjonalne struktury menu (e-commerce, content site)
- **Optimize**: Stosuje najlepsze praktyki UX (max 5-7 pozycji top-level)
- **Analyze**: Ocena nawigacji i użyteczności
- **Restructure**: Przeprojektowanie menu dla lepszego UX

---

### 6. **manage-users** - Zarządzanie użytkownikami
**Opis:** Zarządzanie użytkownikami, rolami i bezpieczeństwem  
**Argumenty:**
- `task` (wymagany): "create", "audit", "security_review", "role_optimization", "bulk_update"
- `details` (opcjonalnie): Szczegóły zadania

**Przykład użycia:**
```
Użyj promptu manage-users z zadaniem "security_review" aby przeprowadzić audyt bezpieczeństwa
```

**Co robi:**
- **Create**: Tworzy konta z odpowiednimi uprawnieniami (least privilege)
- **Audit**: Inwentaryzacja użytkowników, identyfikacja problemów
- **Security Review**: Audyt administratorów, 2FA, bezpieczeństwo
- **Role Optimization**: Optymalizacja przypisania ról
- **Bulk Update**: Masowe zmiany (OSTROŻNIE!)

---

## Prompty WooCommerce (8)

### 7. **search-products** - Wyszukiwanie produktów
**Opis:** Inteligentne wyszukiwanie produktów z fallbackami  
**Argumenty:**
- `query` (wymagany): Zapytanie (np. "najtańsze laptopy na wyprzedaży", "najnowsze buty")

**Przykład użycia:**
```
Użyj promptu search-products z query "czerwone sukienki poniżej 200 zł"
```

**Co robi:**
- Czyta przewodnik wyszukiwania (woocommerce-search-guide)
- Analizuje intencję wyszukiwania
- Wieloetapowa strategia (filtry -> kategorie -> text search)
- ZAWSZE zwraca linki do produktów
- Nigdy nie zwraca pustych wyników bez wypróbowania fallbacków

---

### 8. **analyze-sales** - Analiza sprzedaży
**Opis:** Analizuje dane sprzedażowe WooCommerce  
**Argumenty:**
- `time_span` (wymagany): Okres (last_7_days, last_30_days, last_month, last_quarter, last_year)

**Przykład użycia:**
```
Użyj promptu analyze-sales dla okresu last_30_days
```

**Co robi:**
- Całkowita sprzedaż i trendy
- Średnia wartość zamówienia
- Top produkty
- Analiza trendów sprzedaży
- Wnioski i rekomendacje

---

### 9. **analyze-orders** - Analiza zamówień
**Opis:** Analizuje zamówienia z filtrami i insights  
**Argumenty:**
- `status` (opcjonalnie): Status zamówienia (pending, processing, completed, cancelled)
- `time_period` (opcjonalnie): Okres (today, last_7_days, last_30_days, this_month)

**Przykład użycia:**
```
Użyj promptu analyze-orders ze statusem "pending" dla okresu last_7_days
```

**Co robi:**
- Liczba zamówień i przychody
- Rozkład statusów zamówień
- Top klienci i produkty
- Metody płatności
- Wzorce niepokojące (wysokie anulacje)
- Rekomendacje działań

---

### 10. **customer-support** - Obsługa klienta
**Opis:** AI-powered wsparcie dla zapytań klientów  
**Argumenty:**
- `customer_query` (wymagany): Pytanie klienta
- `order_id` (opcjonalnie): ID zamówienia

**Przykład użycia:**
```
Użyj promptu customer-support z pytaniem "Gdzie jest moje zamówienie?" i order_id 12345
```

**Co robi:**
- Sprawdza status zamówień
- Wyszukuje produkty (kolory, rozmiary, opcje)
- Informacje o wysyłce i dostępności
- Przyjazne, pomocne odpowiedzi
- Linki do produktów

---

### 11. **manage-inventory** - Zarządzanie magazynem
**Opis:** Analiza i zarządzanie stanami magazynowymi  
**Argumenty:**
- `action` (wymagany): "check_low_stock", "check_out_of_stock", "analyze_all", "update_stock"
- `threshold` (opcjonalnie): Próg niskiego stanu (domyślnie 5)

**Przykład użycia:**
```
Użyj promptu manage-inventory z akcją "check_low_stock" i progiem 10
```

**Co robi:**
- Identyfikuje produkty z niskim stanem
- Znajduje produkty bez stanu (out of stock)
- Analizuje poziomy magazynowe
- Rekomendacje uzupełnienia
- Jeśli włączone zapisy: aktualizuje stany

---

### 12. **manage-coupons** - Zarządzanie kuponami
**Opis:** Tworzy i zarządza kampaniami promocyjnymi  
**Argumenty:**
- `action` (wymagany): "create_campaign", "analyze_performance", "optimize_existing", "seasonal_promotion"
- `details` (opcjonalnie): Szczegóły kampanii

**Przykład użycia:**
```
Użyj promptu manage-coupons z akcją "create_campaign" dla "Black Friday Sale"
```

**Co robi:**
- **Create Campaign**: Projektuje strategie kuponów (cele, target, produkty)
- **Analyze Performance**: Metyki ROI, redemption rate, wpływ na AOV
- **Optimize Existing**: Poprawia słabe kupony
- **Seasonal Promotion**: Tworzy kampanie tematyczne (święta, wyprzedaże)

---

### 13. **analyze-customers** - Analiza klientów
**Opis:** Segmentacja i analiza zachowań klientów  
**Argumenty:**
- `analysis_type` (wymagany): "segmentation", "lifetime_value", "churn_risk", "purchase_patterns", "loyalty_analysis"
- `segment` (opcjonalnie): Konkretny segment klientów

**Przykład użycia:**
```
Użyj promptu analyze-customers typu "churn_risk" aby znaleźć klientów do reaktywacji
```

**Co robi:**
- **Segmentation**: VIP, At-Risk, New, Bargain Hunters, Loyal Fans
- **Lifetime Value**: CLV, top 20% klientów, przewidywana wartość
- **Churn Risk**: Klienci nieaktywni, kampanie win-back
- **Purchase Patterns**: Cykle zakupowe, cross-sell, trendy
- **Loyalty Analysis**: Wskaźniki retencji, program lojalnościowy

---

### 14. **manage-shipping-tax** - Wysyłka i podatki
**Opis:** Konfiguracja i optymalizacja wysyłki oraz podatków  
**Argumenty:**
- `focus` (wymagany): "shipping", "tax", "both", "audit", "optimize"
- `region` (opcjonalnie): Konkretny region/strefa

**Przykład użycia:**
```
Użyj promptu manage-shipping-tax z focus "shipping" i akcją "optimize"
```

**Co robi:**
- **Shipping Audit**: Analiza stref, metod, kosztów, luk w pokryciu
- **Shipping Optimize**: Rekomendacje stref, free shipping thresholds
- **Tax Audit**: Sprawdzanie zgodności stawek z przepisami
- **Tax Optimize**: Właściwe klasy podatkowe, zgodność z prawem
- **Both**: Kompleksowa optymalizacja checkoutu

---

## Prompty danych i raportowania (2)

### 15. **migrate-data** - Migracja danych
**Opis:** Import, export, backup, restore danych  
**Argumenty:**
- `operation` (wymagany): "import", "export", "backup", "restore", "migrate", "validate"
- `data_type` (wymagany): "products", "orders", "customers", "content", "all"
- `source` (opcjonalnie): Ścieżka pliku źródłowego

**Przykład użycia:**
```
Użyj promptu migrate-data z operacją "backup" dla data_type "products"
```

**Co robi:**
- **Export**: Bezpieczny eksport do CSV
- **Import**: ⚠️ DESTRUKCYJNE - backup najpierw, walidacja, test, pełny import
- **Backup**: Timestampowane kopie zapasowe z weryfikacją
- **Restore**: ⚠️ BARDZO DESTRUKCYJNE - ostateczność, backup przed restore
- **Validate**: Sprawdza spójność danych, orphaned records

---

### 16. **generate-business-report** - Raporty biznesowe
**Opis:** Kompleksowe raporty biznesowe i KPI  
**Argumenty:**
- `report_type` (wymagany): "executive_summary", "sales_performance", "inventory_status", "customer_insights", "marketing_effectiveness", "operational_health"
- `time_period` (opcjonalnie): Okres czasu

**Przykład użycia:**
```
Użyj promptu generate-business-report typu "executive_summary" dla last_30_days
```

**Co robi:**
- **Executive Summary**: Dashboard KPI, highlights, problemy, top rekomendacje
- **Sales Performance**: Głęboka analiza sprzedaży, produktów, klientów
- **Inventory Status**: Stan magazynu, fast/slow movers, rekomendacje zamawiania
- **Customer Insights**: Segmentacja, CLV, retencja, personalizacja
- **Marketing Effectiveness**: ROI kampanii, kupony, konwersje, content performance
- **Operational Health**: Status techniczny, processing, customer service, metryki

---

## Jak używać promptów?

### W Cursor IDE:
```
# Bezpośrednie wywołanie promptu
Użyj promptu search-products z query "niebieskie koszulki"

# Lub po prostu opisz co chcesz
Znajdź najlepiej sprzedające się produkty w tym miesiącu
```

### W Claude Desktop (przez MCP):
Prompty są automatycznie dostępne w menu MCP jako "Prompts". AI rozpozna Twoje zapytanie i użyje odpowiedniego promptu.

### Najlepsze praktyki:

1. **Zacznij od prostych zapytań** - AI wybierze odpowiedni prompt
2. **Używaj nazw promptów** gdy wiesz czego potrzebujesz
3. **Dla operacji zapisu** - włącz "Enable Write Operations" w ustawieniach
4. **Dla destrukcyjnych operacji** (import, restore, cleanup) - AI poprosi o potwierdzenie
5. **Testuj na małych danych** przed operacjami masowymi

---

## Pokrycie funkcjonalności

### ✅ Pełne pokrycie 99 narzędzi:
- **36 narzędzi Read** - wszystkie objęte promptami
- **63 narzędzia Write/Action** - wszystkie objęte promptami + mechanizmy bezpieczeństwa

### Główne obszary:
- ✅ Produkty WooCommerce (wyszukiwanie, zarządzanie, SEO)
- ✅ Zamówienia i sprzedaż (analiza, obsługa, raporty)
- ✅ Klienci (segmentacja, CLV, churn, support)
- ✅ Magazyn (stany, reordering, optymalizacja)
- ✅ Marketing (kupony, promocje, ROI)
- ✅ Wysyłka i podatki (strefy, metody, compliance)
- ✅ Treść WordPress (posty, strony, SEO)
- ✅ Media (upload, organizacja, optymalizacja)
- ✅ Menu (nawigacja, UX)
- ✅ Użytkownicy (role, bezpieczeństwo, audyt)
- ✅ Dane (import, export, backup, restore)
- ✅ Raporty biznesowe (KPI, insights, rekomendacje)

---

## Bezpieczeństwo

### Operacje tylko do odczytu (Read):
- Bezpieczne, nie modyfikują danych
- Można używać bez obaw

### Operacje zapisu (Write):
- Wymagają włączenia "Enable Write Operations"
- AI zawsze pokazuje co zostanie zmienione
- Dla destrukcyjnych operacji: wymaga potwierdzenia
- Rekomenduje backupy przed krytycznymi operacjami

### ⚠️ Operacje wymagające szczególnej ostrożności:
- `migrate-data` (import, restore)
- `manage-media` (cleanup)
- `manage-users` (bulk_update, delete)
- Wszelkie masowe usuwanie

---

## Wsparcie

W razie problemów lub pytań:
- GitHub: https://github.com/jeden-/wooquant
- Issues: https://github.com/jeden-/wooquant/issues

**Wersja dokumentacji:** 1.1.9  
**Data aktualizacji:** 2025-01-06

