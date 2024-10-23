<?php
$env=parse_ini_file(".env");

$servername = $env['DB_SERVERNAME'];
$username = $env["DB_USERNAME"];
$password = $env["DB_PASSWORD"];

try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "USE scGRNdb; ";
    $conn -> exec($sql);
} catch(PDOException $e) {
    echo $e->getMessage();
    $conn = NULL;
}

// if ($conn){
//     echo "connection stable\n";
// }
return $conn;
// echo $conn;
