# 🔥 Firebase Queue System - Quick Start Guide

## ✅ النظام جاهز للعمل!

تم إعداد نظام Firebase Queue بنجاح وإضافة Events في:

-   ✅ **AuthController**: user_registered, user_login, user_logout
-   ✅ **ExpenseController**: expense_created, expense_updated, expense_deleted

---

## 🚀 كيفية التشغيل

### 1️⃣ تشغيل Queue Worker (في نافذة Terminal منفصلة):

```bash
php artisan queue:work
```

**ملاحظة**: اترك هذا Terminal يعمل في الخلفية!

---

### 2️⃣ اختبار النظام:

#### اختبار بسيط:

```bash
php test-firebase-job.php
```

#### التحقق من Jobs:

```bash
php artisan tinker --execute="echo 'Jobs in queue: ' . DB::table('jobs')->count() . PHP_EOL;"
```

#### عرض الـ Logs:

```bash
# في PowerShell
Get-Content storage\logs\laravel.log -Tail 20

# في Bash/CMD
tail -f storage/logs/laravel.log
```

---

## 📊 الأحداث المُسجلة

### 🔐 Authentication Events:

1. **user_registered**

    - يُرسل عند تسجيل مستخدم جديد
    - البيانات: user_id, email, preferred_currency, timestamp

2. **user_login**

    - يُرسل عند تسجيل الدخول
    - البيانات: user_id, email, timestamp

3. **user_logout**
    - يُرسل عند تسجيل الخروج
    - البيانات: user_id, timestamp

### 💰 Expense Events:

4. **expense_created**

    - يُرسل عند إنشاء مصروف جديد
    - البيانات: user_id, expense_id, amount, currency, original_amount, original_currency, category, timestamp

5. **expense_updated**

    - يُرسل عند تحديث مصروف
    - البيانات: user_id, expense_id, updated_fields, timestamp

6. **expense_deleted**
    - يُرسل عند حذف مصروف
    - البيانات: user_id, expense_id, amount, category, timestamp

---

## 🧪 اختبار عبر API

### 1. تسجيل مستخدم جديد:

```bash
POST http://localhost/expense-tracking-api/public/api/register
Content-Type: application/json

{
  "name": "Test User",
  "email": "test@example.com",
  "password": "password123",
  "preferred_currency": "USD"
}
```

**النتيجة**: سيتم إرسال `user_registered` event إلى Firebase Queue

---

### 2. إنشاء مصروف:

```bash
POST http://localhost/expense-tracking-api/public/api/expenses
Authorization: Bearer {your_token}
Content-Type: application/json

{
  "title": "Grocery Shopping",
  "original_amount": 50,
  "original_currency": "USD",
  "category": "Food",
  "expense_date": "2025-10-20"
}
```

**النتيجة**: سيتم إرسال `expense_created` event إلى Firebase Queue

---

## 📋 مراقبة الـ Queue

### عرض Failed Jobs:

```bash
php artisan queue:failed
```

### إعادة محاولة Failed Job:

```bash
php artisan queue:retry {id}
```

### حذف Failed Jobs:

```bash
php artisan queue:flush
```

### عرض معلومات Queue:

```bash
php artisan queue:monitor
```

---

## 🔧 إعدادات متقدمة

### تغيير Queue Driver إلى Redis (للأداء الأفضل):

1. **تثبيت Redis**:

```bash
composer require predis/predis
```

2. **تحديث .env**:

```env
QUEUE_CONNECTION=redis
```

3. **إعادة تشغيل Queue Worker**:

```bash
php artisan queue:restart
php artisan queue:work
```

---

## 🐛 حل المشاكل

### المشكلة: Jobs لا تُعالج

**الحل**: تأكد من تشغيل Queue Worker:

```bash
php artisan queue:work
```

### المشكلة: Jobs تفشل باستمرار

**الحل**: تحقق من الـ Logs:

```bash
Get-Content storage\logs\laravel.log -Tail 50
php artisan queue:failed
```

### المشكلة: Firebase credentials غير موجودة

**الحل**: تأكد من وجود الملف:

```bash
ls storage/firebase/firebase_credentials.json
```

---

## 📦 الملفات المُنشأة

1. ✅ `app/Services/FirebaseService.php` - خدمة Firebase
2. ✅ `app/Jobs/LogFirebaseEventJob.php` - Job للمعالجة في الخلفية
3. ✅ `config/services.php` - إعدادات Firebase
4. ✅ `storage/firebase/firebase_credentials.json` - بيانات الاعتماد
5. ✅ `test-firebase-job.php` - سكريبت اختبار
6. ✅ `FIREBASE_QUEUE_GUIDE.md` - دليل شامل

---

## 🎯 الخطوات القادمة

-   [ ] تثبيت Redis لأداء أفضل
-   [ ] إعداد Supervisor في Production
-   [ ] إضافة Firebase Cloud Messaging (FCM)
-   [ ] إعداد Firebase Analytics Dashboard
-   [ ] إضافة Unit Tests

---

## 💡 نصائح

1. **في Development**: استخدم `php artisan queue:work --once` لمعالجة job واحد
2. **في Production**: استخدم Supervisor لتشغيل Queue Worker بشكل دائم
3. **للتصحيح**: راقب الـ logs في `storage/logs/laravel.log`
4. **للأداء**: استخدم Redis بدلاً من Database Queue

---

تم بناء النظام بنجاح! 🎉
