<?php

namespace App\Recommender;

use App\FilmDislike;

class Dislikes implements Rater
{
    public function usersForFilm($film)
    {
        return FilmDislike::where('film_id', $film)->pluck('user_id');
    }

    public function filmsForUser($user)
    {
        return FilmDislike::where('user_id', $user)->pluck('film_id');
    }
}