<?php
/**
 *	Fichero: sessions.php
 * 	Descripcion: Contiene toda la funcionalidad relacionada con la gestión de sesiones.
 *		El nombre de las funciones empiezan por la letra "s" (hace referencia al modelo de sesiones para que se más sencillo localizarlo).
 */

/**
 * Comprueba si el usuario tiene definida la variable que almacena la lista de componentes elegidos. Si no la tiene
 * la crea.
 */
function sCheckSessionVar(){
    if (!isset($_SESSION['partList'])){
        $_SESSION['partList'] = [
            'cpu' => null,
            'gpu' => null,
            'cpu-cooler' => null,
            'memory' => null,
            'case' => null,
            'monitor' => null,
            'motherboard' => null,
            'power-supply' => null,
            'optical-drive' => null,
            'storage' => null
        ];
    }
}

/**
 * Añade un componente a la lista de partes elegidas
 */
function sAddPart($part){

    $postData = file_get_contents('php://input');
    $request = json_decode($postData);
    $productId = $request->productId;
    //var_dump($productId);
    $vendorName = $request->productVendorName;
    //var_dump($vendorName);
    $productPrice = $request->productPrice;
    //var_dump($productPrice);
    //var_dump($part);

    if ($part == "cpu"){
        $_SESSION['partList']['cpu'] = ['productId' => $productId, 'vendorId' => $vendorName, 'price' => $productPrice];
        //var_dump($_SESSION['partList']['cpu']);
    }elseif ($part == "gpu"){
        $_SESSION['partList']['gpu'] = ['productId' => $productId, 'vendorId' => $vendorName, 'price' => $productPrice];
    }elseif ($part == "cpu-cooler"){
        $_SESSION['partList']['cpu-cooler'] = ['productId' => $productId, 'vendorId' => $vendorName, 'price' => $productPrice];
    }elseif ($part == "memory"){
        $_SESSION['partList']['memory'] = ['productId' => $productId, 'vendorId' => $vendorName, 'price' => $productPrice];
    }elseif ($part == "case"){
        $_SESSION['partList']['case'] = ['productId' => $productId, 'vendorId' => $vendorName, 'price' => $productPrice];
    }elseif ($part == "monitor"){
        $_SESSION['partList']['monitor'] = ['productId' => $productId, 'vendorId' => $vendorName, 'price' => $productPrice];
    }elseif ($part == "motherboard"){
        $_SESSION['partList']['motherboard'] = ['productId' => $productId, 'vendorId' => $vendorName, 'price' => $productPrice];
    }elseif ($part == "power-supply"){
        $_SESSION['partList']['power-supply'] = ['productId' => $productId, 'vendorId' => $vendorName, 'price' => $productPrice];
    }elseif ($part == "optical-drive"){
        $_SESSION['partList']['optical-drive'] = ['productId' => $productId, 'vendorId' => $vendorName, 'price' => $productPrice];
    }elseif ($part == "storage"){
        $_SESSION['partList']['storage'] = ['productId' => $productId, 'vendorId' => $vendorName, 'price' => $productPrice];
    }
}

/**
 * Elimina un componente de la lista de partes elegidas
 */
function sRemovePart($categoryToRemove){
	switch ($categoryToRemove) {
		case 'cpu':
			$_SESSION['partList']['cpu'] = null;
			break;
		case 'cpu-cooler':
			$_SESSION['partList']['cpu-cooler'] = null;
			break;
		case 'gpu':
			$_SESSION['partList']['gpu'] = null;
			break;
		case 'memory':
			$_SESSION['partList']['memory'] = null;
			break;
		case 'case':
			$_SESSION['partList']['case'] = null;
			break;
		case 'monitor':
			$_SESSION['partList']['monitor'] = null;
			break;
		case 'motherboard':
			$_SESSION['partList']['motherboard'] = null;
			break;
		case 'power-supply':
			$_SESSION['partList']['power-supply'] = null;
			break;
		case 'optical-drive':
			$_SESSION['partList']['optical-drive'] = null;
			break;
		case 'storage':
			$_SESSION['partList']['storage'] = null;
			break;
	}
}
