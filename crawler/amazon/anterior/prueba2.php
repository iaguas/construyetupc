<?php
 
    /* Example usage of the Amazon Product Advertising API */
    include("amazon_api_class.php");
 
    $obj = new AmazonProductAPI();
 
    $a = array();

    try
    {
        $result = $obj->getItemByKeyword("amd", AmazonProductAPI::PC);
        $numResult = count($result->Items->Item);
        
        //$result = $obj->getItemByAsin("B009O7YUF6");
        sleep(1);
        $i=0;
        foreach ($result->Items->Item as $value) {
            # code...
        
        //for ($i=0; $i < $numResult ; $i++) { 
            //print_r($result->Items->Item[$i]);

            //var_dump($result->Items->Item[$i]->ASIN->__toString());
            //print_r($str);
            //$s = new SimpleXMLElement($result->Items->Item[$i]);
            $str = $value->ASIN->__toString();
            //$str = "B009O7YUF6" ;
            echo $i."<br>";
            $result = $obj->getItemByAsin($str);
            $a[$i] = array(); 
            $a[$i]["family"] = "";
            $a[$i]["frecuency"] = "";//str_replace("Frequency: ", "", $result->Items->Item->ItemAttributes->Feature[0]);
            print_r($result->Items->Item->ItemAttributes->Feature);
            $a[$i]["img"] = "";
            $a[$i]["socket"] = "";//str_replace("Socket Type: ", "", $result->Items->Item->ItemAttributes->Feature[3]);
            $a[$i]["prices"] = array();
            $a[$i]["prices"]["url"] = $result->Items->Item->ItemAttributes->DetailPageURL->__toString();
            $a[$i]["prices"]["price"] = floatval($result->Items->Item->ItemAttributes->ListPrice->Amount->__toString()/100.0);
            $a[$i]["prices"]["delivery-fare"] = 0;
            $a[$i]["prices"]["provider"] = "amazon";
            $a[$i]["cores"] = "";//str_replace("Cores: ", "", $result->Items->Item->ItemAttributes->Feature[1]);
            $a[$i]["pn"] = $result->Items->Item->ItemAttributes->MPN->__toString();
            $a[$i]["name"] = $result->Items->Item->ItemAttributes->Title->__toString();
            sleep(1);
            $i = $i+1;
        }
     
        //print_r($result);
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }

    $result = json_encode($a);

    //$json_array = json_decode($result, true);
    $fp = fopen("json_amazon.json", 'w');
    fwrite($fp, $result);
    fclose($fp);

    //print_r($result);
 
?>