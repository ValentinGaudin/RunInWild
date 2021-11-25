<?php

namespace App\Controller;

use Symfony\Component\HttpClient\HttpClient;

class SpotifyController extends AbstractController
{
    public function show()
    {
        $token = "?TOKEN";
        $client = HttpClient::create();

        $response = $client->request("GET", "https://api.spotify.com/v1/search?q=bpm&type=playlist&limit=10", [
            'query' => [
                "Accept" => "application/json",
                "Content-Type" => "application/json"],
            "auth_bearer" => $token]);

        if ($response->getStatusCode() == 200) {
            $results = $response->toArray();
            $playlists = $results['playlists']['items'];
            $id = $playlists['1']['id'];
            return $this->twig->render('Spotify/index.html.twig', ['results' => $results, 'id' => $id]);
        }
        return $response->getStatusCode();
    }
}
