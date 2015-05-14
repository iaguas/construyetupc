<?php
require_once 'DBHelper.php';
$db = new DBHelper();

$postData = file_get_contents('php://input');
$request = json_decode($postData);
$component = $request->component;
$searchText = $request->text;
//Llamada a la función
$response = $db->mSearchProduct($component, $searchText);

$json=array();

foreach ($response as $partItem) {
    if (!isset($partItem['family'][0])) {
        $partItem['family'][0]="N/A";
    }
    if(!isset($partItem['socket'][0])){
        $partItem['socket'][0]="N/A";
    }
    if(!isset($partItem['cores'][0])){
        $partItem['cores'][0]="N/A";
    }
    if(!isset($partItem['frecuency'][0])){
        $partItem['frecuency'][0]="N/A";
    }

    $json[]=array(
        $partItem['pn'][0],
        $partItem['frecuency'][0],
        $partItem['family'][0],
        $partItem['price'][0],
        $partItem['socket'][0],
        $partItem['cores'][0],
        $partItem['name'][0]
    );
}

echo json_encode($json);