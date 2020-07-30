<?php 

ob_start(); // Turns on output bufferring
session_start();

date_default_timezone_set("America/Mexico_City");

try{

    $con = new PDO("mysql:dbname=memeflix;host=localhost", "root", "");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

} 
catch (PDOException $e){

    exit("Connection Failed: " . $e->getMessage());

}

?>