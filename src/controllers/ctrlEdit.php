<?php

function ctrlEdit($request, $response, $container) {
    try {
        $id = $request->get(INPUT_POST, 'id');
        $nombre = $request->get(INPUT_POST, 'nombre');
        $artista = $request->get(INPUT_POST, 'artista');
        
        $songs = $container->Songs();
        
        if ($songs->updateSong($id, $nombre, $artista)) {
            $response->setJson();
            $response->set("status", "success");
            $response->set("message", "Canción actualizada correctamente");
        } else {
            $response->setJson();
            $response->set("status", "error");
            $response->set("message", "Error al actualizar la canción");
        }
        
    } catch (Exception $e) {
        $response->setJson();
        $response->set("status", "error");
        $response->set("message", $e->getMessage());
    }
    
    return $response;
} 