<?php
require_once '../models/model.php';

header('Content-Type: text/html; charset=utf-8');

$json = file_get_contents('usersAdmin.json');
$db->mInsertJson($json, 'users_admin');
$db->mCreateCollection('admin_sessions');

echo 'Usuarios añadidos' . \PHP_EOL;
