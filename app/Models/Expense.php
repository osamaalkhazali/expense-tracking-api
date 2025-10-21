<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Expense",
 *     type="object",
 *     title="Expense",
 *     required={"id","user_id","title","amount","category","expense_date"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Grocery Shopping"),
 *     @OA\Property(property="amount", type="number", format="float", example=50.25, description="Amount in user's preferred currency"),
 *     @OA\Property(property="original_amount", type="number", format="float", example=45.50, description="Original amount entered"),
 *     @OA\Property(property="original_currency", type="string", example="EUR", description="Original currency code"),
 *     @OA\Property(property="category", type="string", example="food", enum={"food","travel","utilities","entertainment","healthcare","shopping","education","other"}),
 *     @OA\Property(property="expense_date", type="string", format="date", example="2025-10-20"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-10-20T10:30:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-10-20T10:30:00.000000Z")
 * )
 */
class Expense extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'amount',
        'original_amount',
        'original_currency',
        'category',
        'expense_date',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'original_amount' => 'decimal:2',
            'expense_date' => 'date',
        ];
    }

    /**
     * Get the user that owns the expense.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
