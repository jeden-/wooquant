#!/bin/bash

# Skrypt pomocniczy do publikacji WooQuant na GitHub
# Autor: @jeden-
# Data: 2025-01-05

set -e  # Zatrzymaj na błędzie

echo "🚀 WooQuant - Publikacja na GitHub"
echo "=================================="
echo ""

# Kolory dla outputu
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# 1. Sprawdź czy jesteśmy w odpowiednim katalogu
if [ ! -f "mcp-for-woocommerce.php" ]; then
    echo -e "${RED}❌ Błąd: Uruchom skrypt z katalogu pluginu!${NC}"
    exit 1
fi

echo -e "${BLUE}📁 Katalog: $(pwd)${NC}"
echo ""

# 2. Sprawdź czy README jest gotowy
if [ -f "README-GIT.md" ] && [ ! -f "README.md" ]; then
    echo -e "${YELLOW}📝 Zmieniam nazwę README-GIT.md → README.md${NC}"
    mv README-GIT.md README.md
fi

# 3. Sprawdź czy istnieją wymagane pliki
REQUIRED_FILES=("README.md" "LICENSE" "CHANGELOG.md" ".gitignore")
for file in "${REQUIRED_FILES[@]}"; do
    if [ ! -f "$file" ]; then
        echo -e "${RED}❌ Brak pliku: $file${NC}"
        exit 1
    fi
done

echo -e "${GREEN}✅ Wszystkie wymagane pliki istnieją${NC}"
echo ""

# 4. Sprawdź składnię PHP
echo -e "${BLUE}🔍 Sprawdzam składnię PHP...${NC}"
PHP_ERRORS=$(find includes -name "*.php" -exec php -l {} \; 2>&1 | grep -i "error" || true)

if [ -n "$PHP_ERRORS" ]; then
    echo -e "${RED}❌ Znaleziono błędy składniowe PHP:${NC}"
    echo "$PHP_ERRORS"
    exit 1
fi

echo -e "${GREEN}✅ Składnia PHP poprawna${NC}"
echo ""

# 5. Sprawdź czy node_modules i vendor są zignorowane
echo -e "${BLUE}🧹 Sprawdzam .gitignore...${NC}"
if ! grep -q "node_modules" .gitignore; then
    echo -e "${YELLOW}⚠️  Dodaję node_modules do .gitignore${NC}"
    echo "node_modules/" >> .gitignore
fi

if ! grep -q "vendor" .gitignore; then
    echo -e "${YELLOW}⚠️  Dodaję vendor do .gitignore${NC}"
    echo "vendor/" >> .gitignore
fi

echo -e "${GREEN}✅ .gitignore skonfigurowany${NC}"
echo ""

# 6. Zbuduj produkcyjną wersję
echo -e "${BLUE}🔨 Buduję produkcyjną wersję...${NC}"

if [ -d "node_modules" ]; then
    echo "   Uruchamiam npm run build..."
    npm run build > /dev/null 2>&1 || {
        echo -e "${RED}❌ Błąd podczas npm run build${NC}"
        exit 1
    }
else
    echo -e "${YELLOW}⚠️  Brak node_modules - pomiń npm build${NC}"
fi

echo -e "${GREEN}✅ Build zakończony${NC}"
echo ""

# 7. Inicjalizuj Git (jeśli jeszcze nie)
if [ ! -d ".git" ]; then
    echo -e "${BLUE}📦 Inicjalizuję Git...${NC}"
    git init
    git branch -M main
    echo -e "${GREEN}✅ Git zainicjalizowany${NC}"
else
    echo -e "${GREEN}✅ Git już zainicjalizowany${NC}"
fi
echo ""

# 8. Sprawdź czy remote jest ustawiony
REMOTE=$(git remote -v | grep origin || true)
if [ -z "$REMOTE" ]; then
    echo -e "${YELLOW}🔗 Dodaję remote origin...${NC}"
    read -p "   Podaj URL repozytorium (np. https://github.com/jeden-/wooquant.git): " REPO_URL
    git remote add origin "$REPO_URL"
    echo -e "${GREEN}✅ Remote dodany: $REPO_URL${NC}"
else
    echo -e "${GREEN}✅ Remote już ustawiony:${NC}"
    echo "$REMOTE"
fi
echo ""

# 9. Dodaj wszystkie pliki
echo -e "${BLUE}📝 Dodaję pliki do Git...${NC}"
git add .

# 10. Pokaż status
echo -e "${BLUE}📊 Status Git:${NC}"
git status --short

echo ""
echo -e "${YELLOW}════════════════════════════════════${NC}"
echo -e "${YELLOW}⚠️  UWAGA: Sprawdź czy wszystko OK!${NC}"
echo -e "${YELLOW}════════════════════════════════════${NC}"
echo ""

# 11. Pytaj o kontynuację
read -p "Czy chcesz zrobić commit i push? (tak/nie): " ANSWER

if [ "$ANSWER" != "tak" ]; then
    echo -e "${YELLOW}❌ Anulowano. Możesz ręcznie zrobić:${NC}"
    echo "   git commit -m 'Twoja wiadomość'"
    echo "   git push -u origin main"
    exit 0
fi

# 12. Commit
echo ""
echo -e "${BLUE}💾 Tworzę commit...${NC}"

COMMIT_MSG="Initial commit: WooQuant v1.2.0 - Extended MCP for WooCommerce

Based on https://github.com/iOSDevSK/mcp-for-woocommerce v1.0.0
Original author: @iOSDevSK

Major additions:
- Full Polish language support (i18n)
- User permissions management system
- Extended admin panel with filtering
- 38+ PHP syntax fixes
- Updated documentation

See CHANGELOG.md for full list of changes."

git commit -m "$COMMIT_MSG"
echo -e "${GREEN}✅ Commit utworzony${NC}"
echo ""

# 13. Push
echo -e "${BLUE}⬆️  Wysyłam na GitHub...${NC}"
git push -u origin main

echo ""
echo -e "${GREEN}✅ Push zakończony!${NC}"
echo ""

# 14. Tag version
read -p "Czy chcesz utworzyć tag v1.2.0? (tak/nie): " TAG_ANSWER

if [ "$TAG_ANSWER" == "tak" ]; then
    echo -e "${BLUE}🏷️  Tworzę tag v1.2.0...${NC}"
    
    TAG_MSG="WooQuant v1.2.0 - Extended MCP for WooCommerce

First public release with:
- Full Polish language support
- User permissions management
- Extended admin panel
- 38+ PHP syntax fixes

Based on MCP for WooCommerce v1.0.0 by @iOSDevSK"
    
    git tag -a v1.2.0 -m "$TAG_MSG"
    git push origin v1.2.0
    
    echo -e "${GREEN}✅ Tag v1.2.0 utworzony i wysłany${NC}"
fi

echo ""
echo -e "${GREEN}════════════════════════════════════${NC}"
echo -e "${GREEN}🎉 GOTOWE!${NC}"
echo -e "${GREEN}════════════════════════════════════${NC}"
echo ""
echo "📍 Twoje repozytorium:"
git remote get-url origin
echo ""
echo "📝 Następne kroki:"
echo "   1. Przejdź na GitHub"
echo "   2. Edytuj opis repozytorium"
echo "   3. Dodaj tags/topics"
echo "   4. (Opcjonalnie) Stwórz Release"
echo "   5. (Opcjonalnie) Poinformuj @iOSDevSK"
echo ""
echo "📖 Zobacz PUBLISHING-TO-GITHUB.md dla szczegółów"
echo ""




