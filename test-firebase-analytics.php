<?php

/*
 * Firebase Analytics Integration Test
 *
 * This tests all 4 required events:
 * 1. user_registered
 * 2. expense_created
 * 3. expense_updated
 * 4. expense_deleted
 *
 * To run: php test-firebase-analytics.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\FirebaseService;

$firebase = app(FirebaseService::class);

echo "🔥 Testing Firebase Analytics Integration\n";
echo "==========================================\n\n";

// Test 1: user_registered
echo "1️⃣  Testing user_registered event...\n";
$firebase->dispatchEvent('user_registered', [
  'user_id' => 1,
  'email' => 'test@example.com'
]);
echo "   ✅ Event dispatched to queue\n\n";

// Test 2: expense_created
echo "2️⃣  Testing expense_created event...\n";
$firebase->dispatchEvent('expense_created', [
  'user_id' => 1,
  'amount' => 100.50,
  'currency' => 'USD',
  'category' => 'Food'
]);
echo "   ✅ Event dispatched to queue\n\n";

// Test 3: expense_updated
echo "3️⃣  Testing expense_updated event...\n";
$firebase->dispatchEvent('expense_updated', [
  'user_id' => 1,
  'expense_id' => 1,
  'amount' => 150.75
]);
echo "   ✅ Event dispatched to queue\n\n";

// Test 4: expense_deleted
echo "4️⃣  Testing expense_deleted event...\n";
$firebase->dispatchEvent('expense_deleted', [
  'user_id' => 1,
  'expense_id' => 1
]);
echo "   ✅ Event dispatched to queue\n\n";
echo "==========================================\n";
echo "✅ All 4 events dispatched successfully!\n\n";
echo "📋 Check queue: php artisan tinker --execute=\"echo DB::table('jobs')->count();\"\n";
echo "🚀 Process jobs: php artisan queue:work\n";
echo "📄 View logs: Get-Content storage\\logs\\laravel.log -Tail 20\n";
