<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Akses ditolak");
}

if(isset($_GET['id']) && !empty($_GET['id'])) {

    $id = $_GET['id'];

    // 1. Ambil data dulu (untuk file)
    $stmt = mysqli_prepare($conn, "SELECT foto_dokter, sertifikat FROM dokter WHERE id_dokter=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    if($data) {

        // 2. Hapus file foto jika ada
        if(!empty($data['foto_dokter'])) {
            $path_foto = "gambar-dokter/" . $data['foto_dokter'];
            if(file_exists($path_foto)) {
                unlink($path_foto);
            }
        }

        // 3. Hapus file sertifikat jika ada
        if(!empty($data['sertifikat'])) {
            $path_sertifikat = "sertifikat-dokter/" . $data['sertifikat'];
            if(file_exists($path_sertifikat)) {
                unlink($path_sertifikat);
            }
        }

        // 4. Hapus dari database
        $delete = mysqli_prepare($conn, "DELETE FROM dokter WHERE id_dokter=?");
        mysqli_stmt_bind_param($delete, "i", $id);
        mysqli_stmt_execute($delete);

        header("Location: dokter.php");
        exit;
    }

    echo "Data dokter tidak ditemukan.";

} else {
    echo "ID tidak valid.";
}
?>