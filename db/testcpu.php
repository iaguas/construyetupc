<?php
require_once '../models/model.php';

header('Content-Type: text/html; charset=utf-8');

// Mejor no descomentar

//$db->mRemoveAllInCollection('hardware_categories');

$json = file_get_contents('../db/cpus.json');
$db->mInsertJson($json, 'cpus');

// Listar categorías
$res = $db->mGetHardwareCategories();

foreach($res as $doc) {
    echo $doc['_id'] . '<br>';
    echo $doc['name'] . '<br>';
    echo $doc['spanishName'] . '<br>';
    echo '<br>';
}
