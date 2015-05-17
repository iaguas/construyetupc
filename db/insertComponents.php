<?php
/**
 * Created by PhpStorm.
 * User: Iñaki
 * Date: 29/04/2015
 * Time: 21:37
 * PHP encargado de almacenar los componentes en la BD
 */
// Clase de encapsulación para MongoDB
require_once '../models/DBHelper.php';
// Creación del objeto de base de datos
$db = new DBHelper();
// crawler/data/..
/*
$json_string = 'crawler/data/cases.json';
$json_string = 'crawler/data/cpuCoolers.json';
*/
$json_string = '../crawler/data/cpus-original.json';
$json = file_get_contents($json_string);
//return $json;
/*
$json_string = 'crawler/data/gpus.json';

$json_string = 'crawler/data/memories.json';
$json_string = 'crawler/data/monitors.json';
$json_string = 'crawler/data/motherboards.json';
$json_string = 'crawler/data/opticalDrives.json';

$json_string = 'crawler/data/powerSupplies.json';
$json_string = 'crawler/data/storages.json';
*/
// Colección destino
$destcol = "cpus";
/*
/// Datos decodificados de ajax
$data = json_decode($json_string,true);

*/
//$db->mCreateCollection($destcol);
$db->mInsertJson($json,$destcol);
//$db->mCompleteData($destcol,$json);
/*if(){
    return 1; // Éxito de inserción
}else{
    return 0; // Fracaso
}
/*
if($this->db->mCompleteData($destcol,$json)){
    return 1;
}else{
    return 0;
}*/
/*if($db->mInsertJson($json_string,$destcol)){
    // Devolver echo a AJAX como indicador de éxito
    echo "";
}*/

?>