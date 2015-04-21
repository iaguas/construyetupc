<?php
/**
 *	Fichero: index.php
 * 	Descripcion: Archivo que contiene todo lo relacionado con el controlador del patrón MVC.
 */

// Router class
require 'AltoRouter.php';

// Modelos y vistas
require 'models/model.php';
require 'view.php';
require 'models/sessions.php';

// Router
$router = new AltoRouter();
$router->setBasePath('');

// Rutas
$router->map('GET', '/', 'vLandingPage', 'landing');

$router->map('GET', '/main', 'vMainPage', 'main');
$router->map('GET', '/whoweare', 'vWhoWeAre', 'who-we-are');

$router->map('GET', '/contact', 'vShowContact', 'contact');

$router->map('GET', '/partList', 'vShowPartList', 'part-list');
$router->map('GET', '/partList/choose/[*:part]', 'vShowComponentSelection', 'component-selection');
$router->map('GET', '/partList/select/[*:part]/[i:id]', 'sAddPart', 'add-part');
$router->map('GET', '/partList/remove/[*:part]', 'sRemovePart', 'remove-part');

$router->map('GET', '/part/[*:part]/[*:serialNumber]', 'vShowDetailedPartModel', 'part-details');

$router->map('GET', '/landing', 'vLandingPage', 'landing-full');
$router->map('GET', '/landing/[i:id]', 'vLandingPage', 'landing-full-2');

$router->map('GET', '/admin', 'vShowAdminLogin', 'administrator-login');
$router->map('GET', '/admin/panel', 'vShowAdmin', 'administrator-panel');
$router->map('GET', '/admin/showemails', 'vShowEmailsLanding', 'administrator-panel-showemails');

$match = $router->match();

if($match) {
    // Si la URL encaja en alguna de las rutas

    //echo 'Parámetro por la URL: ' . $match['params']['id'];
    //echo 'Match: ' . print_r($match) . '<br>';

    switch($match['target']) {
        case 'vLandingPage':
            if($match['params']['id'] == 2) {
                vCreatorId();
            }
            else {
                vLandingPage();
            }
            break;
        case 'vAdmin':
            vShowAdmin();
            break;
        case 'vAdminLogin':
            vShowAdminLogin();
            break;
        case 'vMainPage':
            vShowMainPage();
            break;
        case 'vWhoWeAre':
            vShowWhoWeAre();
            break;
        // Caso concreto: llamada a una función con parámetros
        case 'vShowEmailsLanding':
            vShowEmails(mGetEmails());
            break;
		case 'vShowComponentSelection':
			vShowComponentSelection($match['params']['part']);
			break;
		case 'sAddPart':
			sAddPart($match['params']['part'], $match['params']['id']);
            vShowPartList();
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
