<?php

namespace App\Http\Controllers;

use App\Film;
use App\PredictedRating;
use App\Rating;
use App\Similarity;
use App\Suggestion;
use App\User;

class RatingsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'filmId' => 'required|exists:films,id',
        ]);

        $suggestion = Suggestion::find(auth()->id());
        $wasSuggested = $suggestion ? collect($suggestion->films)->has(request()->filmId) : false;

        Rating::updateOrCreate(
            ['user_id' => auth()->id(), 'film_id' => request()->filmId],
            ['stars' => request()->rating, 'was_suggested' => $wasSuggested]
        );

        $film = Film::find(request()->filmId);
        $film->stars = round(Rating::where('film_id', request()->filmId)->avg('stars'));
        $film->save();

        [$userLikes, $userDislikes] = auth()->user()->ratings->partition(function ($rating) {
            return $rating->stars >= 3;
        });

        $userLikes = $userLikes->pluck('film_id');
        $userDislikes = $userDislikes->pluck('film_id');

        $similarities = auth()->user()->ratings->map(function ($rating) {
            return Rating::where('film_id', $rating->film_id)
                ->where('user_id', '<>', auth()->id())
                ->pluck('user_id');
        })
        ->flatten()
        ->unique()
        ->map(function ($otherUser) use ($userLikes, $userDislikes) {
            [$otherUserLikes, $otherUserDislikes] = Rating::where('user_id', $otherUser)->get()->partition(function ($rating) {
                return $rating->stars >= 3;
            });

            $otherUserLikes = $otherUserLikes->pluck('film_id');
            $otherUserDislikes = $otherUserDislikes->pluck('film_id');

            return [
                'user_id' => $otherUser,
                'similarity' => ($userLikes->intersect($otherUserLikes)->count() +
                                 $userDislikes->intersect($otherUserDislikes)->count() -
                                 $userLikes->intersect($otherUserDislikes)->count() -
                                 $userDislikes->intersect($otherUserLikes)->count()) /
                                 $userLikes->merge($otherUserLikes)->merge($userDislikes)->merge($otherUserDislikes)->unique()->count()
            ];
        });

        $similarity = Similarity::updateOrCreate(
            ['user_id' => auth()->id()],
            ['other_users' => $similarities]
        );

        $suggestions = [];

        $nearestUsers = collect($similarity->other_users)
            ->sortByDesc('similarity')
            ->take(3);

        $totalDistance = $nearestUsers->sum('similarity');

        foreach ($nearestUsers as $nearestUser) {
            $weight = $nearestUser['similarity'] / $totalDistance;
            $neighbour = User::with('ratings')->find($nearestUser['user_id']);

            $neighbourRatings = $neighbour->ratings->reject(function ($otherUserRating) {
                return auth()->user()->ratings->contains(function ($userRating) use ($otherUserRating) {
                    return $userRating->film_id === $otherUserRating->film_id;
                });
            });

            foreach ($neighbourRatings as $neighbourRating) {
                if (isset($suggestions[$neighbourRating->film_id])) {
                    $suggestions[$neighbourRating->film_id] = $suggestions[$neighbourRating->film_id] + $neighbourRating->stars * $weight;
                } else {
                    $suggestions[$neighbourRating->film_id] = $neighbourRating->stars * $weight;
                }
            }
        };

        arsort($suggestions);

        $suggestions = collect($suggestions)->take(10)
            ->map(function ($weight, $filmId) use ($nearestUsers) {
                $predictedRating = 0.0;
                $totalDistance = 0.0;
                $neighbours = [];

                foreach ($nearestUsers as $nearestUser) {
                    $neighbour = User::with('ratings')->find($nearestUser['user_id']);

                    if ($neighbour->ratings->contains(function ($rating) use ($filmId) {
                        return $filmId == $rating->film_id;
                    })) {
                        $totalDistance += $nearestUser['similarity'];
                        $neighbours[] = $nearestUser;
                    }
                }

                foreach ($neighbours as $neighbour) {
                    $stars = Rating::where('user_id', $neighbour['user_id'])->where('film_id', $filmId)->first()->stars;
                    $predictedRating += $stars * ($neighbour['similarity'] / $totalDistance);
                }

                PredictedRating::updateOrCreate(
                    ['user_id' => auth()->id(), 'film_id' => $filmId],
                    ['stars' => $predictedRating]
                );

                return $filmId;
            })
            ->keys();

        Suggestion::updateOrCreate(
            ['user_id' => auth()->id()],
            ['films' => $suggestions]
        );

        return $film->stars;
    }

    /*
     * Process ratings that are at least 3 stars.
     */
    protected function handleLike()
    {

    }

    /*
     * Process ratings that are less than 3 stars.
     */
    protected function handlDisLike()
    {

    }
}
