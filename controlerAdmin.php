<?php
/**
 * Fichero: controlerAdmin.php
 * Descripcion: dicho documento representa al controlador para la parte del Admin.
 */

require 'models/DBHelper.php';

function controlerAdmin($match){

    session_start();

    $admin = false;

    /*if(session_id() === '22060kdc164j9cq3tk61qtral1') {
        $admin = true;
    }*/

    $db = new DBHelper();

    if($db->mCheckAdminSession(session_id())) {
        $admin = true;
    }

    if($match) {
        if($admin) {
            switch($match['target']) {
                case 'vCrawlerPanel':
                    vShowCrawlerPanel();
                    break;
                case 'vInsertCompPanel':
                    vShowInsertCompPanel();
                    break;
                case 'vAdminLogin':
                    vShowAdmin();
                    break;
                case 'vShowAdmin()':
                    vShowAdmin();
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
            vShowAdminLogin();
            exit();
            // Mostrar mensaje de sessión incorrecta/expirada??
        }
    }
    else {
        // Si no encaja, mostramos 404 y nos echamos una siesta
        echo file_get_contents('views/404.html');
    }

}
