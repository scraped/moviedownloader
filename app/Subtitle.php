<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subtitle extends Model
{
    /**
     * @inheritDoc
     */
    protected $guarded = [];

    /**
     * Get related movie.
     *
     * @return BelongsTo
     */
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'fk_movie');
    }

    /**
     * Get matched torrents.
     *
     * @return BelongsToMany
     */
    public function torrents()
    {
        return $this->belongsToMany(Torrent::class, 'torrent_subtitle', 'fk_subtitle', 'fk_torrent');
    }
}
