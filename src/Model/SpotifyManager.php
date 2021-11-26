<?php

namespace App\Model;

use Symfony\Component\HttpClient\HttpClient;
use Exception;

class SpotifyManager
{
    private string $clientId = 'ac2865071d374203af6c8d46629f7bcb';
    private $client;

    public function __construct()
    {
        $this->client = HttpClient::create();
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getPlaylistByBpm(int $bpm, int $limit = 1)
    {
        $response = $this->client->request(
            "GET",
            "https://api.spotify.com/v1/search?q=$bpm%20bpm&type=playlist&limit=$limit",
            [
                'query' => [
                    "Accept" => "application/json",
                    "Content-Type" => "application/json",
                ],
                'auth_bearer' => $_SESSION["token"]
            ]
        );

        $statuscode = $response->getStatusCode();

        if ($statuscode !== 200) {
            throw new Exception($statuscode);
        }

        $results = $response->toArray();
        $playlists = $results['playlists']['items'];
        return $playlists;
    }

    public function refreshToken()
    {
        $client = HttpClient::create();

        $response = $client->request('POST', 'https://accounts.spotify.com/api/token', [
            'headers' => [
                "Content-Type" => "application/x-www-form-urlencoded",
                "Authorization" => "Basic " . CLIENT_64
            ],
            'body' => [
                'grant_type' =>  "refresh_token",
                'refresh_token' => $_SESSION["refreshToken"]
            ],
        ]);

        $statusCode = $response->getStatusCode();
        if ($statusCode === 200) {
            $newTokken = $response->getContent();
            // get the response in JSON format

            $newToken = json_decode($newTokken, true);

            $_SESSION["token"] = $newToken['access_token'];
            $_SESSION["refreshToken"] = $newToken['refresh_token'];
            $_SESSION["tokenType"] = $newToken['token_type'];
            return;
        }
        throw new Exception("Error refresh spotify token : $statusCode");
    }

    public function getAccessToken(string $code): array
    {
        $client = HttpClient::create();

        $response = $client->request('POST', 'https://accounts.spotify.com/api/token', [
            'headers' => [
                "Content-Type" => "application/x-www-form-urlencoded",
                "Authorization" => "Basic " . CLIENT_64

            ],
            'body' => [
                'grant_type' =>  "authorization_code",
                'code' => $code,
                'redirect_uri' => 'http://localhost:8000/login',

            ],

        ]);

        $statusCode = $response->getStatusCode(); // get Response status code 200

        if ($statusCode === 200) {
            $contents = $response->getContent();
            // get the response in JSON format

            $accessToken = json_decode($contents, true);

            return $accessToken;
        }
        throw new \Exception("Error code $statusCode");
    }
}
