<?php

/**
 * Aquest fitxer és un exemple de Front Controller, pel qual passen totes les requests.
 */

include "../src/config.php";
include "../src/Emeset/Container.php";
include "../src/Emeset/Request.php";
include "../src/Emeset/Response.php";

/**
 * Carreguem els models
 */
include "../src/models/Db.php";
include "../src/models/Songs.php";

/**
 * Carreguem els controladors
 */
include "../src/controllers/ctrlIndex.php";
include "../src/controllers/ctrlForm.php";
include "../src/controllers/ctrlJson.php";
include "../src/controllers/ctrlUpdateSong.php";
include "../src/controllers/ctrlDeleteSong.php";
include "../src/controllers/ctrlCredits.php";

/**
 * Carreguem el contenidor
 */
include "../src/views/ProjectContainer.php";

$request = new \Emeset\Request();
$response = new \Emeset\Response();
$container = new ProjectContainer($config);

/* 
 * Aquesta és la part que fa que funcioni el Front Controller.
 * Si no hi ha cap paràmetre, carreguem la pàgina d'inici.
 * Si hi ha paràmetre, carreguem la pàgina que correspongui.
 * Si no existeix la pàgina, carreguem la pàgina d'error.
 */
$r = '';
if(isset($_REQUEST["r"])){
   $r = $_REQUEST["r"];
}

/* Front Controller, aquí es decideix quina acció s'executa */
if($r == "") {
    $response = ctrlIndex($request, $response, $container);
} elseif($r == "form") {
    $response = ctrlForm($request, $response, $container);
} elseif ($r == "updatesong") {
    $response = ctrlUpdateSong($request, $response, $container);
} elseif ($r == "deletesong") {
    $response = ctrlDeleteSong($request, $response, $container);
} elseif ($r == "credits") {
    $response = ctrlCredits($request, $response, $container);
} else {
    echo "No existeix la ruta";
}

/* Enviem la resposta al client. */
$response->response();