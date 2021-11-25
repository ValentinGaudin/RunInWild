<?php

namespace App\Controller;

use Symfony\Component\HttpClient\HttpClient;

class SpotifyController extends AbstractController
{

    private string $clientId = 'ac2865071d374203af6c8d46629f7bcb';

    public function show()
    {
        if (!isset($_SESSION["token"])) {
            return $this->twig->render('Spotify/index.html.twig');
        }

        $token = $_SESSION["tokenType"] . ' ' . $_SESSION["token"];


        $client = HttpClient::create();

        $response = $client->request("GET", "https://api.spotify.com/v1/search?q=bpm&type=playlist&limit=10", [
            'headers' => [
                "Accept" => "application/json",
                "Content-Type" => "application/json",
                "Authorization" => $token
            ],

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
            // $player = $players->toArray();

            $playlists = $results['playlists']['items'];

            $id = $playlists['1']['id'];
            return $this->twig->render('Spotify/index.html.twig', [
                'results' => $results,
                'id' => $id, 
                // 'player' => $player
        ]);
        }
    }

    public function change($bpm)
    {
        $token = $_SESSION["tokenType"] . ' ' . $_SESSION["token"];
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

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $code = $_GET['code'];

            try {
                $infoToken = $this->getAccessToken($code);
            } catch (\Exception $exception) {
                return $this->twig->render('Error/error.html.twig', ['exception' => $exception]);
            }

            $_SESSION["token"] = $infoToken['access_token'];
            $_SESSION["refreshToken"] = $infoToken['refresh_token'];
            $_SESSION["tokenType"] = $infoToken['token_type'];
            return $this->twig->render('Spotify/index.html.twig', ['connexion' => 'Connexion réussie']);
        }

        return $this->twig->render('Spotify/index.html.twig', ['connexion' => 'Echec de connexion à spotify']);
    }

    public function authorize()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header("Location: https://accounts.spotify.com/authorize?client_id=$this->clientId&response_type=code&redirect_uri=http://localhost:8000/login");
            return;
        }

        return $this->twig->render('Spotify/index.html.twig');
    }

    public static function generateRandomString($length = 16)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function getAccessToken(string $code): array
    {
        $client = HttpClient::create();

        var_dump(CLIENT_64);
        die();

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
        throw new \Exception("Error code $statusCode", 1);
    }
}
