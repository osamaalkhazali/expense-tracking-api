<?php

/*
 * Firebase Job Test
 *
 * To run this file:
 * php test-firebase-job.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\FirebaseService;

$firebase = app(FirebaseService::class);

echo "🔥 Sending test event to Firebase Queue...\n\n";

$firebase->logEvent('test_event', [
  'user_id' => 123,
  'action' => 'test',
  'timestamp' => now()->toDateTimeString()
]);

echo "✅ Event sent to Queue successfully!\n";
echo "📋 Check jobs table in database\n";
echo "🚀 To run Queue Worker: php artisan queue:work\n\n";
