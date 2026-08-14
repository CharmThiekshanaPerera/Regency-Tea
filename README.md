# Regency Teas — Laravel 11 rebuild

Ceylon tea exporter site. Laravel 11 rebuild of the legacy WordPress site, deployed at [regencyteas.com](https://regencyteas.com).

For local environment setup, see [SETUP.md](SETUP.md). For bundling and uploading the product/post image library, see [DEPLOY-MEDIA.md](DEPLOY-MEDIA.md).

## Deployment (GoDaddy shared cPanel)

Full deploy scripts and a longer explanation of the layout live in
[`deploy/README.md`](deploy/README.md) and [`deploy/deploy.sh`](deploy/deploy.sh) —
this section is the quick-reference version.

### Server layout

- The Laravel app lives at `~/regency_app` (git repo, `vendor/`, `storage/`, `.env`).
- The web document root is a **separate sibling folder**, `~/public_html`.
  GoDaddy locks the docroot to this path — it cannot be pointed at the app's
  `/public` folder directly.
- `~/public_html` holds the *contents* of `public/`, plus an `index.php` whose
  bootstrap paths are rewritten to reach `../regency_app`. That file is
  [`deploy/index.production.php`](deploy/index.production.php) —
  **never edit `public/index.php` for this**; its stock `../vendor` /
  `../bootstrap` paths are correct for local dev and must stay that way.

### One-time setup

- **PHP version**: set to 8.4 via GoDaddy's dashboard (cPanel's own PHP
  Selector version dropdown is locked on this host — the version is managed
  in the hosting provider dashboard instead).
- **Required extensions**, enabled in cPanel → *Select PHP Version*:
  `pdo_mysql`, `mysqli`, `intl`, `fileinfo`, `zip`, `mbstring`, `bcmath`, `gd`
  — plus the usual `curl`, `openssl`, `dom`, `xml`, `tokenizer`, `ctype`,
  `phar`, `session`.
- **PHP CLI binary**: `/opt/cpanel/ea-php*` paths on this host are stubs —
  the working binary is `/opt/alt/php84/usr/bin/php`. Alias it per session:

  ```bash
  alias php='/opt/alt/php84/usr/bin/php'
  ```

  or append that line to `~/.bashrc` to persist it.
- **`.env`** is created manually on the server and is **never committed**.
  Required keys (values are placeholders — never write real ones here):

  ```ini
  APP_ENV=production
  APP_DEBUG=false
  APP_URL=https://regencyteas.com
  APP_KEY=                      # php artisan key:generate

  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=<DB_NAME>
  DB_USERNAME=<DB_USER>
  DB_PASSWORD=<DB_PASSWORD>
  ```

  On this host the database and user use **short names, without the cPanel
  account prefix** (e.g. `regency` / `rgcy`, not the usual
  `cpanelaccount_regency` form) — check the actual values in cPanel → MySQL
  Databases rather than assuming the prefixed convention.

### Standard deploy (code update)

```bash
ssh -i <SSH_KEY> <SSH_USER>@<SERVER_IP>
alias php='/opt/alt/php84/usr/bin/php'

cd ~/regency_app
git pull origin main
php composer.phar install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:clear && php artisan config:cache
php artisan route:cache
php artisan view:clear && php artisan view:cache
cp -r ~/regency_app/public/. ~/public_html/
cp ~/regency_app/deploy/index.production.php ~/public_html/index.php
```

`deploy/deploy.sh` automates this same sequence (plus `.htaccess` and
`storage:link`) — run `bash deploy/deploy.sh` from `~/regency_app` instead of
typing these out by hand.

### Front-end assets (IMPORTANT)

`public/build` is gitignored — compiled Vite assets do **not** travel
through git, and `npm` cannot run on this shared host. On every deploy that
changes CSS/JS, build locally and upload the compiled output:

```powershell
# local (PowerShell)
npm run build
scp -r -i <SSH_KEY> ".\public\build" <SSH_USER>@<SERVER_IP>:~/regency_app/public/build
```

```bash
# server
cp -r ~/regency_app/public/build ~/public_html/build
```

**Symptom if this is skipped:** HTTP 500 with `Vite manifest not found at
.../public/build/manifest.json`. Fix: upload the `build` folder as above,
then `php artisan view:clear`.

### Verification

- `grep regency_app ~/public_html/index.php` — must show the
  `../regency_app/...` bootstrap paths (confirms the production entry point
  is actually installed, not the stock one).
- Load <https://regencyteas.com> — should render fully styled.
- Check `~/regency_app/storage/logs/laravel.log` for errors.
- **Never leave `APP_DEBUG=true` in production.**

### Rollback

Full site and database backups are retained. Rolling back means putting the
previous release's files back into `~/public_html` (and `~/regency_app` if
the app code also needs reverting) and re-importing the corresponding
database dump — there is no automated rollback script.
