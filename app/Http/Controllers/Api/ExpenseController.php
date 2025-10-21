<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Services\CurrencyService;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

class ExpenseController extends Controller implements HasMiddleware
{
    protected CurrencyService $currencyService;
    protected FirebaseService $firebase;

    public function __construct(CurrencyService $currencyService, FirebaseService $firebase)
    {
        $this->currencyService = $currencyService;
        $this->firebase = $firebase;
    }

    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum')
        ];
    }

    /**
     * @OA\Get(
     *     path="/expenses",
     *     summary="Get list of expenses",
     *     tags={"Expenses"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Filter by category",
     *         required=false,
     *         @OA\Schema(type="string", enum={"food","travel","utilities","entertainment","healthcare","shopping","education","other"})
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Filter by start date",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2025-10-01")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Filter by end date",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2025-10-31")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of expenses",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Expense")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
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
     * @OA\Post(
     *     path="/expenses",
     *     summary="Create a new expense",
     *     tags={"Expenses"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","original_amount","original_currency","category","expense_date"},
     *             @OA\Property(property="title", type="string", example="Lunch at Restaurant"),
     *             @OA\Property(property="original_amount", type="number", format="float", example=45.50),
     *             @OA\Property(property="original_currency", type="string", example="EUR"),
     *             @OA\Property(property="category", type="string", example="food", enum={"food","travel","utilities","entertainment","healthcare","shopping","education","other"}),
     *             @OA\Property(property="expense_date", type="string", format="date", example="2025-10-20")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Expense created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Expense")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
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

        // Log event to Firebase Analytics
        $this->firebase->dispatchEvent('expense_created', [
            'user_id' => $user->id,
            'amount' => $convertedAmount,
            'currency' => $user->preferred_currency,
            'category' => $fields['category']
        ]);

        return response()->json([
            'message' => 'Expense created successfully',
            'expense' => $expense
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/expenses/{id}",
     *     summary="Update an expense",
     *     tags={"Expenses"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Expense ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Updated Lunch"),
     *             @OA\Property(property="original_amount", type="number", format="float", example=55.00),
     *             @OA\Property(property="original_currency", type="string", example="USD"),
     *             @OA\Property(property="category", type="string", example="food"),
     *             @OA\Property(property="expense_date", type="string", format="date", example="2025-10-20")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Expense updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Expense updated successfully"),
     *             @OA\Property(property="expense", ref="#/components/schemas/Expense")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized to update this expense"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Expense not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
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

        // Log event to Firebase Analytics
        $this->firebase->dispatchEvent('expense_updated', [
            'user_id' => $request->user()->id,
            'expense_id' => $expense->id,
            'amount' => $expense->amount
        ]);

        return response()->json([
            'message' => 'Expense updated successfully',
            'expense' => $expense
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/expenses/{id}",
     *     summary="Delete an expense",
     *     tags={"Expenses"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Expense ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Expense deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Expense deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized to delete this expense"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Expense not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function destroy(Expense $expense)
    {
        // Only the expense owner can delete it
        Gate::authorize('modify', $expense);

        // Log event to Firebase Analytics before deleting
        $this->firebase->dispatchEvent('expense_deleted', [
            'user_id' => $expense->user_id,
            'expense_id' => $expense->id
        ]);

        $expense->delete();

        return response()->json([
            'message' => 'Expense deleted successfully'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/expenses/summary",
     *     summary="Get expense summary by category",
     *     tags={"Expenses"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         description="Start date",
     *         required=true,
     *         @OA\Schema(type="string", format="date", example="2025-10-01")
     *     ),
     *     @OA\Parameter(
     *         name="to",
     *         in="query",
     *         description="End date",
     *         required=true,
     *         @OA\Schema(type="string", format="date", example="2025-10-31")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Expense summary by category",
     *         @OA\JsonContent(
     *             @OA\Property(property="currency", type="string", example="USD"),
     *             @OA\Property(
     *                 property="summary",
     *                 type="object",
     *                 @OA\Property(property="food", type="number", format="float", example=450.75),
     *                 @OA\Property(property="travel", type="number", format="float", example=320.00),
     *                 @OA\Property(property="utilities", type="number", format="float", example=150.50),
     *                 @OA\Property(property="entertainment", type="number", format="float", example=85.25)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function summary(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        $user = $request->user();

        $expenses = Expense::where('user_id', $user->id)
            ->whereBetween('expense_date', [$request->from, $request->to])
            ->get();

        $summary = $expenses->groupBy('category')
            ->map(function ($categoryExpenses) {
                return round($categoryExpenses->sum('amount'), 2);
            });

        return response()->json([
            'currency' => $user->preferred_currency,
            'summary' => $summary
        ]);
    }
}
