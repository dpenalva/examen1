<?php

function ctrlUpdateDuration($request, $response, $container) {
    try {
        $id = $request->get(INPUT_POST, 'id');
        $duration = $request->get(INPUT_POST, 'duration');
        
        $songs = $container->Songs();
        $songs->updateDuration($id, $duration);
        
        $response->setJson();
        $response->set('status', 'success');
    } catch (Exception $e) {
        $response->setJson();
        $response->set('status', 'error');
        $response->set('message', $e->getMessage());
    }
    
    return $response;
} 