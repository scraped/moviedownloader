<?php

namespace App\Jobs\SubtitleSearchers;

interface SubtitleSearcherInterface
{

    /**
     * Get subtitles based on imdb identifier
     *
     * @param  string $imdbId
     * @param  array $tags
     *
     * @return array
     */
    public function getAll($imdbId, $tags);
}