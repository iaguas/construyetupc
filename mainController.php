<?php
/**
 * Fichero: mainController.php
 * Descripcion: dicho documento representa al controlador para la parte de la página principal.
 */

function mainController($match){

    if($match) {
        // Si la URL encaja en alguna de las rutas
        switch($match['target']) {
            case 'vLandingPage':
                if($match['params']['id'] == 2) {
                    vCreatorId();
                }
                else {
                    vLandingPage();
                }
                break;
            case 'vMainPage':
                vShowMainPage();
                break;
            case 'vWhoWeAre':
                vShowWhoWeAre();
                break;
            case 'vShowComponentSelection':
                vShowComponentSelection($match['params']['part']);
                break;
            case 'sAddPart':
                sAddPart($match['params']['part']);
                break;
            case 'sRemovePart':
                sRemovePart($match['params']['part']);
                vShowPartList();
                break;
            case 'vShowDetailedPartModel':
                vShowDetailedPartModel($match['params']['part'], $match['params']['serialNumber']);
                break;
            case 'vShowContact':
                vShowContact();
                break;
            case 'vShowAbout':
                vShowAbout();
                break;
            // Por defecto se llama a la función indicada en la ruta
            default:
                call_user_func($match['target']);
                break;
        }
    }
    else {
        // Si no encaja, mostramos 404 y nos echamos una siesta
        echo file_get_contents('views/404.html');
    }
}
