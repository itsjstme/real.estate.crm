<?php

require_once(__DIR__ . '/vendor/autoload.php');
require_once(__DIR__ . '/functions.php');


CRMContactExport();

$dsn = "mysql:host=localhost;dbname=" . $_ENV["DB_NAME"] ;
$user = $_ENV["DB_USER"];
$passwd = $_ENV["DB_PASSWORD"];

$pdo = new PDO($dsn, $user, $passwd);

//Get data from existing json file
if(file_exists("data.json")){

    $jsondata  = file_get_contents('data.json');
    $arr_data  = json_decode($jsondata, true);
    $insert    = array();
    foreach($arr_data as $index_key => $contact){
        $insert[] = "(NULL, '" . $contact['ContactId'] ."', '' , '0', CURRENT_TIMESTAMP)";
    }
    $values = implode("," , $insert );
    $sql = "INSERT IGNORE INTO leads (`id`, `contactid`, `data`, `status`, `created`) VALUES  {$values}";
    $pdo->exec($sql);
}