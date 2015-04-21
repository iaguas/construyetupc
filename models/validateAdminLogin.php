<?php
//require 'model.php';

$postData = file_get_contents('php://input');
$request = json_decode($postData);
@$username = $request->username;
@$password = $request->password;

if($username == 'aaa' && $password == 'aaa') {
    echo 'loginOk';
}
else {
    echo 'loginErr';
}

// Falta consultar user y pass en la base de datos!!
