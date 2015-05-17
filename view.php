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
    $landing = file_get_contents("views/whoweare.html");
    echo $landing;
}

/**
 * Muestra la página principal.
 */
function vShowMainPage() {
    $page = file_get_contents("views/index.html");
    echo $page;
}

/**
 * Muestra la página principal.
 */
function vShowWhoWeAre() {
    $page = file_get_contents("views/whoweare.html");
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
        if(isset($_SESSION)) {
            if (is_null($_SESSION['partList']["$categoryName"])){
                $dhtml .= "<td class='col-md-2 vert-align'><img src='" . $category['img'] . "' alt='" . $category['name'] . "' width='32' height='32' /> " . $category['spanishName'] . "</td>";
                $dhtml .= "<td class='col-md-3 vert-align'><button type='button' class='btn btn-default' onclick='window.location.href=\"partList/choose/" . $category['name'] . "\"'><span class='glyphicon glyphicon-search'></span> Elegir " . $category['spanishName'] . "</button></td>";
                $dhtml .= "<td class='col-md-2 vert-align'></td>";
                $dhtml .= "<td class='col-md-1 vert-align'></td>";
            }else{
                // Obtener el ID del producto seleccionado
                $productId = $_SESSION['partList']["$categoryName"]['productId'];
                $productPrice = $_SESSION['partList']["$categoryName"]['price'];
                $productVendor = $_SESSION['partList']["$categoryName"]['vendorId'];

                // TODO: Obtener los datos de dicho producto desde la BD

                $dhtml .= "<td class='col-md-2 vert-align'><img src='assets/img/hard-icons/" . $category['name'] . ".png' alt='" . $category['name'] . "' width='32' height='32' /> " . $category['spanishName'] . "</td>";
                $dhtml .= "<td class='col-md-3 vert-align'>
                            <table>
                                <tr>
                                    <td rowspan='2'><img src='assets/img/corei3-temp.png' alt='product-image' width='50' height='50'/></td>
                                    <td><strong>Nombre</strong>: Intel Core i3-2105 2.1GHz Quad-Core<br />
                                        <strong>Precio</strong>: <span style='color:forestgreen'>". $productPrice ."</span>
                                    </td>
                                </tr>
                            </table>
                        </td>";
                $dhtml .= "<td class='col-md-2 vert-align'>
                            <img src='assets/img/shops/PcComponentes-logo-min.png' alt='Logo PcComponentes' width='50' height='50' /> <a href='http://www.pccomponentes.com/' title='PcComponentes'>". $productVendor ."</a>
                        </td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' class='btn btn-primary btn-xs' title='Comprar'>Comprar</button> <button type='button' class='btn btn-danger btn-xs' title='Eliminar' onclick='window.location.href=\"partList/remove/" . $category['name'] . "\"'>X</button></td>";
            }
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
 * @param $part tipo de componente por el cual se identifica el listado de componentes a mostrar
 */
function vShowComponentSelection($part) {
    //$part = $_GET['part']; // TODO: Eliminar esta línea cuando estemos seguros de que el AltoRouter funciona bien.

    switch($part){
        case 'cpu':
            $page = file_get_contents("views/components/cpu.html");
            $dhtml = '';
            // TODO: Obtener lista de todos los procesadores

           /* $db = new DBHelper();
            $processors = $db->mGetCpus(); // Obtenemos los datos de todas las CPUs
            foreach ($processors as $partItem) {
                if (isset($partItem['family'][0])) {
                    $family=$partItem['family'][0];
                }else{
                    $family="N/A";
                }
                if(isset($partItem['socket'][0])){
                    $socket=$partItem['socket'][0];
                }else{
                    $socket="N/A";
                }
                if(isset($partItem['cores'][0])){
                    $cores=$partItem['cores'][0];
                }else{
                    $cores="N/A";
                }
                if(isset($partItem['frecuency'][0])){
                    $frecuency=$partItem['frecuency'][0];
                }else{
                    $frecuency="N/A";
                }

                $dhtml .= "<tr>";
                $dhtml .= "<td class='col-md-3 vert-align'><a href='part/cpu/" . $partItem['_id'] ."' title='Ver comparativa y especificaciones'>" . $partItem['name'][0]  . "</a></td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $family . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $socket . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $cores . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $frecuency . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem['price'][0] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/cpu/" . $partItem['_id'] ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }*/

            //$page = str_replace('{{processor-list}}', $dhtml, $page);
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
 * @param $part tipo de componente cuya página detallada se quiere mostrar
 * @param $id número de parte (part number, campo "pn" en la BD) del componente cuyos detalles se quiere mostrar
 */
function vShowDetailedPartModel($part, $id){
    $db = new DBHelper(); // Invocar la clase del modelo de BD
    switch ($part){
        case 'cpu':
            $page = file_get_contents("views/detailedComponents/cpu.html");
            $dhtml = '';
            $model = $id;
            $modelName = $db->mGetCompName($model,'cpus');

            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            $processors = $db->mGetCompPrices($model,'cpus');
            $properties = $db->mGetCompProperties($model, 'cpus');
            // TODO: Obtener especificaciones técnicas del modelo solicitado
            foreach ($processors as $key => $partItem){
                $total = $partItem['price'] + $partItem['delivery-fare'];
                $dhtml .= "<tr>";
                $dhtml .= "<input id='product-id' type='hidden' value='" . $key . "'>";
                $dhtml .= "<td id='product-vendor' class='col-md-3 vert-align'>" . $partItem['provider'] . "</td>";
                $dhtml .= "<td id='product-price' class='col-md-1 vert-align'>" . $partItem['price'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem['delivery-fare'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . (string)$total  . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button id='add-product' type='button'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-processor-list}}', $dhtml, $page);
            $page = str_replace('{{family}}', $properties['family'],$page);
            $page = str_replace('{{frecuency}}', $properties['frecuency'],$page);
            $page = str_replace('{{cores}}', $properties['cores'],$page);
            $page = str_replace('{{socket}}', $properties['socket'],$page);
            // Rellenar si es sin imagen de forma adecuada.
            if ($properties['img']=="") {
                $page = str_replace('{{image-url}}', "./assets/img/no-image150x150.png",$page);
                $page = str_replace('{{image-description}}', "No existe imagen",$page);
            }
            else{
                $page = str_replace('{{image-url}}', $properties['img'],$page);
                $page = str_replace('{{image-description}}', $modelName,$page);
            }
            break;
        case 'cpu-cooler':
            $page = file_get_contents("views/detailedComponents/cpu-cooler.html");
            $dhtml = '';
            $model = $id;
            // TODO: Fijar nombre correcto de la base de datos
            $modelName = $db->mGetCompName($model,'cpu-coolers');

            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            $coolers = $db->mGetCompPrices($model,'cpu-coolers');
            // TODO: Obtener especificaciones técnicas del modelo solicitado
            foreach ($coolers as $key => $partItem){
                $total = $partItem['price'] + $partItem['delivery-fare'];
                $dhtml .= "<tr>";
                $dhtml .= "<input id='product-id' type='hidden' value='" . $key . "'>";
                $dhtml .= "<td class='col-md-3 vert-align'>" . $partItem['provider'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem['price'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem['delivery-fare'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . (string)$total  . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/cpu-cooler/" . $key ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-cpu-cooler-list}}', $dhtml, $page);
            break;
        case 'memory':
            $page = file_get_contents("views/detailedComponents/memory.html");
            $dhtml = '';
            $model = $id;
            // TODO: Fijar nombre correcto de la base de datos
            $modelName = $db->mGetCompName($model,'memories');

            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            $memories = $db->mGetCompPrices($model,'memories');
            // TODO: Obtener especificaciones técnicas del modelo solicitado
            foreach ($memories as $key => $partItem){
                $total = $partItem['price'] + $partItem['delivery-fare'];
                $dhtml .= "<tr>";
                $dhtml .= "<input id='product-id' type='hidden' value='" . $key . "'>";
                $dhtml .= "<td class='col-md-3 vert-align'>" . $partItem['provider'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem['price'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem['delivery-fare'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . (string)$total  . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/memory/" . $key ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-memory-list}}', $dhtml, $page);
            break;
        case 'gpu':
            $page = file_get_contents("views/detailedComponents/gpu.html");
            $dhtml = '';
            $model = $id;
            // TODO: Fijar nombre correcto de la base de datos
            $modelName = $db->mGetCompName($model,'gpus');

            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            $gpus = $db->mGetCompPrices($model,'gpus');
            // TODO: Obtener especificaciones técnicas del modelo solicitado
            foreach ($gpus as $key => $partItem){
                $total = $partItem['price'] + $partItem['delivery-fare'];
                $dhtml .= "<tr>";
                $dhtml .= "<input id='product-id' type='hidden' value='" . $key . "'>";
                $dhtml .= "<td class='col-md-3 vert-align'>" . $partItem['provider'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem['price'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem['delivery-fare'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . (string)$total  . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/cpu-cooler/" . $key ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-gpu-list}}', $dhtml, $page);
            break;
        case 'power-supply':
            $page = file_get_contents("views/detailedComponents/power-supply.html");
            $dhtml = '';
            $model = $id;
            // TODO: Fijar nombre correcto de la base de datos
            $modelName = $db->mGetCompName($model,'power-supplies');

            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            $powersupplies = $db->mGetCompPrices($model,'power-supplies');
            // TODO: Obtener especificaciones técnicas del modelo solicitado
            foreach ($powersupplies as $key => $partItem){
                $total = (float)$partItem[1] + (float)$partItem[2];
                $dhtml .= "<tr>";
                $dhtml .= "<td class='col-md-3 vert-align'>" . $partItem[0] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[1] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem[2] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . (string)$total  . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/gpu/" . $key ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-power-supply-list}}', $dhtml, $page);
            break;
        case 'storage':
            $page = file_get_contents("views/detailedComponents/storage.html");
            $dhtml = '';
            $model = $id;
            // TODO: Fijar nombre correcto de la base de datos
            $modelName = $db->mGetCompName($model,'storages');

            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            $storages = $db->mGetCompPrices($model,'storages');
            // TODO: Obtener especificaciones técnicas del modelo solicitado
            foreach ($storages as $key => $partItem){
                $total = $partItem['price'] + $partItem['delivery-fare'];
                $dhtml .= "<tr>";
                $dhtml .= "<input id='product-id' type='hidden' value='" . $key . "'>";
                $dhtml .= "<td class='col-md-3 vert-align'>" . $partItem['provider'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem['price'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem['delivery-fare'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . (string)$total  . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/storage/" . $key ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-storage-list}}', $dhtml, $page);
            break;
        case 'motherboard':
            $page = file_get_contents("views/detailedComponents/motherboard.html");
            $dhtml = '';
            $model = $id;
            // TODO: Fijar nombre correcto de la base de datos
            $modelName = $db->mGetCompName($model,'motherboards');

            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            $motherboards = $db->mGetCompPrices($model,'motherboards');
            // TODO: Obtener especificaciones técnicas del modelo solicitado
            foreach ($motherboards as $key => $partItem){
                $total = $partItem['price'] + $partItem['delivery-fare'];
                $dhtml .= "<tr>";
                $dhtml .= "<input id='product-id' type='hidden' value='" . $key . "'>";
                $dhtml .= "<td class='col-md-3 vert-align'>" . $partItem['provider'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem['price'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem['delivery-fare'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . (string)$total  . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/motherboard/" . $key ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-motherboard-list}}', $dhtml, $page);
            break;
        case 'case':
            $page = file_get_contents("views/detailedComponents/case.html");
            $dhtml = '';
            $model = $id;
            // TODO: Fijar nombre correcto de la base de datos
            $modelName = $db->mGetCompName($model,'cases');

            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            $cases = $db->mGetCompPrices($model,'cases');
            // TODO: Obtener especificaciones técnicas del modelo solicitado
            foreach ($cases as $key => $partItem){
                $total = $partItem['price'] + $partItem['delivery-fare'];
                $dhtml .= "<tr>";
                $dhtml .= "<input id='product-id' type='hidden' value='" . $key . "'>";
                $dhtml .= "<td class='col-md-3 vert-align'>" . $partItem['provider'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem['price'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem['delivery-fare'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . (string)$total  . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/case/" . $key ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-case-list}}', $dhtml, $page);
            break;
        case 'monitor':
            $page = file_get_contents("views/detailedComponents/monitor.html");
            $dhtml = '';
            $model = $id;
            // TODO: Fijar nombre correcto de la base de datos
            $modelName = $db->mGetCompName($model,'monitors');

            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            $monitors = $db->mGetCompPrices($model,'monitors');
            // TODO: Obtener especificaciones técnicas del modelo solicitado
            foreach ($monitors as $key => $partItem){
                $total = $partItem['price'] + $partItem['delivery-fare'];
                $dhtml .= "<tr>";
                $dhtml .= "<input id='product-id' type='hidden' value='" . $key . "'>";
                $dhtml .= "<td class='col-md-3 vert-align'>" . $partItem['provider'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem['price'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem['delivery-fare'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . (string)$total  . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/monitor/" . $key ."\"'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-monitor-list}}', $dhtml, $page);
            break;
        case 'optical-drive':
            $page = file_get_contents("views/detailedComponents/optical-drive.html");
            $dhtml = '';
            $model = $id;
            // TODO: Fijar nombre correcto de la base de datos
            $modelName = $db->mGetCompName($model,'optical-drives');

            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            $opticaldrives = $db->mGetCompPrices($model,'optical-drives');
            // TODO: Obtener especificaciones técnicas del modelo solicitado
            foreach ($opticaldrives as $key => $partItem){
                $total = $partItem['price'] + $partItem['delivery-fare'];
                $dhtml .= "<tr>";
                $dhtml .= "<input id='product-id' type='hidden' value='" . $key . "'>";
                $dhtml .= "<td class='col-md-3 vert-align'>" . $partItem['provider'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem['price'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $partItem['delivery-fare'] . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . (string)$total  . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' onclick='window.location.href=\"partList/select/optical-drive/" . $key ."\"'>Añadir</button></td>";
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
 * ( Correponde a adminsitrator/views (después será eliminada está función).)
 * @param $lista array que contiene el listado de correos
 */
function vShowEmails($lista){
    $page = file_get_contents("views/admin/emails.html");

    $trozos=explode("##fila1##",$page);
    $aux="";
    $cuerpo="";

    foreach ($lista as $coleccion) {

        $aux=$trozos[1];
        $aux=str_replace("##email##",$coleccion['email'],$aux);
        $aux=str_replace("##emailid##",$coleccion['_id'],$aux);
        $cuerpo.=$aux;
    }

    echo $trozos[0].$cuerpo.$trozos[2];
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
function vShowAdmin() {
    $page = file_get_contents("views/admin/adminPanel.html");
    echo $page;
}

function vShowAdminLogin() {
    $page = file_get_contents("views/admin/adminLogin.html");
    echo $page;
}

/*
 * Crawlea datos de cpus de varias páginas web
 * TODO: Ampliar funcionalidad al resto de los componentes
 */
function vShowCrawlerPanel() {
    $page = file_get_contents("views/admin/crawlPanel.html");

    $dhtml = "";

    $db = new DBHelper();
    $cpus = $db->mGetComponent('cpus');
    $gpus = $db->mGetComponent('gpus');
    $cpuCoolers = $db->mGetComponent('cpu-coolers');
    $opticalDrives = $db->mGetComponent('optical-drives');
    $powerSupplies = $db->mGetComponent('power-supplies');
    $storages = $db->mGetComponent('storages');
    $monitors = $db->mGetComponent('monitors');
    $motherboards = $db->mGetComponent('motherboards');
    $memories = $db->mGetComponent('memories');
    $cases = $db->mGetComponent('cases');

    $components = [
        array('Procesadores', $cpus->count()),
        array('Ventiladores CPU', $cpuCoolers->count()),
        array('Tarjetas gráficas', $gpus->count()),
        array('Placas base', $motherboards->count()),
        array('Almacenamiento', $storages->count()),
        array('Memorias', $memories->count()),
        array('Fuentes de alimentación', $powerSupplies->count()),
        array('Cajas/carcasas', $cases->count()),
        array('Monitores', $monitors->count()),
        array('Dispositivos ópticos', $opticalDrives->count())
    ];

    foreach ($components as $component){
        $dhtml .= "<tr>";
        $dhtml .= "<td>" . $component[0] . "</td>";
        $dhtml .= "<td>" . $component[1] . "</td>";
        $dhtml .= "</tr>";
    }

    $lastExecTime = tailCustom('crawler/crawlData', 1, true);

    $page = str_replace('{{ registered-component-list }}', $dhtml, $page);
    $page = str_replace('{{ crawler-last-exec-time }}', $lastExecTime, $page);

    echo $page;
}

/* Muestra panel para incorporar datos crawleados a la BD*/
function vShowInsertCompPanel() {
    $page = file_get_contents("views/admin/insertCompPanel.html");
    echo $page;
}

/**
 * Obtiene un determinado número de líneas del final de un fichero PHP
 * @param $filepath
 * @param $lines
 * @param bool $adaptive
 * @return bool
 */
function tailCustom($filepath, $lines, $adaptive) {

    // Open file
    $f = @fopen($filepath, "rb");
    if ($f === false) return false;

    // Sets buffer size
    if (!$adaptive) $buffer = 4096;
    else $buffer = ($lines < 2 ? 64 : ($lines < 10 ? 512 : 4096));

    // Jump to last character
    fseek($f, -1, SEEK_END);

    // Read it and adjust line number if necessary
    // (Otherwise the result would be wrong if file doesn't end with a blank line)
    if (fread($f, 1) != "\n") $lines -= 1;

    // Start reading
    $output = '';
    $chunk = '';

    // While we would like more
    while (ftell($f) > 0 && $lines >= 0) {
        // Figure out how far back we should jump
        $seek = min(ftell($f), $buffer);

        // Do the jump (backwards, relative to where we are)
        fseek($f, -$seek, SEEK_CUR);

        // Read a chunk and prepend it to our output
        $output = ($chunk = fread($f, $seek)) . $output;

        // Jump back to where we started reading
        fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);

        // Decrease our line counter
        $lines -= substr_count($chunk, "\n");
    }

    // While we have too many lines
    // (Because of buffer size we might have read too many)
    while ($lines++ < 0) {
        // Find first newline and remove all text before that
        $output = substr($output, strpos($output, "\n") + 1);
    }

    // Close file and return
    fclose($f);
    return trim($output);
}

function vShowAbout() {
    $page = file_get_contents("views/about.html");

    echo $page;
}
