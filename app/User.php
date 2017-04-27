<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address_id', 'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_staff' => 'boolean',
    ];

    /**
     * A user may like multiple films.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes() {
        return $this->hasMany(FilmLike::class);
    }

    /**
     * A user may dislike multiple films.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dislikes() {
        return $this->hasMany(FilmDislike::class);
    }

    /**
     * Determine whether a user rated the given film.
     *
     * @param Film $film
     * @return bool
     */
    public function rated(Film $film)
    {
        return $film->ratings->contains(function ($rating) {
            return auth()->id() === $rating->user_id;
        });
    }

    /**
     * Get the stars the authenticated user gave for the given film.
     *
     * @param Film $film
     * @return int
     */
    public function ratingFor(Film $film)
    {
        return $film->ratings()->where('user_id', auth()->id())->first()->stars;
    }

    /**
     * A user may rate multiple films.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
