<?php

namespace App\Controller;

use Symfony\Component\HttpClient\HttpClient;

class TrackController extends AbstractController
{
    public function trackPlaylist()
    {

        $client = HttpClient::create();

        $response = $client->request("GET", "https://api.spotify.com/v1/search?q=name%3Abpm&type=playlist&limit=40", [
            'headers' => [
                "Accept" => "application/json",
                "Content-Type" => "application/json"
            ],
            "auth_bearer" => $_SESSION['token']
        ]);
        $playlist = $response -> getContent();

        return $playlist;
    }

    public function trackTrack()
    {
        $client = HttpClient::create();

        $response = $client->request("GET", "https://api.spotify.com/v1/search?q=bpm&type=track", [
            'headers' => [
                "Accept" => "application/json",
                "Content-Type" => "application/json"
            ],
            "auth_bearer" => $_SESSION['token']
        ]);
        $track = $response -> getContent();

        return $track;
    }

    public function device()
    {
        $client = HttpClient::create();

        $response = $client->request("GET", "https://api.spotify.com/v1/me/player/devices", [
        'headers' => [
            "Accept" => "application/json",
            "Content-Type" => "application/json"
        ],
        "auth_bearer" => $_SESSION['token']
        ]);
        $device = $response -> getContent();

        return $device;
    }
}

//https://api.spotify.com/v1/search?q=name%3Abpm&type=playlist&limit=40
//https://api.spotify.com/v1/search?q=bpm&type=track
