<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Panel URL Prefix
    |--------------------------------------------------------------------------
    |
    | The path segment the admin panel lives under (e.g. "ha-control-2026" →
    | https://site.com/ha-control-2026). This is read via config() rather than
    | env() directly in the routes file: once `config:cache` runs, .env is no
    | longer loaded, so env() in routes/web.php would return null and the admin
    | routes would silently fall back to the default prefix — breaking links.
    |
    */

    'prefix' => env('ADMIN_PREFIX', 'ha-control-2026'),

];
