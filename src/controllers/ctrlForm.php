<?php

function ctrlForm($request, $response, $container) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $nombre = $request->get(INPUT_POST, 'nombre');
            $artista = $request->get(INPUT_POST, 'artista');
            
            if (empty($nombre) || empty($artista)) {
                throw new Exception("Todos los campos son obligatorios");
            }
            
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