<?php

namespace App\Transformers\Users;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'       => $user->id,
            'email'    => $user->email,
            'name'     => $user->name,
            'username' => $user->username,
        ];
    }
}
