<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_users'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['id_users'];

$stmt = mysqli_prepare(
    $conn,
    "SELECT booking.*, dokter.nama_dokter, dokter.spesialis
     FROM booking
     JOIN dokter ON booking.id_dokter = dokter.id_dokter
     WHERE booking.id_users=?
     ORDER BY booking.id_booking DESC"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cetak Riwayat</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 40px; color: #333; }
        
        /* Header Klinik - Warna disesuaikan dengan screenshot */
        .header-klinik { text-align: center; margin-bottom: 30px; background-color: #f0f0f0; padding: 20px; border-radius: 10px; border-bottom: 3px solid #005a8d; }
        .logo-klinik { width: 100px; height: auto; margin-bottom: 10px; }
        .header-klinik h1 { margin: 5px 0; color: #005a8d; }
        
        /* Tabel */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #005a8d; color: white; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #ddd; }
        
        /* Badge */
        .status-badge { padding: 4px 10px; border-radius: 15px; font-weight: bold; font-size: 12px; background-color: #e3f2fd; color: #005a8d; border: 1px solid #005a8d; }

        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>

<body onload="window.print()">

<div class="header-klinik">
    <!-- Ganti emoji dengan gambar logo -->
    <img src="logo_gigi.jpeg" alt="Logo KlinikKu" class="logo-klinik">
    <h1>KLINIKKU</h1>
    <p>Solusi Kesehatan Gigi Keluarga Anda</p>
</div>

<table>
    <tr>
        <th>Dokter</th>
        <th>Spesialis</th>
        <th>Tanggal</th>
        <th>Jam</th>
        <th>Keluhan</th>
        <th>Status</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= htmlspecialchars($row['nama_dokter']); ?></td>
        <td><?= htmlspecialchars($row['spesialis']); ?></td>
        <td><?= htmlspecialchars($row['tanggal']); ?></td>
        <td><?= htmlspecialchars($row['jam']); ?></td>
        <td><?= htmlspecialchars($row['keluhan']); ?></td>
        <td><span class="status-badge"><?= htmlspecialchars($row['status']); ?></span></td>
    </tr>
    <?php } ?>
</table>

</body>
</html>