# 🔥 Firebase Queue Integration

## نظرة عامة

تم إعداد نظام Firebase لإرسال الأحداث (Events) بشكل غير متزامن باستخدام Laravel Queue.

---

## ✅ ما تم إنجازه

### 1. Firebase Service

-   ملف: `app/Services/FirebaseService.php`
-   الدوال:
    -   `logEvent()` - إرسال حدث إلى الـ Queue (غير متزامن)
    -   `logEventSync()` - إرسال حدث مباشرة (متزامن)

### 2. Firebase Job

-   ملف: `app/Jobs/LogFirebaseEventJob.php`
-   يعمل في الخلفية لإرسال الأحداث إلى Firebase
-   يحتوي على معالجة أخطاء وتسجيل logs

### 3. إعدادات Queue

-   **QUEUE_CONNECTION**: `database`
-   جداول الـ Queue موجودة في قاعدة البيانات

---

## 🚀 كيفية الاستخدام

### في الـ Controller:

```php
use App\Services\FirebaseService;

class ExpenseController extends Controller
{
    public function __construct(
        private FirebaseService $firebase
    ) {}

    public function store(Request $request)
    {
        // ... إنشاء expense

        // إرسال حدث إلى Firebase (بدون انتظار)
        $this->firebase->logEvent('expense_created', [
            'user_id' => auth()->id(),
            'expense_id' => $expense->id,
            'amount' => $expense->amount,
            'category' => $expense->category,
            'timestamp' => now()->toDateTimeString()
        ]);

        return response()->json($expense, 201);
    }
}
```

---

## 🏃 تشغيل Queue Worker

### طريقة 1: تشغيل مستمر

```bash
php artisan queue:work
```

### طريقة 2: معالجة job واحد فقط

```bash
php artisan queue:work --once
```

### طريقة 3: في الخلفية (Production)

```bash
php artisan queue:work --daemon
```

---

## 🧪 الاختبار

### اختبار بسيط:

```bash
php test-firebase-job.php
```

### التحقق من الـ Queue:

```bash
php artisan tinker --execute="echo 'Jobs: ' . DB::table('jobs')->count() . PHP_EOL;"
```

### عرض الـ Logs:

```bash
Get-Content storage\logs\laravel.log -Tail 20
```

---

## 📋 أمثلة أحداث Firebase

### 1. تسجيل مستخدم جديد

```php
$firebase->logEvent('user_registered', [
    'user_id' => $user->id,
    'email' => $user->email,
    'preferred_currency' => $user->preferred_currency
]);
```

### 2. إنشاء مصروف

```php
$firebase->logEvent('expense_created', [
    'user_id' => $user->id,
    'expense_id' => $expense->id,
    'amount' => $expense->amount,
    'category' => $expense->category
]);
```

### 3. تحديث مصروف

```php
$firebase->logEvent('expense_updated', [
    'user_id' => $user->id,
    'expense_id' => $expense->id,
    'old_amount' => $oldAmount,
    'new_amount' => $expense->amount
]);
```

### 4. حذف مصروف

```php
$firebase->logEvent('expense_deleted', [
    'user_id' => $user->id,
    'expense_id' => $expense->id
]);
```

---

## ⚙️ الإعدادات

### `.env`:

```env
QUEUE_CONNECTION=database
```

### `config/services.php`:

```php
'firebase' => [
    'credentials' => storage_path('firebase/firebase_credentials.json'),
],
```

---

## 🔧 Supervisor (Production)

لتشغيل Queue Worker بشكل دائم في Production:

### ملف: `/etc/supervisor/conf.d/laravel-worker.conf`

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/path/to/storage/logs/worker.log
```

### تشغيل:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

---

## 📊 مراقبة الـ Queue

### عرض الـ Failed Jobs:

```bash
php artisan queue:failed
```

### إعادة تشغيل Failed Job:

```bash
php artisan queue:retry {id}
```

### حذف Failed Jobs:

```bash
php artisan queue:flush
```

---

## 🎯 الخطوات القادمة

1. ✅ إضافة Firebase Events في ExpenseController
2. ✅ إضافة Events في AuthController
3. ⏳ إعداد Firebase Analytics Dashboard
4. ⏳ إضافة Notifications عبر FCM

---

## 📝 ملاحظات

-   الـ Queue يستخدم قاعدة البيانات حالياً
-   يمكن التبديل إلى Redis للأداء الأفضل
-   تأكد من تشغيل Queue Worker دائماً في Production
-   الـ Jobs تُحفظ في جدول `jobs`
-   Failed jobs تُحفظ في جدول `failed_jobs`

---

تم بناء النظام بنجاح! 🎉
