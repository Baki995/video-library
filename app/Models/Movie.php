<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{  
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title', 'studio', 'slug', 'runtime', 'genre', 'description',
    ];

    protected $appends = ['favorite'];

    public function getFavoriteAttribute()
    {
        return $this->user_movies_count > 0;
    }

    public function user_movies()
    {
        return $this->belongsToMany(User::class, 'user_movies')->withTimestamps();
    }
}
