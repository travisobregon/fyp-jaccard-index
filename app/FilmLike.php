<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FilmLike extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'film_id', 'user_id',
    ];
}
