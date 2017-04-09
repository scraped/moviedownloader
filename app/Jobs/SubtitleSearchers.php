<?php

namespace App\Jobs;


use App\Jobs\SubtitleSearchers\OpenSubtitles;
use App\Jobs\SubtitleSearchers\SubtitleSearcherInterface;

class SubtitleSearchers
{

    /**
     * @var array
     */
    protected $subtitleSearchers;

    /**
     * SubtitleSearchers constructor.
     *
     * @param $adapters
     */
    public function __construct($adapters)
    {
        // TODO: assemble class name and load dynamically
        foreach ($adapters as $name => $adapter) {
            $this->subtitleSearchers[] = new OpenSubtitles(
                $adapter['username'],
                $adapter['password'],
                $adapter['language'],
                $adapter['user_agent']
            );
        }
    }

    /**
     * Get subtitles based on imdb identifier
     *
     * @param $imdbId
     * @param $tags
     *
     * @return array
     */
    public function getAll($imdbId, $tags)
    {
        $results = [];
        /** @var SubtitleSearcherInterface $subtitleSearcher */
        foreach ($this->subtitleSearchers as $subtitleSearcher) {
            $results = $subtitleSearcher->getAll($imdbId, $tags);
        }

        return $results;
    }

}