<?php

namespace App\Controller;

use Symfony\Component\HttpClient\HttpClient;

class TrackController extends AbstractController
{
    private const TOKEN = "BQDVb11PQv8g0zn8jTIKLpvdZVe5MSWZC450o38pRwq01HauAUjSBuRe_FMkMr1dUbPMQvOETjfpFzoVzrf8JJNKgqUhtFHJA7MDauQfxtJziqYXFHuSVzRnBBHqSFTZ42nQAtRC7vwJ0QAcOYGdE_7E4grwBIX9LQ";

    public function trackPlaylist()
    {
        $token = self::TOKEN;

        $client = HttpClient::create();

        $response = $client->request("GET", "https://api.spotify.com/v1/search?q=name%3Abpm&type=playlist&limit=40", [
            'headers' => [
                "Accept" => "application/json",
                "Content-Type" => "application/json"
            ],
            "auth_bearer" => $token
        ]);
        $track = $response -> getContent();

        
        return $track;

    }

}

//https://api.spotify.com/v1/search?q=name%3Abpm&type=playlist&limit=40
//https://api.spotify.com/v1/search?q=bpm&type=track