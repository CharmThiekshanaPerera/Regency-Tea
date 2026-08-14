# Deploying to GoDaddy shared cPanel hosting

## Layout

GoDaddy's shared hosting locks the web docroot to `~/public_html` and won't
let it point at a Laravel app's `/public` folder directly. To work around
that, the app and the docroot live in two separate, sibling folders on the
server:

```
~/regency_app/     the Laravel app itself — git repo, vendor/, storage/, .env
~/public_html/     the web docroot — gets a copy of public/'s contents plus
                    a rewritten index.php that reaches up into ../regency_app
```

`~/public_html` is not a symlink and is not `regency_app/public` — it is a
plain copy, refreshed by `deploy/deploy.sh` on every deploy.

## One-time server setup

1. Clone the repo to `~/regency_app`.
2. Create `~/regency_app/.env` by hand (copy from `.env.example` and fill in
   real values). **`.env` is never committed** — it's server-managed and
   `.gitignore`d, so a fresh clone will not have one.
3. In cPanel's **PHP Selector / MultiPHP Manager**, set the app's PHP version
   to 8.4 and enable these extensions:
   - `pdo_mysql`
   - `mysqli`
   - `intl`
   - `fileinfo`
   - `zip`
   - `mbstring`
   - `bcmath`
   - `gd`
4. Confirm `composer.phar` is present in `~/regency_app` (GoDaddy shared
   hosting typically doesn't have a global `composer` command — the vendored
   `composer.phar` is what `deploy.sh` calls).
5. Run `bash deploy/deploy.sh` once to populate `~/public_html` for the
   first time.

## PHP CLI version

GoDaddy's default `php` on `$PATH` is often an older version than the app
targets. Always use the alt-php 8.4 binary explicitly:

```bash
alias php='/opt/alt/php84/usr/bin/php'
```

This is aliased at the top of `deploy/deploy.sh` for you, but the alias is
**session-only** — if you run any `php`/`artisan` commands by hand outside
the script, set it again first (or re-source it) in that SSH session.

## Deploying an update

After merging to `main` on GitHub:

```bash
ssh you@yourserver
cd ~/regency_app
bash deploy/deploy.sh
```

The script, in order:

1. Aliases `php` to alt-php 8.4 (session-only).
2. `git pull origin main`
3. `php composer.phar install --no-dev --optimize-autoloader`
4. `php artisan migrate --force`
5. `php artisan config:cache && php artisan route:cache && php artisan view:cache`
6. `php artisan storage:link`
7. Copies `~/regency_app/public/.` into `~/public_html/`
8. Overwrites `~/public_html/index.php` and `~/public_html/.htaccess` with
   the production versions from `deploy/`
9. Prints `DONE — verify https://regencyteas.com`

## What the script deliberately does NOT do

- **Does not touch `.env`.** It's created once by hand on the server (step 2
  above) and is never committed or overwritten by a deploy.
- **Does not run `npm`/`vite`.** Front-end assets (`public/build`) are
  expected to already be built and either committed or uploaded separately
  — the server has no Node toolchain assumption.
- **Does not delete anything in `~/public_html`.** It only copies files in
  and overwrites `index.php`/`.htaccess`. Files left over from a previous
  release that no longer exist in `public/` are not cleaned up.

## Why `deploy/index.production.php` exists instead of editing `public/index.php`

`public/index.php` is Laravel's standard front controller — its
`../vendor` and `../bootstrap` paths are correct for local development and
CI, and must stay that way. On the server, though, `~/public_html`'s
`index.php` needs to reach one level up and then into a **sibling** folder
(`../regency_app/vendor`, `../regency_app/bootstrap`) rather than a direct
parent. `deploy/index.production.php` is a separate file with those paths
rewritten (including the maintenance-mode check, which also points at
`../regency_app/storage`); `deploy.sh` copies it over `~/public_html/index.php`
on every deploy so the canonical `public/index.php` never has to change.

Similarly, `deploy/.htaccess` is a copy of the project's real
`public/.htaccess` (including the `/media` route rewrite rule specific to
this app) — kept here so a deploy always installs the same rewrite rules
that are actually in the repo, rather than a generic stock template.
