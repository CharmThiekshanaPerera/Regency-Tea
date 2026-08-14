#!/usr/bin/env bash
#
# deploy/deploy.sh
#
# Run this manually over SSH on the GoDaddy cPanel server, AFTER pushing to
# GitHub. It is not wired into any CI/CD — it is a human-run runbook script.
#
# Server layout (see deploy/README.md for the full explanation):
#   ~/regency_app   the Laravel app (git repo, vendor/, storage/, .env)
#   ~/public_html   the web docroot GoDaddy forces you to use — gets a copy
#                   of the app's public/ contents plus a rewritten index.php
#
# This script does NOT:
#   - touch .env — it's created once by hand on the server and never committed
#   - run npm/vite — front-end assets (public/build) are pre-built and
#     shipped via git or uploaded separately, not built on the server
#   - delete anything under ~/public_html — it only copies public/ contents
#     into it and overwrites index.php / .htaccess with the production
#     versions. Stale files left over from a previous deploy are not removed.
#
# Usage:
#   ssh you@yourserver
#   cd ~/regency_app
#   bash deploy/deploy.sh

set -euo pipefail

# GoDaddy shared hosting ships several PHP CLI binaries; the default `php`
# on PATH is often an old version. Alias to the alt-php 8.4 binary that
# matches this app's requirement. This alias is session-only — it does not
# persist to ~/.bashrc and must be set again (or re-sourced) in every new
# SSH session that runs this script.
alias php='/opt/alt/php84/usr/bin/php'
shopt -s expand_aliases

cd ~/regency_app

echo "==> Pulling latest code from GitHub"
git pull origin main

echo "==> Installing PHP dependencies (production, no dev)"
php composer.phar install --no-dev --optimize-autoloader

echo "==> Running database migrations"
php artisan migrate --force

echo "==> Caching config, routes, and views"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Re-linking storage"
php artisan storage:link

echo "==> Publishing built assets to the web docroot"
# GoDaddy's docroot (~/public_html) is a separate sibling folder, not
# ~/regency_app/public, so the contents have to be copied out to it on
# every deploy. This copies (overwrites/adds) but does not delete anything
# already sitting in public_html.
cp -r ~/regency_app/public/. ~/public_html/

echo "==> Installing the production entry point and .htaccess"
# public/index.php itself is never modified — its ../vendor and
# ../bootstrap paths are correct for local dev. These two production-only
# copies (with paths rewritten to reach into ../regency_app) are what
# actually run on the server.
cp ~/regency_app/deploy/index.production.php ~/public_html/index.php
cp ~/regency_app/deploy/.htaccess ~/public_html/.htaccess

echo ""
echo "DONE — verify https://regencyteas.com"
