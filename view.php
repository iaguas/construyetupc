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

    $db = new DBHelper();
    $categories = $db->mGetHardwareCategories(); // Obtengo las categorías de componentes desde la base de datos

    $dhtml = '';
    foreach($categories as $category){
        $dhtml .= "<tr>";
        $categoryName = $category['name'];
        if ($_SESSION['partList']["$categoryName"] == null){
            $dhtml .= "<td class='col-md-2 vert-align'><img src='" . $category['img'] . "' alt='" . $category[0] . "' width='32' height='32' /> " . $category['spanishName'] . "</td>";
            $dhtml .= "<td class='col-md-3 vert-align'><button type='button' class='btn btn-default' onclick='window.location.href=\"index.php?action=partList&id=2&part=" . $category['name'] . "\"'><span class='glyphicon glyphicon-search'></span> Elegir " . $category['spanishName'] . "</button></td>";
            $dhtml .= "<td class='col-md-2 vert-align'></td>";
            $dhtml .= "<td class='col-md-1 vert-align'></td>";
        }else{
            // Obtener el ID del producto seleccionado
            $productID = $_SESSION['partList']["$categoryName"];

            // TODO: Obtener los datos de dicho producto desde la BD

            $dhtml .= "<td class='col-md-2 vert-align'><img src='assets/img/hard-icons/" . $category['name'] . ".png' alt='" . $category['name'] . "' width='32' height='32' /> " . $category['spanishName'] . "</td>";
            $dhtml .= "<td class='col-md-3 vert-align'>
                            <table>
                                <tr>
                                    <td rowspan='2'><img src='assets/img/corei3-temp.png' alt='product-image' width='50' height='50'/></td>
                                    <td><strong>Nombre</strong>: Intel Core i3-2105 2.1GHz Quad-Core<br />
                                        <strong>Precio</strong>: <span style='color:forestgreen'>109€</span>
                                    </td>
                                </tr>
                            </table>
                        </td>";
            $dhtml .= "<td class='col-md-2 vert-align'>
                            <img src='assets/img/shops/PcComponentes-logo-min.png' alt='Logo PcComponentes' width='50' height='50' /> <a href='http://www.pccomponentes.com/' title='PcComponentes'>PcComponentes</a>
                        </td>";
            $dhtml .= "<td class='col-md-1 vert-align'><button type='button' class='btn btn-primary btn-xs' title='Comprar'>Comprar</button> <button type='button' class='btn btn-danger btn-xs' title='Eliminar' onclick='window.location.href=\"index.php?action=partList&id=4&part=" . $category['name'] . "\"'>X</button></td>";
        }
        $dhtml .= "</tr>";
    }

    // TODO: Calcular el coste total
    $totalCost = 35;

    $page = str_replace("{{totalCost}}", $totalCost . "€", $page);
    $page = str_replace("{{component-list}}", $dhtml, $page);

    echo $page;
}

/**
 * Muestra la lista de selección de modelo de componente
 */
function vShowComponentSelection(){
    $part = $_GET['part'];
    switch($part){
        case 'cpu':
            $page = file_get_contents("views/components/cpu.html");
            $dhtml = '';
            // TODO: Obtener lista de todos los procesadores
            $processors = array(
                array(0, 'Intel Core i3-2015 MotherFoca edition', 'Core i3', '1150', '4', '2.5GHz', '199€', ''),
                array(1,'Intel Core i5-370 Patatero', 'Core i5', '1150', '4', '0.2MHz', '7€', ''),
                array(2, 'AMD FX-8000 tetera', 'FX Series', 'AM3', '4', '3GHz', '124€', ''),
                array(3, 'Intel Core i7-4700HQ', 'Core i7', '1150', '4', '2.8GHz', '249€', ''),
                array(4, 'Intel Core 2 duo E-5000', 'Core 2 duo', '775', '2', '2.1GHz', '87€', '')
            );
            foreach ($processors as $processor){
                $dhtml .= "<tr>";
                $dhtml .= "<td class='col-md-3 vert-align'>" . $processor[1] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $processor[2] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $processor[3] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $processor[4] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $processor[5] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $processor[6] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"index.php?action=partList&id=3&cpuId=" . $processor[0] ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            break;
        default:
            $page = 'Not implemented'; // TODO: hacer lo mismo para todos los demás componentes
            break;
    }

    $page = str_replace('{{processor-list}}', $dhtml, $page);

    echo $page;
}

/**
 * Muestra una lista con todos los correos registrados.
 */
function vShowEmails($lista){

    $page = file_get_contents("views/emails.html");

    $trozos=explode("##fila1##",$page);
    $aux="";
    $cuerpo="";

    foreach ($lista as $coleccion) {

        $aux=$trozos[1];
        $aux=str_replace("##email##",$coleccion['email'],$aux);
        $cuerpo.=$aux;
    }

    echo $trozos[0].$cuerpo.$trozos[2];
    //echo $page;

    
}

/**
 * Muestra validación del registro de un correo
 */
function vShowValidateRegister(){

   $page=file_get_contents("views/validateRegisterEmail.html");
   echo $page;

}
