<?php

namespace App\Recommender;

class Engine
{
    private $likes;
    private $dislikes;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->likes = new Likes();
        $this->dislikes = new Dislikes();
    }

    /**
     * @return Rater
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @return Rater
     */
    public function getDislikes()
    {
        return $this->dislikes;
    }
}
