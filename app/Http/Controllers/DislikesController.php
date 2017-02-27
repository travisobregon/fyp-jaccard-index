<?php

namespace App\Http\Controllers;

use App\FilmLike;
use App\FilmDislike;
use Illuminate\Http\Request;

class DislikesController extends Controller
{
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

        $filmDislike = FilmDislike::firstOrNew($data);

        if (! $filmDislike->exists) {
            $filmDislike->save();
        }

        $filmLike = FilmLike::where($data)->first();

        if ($filmLike) {
            $filmLike->delete();
        }

        return back();
    }
}
