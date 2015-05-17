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
    if (!isset($partItem['family'])) {
        $partItem['family']="N/A";
    }
    if(!isset($partItem['socket'])){
        $partItem['socket']="N/A";
    }
    if(!isset($partItem['cores'])){
        $partItem['cores']="N/A";
    }
    if(!isset($partItem['frecuency'])){
        $partItem['frecuency']="N/A";
    }

    $json[]=array(
        $partItem['pn'],
        $partItem['frecuency'],
        $partItem['family'],
        ''.$partItem['prices'][0]['price'], // TODO: Cambiarlo cuando se decida como se haya el "desde...";
        $partItem['socket'],
        $partItem['cores'],
        $partItem['name']
    );
}

echo json_encode($json);