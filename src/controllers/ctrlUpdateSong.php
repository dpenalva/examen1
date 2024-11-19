<?php

function ctrlUpdateSong($request, $response, $container) {
    try {
        $id = $request->get(INPUT_POST, 'song_id');
        $nombre = $request->get(INPUT_POST, 'song_name');
        $artista = $request->get(INPUT_POST, 'artist');

        if (empty($id) || empty($nombre) || empty($artista)) {
            throw new Exception("Todos los campos son obligatorios");
        }

        $songs = $container->Songs();

        $songs->updateSong($id, $nombre, $artista);

        if (isset($_FILES['song']) && $_FILES['song']['size'] > 0) {
            $newFileName = $songs->updateSongFile($id, $_FILES['song']);
            $response->set('newSongPath', 'uploads/songs/' . $newFileName);
        }

        $response->setJson();
        $response->set('status', 'success');
        $response->set('message', 'Canción actualizada correctamente');

    } catch (Exception $e) {
        $response->setJson();
        $response->set('status', 'error');
        $response->set('message', $e->getMessage());
    }

    return $response;
}