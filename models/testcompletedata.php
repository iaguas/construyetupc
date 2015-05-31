<?php
require 'DBHelper.php';

$db = new DBHelper();

$json_string = '../crawler/data/cpus.json';
$json = file_get_contents($json_string);

$db->mCompleteData("cpus",$json);
