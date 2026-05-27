#!/usr/bin/env bash
#
# Hello Alibaug — production deploy script
# Run from the project root (the folder that contains "artisan"):
#
#     bash deploy.sh
#
# What it does (in order):
#   1. Puts the site in maintenance mode
#   2. Syncs code by HARD-resetting to the remote branch
#      (discards local changes to tracked files — this is what makes the
#       deploy conflict-proof against generated files like sitemap.xml /
#       public/build. Your .env and .htaccess are untracked, so they're safe.)
#   3. Installs PHP dependencies (composer)
#   4. Builds frontend assets ONLY if npm exists on the server
#      (assets are committed to git, so this is normally skipped)
#   5. Runs database migrations
#   6. Clears and re-warms caches + ensures the storage symlink
#   7. Brings the site back up (also runs automatically if the script fails)
#
set -euo pipefail

# ─── Config (edit if your paths/branch change) ──────────────────────────────
PHP="/opt/alt/php83/usr/bin/php"
BRANCH="master"
# ────────────────────────────────────────────────────────────────────────────

cd "$(dirname "$0")"

# Must be run from the Laravel project root.
if [ ! -f artisan ]; then
    echo "ERROR: 'artisan' not found. Run this from the project root." >&2
    exit 1
fi
if [ ! -f .env ]; then
    echo "ERROR: .env not found. Refusing to deploy without it." >&2
    exit 1
fi

# Always try to bring the site back up, even if a step fails midway.
trap '"$PHP" artisan up >/dev/null 2>&1 || true' EXIT

echo "==> [1/7] Maintenance mode ON"
"$PHP" artisan down --retry=15 || true

echo "==> [2/7] Syncing code to origin/$BRANCH"
git fetch origin
git reset --hard "origin/$BRANCH"

echo "==> [3/7] Installing composer dependencies"
if command -v composer >/dev/null 2>&1; then
    COMPOSER_CMD="composer"
elif [ -f composer.phar ]; then
    COMPOSER_CMD="$PHP composer.phar"
else
    echo "    composer not found — downloading composer.phar"
    curl -sS https://getcomposer.org/installer | "$PHP"
    COMPOSER_CMD="$PHP composer.phar"
fi
$COMPOSER_CMD install --no-dev --optimize-autoloader --no-interaction

echo "==> [4/7] Frontend assets"
if command -v npm >/dev/null 2>&1; then
    echo "    npm found — building assets"
    npm ci --no-audit --no-fund
    npm run build
else
    echo "    npm not on server — using committed build (skipping)"
fi

echo "==> [5/7] Running migrations"
"$PHP" artisan migrate --force

echo "==> [6/7] Refreshing caches"
"$PHP" artisan optimize:clear
"$PHP" artisan storage:link 2>/dev/null || true
"$PHP" artisan config:cache
"$PHP" artisan view:cache
# route:cache fails if any route uses a closure — fall back to clearing it.
"$PHP" artisan route:cache || "$PHP" artisan route:clear

echo "==> [7/7] Maintenance mode OFF"
"$PHP" artisan up

echo ""
echo "✅ Deploy complete."
