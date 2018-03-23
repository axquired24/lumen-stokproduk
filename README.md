# lumen-stokproduk
Stock Monitor using Lumen (Backend Service)

# How To | Backend
- cp `.env.example` to `.env` | Config Database Environtment
- run `composer install`
- run `php artisan migrate --seed`
- run backend - point to `/public/index.php`

# How To | Frontend
- cp `frontend/js/config.example.js` to `frontend/js/config.js` | Config Backend api url
- run frontend - point to `frontend/index.html`