<?php
require __DIR__ . '/../vendor/autoload.php';

\Midtrans\Config::$isProduction = false;       // sandbox mode
\Midtrans\Config::$serverKey   = 'SB-Mid-server-LZH61iLRE0Gf_63bcNUYcboL';  // <-- pakai yang diawali "SB-Mid-server-..."
\Midtrans\Config::$clientKey   = 'SB-Mid-client-9-zjXr5NktVjKGLQ';  // <-- hanya digunakan di front-end Snap.js
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds       = true;
