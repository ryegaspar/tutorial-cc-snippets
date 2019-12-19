<?php

namespace App\Policies;

use App\Snippet;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SnippetPolicy
{
    use HandlesAuthorization;

    /**
     * @param User|null $user
     * @param Snippet $snippet
     * @return bool
     */
    public function show(?User $user, Snippet $snippet)
    {
        if ($snippet->isPublic()) {
            return true;
        }

        return optional($user)->id === $snippet->user_id;
    }

    public function update(User $user, Snippet $snippet)
    {
        return $user->id === $snippet->user_id;
    }

    public function destroy(User $user, Snippet $snippet)
    {
        return $user->id === $snippet->user_id;
    }

    public function storeStep(User $user, Snippet $snippet)
    {
        return $user->id === $snippet->user_id;
    }
}
