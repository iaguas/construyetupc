<?php
/**
 * Created by PhpStorm.
 * User: Iñaki
 * Date: 29/04/2015
 * Time: 21:37
 * PHP encargado de almacenar y actualizar los componentes en la BD
 */
// Clase de encapsulación para MongoDB
require_once '../models/DBHelper.php';
require_once '../models/parseJsons.php';
// Creación del objeto de base de datos
$db = new DBHelper();

$postData = file_get_contents('php://input');
$request = json_decode($postData);//echo $request;
@$files = $request->docs;

foreach($files as $file) {
    // Parsear
    mParseJsons($file);
    // Quitar guiones del nombre de fichero
    $colname = str_replace("-", "", $file);
    $aux = explode('.', $colname);
    $colname=$aux[0];
    // Borra toda la tabla de la base de datos 
    //$db->mRemoveAllInCollection($colname);

    $json_string = '../crawler/data/' . $file;
    $json = file_get_contents($json_string);
    $db->mCompleteData($colname,$json,"4frags");
}
echo 'ok';
