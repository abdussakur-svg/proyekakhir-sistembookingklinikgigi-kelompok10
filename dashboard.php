<?php
session_start();

if(!isset($_SESSION['id_users'])) {
    header("Location: login.php");
    exit;
}

// Menentukan warna badge berdasarkan role untuk estetika
$role = $_SESSION['role'];
$badge_color = ($role == 'admin') ? 'bg-amber-500/10 text-amber-600 border-amber-500/20' : 'bg-blue-500/10 text-blue-600 border-blue-500/20';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CareApps</title>
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

    <!-- Navbar Utama -->
    <nav class="bg-white border-b border-slate-200/80 py-4 px-6 sticky top-0 z-50 shadow-sm shadow-slate-100/50">
        <div class="container mx-auto flex justify-between items-center max-w-4xl">
            <div class="flex items-center space-x-2 text-blue-600 font-bold text-xl tracking-tight">
                <i class="fas fa-heartbeat text-2xl"></i>
                <span>CareApps</span>
            </div>
            <a href="logout.php" class="bg-rose-50 text-rose-600 border border-rose-200/60 px-4 py-2 rounded-xl font-bold text-xs hover:bg-rose-600 hover:text-white transition-all duration-200 flex items-center gap-2 shadow-sm shadow-rose-100">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>

    <!-- Konten Utama -->
    <main class="container mx-auto px-4 py-10 max-w-4xl flex-grow flex flex-col justify-center">
        
        <!-- Banner Selamat Datang -->
        <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 rounded-[2rem] p-8 md:p-10 mb-8 text-white shadow-xl shadow-blue-900/10 relative overflow-hidden">
            <div class="relative z-10">
                <span class="bg-white/10 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-[0.15em] px-3 py-1.5 rounded-lg border border-white/10 mb-4 inline-block">
                    Akses Berhasil
                </span>
                <h2 class="text-2xl md:text-4xl font-extrabold tracking-tight mb-2">
                    Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama']); ?>!
                </h2>
                <p class="text-blue-100 text-sm max-w-md font-light leading-relaxed">
                    Silakan gunakan menu di bawah ini untuk mengakses fitur sistem sesuai hak akses Anda.
                </p>
            </div>
            <!-- Dekorasi Estetik -->
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute right-1/3 top-0 w-24 h-24 bg-indigo-500/20 rounded-full blur-xl pointer-events-none"></div>
        </div>

        <!-- Section Menu Utama -->
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                <i class="fas fa-th-large text-blue-500"></i> Menu Navigasi Utama
            </h3>
            <!-- Badge Hak Akses / Role -->
            <span class="border text-[11px] font-extrabold uppercase tracking-wider px-3 py-1 rounded-full <?php echo $badge_color; ?>">
                <i class="fas fa-shield-alt mr-1"></i> <?php echo htmlspecialchars($_SESSION['role']); ?>
            </span>
        </div>

        <!-- Grid Menu Dinamis Berdasarkan Role -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            <?php if($_SESSION['role'] == 'admin') { ?>
                
                <!-- MENU ADMIN 1: KELOLA DOKTER -->
                <a href="dokter.php" class="group bg-white border border-slate-200/60 p-6 rounded-2xl shadow-sm hover:shadow-md hover:border-blue-500/30 transition-all duration-300 flex items-start gap-4">
                    <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center border border-amber-500/20 text-amber-500 text-lg shrink-0 group-hover:bg-amber-500 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 group-hover:text-blue-600 transition text-base">Kelola Dokter</h4>
                        <p class="text-slate-400 text-xs mt-1 leading-relaxed">Tambah, edit, atau hapus data dokter beserta jadwal praktiknya.</p>
                    </div>
                </a>

                <!-- MENU ADMIN 2: KELOLA BOOKING -->
                <a href="kelola-booking.php" class="group bg-white border border-slate-200/60 p-6 rounded-2xl shadow-sm hover:shadow-md hover:border-blue-500/30 transition-all duration-300 flex items-start gap-4">
                    <div class="w-12 h-12 bg-indigo-500/10 rounded-xl flex items-center justify-center border border-indigo-500/20 text-indigo-500 text-lg shrink-0 group-hover:bg-indigo-500 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 group-hover:text-blue-600 transition text-base">Kelola Booking</h4>
                        <p class="text-slate-400 text-xs mt-1 leading-relaxed">Pantau dan kelola seluruh transaksi janji temu dari pasien.</p>
                    </div>
                </a>

            <?php } else { ?>
                
                <!-- MENU PASIEN 1: BOOKING DOKTER -->
                <a href="booking.php" class="group bg-white border border-slate-200/60 p-6 rounded-2xl shadow-sm hover:shadow-md hover:border-blue-500/30 transition-all duration-300 flex items-start gap-4">
                    <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center border border-blue-500/20 text-blue-500 text-lg shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-notes-medical"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 group-hover:text-blue-600 transition text-base">Booking Dokter</h4>
                        <p class="text-slate-400 text-xs mt-1 leading-relaxed">Buat janji temu online baru dengan dokter pilihan Anda.</p>
                    </div>
                </a>

                <!-- MENU PASIEN 2: RIWAYAT BOOKING -->
                <a href="riwayat.php" class="group bg-white border border-slate-200/60 p-6 rounded-2xl shadow-sm hover:shadow-md hover:border-blue-500/30 transition-all duration-300 flex items-start gap-4">
                    <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center border border-emerald-500/20 text-emerald-500 text-lg shrink-0 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-history"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 group-hover:text-blue-600 transition text-base">Riwayat Booking</h4>
                        <p class="text-slate-400 text-xs mt-1 leading-relaxed">Lihat status kunjungan dan daftar booking yang pernah Anda buat.</p>
                    </div>
                </a>

            <?php } ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-6 text-center text-xs text-slate-400 border-t border-slate-100 bg-white">
        &copy; 2026 CareApps Medical Center. Hak Cipta Dilindungi.
    </footer>

</body>
</html>