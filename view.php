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
        if ($_SESSION['partList']["$category[0]"] == null){
            $dhtml .= "<td class='col-md-2 vert-align'><img src='assets/img/hard-icons/" . $category[0] . ".png' alt='" . $category[0] . "' width='32' height='32' /> " . $category[1] . "</td>";
            $dhtml .= "<td class='col-md-3 vert-align'><button type='button' class='btn btn-default' onclick='window.location.href=\"index.php?action=partList&id=2&part=" . $category[0] . "\"'><span class='glyphicon glyphicon-search'></span> Elegir " . $category[1] . "</button></td>";
            $dhtml .= "<td class='col-md-2 vert-align'></td>";
            $dhtml .= "<td class='col-md-1 vert-align'></td>";
        }else{
            $dhtml .= "<td class='col-md-2 vert-align'><img src='assets/img/hard-icons/" . $category[0] . ".png' alt='" . $category[0] . "' width='32' height='32' /> " . $category[1] . "</td>";
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
            $dhtml .= "<td class='col-md-1 vert-align'><button type='button' class='btn btn-primary btn-xs' title='Comprar'>Comprar</button> <button type='button' class='btn btn-danger btn-xs' title='Eliminar' onclick='window.location.href=\"index.php?action=partList&id=4&part=" . $category[0] . "\"'>X</button></td>";
        }
        $dhtml .= "</tr>";
    }

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
            $page = 'Not implemented'; // TODO
            break;
    }

    $page = str_replace('{{processor-list}}', $dhtml, $page);

    echo $page;
}
