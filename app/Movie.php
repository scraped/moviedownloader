<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\MovieCreated;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    /**
     * @inheritDoc
     */
    protected $guarded = [];

    /**
     * @inheritDoc
     */
    protected $dispatchesEvents = [
        'created' => MovieCreated::class,
    ];

    /**
     * Get movie full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->name} {$this->year}";
    }

    /**
     * Get related torrents.
     *
     * @return HasMany
     */
    public function torrents()
    {
        return $this->hasMany(Torrent::class, 'fk_movie');
    }

    /**
     * Get related subtitles.
     *
     * @return HasMany
     */
    public function subtitles()
    {
        return $this->hasMany(Subtitle::class, 'fk_movie');
    }
}
