<?php

function ctrlDeleteSong($request, $response, $container) {
    $id = $request->get(INPUT_GET, "id");
    $songs = $container->songs();
    $songs->deleteSong($id);
    
    header("Location: index.php");
    exit();
} 