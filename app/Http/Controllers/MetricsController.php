<?php

namespace App\Http\Controllers;

use App\PredictedRating;
use App\Rating;

class MetricsController extends Controller
{
    /**
     * Show the metrics for the recommender system.
     *
     * @return Response
     */
    public function __invoke()
    {
        $meanAbsoluteError = $this->getMeanAbsoluteError();
        $rootMeanSquareError = $this->getRootMeanSquareError();

        return view('metrics', compact('meanAbsoluteError', 'rootMeanSquareError'));
    }

    /**
     * Calculate the Mean Absolute Error
     *
     * @return float|int
     */
    protected function getMeanAbsoluteError()
    {
        $actualRatings = Rating::where('was_suggested', true)->get();

        $sum = $actualRatings->map(function ($actualRating) {
            $predictedRating = PredictedRating::where('user_id', $actualRating->user_id)
                ->where('film_id', $actualRating->film_id)
                ->first();

            return abs($predictedRating->stars - $actualRating->stars);
        })->sum();

        return $sum / $actualRatings->count();
    }

    /**
     * Calculate the Root Mean Square Error
     *
     * @return float|int
     */
    protected function getRootMeanSquareError()
    {
        $actualRatings = Rating::where('was_suggested', true)->get();

        $sum = $actualRatings->map(function ($actualRating) {
            $predictedRating = PredictedRating::where('user_id', $actualRating->user_id)
                ->where('film_id', $actualRating->film_id)
                ->first();

            return pow($predictedRating->stars - $actualRating->stars, 2);
        })->sum();

        return sqrt($sum / $actualRatings->count());
    }
}
