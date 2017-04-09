<?php

namespace App\Jobs\SubtitleSearchers;


class OpenSubtitles implements SubtitleSearcherInterface
{
    const SEARCH_URL = 'http://api.opensubtitles.org/xml-rpc';

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $language;

    /**
     * @var string
     */
    protected $userAgent;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var array
     */
    protected $subtitles;

    /**
     * OpenSubtitles constructor.
     * @param $username
     * @param $password
     * @param $language
     * @param string $userAgent
     */
    public function __construct($username, $password, $language, $userAgent = 'OSTestUserAgentTemp')
    {
        $this->username = $username;
        $this->password = $password;
        $this->language = $language;
        $this->userAgent = $userAgent;
        $this->token = '';
        $this->subtitles = [];
    }

    /**
     * Authenticate
     *
     * @return void
     */
    protected function login()
    {
        $request  = xmlrpc_encode_request(
            'LogIn',
            [$this->username, $this->password, $this->language, $this->userAgent]
        );
        $response = $this->makeRequest($request);
        $this->token = $response['token'];
    }

    public function getAll($imdbId, $tags)
    {
        $this->login();
        $request  = xmlrpc_encode_request(
            'SearchSubtitles',
            [
                $this->token,
                [
                    ['sublanguageid' => $this->language, 'imdbid' => $imdbId, 'tags' => implode(',', $tags)],
                ],
            ]
        );
        $response = $this->makeRequest($request);
        $subtitles = $this->transform($response['data']);
        $this->subtitles = $subtitles;

        return $subtitles;
    }

    /**
     * @param $subtitles
     * @return mixed
     */
    protected function transform($subtitles)
    {
        $keysToKeep = ['IDSubtitleFile', 'MovieReleaseName', 'SubRating', 'SubDownloadLink'];
        foreach ($subtitles as &$subtitle) {
            foreach ($subtitle as $key => $value) {
                if (array_search($key, $keysToKeep) !== false) {
                    continue;
                }
                unset($subtitle[$key]);
            }
        }

        return $subtitles;
    }

    /**
     * Make the remote procedure call
     *
     * @param $content
     *
     * @throws \Exception
     *
     * @return mixed
     */
    protected function makeRequest($content)
    {
        $context  = stream_context_create(
            [
                'http' => [
                    'method'  => "POST",
                    'header'  => "Content-Type: text/xml",
                    'content' => $content,
                ]
            ]
        );
        $file = file_get_contents(self::SEARCH_URL, false, $context);
        $response = xmlrpc_decode($file);
        if ($response && xmlrpc_is_fault($response)) {
            throw new \Exception("Failed: {$response['faultString']} ({$response['faultCode']})");
        }
        if (empty($response['status']) || $response['status'] != '200 OK') {
            throw new \Exception("Failed: the response status is {$response['status']}");
        }

        return $response;
    }

}