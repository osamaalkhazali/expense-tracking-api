<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracking API - Documentation</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Space Grotesk', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            color: #000;
            background: #fff;
            min-height: 100vh;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
        }

        /* Navigation */
        nav {
            background: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(20px);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid #d4af37;
        }

        .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: #d4af37;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
        }

        .logo-text h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #d4af37;
            letter-spacing: -0.5px;
        }

        .logo-text p {
            font-size: 0.75rem;
            color: rgba(212, 175, 55, 0.7);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.875rem 2rem;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: #d4af37;
            transition: left 0.3s ease;
            z-index: -1;
        }

        .btn-primary {
            background: #d4af37;
            color: #000;
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3);
        }

        .btn-secondary {
            background: transparent;
            color: #d4af37;
            border: 1px solid #d4af37;
        }

        .btn-secondary::before {
            background: #d4af37;
        }

        .btn-secondary:hover::before {
            left: 0;
        }

        .btn-secondary:hover {
            color: #000;
        }

        /* Hero Section */
        .hero {
            background: #000;
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            top: -250px;
            right: -250px;
        }

        .hero::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            bottom: -200px;
            left: -200px;
        }

        .hero-content {
            text-align: center;
            z-index: 1;
            max-width: 900px;
        }

        .hero-tag {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid #d4af37;
            color: #d4af37;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 2rem;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 1.5rem;
            line-height: 1.1;
            letter-spacing: -2px;
        }

        .hero h1 span {
            color: #d4af37;
        }

        .hero p {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 3rem;
            line-height: 1.8;
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 2rem;
            margin-top: 4rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #d4af37;
            display: block;
        }

        .stat-label {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Main Content */
        main {
            background: #fff;
        }

        .content {
            padding: 8rem 0;
        }

        .section {
            margin-bottom: 8rem;
        }

        .section-header {
            margin-bottom: 3rem;
        }

        .section-tag {
            display: inline-block;
            padding: 0.35rem 1rem;
            background: #000;
            color: #d4af37;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .section h2 {
            font-size: 3rem;
            font-weight: 700;
            color: #000;
            margin-bottom: 1rem;
            letter-spacing: -1px;
        }

        .section-description {
            font-size: 1.15rem;
            color: #666;
            max-width: 700px;
            line-height: 1.8;
        }

        /* Features Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: #fff;
            padding: 2.5rem;
            border: 1px solid #e0e0e0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: #d4af37;
            transition: width 0.4s ease;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border-color: #d4af37;
        }

        .feature-card:hover::before {
            width: 100%;
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            display: block;
        }

        .feature-card h3 {
            font-size: 1.35rem;
            font-weight: 700;
            color: #000;
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: #666;
            line-height: 1.7;
        }

        /* API Endpoints */
        .endpoints-section {
            background: #fafafa;
            padding: 6rem 0;
            margin: 6rem 0;
        }

        .endpoint-card {
            background: #fff;
            border: 1px solid #e0e0e0;
            margin-bottom: 1rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .endpoint-card:hover {
            border-color: #d4af37;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .endpoint-header {
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            cursor: pointer;
        }

        .method-badge {
            padding: 0.5rem 1.25rem;
            font-weight: 700;
            font-size: 0.8rem;
            letter-spacing: 1px;
            min-width: 80px;
            text-align: center;
        }

        .method-get {
            background: #000;
            color: #d4af37;
        }

        .method-post {
            background: #d4af37;
            color: #000;
        }

        .method-put {
            background: #fff;
            color: #d4af37;
            border: 2px solid #d4af37;
        }

        .method-delete {
            background: #000;
            color: #fff;
            border: 2px solid #000;
        }

        .endpoint-path {
            font-family: 'Courier New', monospace;
            font-size: 1rem;
            color: #000;
            font-weight: 600;
        }

        .endpoint-desc {
            color: #666;
            margin-left: auto;
        }

        /* Code Block */
        .code-container {
            background: #000;
            border: 1px solid #d4af37;
            overflow: hidden;
            margin: 2rem 0;
        }

        .code-header {
            background: rgba(212, 175, 55, 0.1);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #d4af37;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .code-title {
            color: #d4af37;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .code-block {
            background: #000;
            color: #d4af37;
            padding: 2rem;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            line-height: 1.8;
        }

        .code-block .comment {
            color: #666;
        }

        /* Info Box */
        .info-card {
            background: #fffdf7;
            border-left: 4px solid #d4af37;
            padding: 2rem;
            margin: 2rem 0;
        }

        .info-card h4 {
            color: #000;
            font-size: 1.25rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-card h4::before {
            content: '⚡';
            font-size: 1.5rem;
        }

        .info-card ul {
            list-style: none;
            padding: 0;
        }

        .info-card li {
            padding: 0.5rem 0;
            color: #333;
            display: flex;
            gap: 0.75rem;
        }

        .info-card li::before {
            content: '→';
            color: #d4af37;
            font-weight: bold;
        }

        /* Footer */
        footer {
            background: #000;
            color: #fff;
            padding: 4rem 0 2rem;
            border-top: 1px solid #d4af37;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-section h3 {
            color: #d4af37;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .footer-section p,
        .footer-section a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            transition: color 0.3s ease;
        }

        .footer-section a:hover {
            color: #d4af37;
        }

        .footer-bottom {
            border-top: 1px solid rgba(212, 175, 55, 0.2);
            padding-top: 2rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 0 20px;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .section h2 {
                font-size: 2rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .endpoint-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .endpoint-desc {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav>
        <div class="container">
            <div class="nav-content">
                <div class="logo-section">
                    <div class="logo-icon">💰</div>
                    <div class="logo-text">
                        <h1>Expense Tracking API</h1>
                        <p>RESTful API</p>
                    </div>
                </div>
                <div class="nav-buttons">
                    <a href="/api/documentation" class="btn btn-primary">API Docs</a>
                    <a href="/api/docs" class="btn btn-secondary">OpenAPI</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <span class="hero-tag">Modern API Solution</span>
                <h1>Build Better <span>Financial Apps</span></h1>
                <p>A powerful RESTful API for expense management with real-time currency conversion, Firebase Analytics,
                    and comprehensive OpenAPI documentation.</p>
                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    <a href="/api/documentation" class="btn btn-primary"
                        style="background: #d4af37; color: #000; border: none;">Get Started</a>
                    <a href="#features" class="btn btn-secondary"
                        style="color: #d4af37; border: 1px solid #d4af37;">Learn More</a>
                </div>
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">10+</span>
                        <span class="stat-label">Endpoints</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">6</span>
                        <span class="stat-label">Categories</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <span class="stat-label">Available</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">100%</span>
                        <span class="stat-label">Documented</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main>

        <div class="container content">
            <!-- Features -->
            <section class="section" id="features">
                <div class="section-header">
                    <span class="section-tag">Features</span>
                    <h2>Everything You Need</h2>
                    <p class="section-description">Built with modern technologies and best practices to provide a robust
                        and scalable API solution for expense tracking applications.</p>
                </div>
                <div class="features-grid">
                    <div class="feature-card">
                        <span class="feature-icon">🔐</span>
                        <h3>Secure Authentication</h3>
                        <p>Token-based authentication using Laravel Sanctum. Complete registration, login, and logout
                            endpoints with industry-standard security.</p>
                    </div>
                    <div class="feature-card">
                        <span class="feature-icon">💰</span>
                        <h3>Expense Management</h3>
                        <p>Full CRUD operations with advanced filtering by date and category. Efficient tracking and
                            organization of all expenses.</p>
                    </div>
                    <div class="feature-card">
                        <span class="feature-icon">💱</span>
                        <h3>Currency Conversion</h3>
                        <p>Real-time conversion to user's preferred currency with intelligent 12-hour caching for
                            optimal performance.</p>
                    </div>
                    <div class="feature-card">
                        <span class="feature-icon">📊</span>
                        <h3>Firebase Analytics</h3>
                        <p>Comprehensive user behavior tracking with Google Analytics 4 Measurement Protocol
                            integration.</p>
                    </div>
                    <div class="feature-card">
                        <span class="feature-icon">📈</span>
                        <h3>Summary Reports</h3>
                        <p>Category-wise spending analysis with customizable date ranges and detailed breakdowns.</p>
                    </div>
                    <div class="feature-card">
                        <span class="feature-icon">📧</span>
                        <h3>Email Notifications</h3>
                        <p>Automated daily email summaries with expense reports sent to all users via queue system.</p>
                    </div>
                </div>
            </section>

            <!-- Quick Start -->
            <section class="section">
                <div class="section-header">
                    <span class="section-tag">Quick Start</span>
                    <h2>Get Started in Minutes</h2>
                    <p class="section-description">Simple setup process to get your API running locally in just a few
                        commands.</p>
                </div>
                <div class="code-container">
                    <div class="code-header">
                        <span class="code-title">Installation Commands</span>
                    </div>
                    <div class="code-block"><span class="comment"># Clone the repository</span>
                        git clone https://github.com/osamaalkhazali/expense-tracking-api.git
                        cd expense-tracking-api

                        <span class="comment"># Install dependencies</span>
                        composer install --ignore-platform-reqs

                        <span class="comment"># Setup environment</span>
                        cp .env.example .env
                        php artisan key:generate

                        <span class="comment"># Run migrations</span>
                        php artisan migrate

                        <span class="comment"># Start the server</span>
                        php artisan serve

                        <span class="comment"># Start queue worker (separate terminal)</span>
                        php artisan queue:work
                    </div>
                </div>
            </section>

            <!-- API Endpoints -->
            <div class="endpoints-section">
                <div class="container">
                    <div class="section-header">
                        <span class="section-tag">API Reference</span>
                        <h2>Available Endpoints</h2>
                        <p class="section-description"><strong>Base URL:</strong> <code
                                style="background: #000; color: #d4af37; padding: 0.25rem 0.75rem;">http://localhost:8000/api</code>
                        </p>
                    </div>

                    <h3 style="font-size: 1.75rem; margin-bottom: 1.5rem; color: #000;">Authentication</h3>

                    <div class="endpoint-card">
                        <div class="endpoint-header">
                            <span class="method-badge method-post">POST</span>
                            <span class="endpoint-path">/api/register</span>
                            <span class="endpoint-desc">Register a new user</span>
                        </div>
                    </div>

                    <div class="endpoint-card">
                        <div class="endpoint-header">
                            <span class="method-badge method-post">POST</span>
                            <span class="endpoint-path">/api/login</span>
                            <span class="endpoint-desc">Login and receive token</span>
                        </div>
                    </div>

                    <div class="endpoint-card">
                        <div class="endpoint-header">
                            <span class="method-badge method-post">POST</span>
                            <span class="endpoint-path">/api/logout</span>
                            <span class="endpoint-desc">Logout and invalidate token</span>
                        </div>
                    </div>

                    <h3 style="font-size: 1.75rem; margin: 3rem 0 1.5rem; color: #000;">Expense Management</h3>

                    <div class="endpoint-card">
                        <div class="endpoint-header">
                            <span class="method-badge method-get">GET</span>
                            <span class="endpoint-path">/api/expenses</span>
                            <span class="endpoint-desc">List all expenses with filters</span>
                        </div>
                    </div>

                    <div class="endpoint-card">
                        <div class="endpoint-header">
                            <span class="method-badge method-post">POST</span>
                            <span class="endpoint-path">/api/expenses</span>
                            <span class="endpoint-desc">Create a new expense</span>
                        </div>
                    </div>

                    <div class="endpoint-card">
                        <div class="endpoint-header">
                            <span class="method-badge method-put">PUT</span>
                            <span class="endpoint-path">/api/expenses/{id}</span>
                            <span class="endpoint-desc">Update an expense</span>
                        </div>
                    </div>

                    <div class="endpoint-card">
                        <div class="endpoint-header">
                            <span class="method-badge method-delete">DELETE</span>
                            <span class="endpoint-path">/api/expenses/{id}</span>
                            <span class="endpoint-desc">Delete an expense</span>
                        </div>
                    </div>

                    <div class="endpoint-card">
                        <div class="endpoint-header">
                            <span class="method-badge method-get">GET</span>
                            <span class="endpoint-path">/api/expenses/summary</span>
                            <span class="endpoint-desc">Get expense summary by category</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tech Stack -->
            <div class="container">
                <section class="section">
                    <div class="section-header">
                        <span class="section-tag">Technology</span>
                        <h2>Built With Modern Tools</h2>
                        <p class="section-description">Leveraging industry-standard technologies and frameworks for
                            reliability and performance.</p>
                    </div>
                    <div class="features-grid">
                        <div class="feature-card">
                            <span class="feature-icon">🎯</span>
                            <h3>Laravel 11.x</h3>
                            <p>Modern PHP framework with elegant syntax and powerful features.</p>
                        </div>
                        <div class="feature-card">
                            <span class="feature-icon">🔒</span>
                            <h3>Laravel Sanctum</h3>
                            <p>Simple token-based authentication for SPAs and mobile apps.</p>
                        </div>
                        <div class="feature-card">
                            <span class="feature-icon">🗄️</span>
                            <h3>MySQL Database</h3>
                            <p>Reliable relational database for data persistence.</p>
                        </div>
                        <div class="feature-card">
                            <span class="feature-icon">📝</span>
                            <h3>OpenAPI 3.0</h3>
                            <p>L5-Swagger for comprehensive API documentation.</p>
                        </div>
                        <div class="feature-card">
                            <span class="feature-icon">🔥</span>
                            <h3>Firebase SDK</h3>
                            <p>Google Analytics 4 integration for event tracking.</p>
                        </div>
                        <div class="feature-card">
                            <span class="feature-icon">💸</span>
                            <h3>ExchangeRate API</h3>
                            <p>Real-time currency conversion with smart caching.</p>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Firebase Implementation -->
            <div class="container">
                <section class="section">
                    <div class="section-header">
                        <span class="section-tag">Analytics</span>
                        <h2>Firebase Integration</h2>
                    </div>
                    <div class="info-card">
                        <h4>Implementation Details</h4>
                        <p style="color: #666; margin-bottom: 1rem;">The task requirements specified using Firebase
                            Admin SDK for PHP. The SDK is installed and configured, with Google Analytics 4 Measurement
                            Protocol handling the actual event transmission.</p>
                        <ul>
                            <li>Firebase Admin SDK installed and configured (kreait/laravel-firebase)</li>
                            <li>Google Analytics 4 Measurement Protocol for event transmission</li>
                            <li>Asynchronous event sending via queue jobs</li>
                            <li>Complete Firebase credentials and configuration maintained</li>
                        </ul>
                    </div>

                    <h3 style="font-size: 1.5rem; margin: 2rem 0 1rem; color: #000;">Tracked Events</h3>
                    <div class="features-grid"
                        style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                        <div class="feature-card" style="padding: 1.5rem;">
                            <h3 style="font-size: 1rem; margin-bottom: 0.5rem;">user_registered</h3>
                            <p style="font-size: 0.9rem;">When a new user signs up</p>
                        </div>
                        <div class="feature-card" style="padding: 1.5rem;">
                            <h3 style="font-size: 1rem; margin-bottom: 0.5rem;">expense_created</h3>
                            <p style="font-size: 0.9rem;">When a new expense is created</p>
                        </div>
                        <div class="feature-card" style="padding: 1.5rem;">
                            <h3 style="font-size: 1rem; margin-bottom: 0.5rem;">expense_updated</h3>
                            <p style="font-size: 0.9rem;">When an expense is modified</p>
                        </div>
                        <div class="feature-card" style="padding: 1.5rem;">
                            <h3 style="font-size: 1rem; margin-bottom: 0.5rem;">expense_deleted</h3>
                            <p style="font-size: 0.9rem;">When an expense is removed</p>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Configuration -->
            <div class="container">
                <section class="section">
                    <div class="section-header">
                        <span class="section-tag">Configuration</span>
                        <h2>Environment Setup</h2>
                        <p class="section-description">Configure your environment variables for database, queue, mail,
                            and integrations.</p>
                    </div>
                    <div class="code-container">
                        <div class="code-header">
                            <span class="code-title">.env Configuration</span>
                        </div>
                        <div class="code-block"><span class="comment"># Database</span>
                            DB_CONNECTION=mysql
                            DB_DATABASE=expense_tracking

                            <span class="comment"># Queue</span>
                            QUEUE_CONNECTION=database

                            <span class="comment"># Mail</span>
                            MAIL_MAILER=log

                            <span class="comment"># Firebase (absolute path required)</span>
                            FIREBASE_CREDENTIALS=C:\laragon\www\expense-tracking-api\storage\firebase\firebase_credentials.json

                            <span class="comment"># Google Analytics 4</span>
                            GA_MEASUREMENT_ID=G-XXXXXXXXXX
                            GA_API_SECRET=your-api-secret-here

                            <span class="comment"># Currency API</span>
                            CURRENCY_API_URL=https://api.exchangerate-api.com/v4/latest/
                        </div>
                    </div>
                </section>
            </div>

            <!-- Documentation -->
            <div class="container">
                <section class="section">
                    <div class="section-header">
                        <span class="section-tag">Documentation</span>
                        <h2>Explore the Docs</h2>
                        <p class="section-description">Multiple ways to access comprehensive API documentation and
                            import into your favorite tools.</p>
                    </div>
                    <div class="info-card">
                        <h4>Documentation Access</h4>
                        <ul>
                            <li><strong>Interactive Swagger UI:</strong> <a href="/api/documentation"
                                    style="color: #d4af37; font-weight: 600;">/api/documentation</a></li>
                            <li><strong>OpenAPI JSON:</strong> <a href="/api/docs"
                                    style="color: #d4af37; font-weight: 600;">/api/docs</a></li>
                            <li><strong>Local File:</strong> storage/api-docs/api-docs.json</li>
                        </ul>
                    </div>
                    <div class="code-container" style="margin-top: 2rem;">
                        <div class="code-header">
                            <span class="code-title">Import to Postman</span>
                        </div>
                        <div class="code-block">1. Open Postman → Import → Link
                            2. Paste: http://localhost:8000/api/docs
                            3. Or import local file: storage/api-docs/api-docs.json</div>
                    </div>
                </section>
            </div>

            <!-- Categories -->
            <div class="container">
                <section class="section">
                    <div class="section-header">
                        <span class="section-tag">Categories</span>
                        <h2>Expense Categories</h2>
                        <p class="section-description">Six pre-defined categories to organize your expenses
                            efficiently.</p>
                    </div>
                    <div class="features-grid">
                        <div class="feature-card">
                            <span class="feature-icon">🍔</span>
                            <h3>Food</h3>
                            <p>Dining and groceries</p>
                        </div>
                        <div class="feature-card">
                            <span class="feature-icon">✈️</span>
                            <h3>Travel</h3>
                            <p>Transportation costs</p>
                        </div>
                        <div class="feature-card">
                            <span class="feature-icon">💡</span>
                            <h3>Utilities</h3>
                            <p>Bills and services</p>
                        </div>
                        <div class="feature-card">
                            <span class="feature-icon">🎮</span>
                            <h3>Entertainment</h3>
                            <p>Leisure activities</p>
                        </div>
                        <div class="feature-card">
                            <span class="feature-icon">⚕️</span>
                            <h3>Healthcare</h3>
                            <p>Medical expenses</p>
                        </div>
                        <div class="feature-card">
                            <span class="feature-icon">🛍️</span>
                            <h3>Shopping</h3>
                            <p>General purchases</p>
                        </div>
                    </div>
                </section>
            </div>

            <!-- API Examples -->
            <div class="container">
                <section class="section">
                    <div class="section-header">
                        <span class="section-tag">Examples</span>
                        <h2>Try It Out</h2>
                        <p class="section-description">Sample API requests to get you started quickly.</p>
                    </div>
                    <div class="code-container">
                        <div class="code-header">
                            <span class="code-title">Register a User</span>
                        </div>
                        <div class="code-block">POST http://localhost:8000/api/register
                            Content-Type: application/json

                            {
                            "name": "John Doe",
                            "email": "john@example.com",
                            "password": "password123",
                            "password_confirmation": "password123",
                            "preferred_currency": "USD"
                            }</div>
                    </div>

                    <div class="code-container">
                        <div class="code-header">
                            <span class="code-title">Create an Expense</span>
                        </div>
                        <div class="code-block">POST http://localhost:8000/api/expenses
                            Authorization: Bearer {your-token}
                            Content-Type: application/json

                            {
                            "title": "Lunch at Restaurant",
                            "original_amount": 45.50,
                            "original_currency": "EUR",
                            "category": "food",
                            "expense_date": "2025-10-20"
                            }</div>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Expense Tracking API</h3>
                    <p>A modern RESTful API for expense management with advanced features and comprehensive
                        documentation.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <a href="/api/documentation">API Documentation</a>
                    <a href="/api/docs">OpenAPI Specification</a>
                    <a href="https://github.com/osamaalkhazali/expense-tracking-api">GitHub Repository</a>
                </div>
                <div class="footer-section">
                    <h3>Technologies</h3>
                    <a href="https://laravel.com" target="_blank">Laravel 11</a>
                    <a href="https://swagger.io" target="_blank">OpenAPI 3.0</a>
                    <a href="https://firebase.google.com" target="_blank">Firebase</a>
                </div>
                <div class="footer-section">
                    <h3>Contact</h3>
                    <p><strong>Osama Al-Khazali</strong></p>
                    <a href="mailto:osama.khazali97@gmail.com">osama.khazali97@gmail.com</a>
                    <a href="https://github.com/osamaalkhazali" target="_blank">@osamaalkhazali</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Osama Al-Khazali. Built with Laravel 11 | MIT License</p>
            </div>
        </div>
    </footer>
</body>

</html>
