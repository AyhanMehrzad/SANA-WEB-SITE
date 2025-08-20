<?php
// Fill these after deploy (safe placeholders now)
return [
    // Frontend URLs allowed to call this API (add your domain)
    'allowed_origins' => [
        'https://your-frontend-domain.com',
        'http://localhost',
        'http://localhost:5173',
        '*', // keep during development; remove in production if you want strict CORS
    ],

    // Optional MySQL connection (leave placeholders to skip DB usage)
    'db' => [
        'dsn'  => 'mysql:host=YOUR_DB_HOST;dbname=YOUR_DB_NAME;charset=utf8mb4',
        'user' => 'YOUR_DB_USER',
        'pass' => 'YOUR_DB_PASSWORD',
    ],

    // Simple admin token for protected endpoints (e.g., gallery upload)
    'admin_token' => 'CHANGE_ME_TO_A_LONG_RANDOM_TOKEN',

    // Email notifications for contact messages (uses PHP mail() by default)
    'notify_email_to'   => 'you@yourdomain.com',
    'notify_email_from' => 'no-reply@yourdomain.com',
];


