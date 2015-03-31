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

    // TODO: obtener categorías de la BD
    $categories = array(
        array('cpu', 'CPU', 'assets/img/hard-icons/cpu.png'),
        array('gpu', 'GPU', 'assets/img/hard-icons/gpu.png'),
        array('cpu-cooler', 'Ventilador CPU', 'assets/img/hard-icons/cpu-cooler.png'),
        array('motherboard', 'Placa base','assets/img/hard-icons/motherboard.png'),
        array('memory', 'Memoria RAM','assets/img/hard-icons/memory.png'),
        array('power-supply', 'Fuente de alimentación','assets/img/hard-icons/power-supply.png'),
        array('case', 'Torre/Caja','assets/img/hard-icons/case.png'),
        array('optical-drive', 'Unidad óptica','assets/img/hard-icons/optical-drive.png'),
        array('storage', 'Almacenamiento','assets/img/hard-icons/storage.png'),
        array('monitor', 'Monitor','assets/img/hard-icons/monitor.png')
    );

    $dhtml = '';
    foreach($categories as $category){
        $dhtml .= "<tr>";
        $dhtml .= "<td class='col-md-2 vert-align'><img src='assets/img/hard-icons/" . $category[0] . ".png' alt='" . $category[0] . "' width='32' height='32' /> " . $category[1] . "</td>";
        $dhtml .= "<td class='col-md-3 vert-align'><button type='button' class='btn btn-default' onclick='window.location.href=\"index.php?action=partList&id=2&part=\"'" . $category[0] . "><span class='glyphicon glyphicon-search'></span> Elegir " . $category[1] . "</button></td>";
        $dhtml .= "<td class='col-md-2 vert-align'></td>";
        $dhtml .= "<td class='col-md-1 vert-align'></td>";
        $dhtml .= "</tr>";
    }

    $page = str_replace("{{component-list}}", $dhtml, $page);

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
