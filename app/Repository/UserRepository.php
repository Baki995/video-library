<?php

namespace App\Repository;

use App\Models\User;

class UserRepository extends BaseRepository {
    public function __construct(User $user)
    {
        parent::__construct($user);
    }
}