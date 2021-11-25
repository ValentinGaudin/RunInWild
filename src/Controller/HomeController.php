<?php

/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use Symfony\Component\HttpClient\HttpClient;

class HomeController extends AbstractController
{

    private string $accessToken;

    private string $clientId = 'ac2865071d374203af6c8d46629f7bcb';

    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $code = $_GET['code'];

            $this->accessToken = $this->getAccessToken($code);
            var_dump($this->accessToken);

            $client = HttpClient::create();

            $response = $client->request('GET', 'https://api.spotify.com/v1/search?q=130%20bpm&type=playlist&limit=10', [
                'query' => [
                    'client_id' =>  $this->clientId,
                    'response_type' => 'code',
                    'redirect_uri' => 'https://localhost:8000/index',
                    "Accept" => "application/json",
                    "Content-Type" => "application/json",
                    "Authorization" => "Bearer BQDcriaRkYPgATV_AwWVccDRWW3DTXt0OmfbpT641IVLpwQiP0X5hFRhlbhV6q05QakfqjqhOgnIA0enT1kUFtDjcP7brCeI7uo1Cle-V0sbfvUDfBmYJZNAzAZGEZxXrKnb1VtLgrLJlH375BBCafgBgPEB8EYmJWQ"
                ],
                'auth_bearer' => "BQCeMooHuxN6s5PLtkFJ10-z_7NjHZppE9vVau30ZMtsJ6KXaF9TwPEdqDjnccjVR28ZEs9DCb0gkJEL-btJhT0a8U484JJh3tOuNtA6numehDcof40xcxiPs2BldGMSowsKvevllKELIbSLMIBDcv-Klrq9RE2sTM4"
            ]);

            $statusCode = $response->getStatusCode(); // get Response status code 200

            // var_dump($statusCode);
            // die();
            if ($statusCode === 200) {
                $contents = $response->getContent();
                // get the response in JSON format

                $contents = json_decode($contents, true);
                // convert the response (here in JSON) to an PHP array
                return $this->twig->render('Home/index.html.twig', ['contents' => $contents]);
            }
        }
        return $this->twig->render('Home/index.html.twig');
    }

    public function authorize()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header("Location: https://accounts.spotify.com/authorize?client_id=$this->clientId&response_type=code&redirect_uri=http://localhost:8000/loggin");
            return;
        }

        return $this->twig->render('Home/index.html.twig');
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

    public function getAccessToken(string $code): string
    {
        $client = HttpClient::create();

        $response = $client->request('POST', 'https://api.spotify.com/api/token', [
            'query' => [
                'grant_type' =>  "authorization_code",
                'code' => $code,
                'redirect_uri' => 'http://localhost:8000/loggin',
                "Content-Type" => "application/x-www-form-urlencoded",
            ],

        ]);

        $statusCode = $response->getStatusCode(); // get Response status code 200

        if ($statusCode === 200) {
            $contents = $response->getContent();
            // var_dump($contents);
            // die();
            // get the response in JSON format

            $accessToken = json_decode($contents, true);

            return $accessToken;
        }
        return '';
    }
}
