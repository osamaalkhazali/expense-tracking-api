<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class ExpenseController extends Controller implements HasMiddleware
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum')
        ];
    }

    /**
     * Display a listing of expenses with optional filters
     */
    public function index(Request $request)
    {
        $query = Expense::where('user_id', $request->user()->id);

        // Filter by date range
        if ($request->has('start_date')) {
            $query->where('expense_date', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->where('expense_date', '<=', $request->end_date);
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->get();

        return response()->json($expenses);
    }

    /**
     * Store a newly created expense with currency conversion
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|string|max:255',
            'original_amount' => 'required|numeric|min:0',
            'original_currency' => 'required|string|size:3',
            'category' => 'required|string|max:255',
            'expense_date' => 'required|date',
        ]);

        $user = $request->user();

        // Convert amount to user's preferred currency
        $convertedAmount = $this->currencyService->convert(
            $fields['original_amount'],
            $fields['original_currency'],
            $user->preferred_currency
        );

        $expense = Expense::create([
            'user_id' => $user->id,
            'title' => $fields['title'],
            'amount' => $convertedAmount,
            'original_amount' => $fields['original_amount'],
            'original_currency' => $fields['original_currency'],
            'category' => $fields['category'],
            'expense_date' => $fields['expense_date'],
        ]);

        return response()->json([
            'message' => 'Expense created successfully',
            'expense' => $expense
        ], 201);
    }

    /**
     * Update the specified expense with conversion if currency changed
     */
    public function update(Request $request, Expense $expense)
    {
        // Only the expense owner can update it
        Gate::authorize('modify', $expense);

        $fields = $request->validate([
            'title' => 'sometimes|string|max:255',
            'original_amount' => 'sometimes|numeric|min:0',
            'original_currency' => 'sometimes|string|size:3',
            'category' => 'sometimes|string|max:255',
            'expense_date' => 'sometimes|date',
        ]);

        // If amount or currency changed, recalculate conversion
        if (isset($fields['original_amount']) || isset($fields['original_currency'])) {
            $originalAmount = $fields['original_amount'] ?? $expense->original_amount;
            $originalCurrency = $fields['original_currency'] ?? $expense->original_currency;

            $fields['amount'] = $this->currencyService->convert(
                $originalAmount,
                $originalCurrency,
                $request->user()->preferred_currency
            );
        }

        $expense->update($fields);

        return response()->json([
            'message' => 'Expense updated successfully',
            'expense' => $expense
        ]);
    }

    /**
     * Remove the specified expense
     */
    public function destroy(Expense $expense)
    {
        // Only the expense owner can delete it
        Gate::authorize('modify', $expense);

        $expense->delete();

        return response()->json([
            'message' => 'Expense deleted successfully'
        ]);
    }
}
