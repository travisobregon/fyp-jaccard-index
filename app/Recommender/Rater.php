<?php

namespace App\Recommender;

interface Rater
{
    public function filmsForUser($user);
    public function usersForFilm($film);
}
