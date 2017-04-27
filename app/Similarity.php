<?php

namespace App;

use App\FilmDislike;
use App\FilmLike;
use Illuminate\Database\Eloquent\Model;

class Similarity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'other_users',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'other_users' => 'array',
    ];

    public function scopeForUser($query)
    {
        return $query->where('user_id', auth()->user()->id);
    }

    public function refresh()
    {
        if ($similarity = Similarity::forUser()->first()) {
            $similarity->delete();
        }

        $userLikes = FilmLike::where('user_id', auth()->user()->id)->pluck('film_id');
        $userDislikes = FilmDislike::where('user_id', auth()->user()->id)->pluck('film_id');

        $similarities = $userLikes->merge($userDislikes)
            ->map(function ($film) {
                return collect([new Likes(), new Dislikes()])
                    ->map(function ($rater) use ($film) {
                        return $rater->usersForFilm($film);
                    });
            })
            ->flatten()
            ->unique()
            ->reject(function ($user) {
                return $user === auth()->user()->id;
            })
            ->flatten()
            ->map(function ($otherUser) use ($userLikes, $userDislikes) {
                $otherUserLikes = FilmLike::where('user_id', $otherUser)->pluck('film_id');
                $otherUserDislikes = FilmDislike::where('user_id', $otherUser)->pluck('film_id');

                return [
                    'user' => $otherUser,
                    'similarity' => ($userLikes->intersect($otherUserLikes)->count() +
                                    $userDislikes->intersect($otherUserDislikes)->count() -
                                    $userLikes->intersect($otherUserDislikes)->count() -
                                    $userDislikes->intersect($otherUserLikes)->count()) /
                                    $userLikes->merge($otherUserLikes)->merge($userDislikes)->merge($otherUserDislikes)->unique()->count()
                ];
            });

        static::create([
            'user_id' => auth()->user()->id,
            'other_users' => $similarities
        ]);
    }
}
