<?php
 
require_once 'aws_signed_request.php';
 
class AmazonProductAPI
{
 
    private $public_key     = "AKIAJ7HPXCXV2BVGHAVA";
    private $private_key    = "5uKjteWE5QrnhflbN+mVZJzQEa5ngLB7cGIg3BDi";
 
    /* 'Associate Tag' now required, effective from 25th Oct. 2011 */
    private $associate_tag  = "con0ca-21";
 
    const MUSIC = "Music";
    const DVD   = "DVD";
    const GAMES = "VideoGames";
    const PC = "Electronics";
    const ALL = "All";
 
    private function verifyXmlResponse($response)
    {
        if ($response === False)
        {
            throw new Exception("Could not connect to Amazon");
        }
        else
        {
            if (isset($response->Items->Item->ItemAttributes->Title))
            {
                return ($response);
            }
            else
            {
                print_r($response);
                throw new Exception("Invalid xml response.");
            }
        }
    }
 
    private function queryAmazon($parameters)
    {
        return aws_signed_request("com",
                                  $parameters,
                                  $this->public_key,
                                  $this->private_key,
                                  $this->associate_tag);
    }
 
    public function searchProducts($search,$category,$searchType="UPC")
    {
        $allowedTypes = array("UPC", "TITLE", "ARTIST", "KEYWORD");
        $allowedCategories = array("Music", "DVD", "VideoGames");

        switch($searchType) 
        {
            case "UPC" :
                $parameters = array("Operation"     => "ItemLookup",
                                    "ItemId"        => $search,
                                    "SearchIndex"   => $category,
                                    "IdType"        => "UPC",
                                    "ResponseGroup" => "Medium");
                            break;
 
            case "TITLE" :
                $parameters = array("Operation"     => "ItemSearch",
                                    "Title"         => $search,
                                    "SearchIndex"   => $category,
                                    "ResponseGroup" => "Medium");
                            break;
 
        }
 
        $xml_response = $this->queryAmazon($parameters);
 
        return $this->verifyXmlResponse($xml_response);
 
    }
 
    public function getItemByUpc($upc_code, $product_type)
    {
        $parameters = array("Operation"     => "ItemLookup",
                            "ItemId"        => $upc_code,
                            "SearchIndex"   => $product_type,
                            "IdType"        => "UPC",
                            "ResponseGroup" => "Medium");
 
        $xml_response = $this->queryAmazon($parameters);
 
        return $this->verifyXmlResponse($xml_response);
 
    }
 
    public function getItemByAsin($asin_code)
    {
        $parameters = array("Operation"     => "ItemLookup",
                            "ItemId"        => $asin_code,
                            "ResponseGroup" => "Medium");
 
        $xml_response = $this->queryAmazon($parameters);
 
        return $this->verifyXmlResponse($xml_response);
    }

    public function getItemByKeyword($keyword, $product_type)
    {
        $parameters = array("Operation"   => "ItemSearch",
                            "Keywords"    => $keyword,
                            "SearchIndex" => $product_type);
 
        $xml_response = $this->queryAmazon($parameters);
 
        return $this->verifyXmlResponse($xml_response);
    }
 
}
 
?>