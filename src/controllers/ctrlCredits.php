<?php

function ctrlCredits($request, $response, $container) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {

        } catch (Exception $e) {
            $response->set('error', $e->getMessage());
        }
    }

    $response->setTemplate("credits.php");
    return $response;
}