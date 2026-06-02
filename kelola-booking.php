<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Akses ditolak");
}

// --- PROSES UPDATE STATUS & HAPUS (KODE BACKEND TETAP AMAN) ---

if(isset($_GET['setuju'])) {
    $id = $_GET['setuju'];
    $stmt = mysqli_prepare($conn, "UPDATE booking SET status='Disetujui' WHERE id_booking=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header("Location: kelola-booking.php");
    exit;
}

if(isset($_GET['tolak'])) {
    $id = $_GET['tolak'];
    $stmt = mysqli_prepare($conn, "UPDATE booking SET status='Ditolak' WHERE id_booking=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header("Location: kelola-booking.php");
    exit;
}

if(isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = mysqli_prepare($conn, "DELETE FROM booking WHERE id_booking=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header("Location: kelola-booking.php");
    exit;
}

if(isset($_GET['selesai'])) {
    $id = $_GET['selesai'];
    $stmt = mysqli_prepare($conn, "UPDATE booking SET status='Selesai' WHERE id_booking=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header("Location: kelola-booking.php");
    exit;
}

/* AMBIL DATA BOOKING */
$query = mysqli_query($conn,
"SELECT booking.*, users.nama, dokter.nama_dokter
FROM booking
JOIN users ON booking.id_users = users.id_users
JOIN dokter ON booking.id_dokter = dokter.id_dokter
ORDER BY booking.id_booking DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Booking - Panel Admin</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen text-slate-800 antialiased flex flex-col justify-between">

    <!-- Navbar Admin -->
    <nav class="bg-white border-b border-slate-200 py-4 px-6 sticky top-0 z-50 shadow-sm shadow-slate-100/40">
        <div class="container mx-auto flex justify-between items-center max-w-6xl">
            <div class="flex items-center space-x-2 text-blue-600 font-bold text-xl tracking-tight">
                <i class="fas fa-user-shield text-2xl"></i>
                <span>CareApps <span class="text-xs font-semibold bg-amber-100 text-amber-800 px-2 py-0.5 rounded ml-1">Admin Panel</span></span>
            </div>
            <a href="dashboard.php" class="text-sm font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-2 transition">
                <i class="fas fa-arrow-left text-xs"></i> Kembali ke Dashboard
            </a>
        </div>
    </nav>

    <!-- Konten Utama -->
    <main class="container mx-auto px-4 py-10 max-w-6xl flex-grow">
        
        <!-- Header Halaman -->
        <div class="mb-8">
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Kelola Booking Pasien</h2>
            <p class="text-slate-500 text-sm mt-0.5">Konfirmasi, selesaikan, atau batalkan permintaan janji temu medis pasien di sini.</p>
        </div>

        <!-- Wadah Tabel Modern -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-5 px-6">Nama Pasien</th>
                            <th class="py-5 px-6">Dokter Pilihan</th>
                            <th class="py-5 px-6">Keluhan</th>
                            <th class="py-5 px-6">Jadwal Pertemuan</th>
                            <th class="py-5 px-6 text-center">Status</th>
                            <th class="py-5 px-6 text-center">Aksi Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                        <?php if(mysqli_num_rows($query) == 0) { ?>
                            <tr>
                                <td colspan="6" class="py-16 text-center text-slate-400 italic">
                                    <i class="fas fa-calendar-times text-4xl block mb-3 text-slate-300"></i>
                                    Belum ada data transaksi booking masuk.
                                </td>
                            </tr>
                        <?php } else { ?>
                            <?php while($row = mysqli_fetch_assoc($query)) { 
                                // Penentuan warna badge berdasarkan nilai kolom status
                                $status_text = htmlspecialchars($row['status']);
                                switch(strtolower($status_text)) {
                                    case 'disetujui':
                                        $badge_class = "bg-blue-50 text-blue-700 border-blue-200/60";
                                        break;
                                    case 'ditolak':
                                        $badge_class = "bg-rose-50 text-rose-700 border-rose-100";
                                        break;
                                    case 'selesai':
                                        $badge_class = "bg-emerald-50 text-emerald-700 border-emerald-200/60";
                                        break;
                                    default: // Jika status bernilai 'Menunggu' atau kosong
                                        $badge_class = "bg-amber-50 text-amber-700 border-amber-200/60 animate-pulse";
                                        $status_text = empty($status_text) ? 'Menunggu' : $status_text;
                                        break;
                                }
                            ?>
                            <tr class="hover:bg-slate-50/60 transition duration-150">
                                
                                <!-- Kolom Pasien -->
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 bg-slate-100 text-slate-600 rounded-full flex items-center justify-center font-bold text-xs">
                                            <?php echo strtoupper(substr($row['nama'], 0, 1)); ?>
                                        </div>
                                        <span><?php echo htmlspecialchars($row['nama']); ?></span>
                                    </div>
                                </td>
                                
                                <!-- Kolom Dokter -->
                                <td class="py-4 px-6 text-slate-600">
                                    <div class="flex items-center gap-2 text-slate-900 font-semibold">
                                        <i class="fas fa-user-md text-blue-500 text-xs"></i>
                                        <span><?php echo htmlspecialchars($row['nama_dokter']); ?></span>
                                    </div>
                                </td>
                                <!-- Kolom Keluhan -->
                                <td class="py-4 px-6 text-slate-600">
                                    <div class="max-w-xs">
                                        <span class="text-xs leading-relaxed">
                                            <?php echo !empty($row['keluhan']) 
                                                ? htmlspecialchars($row['keluhan']) 
                                                : '-'; ?>
                                        </span>
                                    </div>
                                </td>
                                <!-- Kolom Jadwal -->
                                <td class="py-4 px-6 text-slate-500">
                                    <div class="text-xs space-y-1">
                                        <span class="block text-slate-800 font-bold"><i class="far fa-calendar-alt mr-1 text-slate-400"></i> <?php echo htmlspecialchars($row['tanggal']); ?></span>
                                        <span class="block font-medium"><i class="far fa-clock mr-1 text-slate-400"></i> <?php echo htmlspecialchars($row['jam']); ?> WIB</span>
                                    </div>
                                </td>
                                
                                <!-- Kolom Status (Badge) -->
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-block px-3 py-1 text-xs font-bold uppercase tracking-wide border rounded-full <?php echo $badge_class; ?>">
                                        <?php echo $status_text; ?>
                                    </span>
                                </td>
                                
                                <!-- Kolom Aksi Dinamis -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-1.5">
                                        
                                        <!-- Tombol Setuju -->
                                        <a href="?setuju=<?php echo $row['id_booking']; ?>" title="Setujui Reservasi"
                                           class="p-2 bg-slate-100 hover:bg-blue-600 text-slate-600 hover:text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                                            <i class="fas fa-check px-0.5"></i>
                                        </a>
                                        
                                        <!-- Tombol Tolak -->
                                        <a href="?tolak=<?php echo $row['id_booking']; ?>" title="Tolak Reservasi"
                                           class="p-2 bg-slate-100 hover:bg-amber-600 text-slate-600 hover:text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                                            <i class="fas fa-ban px-0.5"></i>
                                        </a>
                                        
                                        <!-- Tombol Selesai -->
                                        <a href="?selesai=<?php echo $row['id_booking']; ?>" title="Sesi Selesai Medis"
                                           class="p-2 bg-slate-100 hover:bg-emerald-600 text-slate-600 hover:text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                                            <i class="fas fa-flag-checkered px-0.5"></i>
                                        </a>

                                        <div class="h-4 w-[1px] bg-slate-200 mx-1"></div>
                                        
                                        <!-- Tombol Hapus -->
                                        <a href="?hapus=<?php echo $row['id_booking']; ?>" title="Hapus Permanen"
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus data booking milik <?php echo htmlspecialchars($row['nama']); ?> ini?')" 
                                           class="p-2 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                                            <i class="fas fa-trash-alt px-0.5"></i>
                                        </a>
                                    </div>
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
    <footer class="py-6 text-center text-xs text-slate-400 border-t border-slate-200/60 bg-white">
        &copy; 2026 CareApps Medical Center. Hak Cipta Dilindungi.
    </footer>

</body>
</html>