<?php

namespace App\Recommender;

use App\FilmLike;

class Likes implements Rater
{
    public function usersForFilm($film)
    {
        return FilmLike::where('film_id', $film)->pluck('user_id');
    }

    public function filmsForUser($user)
    {
        return FilmLike::where('user_id', $user)->pluck('film_id');
    }
}