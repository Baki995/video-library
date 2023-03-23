<?php

namespace App\Repository;

use App\Models\Movie;
use Illuminate\Support\Str;

class MovieRepository extends BaseRepository {
    public function __construct(Movie $movie)
    {
        parent::__construct($movie);
    }

    public function getListPaginated(int  $perPage, string $userId, string $search = null, string $myMovies = null) {
        $movies = $this->model::withCount(['user_movies' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }]);

        if ($search) {
            $movies = $movies->where('title', 'ilike', '%' . $search . '%');
        }

        if ($myMovies === 'true') {
            $movies = $movies->whereHas('user_movies', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->paginate($perPage);
        } else {
            $movies = $movies->paginate($perPage);
        }
        return $movies;
    }

    public function generateUniqueSlug(string $title, string $id = null): string
    {
        $slug = Str::slug($title, '-');
        $exist = $this->model::where('slug', $slug);
        if ($id) {
            $exist = $exist->where('id', '<>', $id);
        }
        $exist = $exist->first();

        while ($exist) {
            $slug = Str::slug($title, '-') . '-' . Str::lower(Str::random(8));
            $exist = $this->model::where([ 'slug' => $slug ])->first();
        }

        return $slug;
    }
}