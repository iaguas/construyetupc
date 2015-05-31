<?php
require_once 'DBHelper.php';
$db = new DBHelper();

$postData = file_get_contents('php://input');
$request = json_decode($postData);
@$cp = $request->compPn;
@$ct = $request->compType;
//Llamada a la función
$op=$db->mGetCompPrices($cp,$ct);

foreach ($op as $provider_row){
    // Acceder al campo del array, o a la posición 0 de la misma
    if($provider_row['delivery-fare']==0.0) {
        $provider_row['delivery-fare'] = "N/A";
        $total = $provider_row['price'];
    }else{
        $total = round($provider_row['price'] + $provider_row['delivery-fare'], 2);
        $provider_row['delivery-fare'] = $provider_row['delivery-fare'] . " €";
    }


    $json[]=array(
        'name' => $provider_row['provider'],
        'price' => $provider_row['price'] . " €",
        'delivery-fare' => $provider_row['delivery-fare'],
        'total' => $total . " €"
    );
}

echo json_encode($json);

