<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\User;

class QuizPolicy
{
    public function create(User $user): bool
    {
        return $user->role === 'lecturer';
    }

    public function manage(User $user, Quiz $quiz): bool
    {
        return $user->role === 'lecturer' && $quiz->user_id === $user->id;
    }
}
