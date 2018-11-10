<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Torrent extends Model
{
    /**
     * @inheritDoc
     */
    protected $guarded = [];

    /**
     * Related movie.
     *
     * @return BelongsTo
     */
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'fk_movie');
    }

    /**
     * Get matched subtitles.
     *
     * @return BelongsToMany
     */
    public function subtitles()
    {
        return $this->belongsToMany(Subtitle::class, 'torrent_subtitle', 'fk_torrent', 'fk_subtitle')
            ->using(TorrentSubtitle::class)
            ->withTimestamps();
    }
}
