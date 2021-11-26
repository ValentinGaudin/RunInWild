<?php

namespace App\Controller;

use App\Model\SpotifyManager;
use Exception;

class SpotifyController extends AbstractController
{

    private SpotifyManager $spotifyManager;

    public function __construct()
    {
        parent::__construct();
        $this->spotifyManager = new SpotifyManager();
    }

    public function show()
    {
        if (!isset($_SESSION["token"])) {
            if (isset($_SESSION['connexion'])) {
                return $this->twig->render('Spotify/index.html.twig', ['connexion' => $_SESSION['connexion']]);
            }
            return $this->twig->render('Spotify/index.html.twig');
        }

        try {
            $playlists = $this->spotifyManager->getPlaylistByBpm(120, 10);
        } catch (Exception $exception) {
            try {
                $this->spotifyManager->refreshToken();
                $playlists = $this->spotifyManager->getPlaylistByBpm(120, 10);
            } catch (Exception $exception) {
                return $this->twig->render('Error/error.html.twig', ['exception' => $exception]);
            }
        }


        $id = $playlists['1']['id'];
        $this->spotifyManager->play($id);

        return $this->twig->render('Spotify/index.html.twig', ['results' => $playlists, 'id' => $id, 'connexion' => $_SESSION['connexion'], 'session' => 1]);
    }

    public function change($target, $bpm, $actual)
    {
        try {
            $playlists = $this->spotifyManager->getPlaylistByBpm($bpm, 10);
        } catch (Exception $exception) {
            try {
                $this->spotifyManager->refreshToken();
                $playlists = $this->spotifyManager->getPlaylistByBpm($bpm, 10);
            } catch (Exception $exception) {
                return $this->twig->render('Error/error.html.twig', ['exception' => $exception]);
            }
        }


        $randId = rand(0, count($playlists) - 1);

        $id = $playlists[$randId]['id'];
        $this->spotifyManager->play($id);
        return $this->twig->render('Spotify/index.html.twig', ['id' => $id, 'target' => $target, 'actual' => $actual, 'session' => 1]);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $code = $_GET['code'];
            try {
                $infoToken = $this->spotifyManager->getAccessToken($code);
            } catch (\Exception $exception) {
                return $this->twig->render('Error/error.html.twig', ['exception' => $exception]);
            }

            $_SESSION["token"] = $infoToken['access_token'];
            $_SESSION["refreshToken"] = $infoToken['refresh_token'];
            $_SESSION["tokenType"] = $infoToken['token_type'];
            $_SESSION["connexion"] = 'Connexion réussie';
            header('Location:/');
            return;
        }

        $_SESSION["connexion"] = 'Echec connexion';
        header('Location:/');
        return;
    }

    public function authorize()
    {
        $clientId = $this->spotifyManager->getClientId();
        header("Location: https://accounts.spotify.com/authorize?client_id=$clientId&response_type=code&redirect_uri=http://localhost:8000/login");
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



    public function deconnexion()
    {
        session_destroy();
        header('Location:/');
    }

    public function help()
    {
        return $this->twig->render('Spotify/help.html.twig');
    }
}
