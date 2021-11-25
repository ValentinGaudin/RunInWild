<?php

namespace App\Controller;

use Symfony\Component\HttpClient\HttpClient;

class SpotifyController extends AbstractController
{
    public function show()
    {
        $client = HttpClient::create();
        
        $response = $client->request("GET","https://api.spotify.com/v1/search?q=180%20bpm&type=playlist&limit=10",[
            'query' => [
                "Accept" => "application/json",
                "Content-Type" => "application/json"],
        "auth_bearer" => "?token"]
        );

        if ($response->getStatusCode() == 200) {
            $results = $response->toArray();
            return $this->twig->render('Spotify/index.html.twig', ['results' => $results]);
        }
        return $response->getStatusCode();
        return 'il doit y avoir une erreur !';
    }
}