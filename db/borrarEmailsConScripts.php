<?php
require_once '../models/model.php';

header('Content-Type: text/html; charset=utf-8');

// El regex elimina todos, no funciona??
$regex = new MongoRegex('/script/i');
echo 'regex<br>';
$query = array('email' => $regex);
echo 'query<br>';
$emails = $db->mGetEmailsByQuery($query);
echo 'emails<br>';
$db->mRemoveDocsInCollection($emails, 'emails_landing');
echo 'remove<br>';
