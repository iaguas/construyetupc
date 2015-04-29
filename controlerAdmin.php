<?php
/**
 * Fichero: controlerAdmin.php
 * Descripcion: dicho documento representa al controlador para la parte del Admin.
 */


function controlerAdmin($match){

    if($match) {
        // Si la URL encaja en alguna de las rutas
        switch($match['target']) {
            case 'vLandingPage':
                controlerM($match);
                break;
            case 'vCrawlerPanel':
                vShowCrawlerPanel();
                break;
            case 'vInsertCompPanel':
                vShowInsertCompPanel();
                break;
            case 'vAdminLogin':
                vShowAdminLogin();
                break;
            case 'vMainPage':
                controlerM($match);
                break;
            case 'vShowEmailsLanding':
                echo 'aaa';
                vShowEmails(mGetEmails());
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