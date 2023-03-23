<?php

namespace App\Repository;

use App\Models\UserMovie;

class UserMovieRepository extends BaseRepository {
    public function __construct(UserMovie $userMovie)
    {
        parent::__construct($userMovie);
    }
}