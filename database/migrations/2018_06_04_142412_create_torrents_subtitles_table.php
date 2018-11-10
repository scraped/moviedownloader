<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTorrentsSubtitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('torrent_subtitle', function (Blueprint $table) {
            $table->integer('fk_torrent');
            $table->integer('fk_subtitle');
            $table->decimal('score', 10, 2);
            $table->enum('status', ['created', 'to download', 'downloaded']);
            $table->timestamps();

            $table->primary(['fk_torrent', 'fk_subtitle']);
            $table->foreign('fk_torrent')->references('id')->on('torrents');
            $table->foreign('fk_subtitle')->references('id')->on('subtitles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('torrent_subtitle');
    }
}
