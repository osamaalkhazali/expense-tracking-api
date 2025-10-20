<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Expense Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
        }

        .greeting {
            font-size: 18px;
            margin-bottom: 15px;
        }

        .date {
            color: #666;
            margin-bottom: 20px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
        }

        .summary-table th,
        .summary-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .summary-table th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }

        .summary-table tr:hover {
            background-color: #f5f5f5;
        }

        .category {
            font-weight: 500;
            text-transform: capitalize;
        }

        .amount {
            text-align: right;
            font-weight: 600;
        }

        .total-row {
            background-color: #e8f5e9;
            font-weight: bold;
            font-size: 16px;
        }

        .total-row td {
            border-top: 2px solid #4CAF50;
            padding: 15px 12px;
        }

        .footer {
            text-align: center;
            color: #666;
            font-size: 12px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .currency {
            color: #4CAF50;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Daily Expense Summary</h1>
    </div>

    <div class="content">
        <div class="greeting">
            Hello {{ $user->name }},
        </div>

        <div class="date">
            Here is your expense summary for <strong>{{ $date->format('l, F d, Y') }}</strong>
        </div>

        @if ($summary->isEmpty())
            <p>You had no expenses on this day.</p>
        @else
            <table class="summary-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th style="text-align: right;">Amount (<span
                                class="currency">{{ $user->preferred_currency }}</span>)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($summary as $category => $amount)
                        <tr>
                            <td class="category">{{ $category }}</td>
                            <td class="amount">{{ number_format($amount, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td>Total</td>
                        <td class="amount">{{ number_format($total, 2) }} {{ $user->preferred_currency }}</td>
                    </tr>
                </tbody>
            </table>
        @endif

        <p style="margin-top: 20px; color: #666; font-size: 14px;">
            Keep track of your spending to meet your financial goals!
        </p>
    </div>

    <div class="footer">
        <p>This is an automated message from your Expense Tracking System.</p>
        <p>&copy; {{ date('Y') }} Expense Tracker. All rights reserved.</p>
    </div>
</body>

</html>
