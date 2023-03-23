<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Models\Movie;
use App\Models\UserMovie;
use App\Repository\MovieRepository;
use App\Repository\UserMovieRepository;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    private MovieRepository $_movieRepository;
    private UserMovieRepository $_userMovieRepository;

    public function __construct(MovieRepository $movieRepository, UserMovieRepository $userMovieRepository) {
        $this->_movieRepository = $movieRepository;
        $this->_userMovieRepository = $userMovieRepository;
    }

    public function index(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $perPage = $request->query('perPage', 10);
        $search = $request->query('search', null);
        $myMovies = $request->query('myMovies', null);

        return $this->_movieRepository->getListPaginated($perPage, $user->id, $search, $myMovies);
    }

    public function store(StoreMovieRequest $request)
    {
        $input = $request->all();
        $input['slug'] = $this->_movieRepository->generateUniqueSlug($input['title']);
        return $this->_movieRepository->create($input);
    }

    public function show(string $id)
    {
        $movie = $this->_movieRepository->findOneById($id);
        if (!$movie) {
            return response()->json(['message' => 'Movie does not exist'], 404);
        }

        return  $movie;
    }

    public function update(StoreMovieRequest $request, string $id)
    {
        $input = $request->all();
        $exist = $this->show($id);

        if (!($exist instanceof Movie)) {
            return $exist;
        }

        $input['slug'] = $this->_movieRepository->generateUniqueSlug($input['title'], $exist->id);
        return $this->_movieRepository->update($id, $input);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->_movieRepository->delete($id);
        return response()->json([ 'success' => true ]);
    }

    public function toggleMovieFollow(String $movieId): JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();
        $exist = $this->_userMovieRepository->findOne([ 'user_id' => $user->id, 'movie_id' => $movieId ]);

        $exist ? $user->movies()->detach([$movieId]) : $user->movies()->attach([$movieId]);

        return response()->json([ 'follow' => !$exist ]);
    }
}
