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

// Directorio raiz
if($_SERVER[HTTP_HOST] === 'localhost') {
    $basepath = 'ConstruyeTuPC/'; // Para trabajar en local
}
else {
    $basepath = ''; // Para trabajar en el servidor
}

// Router
$router = new AltoRouter();
$router->setBasePath($basepath);

// Rutas
$router->map('GET', '/', 'vLandingPage', 'landing');

$router->map('GET', '/main', 'vMainPage', 'quienes-somos');

$router->map('GET', '/partList', 'vShowPartList', 'part-list');
$router->map('GET', '/partList/choose/[*:part]', 'vShowComponentSelection', 'component-selection');
$router->map('GET', '/partList/select/[*:part]/[i:id]', 'sAddPart', 'add-part');
$router->map('GET', '/partList/remove/[*:part]', 'sRemovePart', 'remove-part');

$router->map('GET', '/showemails', 'vShowEmailsLanding', 'show-emails');

$router->map('GET', '/landing', 'vLandingPage', 'landing-full');
$router->map('GET', '/landing/[i:id]', 'vLandingPage', 'landing-full-2');

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
