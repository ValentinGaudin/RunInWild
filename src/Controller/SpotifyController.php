<?php

namespace App\Controller;

use Symfony\Component\HttpClient\HttpClient;

class SpotifyController extends AbstractController
{

    private const TOKEN = "BQD8sASTO3uJQ96F08DmAq99hhoBulYdNOxd-DaTVilmnYsvdEgmbmKV_z7hvVCeIavIdmmVRapO73fdw350C4C8LcpYmjzkBF6JJpqNpJsBiW-PJgQrOG1zvlG1VG5VyAnOrmbkzaIMBeqdGraA2SAcrpWgV5F5oA";

    public function show()
    {

        $token = self::TOKEN;
        $client = HttpClient::create();

        $response = $client->request("GET", "https://api.spotify.com/v1/search?q=bpm&type=playlist&limit=10", [
            'headers' => [
                "Accept" => "application/json",
                "Content-Type" => "application/json"
            ],
            "auth_bearer" => $token
        ]);

        // $players = $client->request("GET", "https://api.spotify.com/v1/me/player/currently-playing", [
        //     'headers' => [
        //         "Accept" => "application/json",
        //         "Content-Type" => "application/json"
        //     ],
        //     "auth_bearer" => $token
        // ]);

        if ($response->getStatusCode() == 200) {
            $results = $response->toArray();
            // $player = $players->toArray();

            $playlists = $results['playlists']['items'];
            $id = $playlists['1']['id'];
            return $this->twig->render('Spotify/index.html.twig', [
                'results' => $results,
                'id' => $id,
                //'player' => $player
            ]);
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
