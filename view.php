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
    echo vFillTemplatePublic('views/index.html');
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
function vShowContact() {
    $page = file_get_contents('views/contact.html');
    echo $page;
}

/**
 * Muestra la lista de componentes.
 */
function vShowPartList() {

    $page = vFillTemplatePublic('views/partlist.html');

    $db = new DBHelper();
    $categories = $db->mGetHardwareCategories(); // Obtengo las categorías de componentes desde la base de datos

    $dhtml = '';
    $productPrice=0;
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

                $productName=$db->mGetCompName($productId, 'cpus');

                $provider=$db->mGetProviders($productVendor);
                $providerUrl=$provider['url'];
                // TODO: Obtener los datos de dicho producto desde la BD

                $dhtml .= "<td class='col-md-2 vert-align'><img src='assets/img/hard-icons/" . $category['name'] . ".png' alt='" . $category['name'] . "' width='32' height='32' /> " . $category['spanishName'] . "</td>";
                $dhtml .= "<td class='col-md-3 vert-align'>
                            <table>
                                <tr>
                                    <td rowspan='2'><img src='assets/img/corei3-temp.png' alt='product-image' width='50' height='50'/></td>
                                    <td><strong>Nombre</strong>: $productName<br />
                                        <strong>Precio</strong>: <span style='color:forestgreen'>". $productPrice ."</span>
                                    </td>
                                </tr>
                            </table>
                        </td>";
                $dhtml .= "<td class='col-md-2 vert-align'>
                            <img src='assets/img/shops/".$productVendor.".png' alt='Logo ".$productVendor."' width='50' height='50' /> <a href='$providerUrl' title='$productVendor'>". $productVendor ."</a>
                        </td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button type='button' class='btn btn-primary btn-xs' title='Comprar'>Comprar</button> <button type='button' class='btn btn-danger btn-xs' title='Eliminar' onclick='window.location.href=\"partList/remove/" . $category['name'] . "\"'>X</button></td>";
            }
        }

        $dhtml .= "</tr>";
    }

    // TODO: Calcular el coste total
    $totalCost=+$productPrice;
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
            $page = vFillTemplatePublic('views/components/cpu.html');
            // TODO: Obtener lista de todos los procesadores
            break;
        case 'cpu-cooler':
            $page = vFillTemplatePublic('views/components/cpu-cooler.html');
            // TODO: Obtener lista de todos los ventiladores de CPU
            break;
        case 'memory':
            $page = vFillTemplatePublic('views/components/memory.html');
            // TODO: Obtener lista de todas las memorias
            break;
        case 'gpu':
            $page = vFillTemplatePublic('views/components/gpu.html');
            break;
        case 'power-supply':
            $page = vFillTemplatePublic('views/components/power-supply.html');
            break;
        case 'storage':
            $page = file_get_contents("views/components/storage.html");
            break;
        case 'motherboard':
            $page = vFillTemplatePublic('views/components/motherboard.html');
            break;
        case 'case':
            $page = file_get_contents("views/components/case.html");
            break;
        case 'monitor':
            $page = file_get_contents("views/components/monitor.html");
            // TODO: Obtener lista de todos los monitores
            break;
        case 'optical-drive':
            $page = file_get_contents("views/components/optical-drive.html");
            // TODO: Obtener lista de todas las unidades ópticas
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
        //////////////////////////////  CASE  //////////////////////////////  
        case 'case':
            $page = vFillTemplatePublic('views/detailedComponents/case.html');
            $dhtml = '';
            $model = $id;
            $modelName = $db->mGetCompName($model, str_replace('-', '', $part).'s');

            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            $processors = $db->mGetCompPrices($model, str_replace('-', '', $part).'s');
            $properties = $db->mGetCompProperties($model, str_replace('-', '', $part).'s');

            foreach ($processors as $key => $partItem){
                $total = @$partItem['price'] + @$partItem['delivery-fare'];
                $dhtml .= "<tr>";
                $dhtml .= "<input id='product-id' type='hidden' value='" . $properties['pn'] . "'>";
                $dhtml .= "<td id='product-vendor' class='col-md-3 vert-align'>" . @$partItem['provider'] . "</td>";
                $dhtml .= "<td id='product-price' class='col-md-1 vert-align'>" . @$partItem['price']. "€" . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . @$partItem['delivery-fare']. "€" . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $total  . "€". "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button id='add-product' type='button'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            // Insertar las especficificaciones técnicas.
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-processor-list}}', $dhtml, $page);
            $page = str_replace('{{format}}', $properties['format'],$page);
            // Rellenar si es sin imagen de forma adecuada.
            if ($properties['img']=="") {
                $page = str_replace('{{image-url}}', "./assets/img/no-image150x150.png",$page);
                $page = str_replace('{{image-propieties}}', "No existe imagen",$page);
            }
            else{
                $page = str_replace('{{image-url}}', $properties['img'],$page);
                $page = str_replace('{{image-propieties}}', $modelName,$page);
            }
            break;
        //////////////////////////////  CPU  //////////////////////////////  
        case 'cpu':
            $page = vFillTemplatePublic('views/detailedComponents/cpu.html');
            //$dhtml = '';
            $model = $id;
            $modelName = $db->mGetCompName($model,'cpus');
            $properties = $db->mGetCompProperties($model, 'cpus');

            $page = str_replace('{{component-pn}}',$model,$page);
            $page = str_replace('{{component-type}}','cpus',$page);

            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            /*
            $processors = $db->mGetCompPrices($model,'cpus');
            $properties = $db->mGetCompProperties($model, 'cpus');
            foreach ($processors as $key => $partItem){
                $total = @$partItem['price'] + @$partItem['delivery-fare'];
                $dhtml .= "<tr>";
                $dhtml .= "<input id='product-id' type='hidden' value='" . $properties['pn'] . "'>";
                $dhtml .= "<td id='product-vendor' class='col-md-3 vert-align'>" . @$partItem['provider'] . "</td>";
                $dhtml .= "<td id='product-price' class='col-md-1 vert-align'>" . @$partItem['price']. "€" . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . @$partItem['delivery-fare']. "€" . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $total  . "€". "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button id='add-product' type='button'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }*/
            // Insertar las especficificaciones técnicas.
            $page = str_replace('{{component-name}}', $modelName, $page);
            //$page = str_replace('{{vendor-processor-list}}', $dhtml, $page);
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
        //////////////////////////////  CPU-COOLER  //////////////////////////////  
        case 'cpu-cooler':
            $page = vFillTemplatePublic('views/detailedComponents/cpu-cooler.html');
            $dhtml = '';
            $model = $id;
            $modelName = $db->mGetCompName($model,'cpucoolers');

            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            $processors = $db->mGetCompPrices($model,'cpucoolers');
            $properties = $db->mGetCompProperties($model, 'cpucoolers');
            foreach ($processors as $key => $partItem){
                $total = @$partItem['price'] + @$partItem['delivery-fare'];
                $dhtml .= "<tr>";
                $dhtml .= "<input id='product-id' type='hidden' value='" . $properties['pn'] . "'>";
                $dhtml .= "<td id='product-vendor' class='col-md-3 vert-align'>" . @$partItem['provider'] . "</td>";
                $dhtml .= "<td id='product-price' class='col-md-1 vert-align'>" . @$partItem['price']. "€" . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . @$partItem['delivery-fare']. "€" . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $total  . "€". "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button id='add-product' type='button'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            // Insertar las especficificaciones técnicas.
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-processor-list}}', $dhtml, $page);
            $page = str_replace('{{rpm}}', $properties['rpm'],$page);
            $page = str_replace('{{size}}', $properties['size'],$page);
            // Rellenar si es sin imagen de forma adecuada.
            if ($properties['img']=="") {
                $page = str_replace('{{image-url}}', "./assets/img/no-image150x150.png",$page);
                $page = str_replace('{{image-propieties}}', "No existe imagen",$page);
            }
            else{
                $page = str_replace('{{image-url}}', $properties['img'],$page);
                $page = str_replace('{{image-propieties}}', $modelName,$page);
            }
            break;
        //////////////////////////////  MEMORIES  //////////////////////////////  
        case 'memory':
            $page = vFillTemplatePublic('views/detailedComponents/memory.html');
            $dhtml = '';
            $model = $id;
            $modelName = $db->mGetCompName($model, 'memories');

            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            $processors = $db->mGetCompPrices($model, 'memories');
            $properties = $db->mGetCompProperties($model, 'memories');

            foreach ($processors as $key => $partItem){
                $total = @$partItem['price'] + @$partItem['delivery-fare'];
                $dhtml .= "<tr>";
                $dhtml .= "<input id='product-id' type='hidden' value='" . $properties['pn'] . "'>";
                $dhtml .= "<td id='product-vendor' class='col-md-3 vert-align'>" . @$partItem['provider'] . "</td>";
                $dhtml .= "<td id='product-price' class='col-md-1 vert-align'>" . @$partItem['price']. "€" . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . @$partItem['delivery-fare']. "€" . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $total  . "€". "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button id='add-product' type='button'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            // Insertar las especficificaciones técnicas.
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-processor-list}}', $dhtml, $page);
            $page = str_replace('{{size}}', $properties['size'],$page);
            $page = str_replace('{{frecuency}}', $properties['frecuency'],$page);
            $page = str_replace('{{modules}}', $properties['modules'],$page);
            // Rellenar si es sin imagen de forma adecuada.
            if ($properties['img']=="") {
                $page = str_replace('{{image-url}}', "./assets/img/no-image150x150.png",$page);
                $page = str_replace('{{image-propieties}}', "No existe imagen",$page);
            }
            else{
                $page = str_replace('{{image-url}}', $properties['img'],$page);
                $page = str_replace('{{image-propieties}}', $modelName,$page);
            }
            break;
        //////////////////////////////  GPU  //////////////////////////////   
        case 'gpu':
            $page = vFillTemplatePublic('views/detailedComponents/gpu.html');
            $dhtml = '';
            $model = $id;
            $modelName = $db->mGetCompName($model, str_replace('-', '', $part).'s');

            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            $processors = $db->mGetCompPrices($model, str_replace('-', '', $part).'s');
            $properties = $db->mGetCompProperties($model, str_replace('-', '', $part).'s');

            foreach ($processors as $key => $partItem){
                $total = @$partItem['price'] + @$partItem['delivery-fare'];
                $dhtml .= "<tr>";
                $dhtml .= "<input id='product-id' type='hidden' value='" . $properties['pn'] . "'>";
                $dhtml .= "<td id='product-vendor' class='col-md-3 vert-align'>" . @$partItem['provider'] . "</td>";
                $dhtml .= "<td id='product-price' class='col-md-1 vert-align'>" . @$partItem['price']. "€" . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . @$partItem['delivery-fare']. "€" . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $total  . "€". "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button id='add-product' type='button'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            // Insertar las especficificaciones técnicas.
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-processor-list}}', $dhtml, $page);
            $page = str_replace('{{memory}}', $properties['memory'],$page);
            // Rellenar si es sin imagen de forma adecuada.
            if ($properties['img']=="") {
                $page = str_replace('{{image-url}}', "./assets/img/no-image150x150.png",$page);
                $page = str_replace('{{image-propieties}}', "No existe imagen",$page);
            }
            else{
                $page = str_replace('{{image-url}}', $properties['img'],$page);
                $page = str_replace('{{image-propieties}}', $modelName,$page);
            }
            break;

        //////////////////////////////  MONITOR  //////////////////////////////  
        case 'monitor':
            $page = vFillTemplatePublic('views/detailedComponents/monitor.html');
            $dhtml = '';
            $model = $id;
            $modelName = $db->mGetCompName($model, str_replace('-', '', $part).'s');

            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            $processors = $db->mGetCompPrices($model, str_replace('-', '', $part).'s');
            $properties = $db->mGetCompProperties($model, str_replace('-', '', $part).'s');

            foreach ($processors as $key => $partItem){
                $total = @$partItem['price'] + @$partItem['delivery-fare'];
                $dhtml .= "<tr>";
                $dhtml .= "<input id='product-id' type='hidden' value='" . $properties['pn'] . "'>";
                $dhtml .= "<td id='product-vendor' class='col-md-3 vert-align'>" . @$partItem['provider'] . "</td>";
                $dhtml .= "<td id='product-price' class='col-md-1 vert-align'>" . @$partItem['price']. "€" . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . @$partItem['delivery-fare']. "€" . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $total  . "€". "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button id='add-product' type='button'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            // Insertar las especficificaciones técnicas.
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-processor-list}}', $dhtml, $page);
            $page = str_replace('{{resolution}}', $properties['resolution'],$page);
            $page = str_replace('{{size}}', $properties['size'],$page);
            // Rellenar si es sin imagen de forma adecuada.
            if ($properties['img']=="") {
                $page = str_replace('{{image-url}}', "./assets/img/no-image150x150.png",$page);
                $page = str_replace('{{image-propieties}}', "No existe imagen",$page);
            }
            else{
                $page = str_replace('{{image-url}}', $properties['img'],$page);
                $page = str_replace('{{image-propieties}}', $modelName,$page);
            }
            break;

        //////////////////////////////  MOTHERBOARD  //////////////////////////////  
        case 'motherboard':
            $page = vFillTemplatePublic('views/detailedComponents/motherboard.html');
            $dhtml = '';
            $model = $id;
            $modelName = $db->mGetCompName($model, str_replace('-', '', $part).'s');

            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            $processors = $db->mGetCompPrices($model, str_replace('-', '', $part).'s');
            $properties = $db->mGetCompProperties($model, str_replace('-', '', $part).'s');

            foreach ($processors as $key => $partItem){
                $total = @$partItem['price'] + @$partItem['delivery-fare'];
                $dhtml .= "<tr>";
                $dhtml .= "<input id='product-id' type='hidden' value='" . $properties['pn'] . "'>";
                $dhtml .= "<td id='product-vendor' class='col-md-3 vert-align'>" . @$partItem['provider'] . "</td>";
                $dhtml .= "<td id='product-price' class='col-md-1 vert-align'>" . @$partItem['price']. "€" . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . @$partItem['delivery-fare']. "€" . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $total  . "€". "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button id='add-product' type='button'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            // Insertar las especficificaciones técnicas.
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-processor-list}}', $dhtml, $page);
            $page = str_replace('{{socket}}', $properties['socket'],$page);
            // Rellenar si es sin imagen de forma adecuada.
            if ($properties['img']=="") {
                $page = str_replace('{{image-url}}', "./assets/img/no-image150x150.png",$page);
                $page = str_replace('{{image-propieties}}', "No existe imagen",$page);
            }
            else{
                $page = str_replace('{{image-url}}', $properties['img'],$page);
                $page = str_replace('{{image-propieties}}', $modelName,$page);
            }
            break;

        //////////////////////////////  OPTICAL DRIVE  //////////////////////////////  
        case 'optical-drive':
            $page = vFillTemplatePublic('views/detailedComponents/optical-drive.html');
            $dhtml = '';
            $model = $id;
            $modelName = $db->mGetCompName($model, str_replace('-', '', $part).'s');

            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            $processors = $db->mGetCompPrices($model, str_replace('-', '', $part).'s');
            $properties = $db->mGetCompProperties($model, str_replace('-', '', $part).'s');

            foreach ($processors as $key => $partItem){
                $total = @$partItem['price'] + @$partItem['delivery-fare'];
                $dhtml .= "<tr>";
                $dhtml .= "<input id='product-id' type='hidden' value='" . $properties['pn'] . "'>";
                $dhtml .= "<td id='product-vendor' class='col-md-3 vert-align'>" . @$partItem['provider'] . "</td>";
                $dhtml .= "<td id='product-price' class='col-md-1 vert-align'>" . @$partItem['price']. "€" . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . @$partItem['delivery-fare']. "€" . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $total  . "€". "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button id='add-product' type='button'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            // Insertar las especficificaciones técnicas.
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-processor-list}}', $dhtml, $page);
            // Esta categoría no tiene especificaciones técnicas
            // Rellenar si es sin imagen de forma adecuada.
            if ($properties['img']=="") {
                $page = str_replace('{{image-url}}', "./assets/img/no-image150x150.png",$page);
                $page = str_replace('{{image-propieties}}', "No existe imagen",$page);
            }
            else{
                $page = str_replace('{{image-url}}', $properties['img'],$page);
                $page = str_replace('{{image-propieties}}', $modelName,$page);
            }
            break;

        //////////////////////////////  POWER SUPPLY  //////////////////////////////  
        case 'power-supply':
            $page = vFillTemplatePublic('views/detailedComponents/power-supply.html');
            $dhtml = '';
            $model = $id;
            $modelName = $db->mGetCompName($model, 'powersupplies');

            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            $processors = $db->mGetCompPrices($model, 'powersupplies');
            $properties = $db->mGetCompProperties($model, 'powersupplies');

            foreach ($processors as $key => $partItem){
                $total = @$partItem['price'] + @$partItem['delivery-fare'];
                $dhtml .= "<tr>";
                $dhtml .= "<input id='product-id' type='hidden' value='" . $properties['pn'] . "'>";
                $dhtml .= "<td id='product-vendor' class='col-md-3 vert-align'>" . @$partItem['provider'] . "</td>";
                $dhtml .= "<td id='product-price' class='col-md-1 vert-align'>" . @$partItem['price']. "€" . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . @$partItem['delivery-fare']. "€" . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $total  . "€". "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button id='add-product' type='button'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            // Insertar las especficificaciones técnicas.
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-processor-list}}', $dhtml, $page);
            $page = str_replace('{{eficiency}}', $properties['eficiency'],$page);
            // Rellenar si es sin imagen de forma adecuada.
            if ($properties['img']=="") {
                $page = str_replace('{{image-url}}', "./assets/img/no-image150x150.png",$page);
                $page = str_replace('{{image-propieties}}', "No existe imagen",$page);
            }
            else{
                $page = str_replace('{{image-url}}', $properties['img'],$page);
                $page = str_replace('{{image-propieties}}', $modelName,$page);
            }
            break;

        //////////////////////////////  STORAGE  //////////////////////////////  
        case 'storage':
            $page = vFillTemplatePublic('views/detailedComponents/storage.html');
            $dhtml = '';
            $model = $id;
            $modelName = $db->mGetCompName($model, str_replace('-', '', $part).'s');

            // TODO: Obtener lista de todas las tiendas que tienen el modelo solicitado y sus precios
            $processors = $db->mGetCompPrices($model, str_replace('-', '', $part).'s');
            $properties = $db->mGetCompProperties($model, str_replace('-', '', $part).'s');

            foreach ($processors as $key => $partItem){
                $total = @$partItem['price'] + @$partItem['delivery-fare'];
                $dhtml .= "<tr>";
                $dhtml .= "<input id='product-id' type='hidden' value='" . $properties['pn'] . "'>";
                $dhtml .= "<td id='product-vendor' class='col-md-3 vert-align'>" . @$partItem['provider'] . "</td>";
                $dhtml .= "<td id='product-price' class='col-md-1 vert-align'>" . @$partItem['price']. "€" . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . @$partItem['delivery-fare']. "€" . "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'>" . $total  . "€". "</td>";
                $dhtml .= "<td class='col-md-1 vert-align'><button id='add-product' type='button'>Añadir</button></td>";
                $dhtml .= "</tr>";
            }
            // Insertar las especficificaciones técnicas.
            $page = str_replace('{{component-name}}', $modelName, $page);
            $page = str_replace('{{vendor-processor-list}}', $dhtml, $page);
            $page = str_replace('{{format}}', $properties['format'],$page);
            $page = str_replace('{{type}}', $properties['type'],$page);
            // Rellenar si es sin imagen de forma adecuada.
            if ($properties['img']=="") {
                $page = str_replace('{{image-url}}', "./assets/img/no-image150x150.png",$page);
                $page = str_replace('{{image-propieties}}', "No existe imagen",$page);
            }
            else{
                $page = str_replace('{{image-url}}', $properties['img'],$page);
                $page = str_replace('{{image-propieties}}', $modelName,$page);
            }
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
    echo vFillTemplatePublic('views/about.html');
}

