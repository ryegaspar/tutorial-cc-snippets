<?php

namespace App\Transformers\Users;

use App\User;
use League\Fractal\TransformerAbstract;

class PublicUserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'     => $user->id,
            'name'   => $user->name,
            'joined' => $user->created_at->toDateTimeString(),
        ];
    }
}
