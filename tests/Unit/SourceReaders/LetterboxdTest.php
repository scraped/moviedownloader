<?php

namespace Tests\Unit\SourceReaders;

use Tests\TestCase;
use MovieDownloader\SourceReaders\Letterboxd;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

class LetterboxdTest extends TestCase
{
    /**
     * @test
     */
    public function it_retrieve_five_movies()
    {
        $mock = new MockHandler([
            // five movies (just one page)
            new Response(200, [], file_get_contents(base_path('tests/Stubs/letterboxd_watchlist_page_1.html'))),
            new Response(200, [], file_get_contents(base_path('tests/Stubs/letterboxd_watchlist_page_2.html'))),
            // five requests (one request per movie)
            new Response(200, [], file_get_contents(base_path('tests/Stubs/letterboxd_watchlist_movie_1.html'))),
            new Response(200, [], file_get_contents(base_path('tests/Stubs/letterboxd_watchlist_movie_2.html'))),
            new Response(200, [], file_get_contents(base_path('tests/Stubs/letterboxd_watchlist_movie_3.html'))),
            new Response(200, [], file_get_contents(base_path('tests/Stubs/letterboxd_watchlist_movie_4.html'))),
            new Response(200, [], file_get_contents(base_path('tests/Stubs/letterboxd_watchlist_movie_5.html'))),
        ]);
        $handler = HandlerStack::create($mock);

        $sourceReader = new Letterboxd(
            new Client(['handler' => $handler]),
            resolve(Crawler::class),
            'https://letterboxd.com/gustavobgama/watchlist'
        );

        $this->assertArraySubset([
            [
                'name' => 'The Handmaiden',
                'imdb' => '4016934',
                'year' => 2016,
            ],
            [
                'name' => 'The Dressmaker',
                'imdb' => '2910904',
                'year' => 2015,
            ],
            [
                'name' => 'A Ghost Story',
                'imdb' => '6265828',
                'year' => 2017,
            ],
            [
                'name' => 'Defending Your Life',
                'imdb' => '0101698',
                'year' => 1991,
            ],
            [
                'name' => 'The Dark Tower',
                'imdb' => '1648190',
                'year' => 2017,
            ],
        ], $sourceReader->getMovies());
    }
}
