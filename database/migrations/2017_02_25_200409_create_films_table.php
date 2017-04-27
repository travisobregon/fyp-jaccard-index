<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('films', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('language_id');
            $table->string('title');
            $table->text('description');
            $table->unsignedInteger('release_year');
            $table->tinyInteger('rental_duration')->default(3);
            $table->decimal('rental_rate', 4, 2)->default(4.99);
            $table->smallInteger('length');
            $table->decimal('replacement_cost', 5, 2)->default(19.99);
            $table->enum('rating', ['G', 'PG', 'PG-13', 'R', 'NC-17'])->default('G');
            $table->unsignedTinyInteger('stars')->default(0);
            $table->json('special_features')->nullable();
            $table->timestamps();

            $table->foreign('language_id')
                  ->references('id')->on('languages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('films');
    }
}
