<?php

error_reporting(E_ALL);

require_once '../models/model.php';

header('Content-Type: text/html; charset=utf-8');

// Mejor no descomentar

//$db->mRemoveAllInCollection('hardware_categories');

$jsonFile = file_get_contents('cpus.json');
$db->mInsertJson($jsonFile, 'cpus');

echo "Esto no mete una mierda";