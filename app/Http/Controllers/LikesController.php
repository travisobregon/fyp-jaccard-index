<?php

namespace App\Http\Controllers;

use App\FilmLike;
use App\FilmDislike;
use App\Recommender\Engine;
use App\Recommender\Similarity;
use App\Recommender\Suggestion;
use Illuminate\Http\Request;

class LikesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'film_id' => 'required|exists:films,id',
        ]);

        $data = [
            'film_id' => $request->film_id,
            'user_id' => auth()->user()->id
        ];

        $filmLike = FilmLike::firstOrNew($data);

        if (! $filmLike->exists) {
            $filmLike->save();
        }

        $filmDislike = FilmDislike::where($data)->first();

        if ($filmDislike) {
            $filmDislike->delete();
        }

        (new Similarity())->refresh();
        (new Suggestion())->refresh();

        return back();
    }
}
