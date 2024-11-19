<?php

function ctrlUpdateSong($request, $response, $container) {
    $id = $request->get(INPUT_POST, 'song_id');
    $nombre = $request->get(INPUT_POST, 'song_name');
    $artista = $request->get(INPUT_POST, 'artist');
    
    $songs = $container->Songs();
    
    // Si hay archivo nuevo, lo pasamos
    if (isset($_FILES['song']) && $_FILES['song']['size'] > 0) {
        $newFileName = $songs->updateSong($id, $nombre, $artista, $_FILES['song']);
        $response->set('newSongPath', 'uploads/songs/' . $newFileName);
    } else {
        // Si no hay archivo, solo actualizamos nombre y artista
        $songs->updateSong($id, $nombre, $artista);
    }

    $response->setJson();
    $response->set('status', 'success');
    return $response;
}