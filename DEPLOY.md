# Production Deployment Checklist

## First-time server setup

```bash
# 1. Clone and install
git clone <repo> && cd helloalibagv2
composer install --no-dev --optimize-autoloader
npm install && npm run build

# 2. Configure environment
cp .env.example .env
php artisan key:generate          # generates fresh APP_KEY
nano .env                         # fill in DB, MAIL, ADMIN_PREFIX, etc.

# 3. Database
php artisan migrate --force

# 4. Storage symlink (REQUIRED — images 404 without this)
php artisan storage:link

# 5. Cache for production performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Queue worker (required for emails)
php artisan queue:work --daemon --tries=3 &
# or use Supervisor — see below
```

## Environment variables that MUST be set in production

| Variable | Required | Notes |
|----------|----------|-------|
| `APP_KEY` | yes | Generate with `php artisan key:generate` |
| `APP_ENV` | yes | `production` |
| `APP_DEBUG` | yes | `false` |
| `APP_URL` | yes | `https://helloalibaug.com` |
| `DB_HOST` / `DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` | yes | |
| `MAIL_MAILER` | yes | `smtp` or `mailgun` |
| `MAIL_HOST` / `MAIL_PORT` / `MAIL_USERNAME` / `MAIL_PASSWORD` | yes | |
| `SESSION_DRIVER` | yes | `database` |
| `QUEUE_CONNECTION` | yes | `database` |
| `ADMIN_PREFIX` | yes | Choose a non-obvious path (not `admin`) |

## Supervisor config for queue worker

```ini
[program:helloalibaug-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/helloalibagv2/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/helloalibagv2/storage/logs/worker.log
```

## Ongoing deployments

```bash
git pull
composer install --no-dev --optimize-autoloader
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo supervisorctl restart helloalibaug-worker:*
```

## Admin 2FA setup

After first login as admin, you will be redirected to `/2fa/setup`. Scan the QR code with Google Authenticator or Authy. You must complete setup before accessing the admin panel.
