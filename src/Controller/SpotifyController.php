<?php

namespace App\Controller;

use Symfony\Component\HttpClient\HttpClient;

class SpotifyController extends AbstractController
{
    public function show()
    {
        $token = "BQBgvqqYQriQSqphACE69_vo7eBV3DZtQkoNV8dbIf2nNYkipZ2e7OKzxGGV5jM5kmyEePbXwmwU478zChlknwY1m8e8cHkaXUAo-8moBY-WKQDLipeV59jGpa_YEzx94cqUmDL20aGTE4gYh3Hn";
        $client = HttpClient::create();

        $response = $client->request("GET", "https://api.spotify.com/v1/search?q=bpm&type=playlist&limit=10", [
            'query' => [
                "Accept" => "application/json",
                "Content-Type" => "application/json"],
            "auth_bearer" => $token]);

            $players = $client->request("GET", "https://api.spotify.com/v1/me/player", [
                'headers' => [
                    "Accept" => "application/json",
                    "Content-Type" => "application/json"],
                "auth_bearer" => $token]);

        if ($response->getStatusCode() == 200) {
            $results = $response->toArray();
            $player = $players->getContent();

            $playlists = $results['playlists']['items'];
            $id = $playlists ['1']['id'];
            return $this->twig->render('Spotify/index.html.twig', ['results' => $results, 'id' => $id, 'player' => $player]);
        }

        return $response->getStatusCode();
    }
}
