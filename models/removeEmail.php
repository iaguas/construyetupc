<?php
require 'model.php';

$postData = file_get_contents('php://input');
$request = json_decode($postData);

$deleted = $db->mRemoveDocsInCollection(array('_id' => new MongoId($request->emailid)), 'emails_landing');

if($deleted) {
    echo 'deleteOk';
}
else {
    echo 'deleteErr';
}
