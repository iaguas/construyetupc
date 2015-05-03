<?php
require_once 'DBHelper.php';

$db = new DBHelper();

$postData = file_get_contents('php://input');
$request = json_decode($postData);
@$username = $request->username;
@$password = $request->password;

//password: patatasparatodos

if($db->mCheckAdminUserCredentials($username, $password)) {
    session_start();
    $db->mInsertAdminSession(session_id());

    echo 'loginOk';
}
else {
    echo 'loginErr';
}
