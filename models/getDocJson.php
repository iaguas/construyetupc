<?php
/**
 * Created by PhpStorm.
 * User: paul
 * Date: 27/05/2015
 * Time: 11:46
 */

//require_once 'DBHelper.php';
//$db = new DBHelper();

$postData = file_get_contents('php://input');
$request = json_decode($postData);
@$ruta = $request->url2;

if (is_dir($ruta)) {
    if ($dh = opendir($ruta)) {
        while (($file = readdir($dh)) !== false) {
            //echo "Nombre de archivo: $file ";
            if(strcmp($file,'.') && strcmp($file,'..')) {
                $json[] = array(
                    'name' => $file,
                );
            }
        }
        closedir($dh);
        echo json_encode($json);
    }
}