# Pełna lista narzędzi MCP for WooCommerce

Ten dokument zawiera kompletną listę wszystkich dostępnych narzędzi MCP w wtyczce WooQuant (rozszerzona wersja mcp-for-woocommerce).

## Narzędzia tylko do odczytu (Read) - 36 narzędzi

### Produkty WooCommerce

1. **wc_products_search** - Główne narzędzie wyszukiwania produktów (uniwersalne dla wszystkich typów sklepów)
2. **wc_get_product** - Pobierz produkt po ID
3. **wc_get_product_variations** - Pobierz wszystkie warianty produktu
4. **wc_get_product_variation** - Pobierz konkretny wariant po ID
5. **wc_intelligent_search** - Zaawansowane inteligentne wyszukiwanie produktów
6. **wc_analyze_search_intent** - Analiza intencji wyszukiwania użytkownika
7. **wc_analyze_search_intent_helper** - Pomocnik do analizy intencji wyszukiwania
8. **wc_get_products_by_brand** - Produkty według marki
9. **wc_get_products_by_category** - Produkty według kategorii
10. **wc_get_products_by_attributes** - Produkty według atrybutów
11. **wc_get_products_filtered** - Produkty z wieloma filtrami
12. **wc_get_product_detailed** - Szczegółowe informacje o produkcie

### Kategorie, tagi i atrybuty

13. **wc_get_categories** - Lista kategorii produktów
14. **wc_get_tags** - Lista tagów produktów
15. **wc_get_product_attributes** - Definicje atrybutów produktów
16. **wc_get_product_attribute** - Atrybut po ID
17. **wc_get_attribute_terms** - Terminy atrybutów (np. Czerwony, Niebieski dla Koloru)

### Zamówienia

18. **wc_get_orders** - Lista zamówień WooCommerce
19. **wc_get_order** - Zamówienie po ID

### Recenzje

20. **wc_get_product_reviews** - Lista recenzji produktu
21. **wc_get_product_review** - Recenzja po ID

### Wysyłka i płatności

22. **wc_get_shipping_zones** - Strefy wysyłki
23. **wc_get_shipping_zone** - Strefa wysyłki po ID
24. **wc_get_shipping_methods** - Metody wysyłki
25. **wc_get_shipping_locations** - Lokalizacje wysyłki
26. **wc_get_payment_gateways** - Bramki płatności
27. **wc_get_payment_gateway** - Bramka płatności po ID

### Podatki i system

28. **wc_get_tax_classes** - Klasy podatkowe
29. **wc_get_tax_rates** - Stawki podatkowe
30. **wc_get_system_status** - Status systemu WooCommerce
31. **wc_get_system_tools** - Narzędzia systemowe

### Treść WordPress

32. **wordpress_posts_list** - Lista postów WordPress
33. **wordpress_posts_get** - Post po ID
34. **wordpress_pages_list** - Lista stron WordPress
35. **wordpress_pages_get** - Strona po ID

### Inne

36. **wordpress_site_info** - Informacje o witrynie WordPress

---

## Narzędzia zapisu (Write) - 63 narzędzia

**UWAGA:** Narzędzia Write są dostępne tylko po włączeniu opcji "Włącz operacje zapisu" w ustawieniach wtyczki.

### Produkty WooCommerce (4 narzędzia)

1. **wc_create_product** - Utwórz nowy produkt
2. **wc_update_product** - Aktualizuj istniejący produkt
3. **wc_delete_product** - Usuń produkt
4. **wc_bulk_update_products** - Masowa aktualizacja produktów

### Zamówienia WooCommerce (3 narzędzia)

5. **wc_create_order** - Utwórz nowe zamówienie
6. **wc_update_order_status** - Aktualizuj status zamówienia
7. **wc_add_order_note** - Dodaj notatkę do zamówienia

### Kategorie produktów (4 narzędzia)

8. **wc_create_category** - Utwórz kategorię
9. **wc_update_category** - Aktualizuj kategorię
10. **wc_delete_category** - Usuń kategorię
11. **wc_reorder_categories** - Zmień kolejność kategorii

### Tagi produktów (3 narzędzia)

12. **wc_create_tag** - Utwórz tag
13. **wc_update_tag** - Aktualizuj tag
14. **wc_delete_tag** - Usuń tag

