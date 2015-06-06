<?php
//namespace Acme\Demo;
require_once "vendor/autoload.php";
require_once '../../models/DBHelper.php';

use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\Operations\Search;
use ApaiIO\Operations\Lookup;
use ApaiIO\ApaiIO;
use ApaiIO\ResponseTransformer\XmlToSimpleXmlObject;

$conf = new GenericConfiguration();
$conf
    ->setCountry('es')
    ->setAccessKey("AKIAJ7HPXCXV2BVGHAVA")
    ->setSecretKey("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")
    ->setAssociateTag("con0ca-21");

$apaiIO = new ApaiIO($conf);

$db = new DBHelper();

$component = ["cpus", "cases", "cpucoolers", "gpus", "memories", "monitors", "motherboards", "opticaldrives", "powersupplies", "storages"];
foreach ($component as $ct) {
    $col = $db->mGetComponent($ct);
    foreach ($col as $doc){
        
        // Hacemos la búsqueda en Amazon
        $search = new Search();
        $search->setCategory('Electronics');
        $search->setKeywords($doc["pn"]);
        $search->setResponseGroup(array('Large', 'Large'));

        // Formateamos la respuesta adecuadamente en XML
        $formattedResponse = $apaiIO->runOperation($search);
        $tranformer = new XmlToSimpleXmlObject();
        $xml = $tranformer->transform($formattedResponse);

        $numProductos = (int) $xml->Items->TotalResults;
        echo $numProductos."    ";
        if($numProductos>1){
            for ($i=0; $i < $numProductos ; $i++) { 
                
                $request = $xml->Items->Item[$i]->DetailPageURL;

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,$request);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 15);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

                $xml_response = curl_exec($ch);

                $pos = strpos($xml_response, "Vendido y enviado por Amazon");

                if (! $pos === false) {
                    break;
                }
            }
        }


    /*echo $xml->getName() . "\n  <br>   ";

    foreach ($xml->children() as $hijo)
    {
        echo ">".$hijo->getName() . "\n  <br>    ";
        foreach ($hijo->children() as $h)
        {
        echo ">>".$h->getName() . "\n  <br>    ";
        
        foreach ($h->children() as $h1)
        {
        echo ">>>".$h1->getName() . "\n  <br>    ";

        foreach ($h1->children() as $h2)
    {
        echo ">>>>".$h2->getName() . "\n  <br>    ";
    }}}
    }*/

      /*  //print_r($xml);
        echo "<br>";
        echo "<br>";
            echo "<br>";
                echo "<br>";
    //            echo (string) $xml->Items->Item->ItemAttributes->ListPrice->getName();//->__toString());*/
    /*    array_push($doc["prices"], array("url"=>$xml->Items->Item[$i]->DetailPageURL->__toString(), 
                                         "price"=>floatval($xml->Items->Item[$i]->ItemAttributes->ListPrice->Amount->__toString())/100.00, 
                                         "delivery-fare"=>0, "provider"=>"amazon"));
    */
        //$col->update(array("pn" => $doc["pn"]), $doc);

    /*    print_r($doc["prices"]);
        echo "<br>";
    $json = json_encode($xml);
    $json = str_replace("<ul>", "", $json);
    $json = str_replace("</ul>", "", $json);
    $json = str_replace("<li>", "", $json);
    $json = str_replace("</li>", "", $json);
    */
    //var_dump($json); echo "<br>";echo "<br>";echo "<br>";echo "<br>";
        $j = 0;
        $inside = 0;
        foreach ($doc["prices"] as $item) {
            if ($item["provider"] == "amazon") {
                $inside = 1;
                $j = $j+1;
                break;
            }
            $j = $j+1;
        }

        if ($inside == 0) {
            $price = floatval($xml->Items->Item[$i]->OfferSummary->LowestNewPrice->Amount)/100;
            //echo floatval($xml->Items->Item[$i]->)/100;
            if ($price != 0) {
                array_push($doc["prices"], array("url"=>$xml->Items->Item[$i]->DetailPageURL->__toString(), 
                                                 "price"=>$price, "delivery-fare"=>0, "provider"=>"amazon"));
                $db->mUpdateDoc($ct, $doc);
            }
        }
        else {
            $price = floatval($xml->Items->Item[$i]->OfferSummary->LowestNewPrice->Amount)/100;
            //echo floatval($xml->Items->Item[$i]->)/100;
            if ($price != 0) {
                $doc["prices"][$j]["url"] = $xml->Items->Item[$i]->DetailPageURL->__toString();
                $doc["prices"][$j]["price"] = $price;
                $db->mUpdateDoc($ct, $doc);
            }
        }

    } // foreach
} // foreach



/*
$search = new Search();
$search->setCategory('Electronics');
$search->setKeywords('BX80648I75960X');
$search->setResponseGroup(array('Large', 'Small'));

$formattedResponse = $apaiIO->runOperation($search);

/*$lockup = new Lookup();
$lockup->setItemId('B009O7YUF6');
$lockup->setIdType('ASIN');
//$lockup->setSearchIndex();

$formattedResponse = $apaiIO->runOperation($lockup);*/
/*$lookup = new Lookup();
$lookup->setItemId('B009O7YUF6');
$lookup->setResponseGroup(array('Large', 'Small'));

$formattedResponse = $apaiIO->runOperation($lookup);
*//*
$tranformer = new XmlToSimpleXmlObject();
$xml = $tranformer->transform($formattedResponse);

echo "<br><br><br>".$xml->Items->Item->DetailPageURL->__toString();
*/
$json = json_encode($xml);
?>