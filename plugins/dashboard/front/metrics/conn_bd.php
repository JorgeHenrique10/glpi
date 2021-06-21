<?php
header('Content-Type: application/json');

define('DB_HOST', 'localhost');
define('DB_USERNAME', 'glpi');
define('DB_PASSWORD', 'ints@Glpi2020');
define('DB_NAME', 'glpi');

$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if(!$mysqli){
    die("Conexão Falho: " . $mysqli->error);
}
?>
