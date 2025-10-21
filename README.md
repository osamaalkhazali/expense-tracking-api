# 💰 Expense Tracking API

A feature-rich RESTful API for expense management with real-time currency conversion and Firebase Analytics integration. Built with Laravel 11, this API demonstrates enterprise-level patterns including authentication, third-party integrations, caching strategies, and asynchronous job processing.

[![Swagger API Documentation](https://img.shields.io/badge/API-Swagger-green.svg)](http://localhost:8000/api/documentation)

## 📋 Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Setup](#database-setup)
- [Running the Application](#running-the-application)
- [API Documentation](#api-documentation)
- [Swagger UI](#swagger-ui)
- [Firebase Analytics Events](#firebase-analytics-events)
- [Queue System](#queue-system)
- [Testing](#testing)
- [Project Structure](#project-structure)
- [License](#license)

## ✨ Features

### Core Functionality
- ✅ **User Authentication** - Secure token-based authentication using Laravel Sanctum
- ✅ **Expense Management** - Full CRUD operations for expense tracking
- ✅ **Real-time Currency Conversion** - Automatic conversion to user's preferred currency
- ✅ **Firebase Analytics Integration** - Track user behavior and expense events
- ✅ **Smart Caching** - Exchange rates cached for 12 hours to optimize performance
- ✅ **Asynchronous Processing** - Background jobs for analytics and email notifications
- ✅ **Expense Summary Reports** - Category-wise spending analysis
- ✅ **Daily Email Notifications** - Scheduled summary emails for users

### Technical Highlights
- RESTful API design following industry best practices
- Eloquent ORM with relationship management
- Queue-based job processing for scalability
- Comprehensive error handling and logging
- Environment-based configuration
- Third-party API integration with fault tolerance

## 🛠 Tech Stack

- **Framework**: Laravel 11.x
- **Authentication**: Laravel Sanctum
- **Database**: MySQL
- **Queue Driver**: Database (configurable to Redis/SQS)
- **Cache**: File-based (configurable to Redis/Memcached)
- **API Documentation**: L5-Swagger (OpenAPI 3.0)
- **Third-Party APIs**:
  - Currency Conversion API (https://api.exchangerate-api.com)
  - Google Analytics 4 Measurement Protocol
- **Firebase**: Admin SDK for PHP (kreait/laravel-firebase)
- **PHP Version**: 8.2+

## 📦 Installation

### Prerequisites
- PHP >= 8.2
- Composer
- MySQL (Laragon recommended for Windows)
- Node.js & NPM (for frontend assets, optional)
- Firebase project with Admin SDK credentials

**Recommended for Windows:**
- [Laragon](https://laragon.org/) - Portable development environment
- Includes: Apache, MySQL, PHP, Composer
- Easy setup and management

### Step 1: Clone the Repository
```bash
git clone https://github.com/osamaalkhazali/expense-tracking-api.git
cd expense-tracking-api
```

### Step 2: Install Dependencies
```bash
composer install --ignore-platform-reqs
```

**Note:** The `--ignore-platform-reqs` flag is used because the Firebase SDK requires the `ext-sodium` extension. This is safe for development on Windows with Laragon.

### Step 3: Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

## ⚙️ Configuration

### 1. Database Configuration
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=expense_tracking
DB_USERNAME=root
DB_PASSWORD=
```

### 2. Queue Configuration
```env
QUEUE_CONNECTION=database
```

### 3. Mail Configuration
```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

For production, configure SMTP:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

### 4. Firebase Configuration
Place your Firebase credentials JSON file in:
```
storage/firebase/firebase_credentials.json
```

**Important for Windows:** Use the full absolute path in `.env`:
```env
FIREBASE_CREDENTIALS=C:\laragon\www\expense-tracking-api\storage\firebase\firebase_credentials.json
```

**Steps to get Firebase credentials:**
1. Go to [Firebase Console](https://console.firebase.google.com)
2. Select your project
3. Go to Project Settings → Service Accounts
4. Click "Generate New Private Key"
5. Save the JSON file to `storage/firebase/firebase_credentials.json`

### 5. Currency API Configuration
```env
CURRENCY_API_URL=https://api.exchangerate-api.com/v4/latest/
```

### 6. Google Analytics 4
```env
GA_MEASUREMENT_ID=G-XXXXXXXXXX
GA_API_SECRET=your-api-secret
```

## 🗄 Database Setup

### Run Migrations
```bash
php artisan migrate
```

### Database Schema
The application creates the following tables:
- `users` - User accounts with preferred currency
- `expenses` - Expense records with original and converted amounts
- `personal_access_tokens` - Sanctum authentication tokens
- `jobs` - Queue job processing
- `cache` - Application cache storage
- `failed_jobs` - Failed queue jobs for debugging

## 🚀 Running the Application

### Start the Development Server
```bash
php artisan serve
```
API will be available at: `http://localhost:8000`

### Start the Queue Worker
In a separate terminal/command prompt:
```bash
php artisan queue:work --tries=3 --timeout=30
```

For development with auto-reload:
```bash
php artisan queue:listen
```

### Schedule Daily Emails
The application includes a scheduled task to send daily expense summaries.

**Enable the Scheduler (Windows Task Scheduler):**

1. Open **Task Scheduler** (search in Windows Start menu)
2. Click **Create Basic Task**
3. Name: "Laravel Scheduler - Expense Tracking"
4. Trigger: **Daily**
5. Action: **Start a program**
6. Program/script: `C:\path\to\php.exe` (e.g., `C:\wamp64\bin\php\php8.3.14\php.exe`)
7. Arguments: `artisan schedule:run`
8. Start in: `C:\laragon\www\expense-tracking-api`
9. In **Settings**, check "Run task as soon as possible after a scheduled start is missed"
10. Set the task to repeat every **1 minute** for a duration of **1 day**

**For Manual Testing:**
```bash
php artisan email:daily-summary [user_id]
```

Example:
```bash
# Send to specific user
php artisan email:daily-summary 1

# Send to all users
php artisan email:daily-summary
```

## 📚 API Documentation

### Base URL
```
http://localhost:8000/api
```

### 📖 Interactive Swagger Documentation

Access the complete interactive API documentation at:
**[http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)**

You can also access the raw OpenAPI JSON specification at:
**[http://localhost:8000/api-docs.json](http://localhost:8000/api-docs.json)**

Or view the stored file directly:
```
storage/api-docs/api-docs.json
```

The Swagger UI provides:
- 🔍 **Interactive API Explorer** - Test endpoints directly from the browser
- 📝 **Complete Request/Response Examples** - See all parameters and response structures
- 🔐 **Authentication Testing** - Easily test authenticated endpoints with bearer tokens
- 📋 **Schema Definitions** - Detailed model schemas for all resources
- 🚀 **Try It Out** - Execute real API calls and see live responses

#### Using Swagger UI:
1. Start your development server: `php artisan serve`
2. Open [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)
3. Click "Authorize" button and enter your token in format: `Bearer {your-token}`
4. Expand any endpoint and click "Try it out" to test it

### Authentication Endpoints

#### Register
```http
POST /register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "preferred_currency": "USD"
}
```

**Response:**
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "preferred_currency": "USD"
  },
  "token": "1|xxxxxxxxxxxxxxxxxxxx"
}
```

#### Login
```http
POST /login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "preferred_currency": "USD"
  },
  "token": "2|xxxxxxxxxxxxxxxxxxxx"
}
```

#### Logout
```http
POST /logout
Authorization: Bearer {token}
```

**Response:**
```json
{
  "message": "Logged out successfully"
}
```

### Expense Endpoints

All expense endpoints require authentication. Include the token in the header:
```
Authorization: Bearer {your-token}
```

#### List Expenses
```http
GET /expenses?category=food&from=2025-01-01&to=2025-12-31
```

**Query Parameters:**
- `category` (optional) - Filter by category
- `from` (optional) - Start date (YYYY-MM-DD)
- `to` (optional) - End date (YYYY-MM-DD)

**Response:**
```json
[
  {
    "id": 1,
    "title": "Grocery Shopping",
    "amount": "50.00",
    "original_amount": "150.00",
    "original_currency": "SAR",
    "category": "food",
    "expense_date": "2025-10-20",
    "created_at": "2025-10-20T10:30:00.000000Z",
    "updated_at": "2025-10-20T10:30:00.000000Z"
  }
]
```

#### Create Expense
```http
POST /expenses
Content-Type: application/json
Authorization: Bearer {token}

{
  "title": "Lunch at Restaurant",
  "amount": 45.50,
  "currency": "EUR",
  "category": "food",
  "expense_date": "2025-10-20"
}
```

**Response:**
```json
{
  "id": 2,
  "title": "Lunch at Restaurant",
  "amount": "50.25",
  "original_amount": "45.50",
  "original_currency": "EUR",
  "category": "food",
  "expense_date": "2025-10-20",
  "created_at": "2025-10-20T14:30:00.000000Z",
  "updated_at": "2025-10-20T14:30:00.000000Z"
}
```

#### Update Expense
```http
PUT /expenses/{id}
Content-Type: application/json
Authorization: Bearer {token}

{
  "title": "Updated Lunch",
  "amount": 55.00,
  "currency": "USD",
  "category": "food",
  "expense_date": "2025-10-20"
}
```

#### Delete Expense
```http
DELETE /expenses/{id}
Authorization: Bearer {token}
```

**Response:**
```json
{
  "message": "Expense deleted successfully"
}
```

#### Expense Summary
```http
GET /expenses/summary?from=2025-10-01&to=2025-10-31
Authorization: Bearer {token}
```

**Response:**
```json
{
  "currency": "USD",
  "summary": {
    "food": 450.75,
    "travel": 320.00,
    "utilities": 150.50,
    "entertainment": 85.25
  }
}
```

### Supported Categories
- `food` - Food and dining
- `travel` - Transportation and travel
- `utilities` - Bills and utilities
- `entertainment` - Entertainment and leisure
- `healthcare` - Medical expenses
- `shopping` - General shopping
- `education` - Educational expenses
- `other` - Miscellaneous

### Supported Currencies
The API supports 150+ currencies including:
- USD, EUR, GBP, JPY, CNY
- SAR, AED, KWD, QAR, BHD
- JOD, EGP, TRY, INR
- And many more...

## 🔥 Firebase Analytics Events

The application automatically tracks the following events:

### Implementation Note
The task requirements specified using **Firebase Admin SDK for PHP** to send events to Firebase Analytics. However, the Firebase Admin SDK for PHP does not directly support sending analytics events (it's designed for authentication, database, and messaging operations).

**Solution Implemented:**
- ✅ **Firebase Admin SDK** is installed and configured (`kreait/laravel-firebase`)
- ✅ **Google Analytics 4 Measurement Protocol** is used to actually send the events
- ✅ Events are sent asynchronously via queue jobs to avoid blocking requests
- ✅ All Firebase credentials and configuration are maintained as required

This hybrid approach:
- Satisfies the Firebase SDK requirement (package is installed and configured)
- Actually delivers events to Firebase/Google Analytics (via Measurement Protocol)
- Provides better performance (async queue processing)
- Offers detailed logging for debugging

### user_registered
Triggered when a new user signs up.
```json
{
  "user_id": 1,
  "email": "john@example.com"
}
```

### expense_created
Triggered when a new expense is created.
```json
{
  "user_id": 1,
  "amount": 50.25,
  "currency": "USD",
  "category": "food"
}
```

### expense_updated
Triggered when an expense is modified.
```json
{
  "user_id": 1,
  "expense_id": 5,
  "amount": "75.50"
}
```

### expense_deleted
Triggered when an expense is removed.
```json
{
  "user_id": 1,
  "expense_id": 5
}
```

All events are sent asynchronously via queue jobs to avoid blocking the main request.

## 🔄 Queue System

The application uses Laravel's queue system for:
1. **Firebase Analytics Events** - Sent asynchronously to avoid request delays
2. **Daily Email Notifications** - Scheduled summary emails
3. **Future Scalability** - Easy to add more background tasks

### Queue Commands

Start queue worker:
```bash
php artisan queue:work
```

Process specific number of jobs:
```bash
php artisan queue:work --once
```

Restart all queue workers:
```bash
php artisan queue:restart
```

View failed jobs:
```bash
php artisan queue:failed
```

Retry failed jobs:
```bash
php artisan queue:retry all
```

## 🧪 Testing

### Regenerating API Documentation

After making changes to controller annotations, regenerate the Swagger documentation:
```bash
php artisan l5-swagger:generate
```

### Manual Testing with Postman

1. Import the API collection (if provided)
2. Set the base URL to `http://localhost:8000/api`
3. Test registration and store the returned token
4. Use the token for all authenticated endpoints

### Example Test Flow
```bash
# 1. Register a user
POST /register

# 2. Login
POST /login

# 3. Create expenses
POST /expenses (with different currencies)

# 4. View expenses
GET /expenses

# 5. Get summary
GET /expenses/summary?from=2025-10-01&to=2025-10-31

# 6. Test email (with queue worker running)
php artisan email:daily-summary {user_id}
```

## 📁 Project Structure

```
expense-tracking-api/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── SendTestDailySummary.php
│   ├── Http/
│   │   └── Controllers/
│   │       └── Api/
│   │           ├── AuthController.php (with OpenAPI annotations)
│   │           └── ExpenseController.php (with OpenAPI annotations)
│   ├── Jobs/
│   │   ├── LogFirebaseEventJob.php
│   │   └── SendDailyExpenseSummaryEmail.php
│   ├── Mail/
│   │   └── DailyExpenseSummaryMail.php
│   ├── Models/
│   │   ├── User.php (with OpenAPI schema)
│   │   └── Expense.php (with OpenAPI schema)
│   └── Services/
│       ├── CurrencyService.php
│       └── FirebaseService.php (Google Analytics Measurement Protocol)
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── l5-swagger.php (Swagger configuration)
│   └── services.php
├── database/
│   └── migrations/
├── resources/
│   └── views/
│       └── emails/
│           └── daily-expense-summary.blade.php
├── routes/
│   ├── api.php
│   └── console.php
├── storage/
│   ├── api-docs/
│   │   └── api-docs.json (Generated OpenAPI specification)
│   ├── firebase/
│   │   └── firebase_credentials.json
│   └── logs/
│       └── laravel.log
├── .env.example
├── composer.json
└── README.md
```

## 🔑 Key Classes

### Services
- **CurrencyService** - Handles currency conversion with caching
- **FirebaseService** - Manages Firebase Analytics event logging

### Controllers
- **AuthController** - User registration, login, logout (with OpenAPI annotations)
- **ExpenseController** - CRUD operations and summary endpoint (with OpenAPI annotations)

### Jobs
- **LogFirebaseEventJob** - Async Firebase event processing
- **SendDailyExpenseSummaryEmail** - Daily expense summary notifications

### Models
- **User** - User account with preferred currency (OpenAPI schema)
- **Expense** - Expense record with currency conversion (OpenAPI schema)

### API Documentation
- **l5-swagger** - Generates OpenAPI 3.0 documentation
- **Swagger UI** - Interactive API documentation at `/api/documentation`
- **OpenAPI JSON** - Raw specification at `/api-docs.json` or `storage/api-docs/api-docs.json`
- **Annotations** - OpenAPI attributes in controllers and models

## 🐛 Troubleshooting

### SSL Certificate Issues (Development)
If you encounter SSL errors with external APIs:
```php
// Already handled in CurrencyService and FirebaseService
Http::withOptions(['verify' => false])
```

### Queue Not Processing
Make sure queue worker is running:
```bash
php artisan queue:work
```

### Firebase Credentials Error
Ensure the path in `.env` is absolute:
```env
FIREBASE_CREDENTIALS=C:\full\path\to\storage\firebase\firebase_credentials.json
```

### Currency API Rate Limit
The application caches exchange rates for 12 hours. If you hit rate limits, consider:
- Using a paid API plan
- Increasing cache duration
- Implementing Redis for distributed caching

### Swagger UI Not Loading
If Swagger UI doesn't load properly:
1. Regenerate documentation: `php artisan l5-swagger:generate`
2. Clear cache: `php artisan cache:clear`
3. Check that the server is running: `php artisan serve`
4. Access: `http://localhost:8000/api/documentation`

### Adding New Endpoints to Swagger
When adding new API endpoints, use OpenAPI annotations:

```php
/**
 * @OA\Get(
 *     path="/your-endpoint",
 *     summary="Endpoint description",
 *     tags={"YourTag"},
 *     security={{"sanctum":{}}},
 *     @OA\Response(response=200, description="Success")
 * )
 */
public function yourMethod(Request $request)
{
    // Your code
}
```

Then regenerate docs:
```bash
php artisan l5-swagger:generate
```

### Accessing API Documentation

The API documentation is available in multiple formats:

1. **Interactive Swagger UI:**
   - URL: `http://localhost:8000/api/documentation`
   - Features: Try out endpoints, see examples, test authentication

2. **OpenAPI JSON Specification:**
   - URL: `http://localhost:8000/api-docs.json`
   - Use for: API client generation, Postman import, external tools

3. **Local JSON File:**
   - Path: `storage/api-docs/api-docs.json`
   - Use for: Offline access, version control, sharing with team

4. **Import to Postman:**
   - Open Postman → Import → Link
   - Paste: `http://localhost:8000/api-docs.json`
   - Or import the local file: `storage/api-docs/api-docs.json`

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👤 Author

**Osama Al-Khazali**
- GitHub: [@osamaalkhazali](https://github.com/osamaalkhazali)
- Email: osama.khazali97@gmail.com

## 🙏 Acknowledgments

- Laravel Framework
- Firebase Admin SDK
- ExchangeRate API
- Google Analytics Measurement Protocol

---

**Built with ❤️ using Laravel**
