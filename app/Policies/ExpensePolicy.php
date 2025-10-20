<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ExpensePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given expense can be modified by the user.
     */
    public function modify(User $user, Expense $expense): Response
    {
        return $user->id === $expense->user_id
            ? Response::allow()
            : Response::deny('You do not have permission to modify this expense.');
    }
}
