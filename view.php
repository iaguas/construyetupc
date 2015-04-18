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
 * Muestra la página de contacto
 */
function vShowContact(){
    $page = file_get_contents('views/contact.html');
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
            $dhtml .= "<td class='col-md-3 vert-align'><button type='button' class='btn btn-default' onclick='window.location.href=\"partList/choose/" . $category['name'] . "\"'><span class='glyphicon glyphicon-search'></span> Elegir " . $category['spanishName'] . "</button></td>";
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
            $dhtml .= "<td class='col-md-1 vert-align'><button type='button' class='btn btn-primary btn-xs' title='Comprar'>Comprar</button> <button type='button' class='btn btn-danger btn-xs' title='Eliminar' onclick='window.location.href=\"partList/remove/" . $category['name'] . "\"'>X</button></td>";
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
function vShowComponentSelection($part){
    //$part = $_GET['part']; // TODO: Eliminar esta línea cuando estemos seguros de que el AltoRouter funciona bien.
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
            foreach ($processors as $partItem){
                $dhtml .= "<tr>";
                $dhtml .= "<td class='col-md-3 vert-align'><a href='part/cpu/" . $partItem[0] ."' title='Ver comparativa y especificaciones'>" . $partItem[1] . "</a></td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[2] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[3] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[4] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[5] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[6] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/cpu/" . $partItem[0] ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{processor-list}}', $dhtml, $page);
            break;
        case 'cpu-cooler':
            $page = file_get_contents("views/components/cpu-cooler.html");
            $dhtml = '';
            // TODO: Obtener lista de todos los ventiladores de CPU
            $cpu_coolers = array(
                array(0, 'Super mega ventilador tope 1000', '92mm', '2000', '20dB', '21€', ''),
                array(1,'Ventilador motherfoca edition', '120mm', '600 - 1200', '10dB', '7€', ''),
                array(2, 'Ventitetera', '2mm', '10', '50dB', '205€', ''),
                array(3, 'MEGACICLON 9000', '120mm', '9000', '35dB', '249€', ''),
                array(4, 'Tren chu chu', '120mm', '800', '12dB', '2€', '')
            );
            foreach ($cpu_coolers as $partItem){
                $dhtml .= "<tr>";
                $dhtml .= "<td class='col-md-3 vert-align'><a href='part/cpu-cooler/" . $partItem[0] ."' title='Ver comparativa y especificaciones'>" . $partItem[1] . "</a></td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[2] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[3] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[4] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[5] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[6] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/cpu-cooler/" . $partItem[0] ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{cpu-cooler-list}}', $dhtml, $page);
            break;
        case 'memory':
            $page = file_get_contents("views/components/memory.html");
            $dhtml = '';
            // TODO: Obtener lista de todas las memorias
            $memories = array(
                array(0, 'Memoria 1', '1600', '2x4GB', '8GB', '80€', ''),
                array(1,'Memoria 2', '2100', '2x8GB', '16GB', '50€', '')
            );
            foreach ($memories as $partItem){
                $dhtml .= "<tr>";
                $dhtml .= "<td class='col-md-3 vert-align'><a href='part/memory/" . $partItem[0] ."' title='Ver comparativa y especificaciones'>" . $partItem[1] . "</a></td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[2] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[3] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[4] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[5] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[6] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/memory/" . $partItem[0] ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{memory-list}}', $dhtml, $page);
            break;
        case 'gpu':
            $page = file_get_contents("views/components/gpu.html");
            $dhtml = '';
            // TODO: Obtener lista de todas las tarjetas gráficas
            $gpus = array(
                array(0, 'Grafica 1', '4GB', '1.5GHz', '350€', ''),
                array(1,'Gráfica 2', '1GB', '2GHz', '150€', '')
            );
            foreach ($gpus as $partItem){
                $dhtml .= "<tr>";
                $dhtml .= "<td class='col-md-3 vert-align'><a href='part/gpu/" . $partItem[0] ."' title='Ver comparativa y especificaciones'>" . $partItem[1] . "</a></td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[2] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[3] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[4] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[5] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[6] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/gpu/" . $partItem[0] ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{gpu-list}}', $dhtml, $page);
            break;
        case 'power-supply':
            $page = file_get_contents("views/components/power-supply.html");
            $dhtml = '';
            // TODO: Obtener lista de todas las fuentes de alimentación
            $power_supplies = array(
                array(0, 'Fuente 1', '4GB', '1.5GHz', '350€', ''),
                array(1,'Fuente 2', '1GB', '2GHz', '150€', '')
            );
            foreach ($power_supplies as $partItem){
                $dhtml .= "<tr>";
                $dhtml .= "<td class='col-md-3 vert-align'><a href='part/power-supply/" . $partItem[0] ."' title='Ver comparativa y especificaciones'>" . $partItem[1] . "</a></td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[2] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[3] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[4] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[5] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[6] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/power-supply/" . $partItem[0] ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{power-supply-list}}', $dhtml, $page);
            break;
        case 'storage':
            $page = file_get_contents("views/components/storage.html");
            $dhtml = '';
            // TODO: Obtener lista de todos los discos duros
            $storages = array(
                array(0, 'Disco 1', 'SSD', '1TB', '2.5"','350€', ''),
                array(1,'Disco 2', 'HDD', '1TB', '3.5"','150€', '')
            );
            foreach ($storages as $partItem){
                $dhtml .= "<tr>";
                $dhtml .= "<td class='col-md-3 vert-align'><a href='part/storage/" . $partItem[0] ."' title='Ver comparativa y especificaciones'>" . $partItem[1] . "</a></td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[2] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[3] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[4] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[5] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[6] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/storage/" . $partItem[0] ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{storage-list}}', $dhtml, $page);
            break;
        case 'motherboard':
            $page = file_get_contents("views/components/motherboard.html");
            $dhtml = '';
            // TODO: Obtener lista de todas las placas base
            $motherboards = array(
                array(0, 'Placa 1', '1150', '350€', ''),
                array(1,'Placa 2', '775', '150€', '')
            );
            foreach ($motherboards as $partItem){
                $dhtml .= "<tr>";
                $dhtml .= "<td class='col-md-3 vert-align'><a href='part/motherboard/" . $partItem[0] ."' title='Ver comparativa y especificaciones'>" . $partItem[1] . "</a></td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[2] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[3] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[4] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[5] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[6] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/motherboard/" . $partItem[0] ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{motherboard-list}}', $dhtml, $page);
            break;
        case 'case':
            $page = file_get_contents("views/components/case.html");
            $dhtml = '';
            // TODO: Obtener lista de todas las cajas
            $cases = array(
                array(0, 'Caja 1', 'ATX', '350€', ''),
                array(1,'Caja 2', 'uATX', '150€', '')
            );
            foreach ($cases as $partItem){
                $dhtml .= "<tr>";
                $dhtml .= "<td class='col-md-3 vert-align'><a href='part/case/" . $partItem[0] ."' title='Ver comparativa y especificaciones'>" . $partItem[1] . "</a></td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[2] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[3] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[4] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[5] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[6] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/case/" . $partItem[0] ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{case-list}}', $dhtml, $page);
            break;
        case 'monitor':
            $page = file_get_contents("views/components/monitor.html");
            $dhtml = '';
            // TODO: Obtener lista de todos los monitores
            $monitors = array(
                array(0, 'Monitor 1', '1920x1080', '21"','350€', ''),
                array(1,'Monitor 2', '1440x900', '30"', '100€','')
            );
            foreach ($monitors as $partItem){
                $dhtml .= "<tr>";
                $dhtml .= "<td class='col-md-3 vert-align'><a href='part/monitor/" . $partItem[0] ."' title='Ver comparativa y especificaciones'>" . $partItem[1] . "</a></td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[2] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[3] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[4] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[5] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[6] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/monitor/" . $partItem[0] ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{monitor-list}}', $dhtml, $page);
            break;
        case 'optical-drive':
            $page = file_get_contents("views/components/optical-drive.html");
            $dhtml = '';
            // TODO: Obtener lista de todas las unidades ópticas
            $optical_drives = array(
                array(0, 'Unidad óptica 1', 'Alta', '350€', ''),
                array(1,'Unidad óptica 2', 'Extrema', '100€','')
            );
            foreach ($optical_drives as $partItem){
                $dhtml .= "<tr>";
                $dhtml .= "<td class='col-md-3 vert-align'><a href='part/optical-drive/" . $partItem[0] ."' title='Ver comparativa y especificaciones'>" . $partItem[1] . "</a></td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[2] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[3] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[4] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[5] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[6] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/optical-drive/" . $partItem[0] ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{optical-drive-list}}', $dhtml, $page);
            break;
    }

    echo $page;
}

