<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubtitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subtitles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_movie');
            $table->string('movie_release_name');
            $table->decimal('rating', 10, 2);
            $table->smallInteger('votes');
            $table->mediumInteger('downloads');
            $table->string('link');
            $table->timestamps();

            $table->foreign('fk_movie')->references('id')->on('movies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subtitles');
    }
}
