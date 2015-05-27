<?php
/**
 * Created by PhpStorm.
 * User: Iñaki
 * Date: 29/04/2015
 * Time: 21:37
 * PHP encargado de almacenar (y próximamente, actualizar) los componentes en la BD
 */
// Clase de encapsulación para MongoDB
require_once '../models/DBHelper.php';
require_once '../models/parseJsons.php';
// Creación del objeto de base de datos
$db = new DBHelper();
$comp = $_POST['componente'];

// Se recorren los ficheros de forma iterativa
// TODO: ---------- LEER NOTAS ------------------
// NOTAS:
// - Se da por hecho que se encuentran los ficheros pertinentes, y que coinciden en nombre con cómo se desea
// en la BD
// - Este script se ejecutará siempre y cuando los JSON no estén ya parseados
if($comp == 'todos') {
    $files = ['cpus', 'cpu-coolers', 'cases', 'gpus', 'memories', 'monitors', 'motherboards', 'optical-drives', 'power-supplies', 'storages'];
    foreach ($files as $file) {
        // Parsear
        mParseJsons($file);
        // Quitar guiones del nombre de fichero
        $colname = str_replace("-", "", $file);
        //echo $colname;
        $json_string = '../crawler/data/' . $file . '.json';
        $json = file_get_contents($json_string);
        $db->mInsertJson($json, $colname);
    }
}
else{
    // Parsear
    mParseJsons($comp);
    // Quitar guiones del nombre de fichero
    $colname = str_replace("-", "", $comp);
    //echo $colname;
    $json_string = '../crawler/data/' . $comp . '.json';
    $json = file_get_contents($json_string);
    $db->mInsertJson($json, 'cpus');
}
/*if(){
    return 1; // Éxito de inserción
}else{
    return 0; // Fracaso
}*/

?>