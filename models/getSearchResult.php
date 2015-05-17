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
    $t=0;
    $finalPrice=0;
    $size=count(@$partItem['prices']);

    if (!isset($partItem['family'][0])) {
        $partItem['family']="N/A";
    }
    if(!isset($partItem['socket'][0])){
        $partItem['socket']="N/A";
    }
    if(!isset($partItem['cores'][0])){
        $partItem['cores']="N/A";
    }
    if(!isset($partItem['frecuency'][0])){
        $partItem['frecuency']="N/A";
    }

    for ($i = 1; $i <= $size; $i++) {

        if($finalPrice==0){
            $finalPrice=$partItem['prices'][$t]['price'];
        }
        if($finalPrice>$partItem['prices'][$t]['price']){
            $finalPrice=$partItem['prices'][$t]['price'];
        }
        $t++;
    }

    $json[]=array(
        $partItem['pn'],
        $partItem['frecuency'],
        $partItem['family'],
        $partItem['prices'][0]['price'], // TODO: Cambiarlo cuando se decida como se haya el "desde...";
        $partItem['socket'],
        $partItem['cores'],
        $partItem['name']
    );
}

echo json_encode($json);