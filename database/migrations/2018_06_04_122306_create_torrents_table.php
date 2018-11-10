<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTorrentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('torrents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_movie');
            $table->string('name');
            $table->smallInteger('seeders');
            $table->smallInteger('leechers');
            $table->string('magnet_url', 1024);
            $table->decimal('size', 10, 2);
            $table->string('client_hash')->nullable();
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
        Schema::dropIfExists('torrents');
    }
}
