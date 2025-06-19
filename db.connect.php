<?php

$host = "localhost";
$root = "root";
$pass = "";
$dbname = "techtala_blog";

$conn = new mysqli( $host, $root,  $pass, $dbname);

if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}
?>