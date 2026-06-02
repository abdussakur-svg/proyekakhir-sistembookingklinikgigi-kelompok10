<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "klinik_gigi";

$conn = mysqli_connect($host,$user,$pass,$db);

if(!$conn){
    die("Koneksi gagal");
}
?>