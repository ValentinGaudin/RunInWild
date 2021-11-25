<?php

// list of accessible routes of your application, add every new route here
// key : route to match
// values : 1. controller name
//          2. method name
//          3. (optional) array of query string keys to send as parameter to the method
// e.g route '/item/edit?id=1' will execute $itemController->edit(1)
return [
    'login' => ['SpotifyController', 'login', ['code']],
    'spotify' => ['SpotifyController', 'authorize'],
    'items' => ['ItemController', 'index',],
    'items/edit' => ['ItemController', 'edit', ['id']],
    'items/show' => ['ItemController', 'show', ['id']],
    'items/add' => ['ItemController', 'add',],
    'items/delete' => ['ItemController', 'delete',],
    'spotify/test' => ['SpotifyController', 'test'],
    '' => ['SpotifyController', 'show'],
    'spotify/change' => ['SpotifyController', 'change', ['target', 'bpm', 'actual']],
    'deconnexion' => ['SpotifyController', 'deconnexion']
];
