<?php
$host = "sql200.infinityfree.com";
$user = "if0_42214405";
$pass = "BafC1yfeaE9";
$db = "if0_42214405_klinik_gigi";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal");
}
