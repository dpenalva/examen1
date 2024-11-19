<?php

function ctrlIndex($request, $response, $container) {
    $modelsongs = $container->Songs();
    $allSongs = $modelsongs->getAllSongs();
    
    $response->set('songs', $allSongs);
    $response->setTemplate("index.php");
    return $response;
}