<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Akses ditolak");
}

$where = "";
$periode = "Semua Data Booking";

$where = "";
$periode = "Semua Data Booking";
$error = "";

if (
    isset($_GET['filter']) &&
    !empty($_GET['tanggal_awal']) &&
    !empty($_GET['tanggal_akhir'])
) {

    $tanggal_awal = $_GET['tanggal_awal'];
    $tanggal_akhir = $_GET['tanggal_akhir'];

    if ($tanggal_awal > $tanggal_akhir) {

        $error = "Tanggal awal tidak boleh lebih besar dari tanggal akhir.";
    } else {

        $where = "
            WHERE booking.tanggal
            BETWEEN '$tanggal_awal'
            AND '$tanggal_akhir'
        ";

        $periode = "Periode $tanggal_awal s/d $tanggal_akhir";
    }
} {

    $tanggal_awal = $_GET['tanggal_awal'];
    $tanggal_akhir = $_GET['tanggal_akhir'];

    $where = "
        WHERE booking.tanggal
        BETWEEN '$tanggal_awal'
        AND '$tanggal_akhir'
    ";

    $periode = "Periode $tanggal_awal s/d $tanggal_akhir";
}

$query = mysqli_query(
    $conn,
    "SELECT
        booking.*,
        users.nama,
        dokter.nama_dokter,
        dokter.spesialis
    FROM booking
    JOIN users
        ON booking.id_users = users.id_users
    JOIN dokter
        ON booking.id_dokter = dokter.id_dokter
    $where
    ORDER BY booking.tanggal DESC"
);

$total_booking = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Booking Pasien</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .print-area {
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen">

    <div class="max-w-7xl mx-auto p-6">

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 mb-6 no-print">
            <div class="flex justify-between items-center mb-5">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Filter Data Booking Pasien</h1>
                </div>
                <a href="kelola-booking.php" class="text-sm font-semibold text-slate-600 hover:text-blue-600 flex items-center gap-2 transition">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <form method="GET">

                <?php if (!empty($error)) { ?>
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                        <i class="fas fa-circle-exclamation mr-2"></i>
                        <?= $error ?>
                    </div>
                <?php } ?>

                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Dari Tanggal</label>
                        <input type="date" name="tanggal_awal" value="<?= $_GET['tanggal_awal'] ?? '' ?>" class="w-full border border-slate-300 rounded-xl px-4 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Sampai Tanggal</label>
                        <input type="date" name="tanggal_akhir" value="<?= $_GET['tanggal_akhir'] ?? '' ?>" class="w-full border border-slate-300 rounded-xl px-4 py-2" required>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" name="filter" value="1" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl font-bold">
                            <i class="fas fa-search"></i> Tampilkan
                        </button>
                        <button type="button" onclick="window.print()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-xl font-bold">
                            <i class="fas fa-print"></i> Cetak PDF
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 print-area">
            <div class="text-center mb-8">
                <img src="logo_gigi.jpeg" alt="Logo Klinik" class="mx-auto w-24 mb-3">
                <h1 class="text-3xl font-black text-slate-800">KLINIKKU</h1>
                <p class="text-slate-500">Solusi Kesehatan Gigi Keluarga Anda</p>
                <hr class="my-4">
                <h2 class="text-xl font-bold text-blue-900">LAPORAN DATA BOOKING PASIEN</h2>
                <p class="text-sm text-slate-500 mt-1"><?= $periode ?></p>
            </div>

            <div class="mb-4 flex justify-between font-medium">
                <div>Total Booking : <span class="text-blue-600 font-bold"><?= $total_booking ?> Data</span></div>
                <div>Dicetak : <?= date('d-m-Y') ?></div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-blue-600 text-white">
                            <th class="p-4 rounded-tl-lg">No</th>
                            <th class="p-4">Pasien</th>
                            <th class="p-4">Dokter</th>
                            <th class="p-4">Spesialis</th>
                            <th class="p-4">Tanggal</th>
                            <th class="p-4">Jam</th>
                            <th class="p-4">Keluhan</th>
                            <th class="p-4 rounded-tr-lg">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <?php
                        $no = 1;
                        if ($total_booking == 0) {
                            echo "<tr><td colspan='8' class='p-8 text-center text-slate-500'>Tidak ada data booking.</td></tr>";
                        } else {
                            while ($row = mysqli_fetch_assoc($query)) {
                                // Menentukan warna status
                                $status_badge = ($row['status'] == 'Selesai') ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700';
                        ?>
                                <tr class="hover:bg-blue-50 transition-colors">
                                    <td class="p-4 text-center font-bold"><?= $no++; ?></td>
                                    <td class="p-4 text-slate-700"><?= htmlspecialchars($row['nama']); ?></td>
                                    <td class="p-4 text-slate-700"><?= htmlspecialchars($row['nama_dokter']); ?></td>
                                    <td class="p-4 text-slate-700"><?= htmlspecialchars($row['spesialis']); ?></td>
                                    <td class="p-4 text-slate-700"><?= htmlspecialchars($row['tanggal']); ?></td>
                                    <td class="p-4 text-slate-700"><?= htmlspecialchars($row['jam']); ?></td>
                                    <td class="p-4 text-slate-700"><?= htmlspecialchars($row['keluhan']); ?></td>
                                    <td class="p-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold <?= $status_badge ?>">
                                            <?= htmlspecialchars($row['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-16 text-right">
                <p>             , <?= date('d F Y'); ?></p>
                <br><br><br>
                <strong class="text-blue-900 border-b-2 border-blue-600">Administrator Klinik</strong>
            </div>
        </div>
    </div>
</body>

</html>