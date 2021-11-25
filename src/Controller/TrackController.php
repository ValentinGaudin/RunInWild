<?php

namespace App\Controller;

use Symfony\Component\HttpClient\HttpClient;

class TrackController extends AbstractController
{
    private const TOKEN = "BQD8sASTO3uJQ96F08DmAq99hhoBulYdNOxd-DaTVilmnYsvdEgmbmKV_z7hvVCeIavIdmmVRapO73fdw350C4C8LcpYmjzkBF6JJpqNpJsBiW-PJgQrOG1zvlG1VG5VyAnOrmbkzaIMBeqdGraA2SAcrpWgV5F5oA";

    public function track()
    {
        $token = self::TOKEN;

        $client = HttpClient::create();

        $response = $client->request("GET", "https://api.spotify.com/v1/me/player/currently-playing", [
            'headers' => [
                "Accept" => "application/json",
                "Content-Type" => "application/json"
            ],
            "auth_bearer" => $token
        ]);
        $players = $response -> getContent();

        return $players;
    }
}
