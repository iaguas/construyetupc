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
                if($finalPrice == 0){
                    $finalPrice = $partItem['prices'][$t]['price'];
                }
                if($finalPrice > $finalPrice = $partItem['prices'][$t]['price']){
                    $finalPrice = $partItem['prices'][$t]['price'];
                }
                $t++;
            }

            $json[]=array(
                $partItem['pn'],
                $partItem['frecuency'],
                $partItem['family'],
                $finalPrice  . " €",
                $partItem['socket'],
                $partItem['cores'],
                $partItem['name']
            );
        }
        break;
    case 'gpus':
        foreach ($op as $partItem) {
            $t=0;
            $finalPrice=0;
            $size=count(@$partItem['prices']);

            if (!isset($partItem['frecuency'][0])) {
                $partItem['frecuency']="N/A";
            }
            if(!isset($partItem['memory'][0])){
                $partItem['memory']="N/A";
            }

            for ($i = 1; $i <= $size; $i++) {
                if($finalPrice == 0){
                    $finalPrice = $partItem['prices'][$t]['price'];
                }
                if($finalPrice > $finalPrice = $partItem['prices'][$t]['price']){
                    $finalPrice = $partItem['prices'][$t]['price'];
                }
                $t++;
            }

            $json[]=array(
                $partItem['pn'],
                $partItem['frecuency'],
                $partItem['memory'],
                $finalPrice  . " €",
                $partItem['name']
            );
        }
        break;
    case 'cpucoolers':
        foreach ($op as $partItem) {
            $t=0;
            $finalPrice=0;
            $size=count(@$partItem['prices']);

            if (!isset($partItem['rpm'][0])) {
                $partItem['rpm']="N/A";
            }
            if (!isset($partItem['noise'][0])) {
                $partItem['noise']="N/A";
            }
            if (!isset($partItem['size'][0])) {
                $partItem['size']="N/A";
            }

            for ($i = 1; $i <= $size; $i++) {
                if($finalPrice == 0){
                    $finalPrice = $partItem['prices'][$t]['price'];
                }
                if($finalPrice > $finalPrice = $partItem['prices'][$t]['price']){
                    $finalPrice = $partItem['prices'][$t]['price'];
                }
                $t++;
            }

            $json[]=array(
                $partItem['pn'],
                $partItem['size'],
                $partItem['rpm'],
                $partItem['noise'],
                $finalPrice  . " €",
                $partItem['name']
            );
        }
        break;
    case 'motherboards':
        foreach ($op as $partItem) {
            $t=0;
            $finalPrice=0;
            $size=count(@$partItem['prices']);

            if(!isset($partItem['socket'][0])){
                $partItem['socket']="N/A";
            }

            for ($i = 1; $i <= $size; $i++) {
                if($finalPrice == 0){
                    $finalPrice = $partItem['prices'][$t]['price'];
                }
                if($finalPrice > $finalPrice = $partItem['prices'][$t]['price']){
                    $finalPrice = $partItem['prices'][$t]['price'];
                }
                $t++;
            }

            $json[]=array(
                $partItem['pn'],
                $finalPrice  . " €",
                $partItem['name'],
                $partItem['socket']
            );
        }
        break;
    case 'memories':
        foreach ($op as $partItem) {
            $t=0;
            $finalPrice=0;
            $size=count(@$partItem['prices']);

            if (!isset($partItem['frecuency'][0])) {
                $partItem['frecuency']="N/A";
            }
            if(!isset($partItem['memory'][0])){
                $partItem['memory']="N/A";
            }
            if(!isset($partItem['modules'][0])){
                $partItem['modules']="N/A";
            }
            if(!isset($partItem['size'][0])){
                $partItem['size']="N/A";
            }

            for ($i = 1; $i <= $size; $i++) {
                if($finalPrice == 0){
                    $finalPrice = $partItem['prices'][$t]['price'];
                }
                if($finalPrice > $finalPrice = $partItem['prices'][$t]['price']){
                    $finalPrice = $partItem['prices'][$t]['price'];
                }
                $t++;
            }

            $json[]=array(
                $partItem['pn'],
                $partItem['frecuency'],
                $partItem['modules'],
                $partItem['size'],
                $finalPrice  . " €",
                $partItem['name']
            );
        }
        break;
    case 'powersupplies':
        foreach ($op as $partItem) {
            $t=0;
            $finalPrice=0;
            $size=count(@$partItem['prices']);

            if (!isset($partItem['watts'][0])) {
                $partItem['watts']="N/A";
            }
            if(!isset($partItem['efficiency'][0])){
                $partItem['efficiency']="N/A";
            }

            for ($i = 1; $i <= $size; $i++) {
                if($finalPrice == 0){
                    $finalPrice = $partItem['prices'][$t]['price'];
                }
                if($finalPrice > $finalPrice = $partItem['prices'][$t]['price']){
                    $finalPrice = $partItem['prices'][$t]['price'];
                }
                $t++;
            }

            $json[]=array(
                $partItem['pn'],
                $partItem['watts'],
                $partItem['efficiency'],
                $finalPrice  . " €",
                $partItem['name']
            );
        }
        break;
    case 'cases':
        foreach ($op as $partItem) {
            $t=0;
            $finalPrice=0;
            $size=count(@$partItem['prices']);

            if (!isset($partItem['format'][0])) {
                $partItem['format']="N/A";
            }

            for ($i = 1; $i <= $size; $i++) {
                if($finalPrice == 0){
                    $finalPrice = $partItem['prices'][$t]['price'];
                }
                if($finalPrice > $finalPrice = $partItem['prices'][$t]['price']){
                    $finalPrice = $partItem['prices'][$t]['price'];
                }
                $t++;
            }

            $json[]=array(
                $partItem['pn'],
                $partItem['format'],
                $finalPrice  . " €",
                $partItem['name']
            );
        }
        break;
    case 'opticaldrives':
        foreach ($op as $partItem) {
            $t=0;
            $finalPrice=0;
            $size=count(@$partItem['prices']);

            for ($i = 1; $i <= $size; $i++) {
                if($finalPrice == 0){
                    $finalPrice = $partItem['prices'][$t]['price'];
                }
                if($finalPrice > $finalPrice = $partItem['prices'][$t]['price']){
                    $finalPrice = $partItem['prices'][$t]['price'];
                }
                $t++;
            }

            $json[]=array(
                $partItem['pn'],
                $finalPrice  . " €",
                $partItem['name']
            );
        }
        break;
    case 'monitors':
        foreach ($op as $partItem) {
            $t=0;
            $finalPrice=0;
            $size=count(@$partItem['prices']);

            if (!isset($partItem['resolution'][0])) {
                $partItem['resolution']="N/A";
            }
            if (!isset($partItem['size'][0])) {
                $partItem['size']="N/A";
            }

            for ($i = 1; $i <= $size; $i++) {
                if($finalPrice == 0){
                    $finalPrice = $partItem['prices'][$t]['price'];
                }
                if($finalPrice > $finalPrice = $partItem['prices'][$t]['price']){
                    $finalPrice = $partItem['prices'][$t]['price'];
                }
                $t++;
            }

            $json[]=array(
                $partItem['pn'],
                $partItem['resolution'],
                $partItem['size'],
                $finalPrice  . " €",
                $partItem['name']
            );
        }
        break;
}

echo json_encode($json);
