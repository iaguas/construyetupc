<?php
require_once 'DBHelper.php';

$db = new DBHelper();

$postData = file_get_contents('php://input');
$request = json_decode($postData);
@$session = $request->session;

session_start();

if($db->mRemoveAdminSession(session_id())) {
    echo 'logoutOk';
}
else {
    echo 'logoutErr';
}
