<?php
require_once '../models/model.php';

header('Content-Type: text/html; charset=utf-8');

$json = file_get_contents('providers.json');
$db->mInsertJson($json, 'providers');
$db->mCreateCollection('providers');

echo 'Proveedores añadidos' . \PHP_EOL;
