<?php

namespace App;

use App\FilmDislike;
use App\FilmLike;
use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'films',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'films' => 'array',
    ];

    public function scopeForUser($query)
    {
        return $query->where('user_id', auth()->user()->id);
    }

    public function refresh()
    {
        if ($suggestion = Suggestion::forUser()->first()) {
            $suggestion->delete();
        }

        $otherUsers = Similarity::forUser()->first(['other_users']);

        $userLikes = FilmLike::where('user_id', auth()->user()->id)->pluck('film_id');
        $userDislikes = FilmDislike::where('user_id', auth()->user()->id)->pluck('film_id');

        $films = collect($otherUsers->other_users)
            ->map(function ($otherUser) {
                return collect([new Likes(), new Dislikes()])
                    ->map(function ($rater) use ($otherUser) {
                        return $rater->filmsForUser($otherUser);
                    });
            })
            ->flatten()
            ->unique()
            ->diff($userLikes)
            ->diff($userDislikes)
            ->flatMap(function ($film) use ($otherUsers) {
                $likers = (new Likes())->usersForFilm($film);
                $dislikers = (new Dislikes())->usersForFilm($film);
                $numerator = 0;

                collect([$likers, $dislikers])
                    ->flatten()
                    ->reject(function ($user) {
                        return $user === auth()->user()->id;
                    })
                    ->each(function ($otherUser) use ($otherUsers, &$numerator) {
                        $otherUser = collect($otherUsers->other_users)->first(function ($value) use ($otherUser) {
                            return $value['user'] === $otherUser;
                        });

                        if ($otherUser) {
                            $numerator += $otherUser['similarity'];
                        }
                    });

                return [
                    [
                        'film' => $film,
                        'weight' => $numerator / $likers->merge($dislikers)->count()
                    ]
                ];
            });

        static::create([
            'user_id' => auth()->user()->id,
            'films' => $films
        ]);
    }
}
