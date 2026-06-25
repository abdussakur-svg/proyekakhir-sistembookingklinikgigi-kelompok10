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
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Booking Kunjungan - CareApps</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen text-slate-800 antialiased flex flex-col justify-between">

    <!-- Navbar Utama -->
    <nav class="bg-white border-b border-slate-200 py-4 px-6 sticky top-0 z-50 shadow-sm shadow-slate-100/40">
        <div class="container mx-auto flex justify-between items-center max-w-4xl">
            <div class="flex items-center space-x-2 text-blue-600 font-bold text-xl tracking-tight">
                <i class="fas fa-heartbeat text-2xl"></i>
                <span>CareApps</span>
            </div>
            <a href="dashboard.php" class="text-sm font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-2 transition">
                <i class="fas fa-arrow-left text-xs"></i> Kembali ke Dashboard
            </a>
        </div>
    </nav>

    <!-- Konten Utama -->
    <main class="container mx-auto px-4 py-10 max-w-4xl flex-grow">

        <!-- Header Halaman -->

        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Riwayat Janji Temu Anda</h2>
                <p class="text-slate-500 text-sm mt-0.5">Pantau status konfirmasi dan berkas jadwal pemeriksaan dokter aktif Anda.</p>
            </div>
        </div>

        <!-- Wadah Tabel Modern -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-5 px-6">Dokter Pemeriksa</th>
                            <th class="py-5 px-6">Tanggal Kunjungan</th>
                            <th class="py-5 px-6">Jam Sesi</th>
                            <th class="py-5 px-6">Keluhan</th>
                            <th class="py-5 px-6 text-center">Status Validasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">

                        <?php if (mysqli_num_rows($result) == 0) { ?>

                            <tr>
                                <td colspan="5" class="py-16 text-center text-slate-400 italic">

                                    <i class="far fa-calendar-times text-4xl block mb-3 text-slate-300"></i>

                                    Anda belum pernah melakukan reservasi dokter apa pun.

                                    <a href="booking.php"
                                        class="block text-blue-600 font-bold text-xs mt-2 not-italic hover:underline">

                                        Mulai Buat Janji Sekarang &rarr;

                                    </a>

                                </td>
                            </tr>

                        <?php } else { ?>

                            <?php while ($row = mysqli_fetch_assoc($result)) {

                                $status_raw = htmlspecialchars($row['status']);

                                switch (strtolower($status_raw)) {

                                    case 'disetujui':
                                        $badge_style = "bg-blue-50 text-blue-700 border-blue-200/60";
                                        $status_clean = "Disetujui";
                                        break;

                                    case 'ditolak':
                                        $badge_style = "bg-rose-50 text-rose-700 border-rose-100";
                                        $status_clean = "Ditolak";
                                        break;

                                    case 'selesai':
                                        $badge_style = "bg-emerald-50 text-emerald-700 border-emerald-200/60";
                                        $status_clean = "Selesai";
                                        break;

                                    default:
                                        $badge_style = "bg-amber-50 text-amber-700 border-amber-200/60 animate-pulse";
                                        $status_clean = empty($status_raw) ? 'Menunggu' : $status_raw;
                                        break;
                                }

                            ?>

                                <tr class="hover:bg-slate-50/60 transition duration-150">

                                    <!-- Dokter -->
                                    <td class="py-4 px-6">

                                        <div class="flex items-center gap-3">

                                            <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-sm border border-blue-100">
                                                <i class="fas fa-user-md"></i>
                                            </div>

                                            <div>

                                                <span class="block font-bold text-slate-900">
                                                    <?php echo htmlspecialchars($row['nama_dokter']); ?>
                                                </span>

                                                <span class="block text-[11px] text-slate-400 font-medium">
                                                    <?php echo isset($row['spesialis']) ? htmlspecialchars($row['spesialis']) : 'Spesialis Gigi'; ?>
                                                </span>

                                            </div>

                                        </div>

                                    </td>

                                    <!-- Tanggal -->
                                    <td class="py-4 px-6 text-slate-900 font-bold">

                                        <div class="flex items-center gap-2">

                                            <i class="far fa-calendar-alt text-slate-400 text-xs"></i>

                                            <span>
                                                <?php echo htmlspecialchars($row['tanggal']); ?>
                                            </span>

                                        </div>

                                    </td>

                                    <!-- Jam -->
                                    <td class="py-4 px-6 text-slate-600 font-medium">

                                        <div class="flex items-center gap-1.5 bg-slate-100 border border-slate-200 text-slate-700 px-2.5 py-1 rounded-xl text-xs w-fit">

                                            <i class="far fa-clock text-slate-400"></i>

                                            <span>
                                                <?php echo htmlspecialchars($row['jam']); ?> WIB
                                            </span>

                                        </div>

                                    </td>

                                    <!-- Keluhan -->
                                    <td class="py-4 px-6 text-slate-700">

                                        <?php
                                        if (isset($row['keluhan']) && !empty($row['keluhan'])) {
                                            echo htmlspecialchars($row['keluhan']);
                                        } else {
                                            echo "-";
                                        }
                                        ?>

                                    </td>

                                    <!-- Status -->
                                    <td class="py-4 px-6 text-center">

                                        <span class="inline-block px-3 py-1 text-xs font-bold uppercase tracking-wide border rounded-full <?php echo $badge_style; ?>">

                                            <?php echo $status_clean; ?>

                                        </span>

                                    </td>

                                </tr>

                            <?php } ?>

                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-6 text-center text-xs text-slate-400 border-t border-t-slate-200/60 bg-white">
        &copy; 2026 CareApps Medical Center. Hak Cipta Dilindungi.
    </footer>

</body>

</html>
