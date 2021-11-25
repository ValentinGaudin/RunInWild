<?php

namespace App\Controller;

use Symfony\Component\HttpClient\HttpClient;

class SpotifyController extends AbstractController
{

    private const TOKEN = "BQA1lNjjmORVSuZ2ofiPIyJKoQVClT0YUmGmP-yVm7zagi0aT5yfs902RskxT9qA7X3pDXb1uz0w1QD0cKUiAX3DaZUboi4ewOWOWn0snrp_FEq0XJrF80ASt92dTo7reQqs4R-FS9P8UYrvhyc9qlSDv3gBFAk";

    public function show()
    {


        $token = self::TOKEN;

        $client = HttpClient::create();

        $response = $client->request("GET", "https://api.spotify.com/v1/search?q=bpm&type=playlist&limit=10", [
            'query' => [
                "Accept" => "application/json",
                "Content-Type" => "application/json"
            ],
            "auth_bearer" => $token
        ]);

        // $players = $client->request("GET", "https://api.spotify.com/v1/me/player", [
        //     'headers' => [
        //         "Accept" => "application/json",
        //         "Content-Type" => "application/json"
        //     ],
        //     "auth_bearer" => $token
        // ]);

        if ($response->getStatusCode() == 200) {
            $results = $response->toArray();
            // $player = $players->getContent();

            $playlists = $results['playlists']['items'];

            $id = $playlists['1']['id'];
            return $this->twig->render('Spotify/index.html.twig', ['results' => $results, 'id' => $id]);
        }

        return $response->getStatusCode();
    }

    public function change($bpm)
    {
        $token = self::TOKEN;
        $client = HttpClient::create();

        $response = $client->request("GET", "https://api.spotify.com/v1/search?q=bpm&type=playlist&limit=10", [
            'query' => [
                "Accept" => "application/json",
                "Content-Type" => "application/json"
            ],
            "auth_bearer" => $token
        ]);

        if ($response->getStatusCode() == 200) {
            $results = $response->toArray();
            $playlists = $results['playlists']['items'];

            $filteredPlaylists = $this->getFilteredPlaylists($playlists, $bpm);
            $randId = rand(0, count($filteredPlaylists) - 1);

            $id = $filteredPlaylists[$randId]['id'];
            return $this->twig->render('Spotify/index.html.twig', ['id' => $id]);
        }
        return $response->getStatusCode();
    }

    private function getFilteredPlaylists($playlists, $key)
    {
        $filteredPlaylists = [];

        foreach ($playlists as $playlist) {
            if (str_contains($playlist['name'], $key)) {
                $filteredPlaylists[] = $playlist;
            };
        }

        return $filteredPlaylists;
    }
}
