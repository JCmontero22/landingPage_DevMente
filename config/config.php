<?php

// Load environment variables from .env file
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            putenv(trim($key) . '=' . trim($value));
        }
    }
}

// SMTP Configuration from environment variables
define('SMTP_HOST', getenv('SMTP_HOST') ?: 'mail.jmdevmente.com');
define('SMTP_USERNAME', getenv('SMTP_USERNAME') ?: '');
define('SMTP_PASSWORD', getenv('SMTP_PASSWORD') ?: '');
define('SMTP_PORT', getenv('SMTP_PORT') ?: 465);
define('SMTP_FROM', getenv('SMTP_FROM') ?: '');
define('SMTP_FROM_NAME', getenv('SMTP_FROM_NAME') ?: 'JMontero Freelance');
define('SMTP_ADDRESS', getenv('SMTP_ADDRESS') ?: '');

// Application Configuration
define('PRODUCTION', true); // Set to false for development/debugging