/**
 * Muestra los detalles, puntos de venta y precios de un modelo de componente
 */
function vShowDetailedPartModel($part, $id){
    switch ($part){
        case 'cpu':
            $page = file_get_contents("views/detailedComponents/cpu.html");
            $dhtml = '';
            $model = $id;
            $modelName = 'NombreComponente';
            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            // TODO: Obtener especificaciones técnicas del modelo solicitado
            $processors = array(
                array(0, 'Chino de confianza', '580€', '5€', '585€')
            );
            foreach ($processors as $partItem){
                $dhtml .= "<tr>";
                $dhtml .= "<td class='col-md-3 vert-align'>" . $partItem[1] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[2] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[3] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[4] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/cpu/" . $partItem[0] ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-processor-list}}', $dhtml, $page);
            break;
        case 'cpu-cooler':
            $page = file_get_contents("views/detailedComponents/cpu-cooler.html");
            $dhtml = '';
            $model = $id;
            $modelName = 'NombreComponente';
            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            // TODO: Obtener especificaciones técnicas del modelo solicitado
            $processors = array(
                array(0, 'Chino de confianza', '580€', '5€', '585€')
            );
            foreach ($processors as $partItem){
                $dhtml .= "<tr>";
                $dhtml .= "<td class='col-md-3 vert-align'>" . $partItem[1] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[2] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[3] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[4] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/cpu-cooler/" . $partItem[0] ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-cpu-cooler-list}}', $dhtml, $page);
            break;
        case 'memory':
            $page = file_get_contents("views/detailedComponents/memory.html");
            $dhtml = '';
            $model = $id;
            $modelName = 'NombreComponente';
            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            // TODO: Obtener especificaciones técnicas del modelo solicitado
            $processors = array(
                array(0, 'Chino de confianza', '580€', '5€', '585€')
            );
            foreach ($processors as $partItem){
                $dhtml .= "<tr>";
                $dhtml .= "<td class='col-md-3 vert-align'>" . $partItem[1] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[2] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[3] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[4] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/memory/" . $partItem[0] ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-memory-list}}', $dhtml, $page);
            break;
        case 'gpu':
            $page = file_get_contents("views/detailedComponents/gpu.html");
            $dhtml = '';
            $model = $id;
            $modelName = 'NombreComponente';
            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            // TODO: Obtener especificaciones técnicas del modelo solicitado
            $processors = array(
                array(0, 'Chino de confianza', '580€', '5€', '585€')
            );
            foreach ($processors as $partItem){
                $dhtml .= "<tr>";
                $dhtml .= "<td class='col-md-3 vert-align'>" . $partItem[1] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[2] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[3] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[4] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/gpu/" . $partItem[0] ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-gpu-list}}', $dhtml, $page);
            break;
        case 'power-supply':
            $page = file_get_contents("views/detailedComponents/power-supply.html");
            $dhtml = '';
            $model = $id;
            $modelName = 'NombreComponente';
            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            // TODO: Obtener especificaciones técnicas del modelo solicitado
            $processors = array(
                array(0, 'Chino de confianza', '580€', '5€', '585€')
            );
            foreach ($processors as $partItem){
                $dhtml .= "<tr>";
                $dhtml .= "<td class='col-md-3 vert-align'>" . $partItem[1] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[2] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[3] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[4] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/power-supply/" . $partItem[0] ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-power-supply-list}}', $dhtml, $page);
            break;
        case 'storage':
            $page = file_get_contents("views/detailedComponents/storage.html");
            $dhtml = '';
            $model = $id;
            $modelName = 'NombreComponente';
            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            // TODO: Obtener especificaciones técnicas del modelo solicitado
            $processors = array(
                array(0, 'Chino de confianza', '580€', '5€', '585€')
            );
            foreach ($processors as $partItem){
                $dhtml .= "<tr>";
                $dhtml .= "<td class='col-md-3 vert-align'>" . $partItem[1] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[2] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[3] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[4] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/storage/" . $partItem[0] ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-storage-list}}', $dhtml, $page);
            break;
        case 'motherboard':
            $page = file_get_contents("views/detailedComponents/motherboard.html");
            $dhtml = '';
            $model = $id;
            $modelName = 'NombreComponente';
            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            // TODO: Obtener especificaciones técnicas del modelo solicitado
            $processors = array(
                array(0, 'Chino de confianza', '580€', '5€', '585€')
            );
            foreach ($processors as $partItem){
                $dhtml .= "<tr>";
                $dhtml .= "<td class='col-md-3 vert-align'>" . $partItem[1] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[2] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[3] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[4] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/motherboard/" . $partItem[0] ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-motherboard-list}}', $dhtml, $page);
            break;
        case 'case':
            $page = file_get_contents("views/detailedComponents/case.html");
            $dhtml = '';
            $model = $id;
            $modelName = 'NombreComponente';
            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            // TODO: Obtener especificaciones técnicas del modelo solicitado
            $processors = array(
                array(0, 'Chino de confianza', '580€', '5€', '585€')
            );
            foreach ($processors as $partItem){
                $dhtml .= "<tr>";
                $dhtml .= "<td class='col-md-3 vert-align'>" . $partItem[1] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[2] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[3] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[4] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/case/" . $partItem[0] ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-case-list}}', $dhtml, $page);
            break;
        case 'monitor':
            $page = file_get_contents("views/detailedComponents/monitor.html");
            $dhtml = '';
            $model = $id;
            $modelName = 'NombreComponente';
            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            // TODO: Obtener especificaciones técnicas del modelo solicitado
            $processors = array(
                array(0, 'Chino de confianza', '580€', '5€', '585€')
            );
            foreach ($processors as $partItem){
                $dhtml .= "<tr>";
                $dhtml .= "<td class='col-md-3 vert-align'>" . $partItem[1] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[2] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[3] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[4] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/monitor/" . $partItem[0] ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-monitor-list}}', $dhtml, $page);
            break;
        case 'optical-drive':
            $page = file_get_contents("views/detailedComponents/optical-drive.html");
            $dhtml = '';
            $model = $id;
            $modelName = 'NombreComponente';
            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            // TODO: Obtener especificaciones técnicas del modelo solicitado
            $processors = array(
                array(0, 'Chino de confianza', '580€', '5€', '585€')
            );
            foreach ($processors as $partItem){
                $dhtml .= "<tr>";
                $dhtml .= "<td class='col-md-3 vert-align'>" . $partItem[1] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[2] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[3] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[4] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/optical-drive/" . $partItem[0] ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-optical-drive-list}}', $dhtml, $page);
            break;
    }
    echo $page;
}

/**
 * Muestra una lista con todos los correos registrados.
 * ( Correponde a adminsitrator/views (después será eliminada está funciont).)
 */
function vShowEmails($lista){

    $page = file_get_contents("views/admin/emails.html");

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

/**
 * Muestra la parte del administrador.
 */
function vShowAdministrator(){

    $page = file_get_contents("views/admin/adminPanel.html");
    echo $page;
}