function vFillTemplatePublic($contentRoute) {
    $templateRoute = 'views/templates/public.html';

    $template = file_get_contents($templateRoute);
    $content = file_get_contents($contentRoute);
    $result = str_replace('{{template_content}}', $content, $template);

    switch($contentRoute) {
        case 'views/partlist.html':
            $result = str_replace('{{partlist_class}}', 'active animated fadeIn', $result);
            $result = str_replace('{{partlist_link}}', 'javascript:void(0);', $result);
            $result = str_replace('{{about_link}}', '/about', $result);
            break;
        case 'views/about.html':
            $result = str_replace('{{about_class}}', 'active animated fadeIn', $result);
            $result = str_replace('{{about_link}}', 'javascript:void(0);', $result);
            $result = str_replace('{{partlist_link}}', '/partList', $result);
            break;
        case 'views/components/cpu.html':
            $result = str_replace('{{partlist_class}}', 'active animated fadeIn', $result);
            $result = str_replace('{{about_link}}', '/about', $result);
            $result = str_replace('{{partlist_link}}', '/partList', $result);
            break;
        case 'views/components/gpu.html':
            $result = str_replace('{{partlist_class}}', 'active animated fadeIn', $result);
            $result = str_replace('{{about_link}}', '/about', $result);
            $result = str_replace('{{partlist_link}}', '/partList', $result);
            break;
        case 'views/components/cpu-cooler.html':
            $result = str_replace('{{partlist_class}}', 'active animated fadeIn', $result);
            $result = str_replace('{{about_link}}', '/about', $result);
            $result = str_replace('{{partlist_link}}', '/partList', $result);
            break;
        case 'views/components/motherboard.html':
            $result = str_replace('{{partlist_class}}', 'active animated fadeIn', $result);
            $result = str_replace('{{about_link}}', '/about', $result);
            $result = str_replace('{{partlist_link}}', '/partList', $result);
            break;
        case 'views/components/memory.html':
            $result = str_replace('{{partlist_class}}', 'active animated fadeIn', $result);
            $result = str_replace('{{about_link}}', '/about', $result);
            $result = str_replace('{{partlist_link}}', '/partList', $result);
            break;
        case 'views/components/power-supply.html':
            $result = str_replace('{{partlist_class}}', 'active animated fadeIn', $result);
            $result = str_replace('{{about_link}}', '/about', $result);
            $result = str_replace('{{partlist_link}}', '/partList', $result);
            break;
        case 'views/detailedComponents/cpu.html':
            $result = str_replace('{{partlist_class}}', 'active animated fadeIn', $result);
            $result = str_replace('{{about_link}}', '/about', $result);
            $result = str_replace('{{partlist_link}}', '/partList', $result);
            break;
        default:
            $result = str_replace('{{partlist_class}}', '', $result);
            $result = str_replace('{{about_class}}', '', $result);
            $result = str_replace('{{partlist_link}}', '/partList', $result);
            $result = str_replace('{{about_link}}', '/about', $result);
            break;
    }

    return $result;
}
