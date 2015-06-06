<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
  xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<title>Amazon API Example</title>
</head>
<body>
<?php
if (isset($_POST['amazon_search']))  {
  //datos
  $secret_key = "5uKjteWE5QrnhflbN+mVZJzQEa5ngLB7cGIg3BDi";
  $region = "com";  // ca,com,co.uk,de,fr,jp
  $method = "GET";
  $host = "ecs.amazonaws.".$region;
  $uri = "/onca/xml";
 
  //parametros
  $params["Keywords"] = $_POST['Keywords'];
  $params["Service"] = "AWSECommerceService";
  $params["Operation"] = "ItemSearch";
  $params["SearchIndex"] = $_POST['SearchIndex'];
  $params["ResponseGroup"] = "ItemAttributes";
  $params["Sort"] = "salesrank";
  $params["AWSAccessKeyId"] = "AKIAJ7HPXCXV2BVGHAVA";
  $params["AssociateTag"] = "con0ca-21";
  $params["Version"] = "2010-11-01";
  $params["Timestamp"] = gmdate("Y-m-dTH:i:sZ");
 
  //ordenar los parametros
  ksort($params);
 
  //crear la consulta
  $canonicalized_query = array();
  foreach ($params as $param=>$value) {
    $param = str_replace("%7E", "~", rawurlencode($param));
    $value = str_replace("%7E", "~", rawurlencode($value));
    $canonicalized_query[] = $param."=".$value;
  }
  $canonicalized_query = implode("&", $canonicalized_query);
 
  //crear la cadena a firmar
  $string_to_sign = $method."n".$host."n".$uri."n".$canonicalized_query;
 
  //calcular HMAC con SHA256 y base64-encoding
  $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $secret_key, True));
 
  //encode la firma para la consulta
  $signature = str_replace("%7E", "~", rawurlencode($signature));
 
  //crear consulta
  $request = "http://".$host.$uri."?".$canonicalized_query."&Signature=".$signature;
 
  //obtener request
  $response = file_get_contents($request);
  echo '<h2>Resultado de la b&uacute;queda en Amazon</h2>';
  if ($response === False) {
    echo "<p>No hay resultados para '".$params["Keywords"]."'</p>";
  } else {
    // parse XML
    $xml = simplexml_load_string($response);
    if ($xml === False) {
      echo "<p>No XML</p>";
    } else {
      $TotalResults = $xml->Items->TotalResults;
      echo "<p>Resultados para '".$params["Keywords"]."': ".$TotalResults."</p>";
      $Items = $xml->Items;
      $i = 0;
      echo "<table>";
      foreach ($Items->Item as $Item) {
        $Title = $Item->ItemAttributes->Title;
        $ASIN = $Item->ASIN;
        $i = $i + 1;
        echo "<tr>";
        echo "<td>".$i."</td>";
        echo "<td>".$ASIN."</td>";
        echo "<td>".$Title."</td>";
        echo "</tr>";
      }
      echo "</table>";
    }
  }
  echo '<p><a href="/pruebaAmazon.php">New Search</a></p>';
} else {
?>
<h2>Buscar Keywords, ASIN o ISBN en Amazon</h2>
<form method="post" action="">
  <p>Introduzca cadena a buscar (Keywords, ASIN o ISBN):</p>
  <p><input type="text" name="Keywords" id="Keywords" value="" size="50" />
    <select name='SearchIndex'>
      <option value='Books'>Books</option>
      <option value='DVD'>DVD</option>
      <option value='Music'>Music</option>
    </select>
    <input type="submit" value="Amazon Search" name="enviar" />
    <input type="hidden" name="amazon_search" value="" /></p>
  </p>
</form>
<?php
}
?>
</body>
</html>