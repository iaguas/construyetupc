<?php
/**
 *	Fichero: Vista.php
 * 	Descripcion: Archivo que contiene todo lo relacionado con la vista del patrón MVC.
 *		El nombre de las funciones empiezan por la letra "v" (hace referencia a la vista para que se más sencillo localizarlo).
 *		Después de dicha letra, las palabras empiezan con mayúsculas.
 */

/**
 * Muestra la Landing Page.
 */
function vLandingPage() {
    $landing = file_get_contents("views/landing.html");
    echo $landing;
}

/**
 * Muestra la página de quíenes somos.
 */
function vCreatorId() {
    $landing = file_get_contents("views/quienessomos.html");
    echo $landing;
}

/**
 * Muestra la página principal.
 */
function vMainPage() {
    $page = file_get_contents("views/index.html");
    echo $page;
}

/**
 * Muestra la lista de componentes.
 */
function vShowPartList() {
    $page = file_get_contents("views/partlist.html");
    echo $page;
}

/**
 * Muestra la lista de selección de modelo de componente
 */
function vShowComponentSelection(){
    // TODO: Obtener el componente seleccionado
    $part = $_GET['part'];
    //$page = file_get_contents("views/components/cpu.html");
    echo $part;
    //echo $page;
}
