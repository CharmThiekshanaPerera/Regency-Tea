# Getting the images onto your server

## Why they weren't showing

Every image URL was built with `Storage::url()`, which produces `/storage/media/...`. That path only works if:

1. `php artisan storage:link` has been run, **and**
2. your document root actually follows the symlink.

On shared/cPanel hosting one or both usually fails — the symlink can't be created, or the docroot is `public_html` and never sees it. The result is exactly what you saw: the site loads, every image 404s.

**Fixed.** Images are now served from `public/media/…` as ordinary files. No symlink, no storage disk, nothing to configure. `App\Support\Media` owns URL construction in one place.

---

## What has to reach the server

The full WordPress uploads folder is **21,394 files / 3.85 GB** — mostly thumbnails WordPress generated and files nothing references. You do **not** need it.

| | Files | Size |
|---|---|---|
| Full `wp-content/uploads` | 21,394 | 3.85 GB |
| **What the site actually needs** | **1,599** | **802 MB** |
| …of which catalogue PDFs | 2 | 80 MB |

So: **802 MB**, or **722 MB** if you upload the PDFs separately.

---

## Path A — build the bundle locally, upload once (recommended)

You have the backup on your Windows machine, so do the copy there and ship a single archive.

### 1. On your machine

```bash
cd "D:\Phyxle Web Projects\Regency Teas\regency-laravel"

# copies the 1,599 referenced originals into public/media
php artisan wp:media

# confirms every DB reference resolves to a real file
php artisan media:verify

# builds storage/app/regency-media.tar.gz
php artisan media:bundle --manifest
```

`wp:media` reads `WP_UPLOADS_PATH` from `.env` (defaults to `../_backup/wp-content/uploads`) and `../discovery/media-files.csv`. It skips `-600x600` derivatives and plugin cache folders automatically.

### 2. Upload

```bash
scp storage/app/regency-media.tar.gz user@your-server:/tmp/
```

### 3. On the server

```bash
cd /path/to/site/public
tar -xzf /tmp/regency-media.tar.gz          # extracts into public/media/
cd /path/to/site
php artisan media:verify                    # must report 0 missing
rm /tmp/regency-media.tar.gz
```

`media:verify` walks every product, gallery and post image reference in the database and checks the file exists. **If it reports 0 missing, images will render.**

---

## Path B — rsync directly

If you'd rather skip the archive:

```bash
php artisan wp:media                        # populate public/media locally

rsync -avz --progress \
      public/media/ \
      user@your-server:/path/to/site/public/media/
```

`rsync` resumes, so a dropped connection isn't fatal. Add `--exclude='*.pdf'` to defer the 80 MB of catalogues.

---

## Path C — run the import on the server

Only if the backup is already on the server (3.85 GB upload — usually not worth it):

```bash
# in .env on the server
WP_UPLOADS_PATH=/absolute/path/to/_backup/wp-content/uploads

php artisan wp:media
php artisan media:verify
```

---

## Permissions

```bash
chown -R www-data:www-data public/media     # or your web user
find public/media -type d -exec chmod 755 {} \;
find public/media -type f -exec chmod 644 {} \;
```

`public/media` is in `.gitignore` — ship it with the bundle, not through git.

---

## Verifying

```bash
php artisan media:verify
```

```
public/media contains 1,599 files (802 MB)

  image references checked      1,358
  resolved to a real file       1,358
  MISSING                           0
  products with no image set        0
  posts with no image set           0

All image references resolve. Images will render.
```

Then spot-check in a browser:

- `https://your-site/media/2023/12/EAT-100TB.png` — should return the image directly
- any product page — main image, thumbnails, and hover swap
- `/products` — the grid
- `/media` — article thumbnails

---

## If images still 404

| Symptom | Cause | Fix |
|---|---|---|
| `media:verify` says files are missing | bundle not extracted, or extracted to the wrong place | It must land at `public/media/2023/...`, not `public/media/media/2023/...`. Check with `ls public/media \| head` |
| Verify passes but browser 404s | docroot isn't `public/` | Point the vhost at `.../site/public`, or on cPanel symlink `public_html` → `public` |
| 403 Forbidden | permissions | See the chmod block above |
| Images load, OG previews don't | `APP_URL` wrong | OpenGraph and JSON-LD need absolute URLs — set `APP_URL=https://www.regencyteas.com` |
| Some product images missing only | those files weren't in the backup | `storage/logs/media-missing.txt` lists them. Last full check: **0 missing** |

---

## Storage paths are stored clean

The database holds `2023/12/EAT-100TB.png` — no prefix, no domain. `Media::normalise()` also accepts and repairs the older shapes, so nothing breaks if a row still has a legacy value:

| Stored value | Normalised |
|---|---|
| `2023/12/x.png` | `2023/12/x.png` |
| `media/2023/12/x.png` | `2023/12/x.png` |
| `/storage/media/2023/12/x.png` | `2023/12/x.png` |
| `http://regencyteas.com/wp-content/uploads/2023/12/x.png` | `2023/12/x.png` |
| `\2023\12\x.png` | `2023/12/x.png` |
| anything containing `..` | rejected |

---

## Optional: shrink the payload

605 MB of the 802 MB is PNG — product shots saved in the wrong format. Converting to WebP typically cuts that by 60–80%:

```bash
# on the server, after uploading
find public/media -name '*.png' -exec cwebp -q 82 {} -o {}.webp \;
```

Worth doing before launch, but not required to get images working.
