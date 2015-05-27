<?php
/**
 * Created by PhpStorm.
 * User: Iñaki
 * Date: 08/05/2015
 * Time: 15:54
 */
/* TODO: PARA LAS PRUEBAS CON ENTRADAS DE PRICES DE 3 CAMPOS (PRICE,DELIVERY-FARE,PROVIDER) COMENTAR LÍNEAS 48 Y 56 Y DESCOMENTAR SUS
RESPECTIVAS ANTERIORES (47 Y 55)
*/
/**
 * Esta funcion normaliza los JSONs creados por la araña web. Emplea la función toFloat() para normalizar los precios
 * Funcionalidades de éste parser
 * - Sustituir caracteres extraños por espacio vacio
 * - Eliminar espacios en claves para identificar productos
 * - Eliminar filas con claves ("pn") que no cumplen con el mínimo de 8 carácteres o no existen
 * - Mapear correctamente (en arrays) los precios por tiendas
 * - Eliminar euro/dolar de los precios
 * - Mapear correctamente el array general
 * @param $dbcol string con el nombre de la tabla de base de datos al que se va almacenar el fichero JSON de mismo nombre
 */
function mParseJsons($dbcol){
    /**
     * Este fichero PHP parsea los ficheros JSON antes de ser incorporados a la BD eliminando espacios en blanco no deseados
     */
    $json_string = '../crawler/data/'. $dbcol .'.json';
    //$json_string = '../crawler/data/cpus.json';
    $json = file_get_contents($json_string);
    // Devolver array, no un objeto
    $json_array = json_decode($json, true);

    // NOTA: necesario pasar la variable por referencia para modificar la variable recorrida
    foreach($json_array as &$row) {
        if(isset($row['pn']) || !empty($row['pn'])) {
            $row['pn'][0] = trim($row['pn'][0]);
        }
        // Arrays auxiliares para el array de arrays de prices
        $prices = [];
        //$shop_keys = ['price', 'delivery-fare', 'provider'];
        $shop_keys = ['url', 'price', 'delivery-fare', 'provider'];
        $shop_vals = [];
        $j = 0;
        // Recorremos los valores almacenados y los tratamos (teniendo en cuenta los índices repetidos
        foreach ($row['prices'] as $row_in_prices) {
            array_push($shop_vals, $row_in_prices);
            $j++;
            //if ($j == 3) {
            if ($j == 4) {
                array_push($prices, array_combine($shop_keys, $shop_vals));
                $j = 1;
                $shop_vals = [];
            }
        }
        // Incorporamos el array al campo prices
        $row['prices'] = $prices;
        // Se normalizan los valores en el array de precios
        $k = 0;
        foreach ($row['prices'] as $row_in_prices) {
            foreach ($row_in_prices as $index => $sub_row) {
                if (!is_string($row_in_prices[$index])) {
                    $row['prices'][$k][$index] = $row['prices'][$k][$index][0];
                }
                // Los precios se normalizan, cambiando los valores al tipo "float" de php y eliminando carácteres no deseados
                // TODO: El cambio se puede realizar aquí, o bien a la hora de mostrar los valores para el cálculo del total
                if ($index == 'price') {
                    $row['prices'][$k][$index] = toFloat($row['prices'][$k][$index]);
                }
                if ($index == 'delivery-fare') {
                    if ($row['prices'][$k][$index] == "") {
                        $row['prices'][$k][$index] = 0.0;
                    }
                }
            }
            $k++;
        }
        // Se normalizan el resto de valores de la fila
        foreach ($row as $key1 => $value) {
            if (!($key1 == 'prices')) {
                if (!is_string($row[$key1])) {
                    if (empty($row[$key1])) {
                        $row[$key1] = "";
                    } else {
                        $row[$key1] = $row[$key1][0];
                    }
                }
            }
        }
    }
    // Ahora se eliminan las filas con 'pn's no válidos
    $json_array1 = array();
    foreach($json_array as $row){
        // Eliminar filas con "pn"s no válidos
        if (!(strlen($row['pn']) < 9)) {
            array_push($json_array1, $row);
        }
    }
    // Los datos ya parseados se sobreescriben sobre el fichero original
    // TODO: optar por almacenar los datos en otro archivo para evitar posibles corrupciones de fichero
    $fp = fopen($json_string, 'w');
    fwrite($fp, json_encode($json_array1));
    fclose($fp);
}

/**
 * Esta función toma la última coma o punto (de haber alguno) para hacer un float limpio, ignorando el separador de
 * millares, moneda o cualquier otra letra
 * @param $num string del valor a tratar
 * @return float número tratado a float
 */
// TODO: parece que funciona siempre
function toFloat($num)
{
    $dotPos = strrpos($num, '.');
    $commaPos = strrpos($num, ',');
    $apostrophePos = strrpos($num, '\'');
    $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
        ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);
    $sep2 = ((($apostrophePos > $dotPos) && $apostrophePos) ? $apostrophePos : false);

    if (!$sep && !$sep2) {
        return floatval(preg_replace("/[^0-9]/", "", $num));
    }

    if ($sep2) {
        return floatval(
            preg_replace("/[^0-9]/", "", substr($num, 0, $sep2)) . '.' .
            preg_replace("/[^0-9]/", "", substr($num, $sep2 + 1, strlen($num)))
        );
    }

    if ($sep) {
        return floatval(
            preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
            preg_replace("/[^0-9]/", "", substr($num, $sep + 1, strlen($num)))
        );
    }
}


?>