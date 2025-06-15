<?php
require __DIR__ . '/../vendor/autoload.php';

\Midtrans\Config::$isProduction = false;       // sandbox mode
\Midtrans\Config::$serverKey   = 'SB-Mid-server-IVuY1hF_u9d4vb_9TN4k97Zz';  // <-- pakai yang diawali "SB-Mid-server-..."
\Midtrans\Config::$clientKey   = 'SB-Mid-client-XlsIlxgERdS2dDHc';  // <-- hanya digunakan di front-end Snap.js
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds       = true;
