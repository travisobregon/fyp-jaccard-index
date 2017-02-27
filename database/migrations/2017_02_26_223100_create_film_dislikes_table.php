<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilmDislikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('film_dislikes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('film_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->foreign('film_id')
                  ->references('id')->on('films')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('film_dislikes');
    }
}
