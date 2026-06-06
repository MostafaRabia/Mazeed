#!/bin/bash

# Deployment Validation Script for Render.com
# This script checks if the project is ready for deployment

echo "🔍 Checking Mazeed project for Render.com deployment readiness..."
echo ""

PASSED=0
FAILED=0

# Check 1: Composer dependencies
echo "1. Checking Composer dependencies..."
if [ -f "composer.json" ]; then
    echo "   ✓ composer.json found"
    PASSED=$((PASSED+1))
else
    echo "   ✗ composer.json not found"
    FAILED=$((FAILED+1))
fi

# Check 2: Package.json
echo "2. Checking NPM configuration..."
if [ -f "package.json" ]; then
    echo "   ✓ package.json found"
    PASSED=$((PASSED+1))
else
    echo "   ✗ package.json not found"
    FAILED=$((FAILED+1))
fi

# Check 3: .env file
echo "3. Checking environment configuration..."
if [ -f ".env" ]; then
    echo "   ✓ .env file found"
    if grep -q "APP_KEY=" .env; then
        echo "   ✓ APP_KEY is set"
        PASSED=$((PASSED+1))
    else
        echo "   ✗ APP_KEY is not set"
        FAILED=$((FAILED+1))
    fi
else
    echo "   ✗ .env file not found"
    FAILED=$((FAILED+1))
fi

# Check 4: Render configuration
echo "4. Checking Render configuration..."
if [ -f "render.yaml" ]; then
    echo "   ✓ render.yaml found"
    PASSED=$((PASSED+1))
else
    echo "   ✗ render.yaml not found"
    FAILED=$((FAILED+1))
fi

# Check 4b: Docker configuration
echo "4b. Checking Docker configuration..."
if [ -f "Dockerfile" ]; then
    echo "   ✓ Dockerfile found"
    if [ -f ".dockerignore" ]; then
        echo "   ✓ .dockerignore found"
        PASSED=$((PASSED+1))
    else
        echo "   ✗ .dockerignore not found"
        FAILED=$((FAILED+1))
    fi
else
    echo "   ✗ Dockerfile not found"
    FAILED=$((FAILED+1))
fi

# Check 4c: Docker Compose for local development
echo "4c. Checking docker-compose.yml..."
if [ -f "docker-compose.yml" ]; then
    echo "   ✓ docker-compose.yml found (local development ready)"
    PASSED=$((PASSED+1))
else
    echo "   ⚠ docker-compose.yml not found (optional for local development)"
fi

# Check 5: Build script
echo "5. Checking build script..."
if [ -f "build.sh" ]; then
    echo "   ✓ build.sh found"
    if [ -x "build.sh" ]; then
        echo "   ✓ build.sh is executable"
        PASSED=$((PASSED+1))
    else
        echo "   ⚠ build.sh is not executable (will fix)"
        chmod +x build.sh
    fi
else
    echo "   ✗ build.sh not found"
    FAILED=$((FAILED+1))
fi

# Check 6: GitHub repository
echo "6. Checking Git repository..."
if git rev-parse --git-dir > /dev/null 2>&1; then
    echo "   ✓ Git repository found"
    if git remote get-url origin | grep -q github.com; then
        echo "   ✓ GitHub remote configured"
        PASSED=$((PASSED+1))
    else
        echo "   ✗ GitHub remote not found"
        FAILED=$((FAILED+1))
    fi
else
    echo "   ✗ Git repository not found"
    FAILED=$((FAILED+1))
fi

# Check 7: Public directory
echo "7. Checking public directory..."
if [ -f "public/index.php" ]; then
    echo "   ✓ public/index.php found"
    PASSED=$((PASSED+1))
else
    echo "   ✗ public/index.php not found"
    FAILED=$((FAILED+1))
fi

# Check 8: Storage directory
echo "8. Checking storage directory..."
if [ -d "storage" ]; then
    echo "   ✓ storage directory found"
    if [ -d "storage/app/public" ]; then
        echo "   ✓ storage/app/public directory found"
        PASSED=$((PASSED+1))
    else
        echo "   ✗ storage/app/public directory not found"
        mkdir -p storage/app/public
    fi
else
    echo "   ✗ storage directory not found"
    FAILED=$((FAILED+1))
fi

# Check 9: Database configuration
echo "9. Checking database configuration..."
if grep -q "DB_CONNECTION=mysql" .env; then
    echo "   ✓ MySQL is configured"
    PASSED=$((PASSED+1))
else
    echo "   ✗ MySQL configuration not found"
    FAILED=$((FAILED+1))
fi

# Check 10: LinkedIn OAuth
echo "10. Checking LinkedIn OAuth..."
if grep -q "LINKEDIN_CLIENT_ID" .env; then
    echo "   ✓ LinkedIn OAuth is configured"
    PASSED=$((PASSED+1))
else
    echo "   ✗ LinkedIn OAuth not configured"
    FAILED=$((FAILED+1))
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Results: ✓ $PASSED passed, ✗ $FAILED failed"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

if [ $FAILED -eq 0 ]; then
    echo ""
    echo "✅ Project is ready for Docker deployment on Render.com!"
    echo ""
    echo "Next steps:"
    echo "1. Test locally: docker-compose up -d"
    echo "2. Push to GitHub: git push origin main"
    echo "3. Connect to Render.com dashboard"
    echo "4. Create a new Web Service with Docker runtime"
    echo "5. Set environment variables"
    echo "6. Deploy!"
    exit 0
else
    echo ""
    echo "❌ Please fix the issues above before deploying"
    exit 1
fi
