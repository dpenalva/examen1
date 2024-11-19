<?php

function ctrlDeleteSong($request, $response, $container) {
    try {
        $id = $request->get(INPUT_POST, 'song_id');
        
        if (empty($id)) {
            throw new Exception("ID de canción no válido");
        }

        $songs = $container->Songs();
        
        if ($songs->deleteSong($id)) {
            echo "success";
        } else {
            echo "error";
        }
        
    } catch (Exception $e) {
        echo "error";
    }
    exit;
} 