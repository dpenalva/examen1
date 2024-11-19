<?php

function ctrlForm($request, $response, $container) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $nombre = $request->get(INPUT_POST, 'nombre');
            $artista = $request->get(INPUT_POST, 'artista');

            $songs = $container->Songs();
            
            if ($songs->addSong($nombre, $artista, $_FILES['archivo'])) {
                header("Location: index.php");
                exit();
            }
        } catch (Exception $e) {
            $response->set('error', $e->getMessage());
        }
    }

    $response->setTemplate("form.php");
    return $response;
}