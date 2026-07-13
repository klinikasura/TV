<?php

$host="10.10.20.250";
$user="root";
$pass="";
$db="sikdraisyah";

$koneksi=mysqli_connect(
    $host,
    $user,
    $pass,
    $db
);

if(!$koneksi){
    die(mysqli_connect_error());
}

?>
