<?php

namespace App\Policies;

use App\Models\User;
use App\Models\QuizResponse;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class QuizResponsePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create quiz responses.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if(!Gate::allows('view', $quizResponse->quiz)) {
            return false;
        }
    }
}
