<?php
/**
 *	Fichero: index.php
 * 	Descripcion: Archivo que contiene todo lo relacionado con el controlador del patrón MVC.
 */

// Router class
require 'AltoRouter.php';

// Modelos y vistas
require 'adminController.php';
require 'mainController.php';

require 'models/model.php'; //Estos tres require se utilizan solo una vez para todos los controladores.
require 'view.php';
require 'models/sessions.php';

session_start();
sCheckSessionVar();

// Router
$router = new AltoRouter();
$router->setBasePath('');

// Rutas
$router->map('GET', '/', 'vMainPage', 'landing');

$router->map('GET', '/main', 'vMainPage', 'main');
$router->map('GET', '/whoweare', 'vWhoWeAre', 'who-we-are');

$router->map('GET', '/contact', 'vShowContact', 'contact');

$router->map('GET', '/partList', 'vShowPartList', 'part-list');
$router->map('GET', '/partList/choose/[*:part]', 'vShowComponentSelection', 'component-selection');
$router->map('POST', '/partList/select/[*:part]', 'sAddPart', 'add-part');

$router->map('GET', '/partList/remove/[*:part]', 'sRemovePart', 'remove-part');

$router->map('GET', '/part/[*:part]/[*:serialNumber]', 'vShowDetailedPartModel', 'part-details');

$router->map('GET', '/landing', 'vLandingPage', 'landing-full');
$router->map('GET', '/landing/[i:id]', 'vLandingPage', 'landing-full-2');

$router->map('GET', '/admin', 'vShowAdminLogin', 'administrator-login');
$router->map('GET', '/admin/panel', 'vShowAdmin', 'administrator-panel');
$router->map('GET', '/admin/showemails', 'vShowEmailsLanding', 'administrator-panel-showemails');
$router->map('GET', '/admin/crawlerPanel', 'vCrawlerPanel', 'administrator-crawler-panel');
$router->map('GET', '/admin/insertCompPanel', 'vInsertCompPanel', 'administrator-insert-comp-panel');

$router->map('GET', '/about', 'vShowAbout', 'about');


$match = $router->match();

if($match) {
    // Si la URL encaja en alguna de las rutas

    //Compara la cadena String1 con la String2 (solo las 13 primeras letras) para
    // ver si coincide la palabra inicial administrator de cada name (ruta).
    $string1='administrator';
    $string2=$match['name'];

    //echo substr_compare ($string1 , $string2 , 0, strlen($string1), true);

    if(substr_compare ($string1 , $string2 , 0, strlen($string1), true)==0){
        adminController($match);
    }else{
        mainController($match);
    }

}
else {
    // Si no encaja, mostramos 404 y nos echamos una siesta
    echo file_get_contents('views/404.html');
}
