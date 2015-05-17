<?php
/**
 * Created by PhpStorm.
 * User: Iñaki
 * Date: 08/05/2015
 * Time: 15:54
 */
// TODO: cambiar esta función de lugar?
// TODO: repetir esto por cada fichero JSON
/*
 * Funciones que hace éste parser
 * - Sustituir caracteres extraños por espacio vacio
 * - Eliminar espacios en claves para identificar productos
 * - Eliminar claves ("pn") que no cumplen con el mínimo de 8 carácteres
 * - Mapear correctamente (en arrays) los precios por tiendas
 * - Eliminar euro/dolar de los precios
 * - Mapear correctamente el array general
 */
/**
 * Este fichero PHP parsea los ficheros JSON antes de ser incorporados a la BD eliminando espacios en blanco no deseados
 */
$json_string = '../crawler/data/cpus-original-with-imgs.json';
$json = file_get_contents($json_string);
// Devolver array, no un objeto
$json_array = json_decode($json, true);

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
    /*echo"[";
    echo $sep;
    echo ", ";
    echo $sep2;
    echo "] ";*/
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

// NOTA: necesario pasar la variable por referencia para modificar la variable recorrida
foreach($json_array as &$row) {
    // Eliminar "pn"s no válidos
    $row['pn'][0] = trim($row['pn'][0]);
    if (strlen($row['pn'][0]) < 8) {
        $row['pn'][0] = "";
    }
    // Arrays auxiliares para el array de arrays de prices
    $prices = [];
    $shop_keys = ['price', 'delivery-fare', 'provider'];
    $shop_vals = [];
    $j = 0;
    // Recorremos los valores almacenados y los tratamos (teniendo en cuenta los índices repetidos
    foreach ($row['prices'] as $row_in_prices) {
        array_push($shop_vals, $row_in_prices);
        $j++;
        if ($j == 3) {
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
                if($row['prices'][$k][$index] == ""){
                    $row['prices'][$k][$index] = 0.0;
                }
            }
        }
        $k++;
    }
    // Se normalizan el resto de valores de la fila
    // frecuency, family, socket, cores, pn, name
    if (!is_string($row['frecuency'])) {
        if (empty($row['frecuency'])) {
            $row['frecuency'] = "";
        } else {
            $row['frecuency'] = $row['frecuency'][0];
        }
    }
    if (!is_string($row['family'])) {
        if (empty($row['family'])) {
            $row['family'] = "";
        } else {
            $row['family'] = $row['family'][0];
        }
    }
    if (!is_string($row['socket'])) {
        if (empty($row['socket'])) {
            $row['socket'] = "";
        } else {
            $row['socket'] = $row['socket'][0];
        }
    }
    if (!is_string($row['cores'])) {
        if (empty($row['cores'])) {
            $row['cores'] = "";
        } else {
            $row['cores'] = $row['cores'][0];
        }
    }
    if (!is_string($row['pn'])) {
        if (empty($row['pn'])) {
            $row['pn'] = "";
        } else {
            $row['pn'] = $row['pn'][0];
        }
    }
    if (!is_string($row['name'])) {
        if (empty($row['name'])) {
            $row['name'] = "";
        } else {
            $row['name'] = $row['name'][0];
        }
    }
    // Comprobación de la existencia de la imagen
    if (isset($row['img'])){
        if (!is_string($row['img'])) {
            if (empty($row['img'])) {
                $row['img'] = "";
            } else {
                $row['img'] = $row['img'][0];
            }
        }
    }
}

// Los datos ya parseados se sobreescriben sobre el fichero original
// TODO: optar por almacenar los datos en otro archivo para evitar posibles corrupciones de fichero
$fp = fopen('../crawler/data/cpus-original-with-imgs1.json', 'w');
fwrite($fp, json_encode($json_array));
fclose($fp);


?>