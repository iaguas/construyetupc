<?php
/**
 *	Fichero: index.php
 * 	Descripcion: Archivo que contiene todo lo relacionado con el controlador del patrón MVC.
 */

require_once("models/model.php");
require_once("view.php");
require_once("models/sessions.php");

session_start();
sCheckSessionVar();

/**
 * Control de la acción que se lleva a cabo.
 */
if (isset($_GET["action"])){
    $action = $_GET["action"];
}
else {
    if (isset($_POST["action"])){
        $action = $_POST["action"];
    }
    else {
        $action = "landing";
        // Descomentar cuando quitemos la Landing Page
        //$action = "main";
    }
}

/**
 * Control de la id.
 */
if (isset($_GET["id"])){
    $id = $_GET["id"];
}
else {
    if (isset($_POST["id"])) {
        $id = $_POST["id"];
    }
    else {
        $id = 1;
    }
}

/**
 * Cargamos la página principal
 */

switch($action) {
    // Landing page
    case 'landing':
        switch ($id){
            case 1:
                vLandingPage();
                break;
            case 2:
                vCreatorId();
                break;
        }
        break;

    // Main page
    case 'main':
        switch ($id){
            case 1:
                vMainPage();
                break;
        }
        break;

    // Selección de tipo de componente
    case 'partList':
        switch ($id){
            case 1:
                vShowPartList();
                break;
            case 2:
                vShowComponentSelection();
                break;
            case 3:
                sAddPart();
                vShowPartList();
                break;
            case 4:
                sRemovePart();
                vShowPartList();
                break;
        }
        break;

    // Muestra los emails introducidos en MONGODB
    case 'showEmails':
        switch ($id){
            case 1:
                vShowEmails(mGetEmails());
                break;
        }
        break;
    // Muestra los emails introducidos en MONGODB
    case 'validateRegister':
        switch ($id){
            case 1:
                mResgiterEmail();
                vShowValidateRegister();
                break;
        }
        break;
}
