<?php

$host         = "localhost";
$username     = "root";
$password     = "";
$dbname       = "bdtest ";

try {
    $dbconn = new PDO('mysql:host=localhost;dbname=bdtest', $username, $password);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
