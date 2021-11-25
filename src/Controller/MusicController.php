<?php

namespace App\Controller;

use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;

class MusicController extends AbstractController
{

    public function test()
    {
        return $this->twig->render('Music/index.html.twig');
    }
}
