<?php
// config/quickzoom.php

return [
    /*
    |--------------------------------------------------------------------------
    | Zoom API Credentials
    |--------------------------------------------------------------------------
    |
    | These values are required to authenticate with the Zoom API.
    | You can obtain these credentials from the Zoom Marketplace.
    |
    */
    'api_key' => env('ZOOM_API_KEY', ''),
    'api_secret' => env('ZOOM_API_SECRET', ''),
    
    /*
    |--------------------------------------------------------------------------
    | Zoom API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the Zoom API.
    |
    */
    'base_url' => env('ZOOM_BASE_URL', 'https://api.zoom.us/v2'),
    
    /*
    |--------------------------------------------------------------------------
    | JWT Token Expiration
    |--------------------------------------------------------------------------
    |
    | How long the JWT token is valid for in seconds.
    |
    */
    'token_expiration' => env('ZOOM_TOKEN_EXPIRATION', 60 * 60), // 1 hour
];