### Atrybuty produktów (4 narzędzia)

15. **wc_create_attribute** - Utwórz atrybut globalny
16. **wc_update_attribute** - Aktualizuj atrybut
17. **wc_delete_attribute** - Usuń atrybut
18. **wc_add_attribute_terms** - Dodaj terminy do atrybutu

### Kupcy (3 narzędzia)

19. **wc_create_customer** - Utwórz klienta
20. **wc_update_customer** - Aktualizuj klienta
21. **wc_delete_customer** - Usuń klienta

### Kupony (3 narzędzia)

22. **wc_create_coupon** - Utwórz kupon
23. **wc_update_coupon** - Aktualizuj kupon
24. **wc_delete_coupon** - Usuń kupon

### Recenzje (4 narzędzia)

25. **wc_create_review** - Utwórz recenzję
26. **wc_update_review** - Aktualizuj recenzję
27. **wc_delete_review** - Usuń recenzję
28. **wc_approve_review** - Zatwierdź recenzję

### Operacje masowe (4 narzędzia)

29. **wc_bulk_create_products** - Masowe tworzenie produktów
30. **wc_bulk_delete_products** - Masowe usuwanie produktów
31. **wc_bulk_update_prices** - Masowa aktualizacja cen
32. **wc_bulk_update_stock** - Masowa aktualizacja stanu magazynowego

### Import/Eksport (4 narzędzia)

33. **wc_import_products_csv** - Import produktów z CSV
34. **wc_export_products_csv** - Eksport produktów do CSV
35. **wc_import_orders_csv** - Import zamówień z CSV
36. **wc_export_orders_csv** - Eksport zamówień do CSV

### Posty WordPress (4 narzędzia)

37. **wp_create_post** - Utwórz post
38. **wp_update_post** - Aktualizuj post
39. **wp_delete_post** - Usuń post
40. **wp_publish_post** - Opublikuj post

### Strony WordPress (3 narzędzia)

41. **wp_create_page** - Utwórz stronę
42. **wp_update_page** - Aktualizuj stronę
43. **wp_delete_page** - Usuń stronę

### Media WordPress (4 narzędzia)

44. **wp_upload_image** - Prześlij obraz
45. **wp_upload_file** - Prześlij plik
46. **wp_delete_media** - Usuń media
47. **wp_update_media_metadata** - Aktualizuj metadane mediów

### Użytkownicy WordPress (4 narzędzia)

48. **wp_create_user** - Utwórz użytkownika
49. **wp_update_user** - Aktualizuj użytkownika
50. **wp_delete_user** - Usuń użytkownika
51. **wp_change_user_role** - Zmień rolę użytkownika

### Menu WordPress (4 narzędzia)

52. **wp_create_menu** - Utwórz menu
53. **wp_add_menu_item** - Dodaj element menu
54. **wp_update_menu** - Aktualizuj menu
55. **wp_delete_menu** - Usuń menu

### Ustawienia (4 narzędzia)

56. **wc_update_settings** - Aktualizuj ustawienia WooCommerce
57. **wp_update_settings** - Aktualizuj ustawienia WordPress
58. **wc_update_shipping_zone** - Aktualizuj strefę wysyłki
59. **wc_update_payment_gateway** - Aktualizuj bramkę płatności

### Backup i przywracanie (4 narzędzia)

60. **wc_backup_products** - Kopia zapasowa produktów
61. **wc_restore_products** - Przywróć produkty z kopii
62. **wp_backup_content** - Kopia zapasowa treści WordPress
63. **wp_restore_content** - Przywróć treść z kopii

---

## Podsumowanie

- **Narzędzia Read:** 36
- **Narzędzia Write:** 63
- **Razem:** 99 narzędzi MCP

## Typy funkcjonalności

- **read** - Tylko odczyt (36 narzędzi)
- **write** - Zapis/edycja/usuwanie (63 narzędzia)

## Uwagi

1. Wszystkie narzędzia Write wymagają włączenia opcji "Włącz operacje zapisu" w ustawieniach wtyczki.
2. Narzędzia Write wymagają odpowiednich uprawnień użytkownika (np. `manage_woocommerce`, `edit_posts`).
3. Niektóre narzędzia Write mogą być destrukcyjne (np. `wc_delete_product`, `wp_delete_user`) - używaj z ostrożnością.

