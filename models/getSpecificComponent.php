<?php
require_once 'DBHelper.php';
$db = new DBHelper();

$postData = file_get_contents('php://input');
$request = json_decode($postData);
@$ct = $request->component;
//Llamada a la función
$op=$db->mGetComponent($ct);

switch($ct) {
    case 'cpus':
        foreach ($op as $partItem) {
            $t=0;
            $finalPrice=0;
            $size=count(@$partItem['prices']);

            //
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
                    $finalPrice=$finalPrice=$partItem['prices'][$t]['price'];
                }
                if($finalPrice>$finalPrice=$finalPrice=$partItem['prices'][$t]['price']){
                    $finalPrice=$finalPrice=$finalPrice=$partItem['prices'][$t]['price'];
                }
                $t++;
            }

            $json[]=array(
                $partItem['pn'],
                $partItem['frecuency'],
                $partItem['family'],
                $finalPrice,
                $partItem['socket'],
                $partItem['cores'],
                $partItem['name']
            );
        }
        break;
}

echo json_encode($json);
