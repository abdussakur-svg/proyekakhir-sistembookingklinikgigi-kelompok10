<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Klinik | Layanan Kesehatan Terpercaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-pattern {
            background-color: #f3f4f6; 
            background-image: 
                radial-gradient(#d1d5db 1.25px, transparent 1.25px), 
                radial-gradient(#d1d5db 1.25px, #f3f4f6 1.25px);
            background-size: 50px 50px;
            background-position: 0 0, 25px 25px;
        }
    </style>
</head>
<body class="bg-pattern font-sans antialiased text-gray-800">

    <nav class="bg-white p-4 shadow-md sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center px-6">
            <h1 class="text-2xl font-extrabold text-blue-700 tracking-tight">Klinik<span class="text-gray-900">Ku</span></h1>
            
            <div class="flex items-center space-x-6 text-gray-700 font-medium">
                <a href="#tentang" class="hover:text-blue-600 transition">Tentang Kami</a>
                <a href="#layanan" class="hover:text-blue-600 transition">Layanan</a>
                
                <?php if (isset($_SESSION['login'])): ?>
                    <div class="relative group">
                        <a href="dashboard.php" class="flex items-center space-x-2 text-gray-900 group-hover:text-blue-600">
                            <span class="bg-blue-100 p-2 rounded-full text-blue-700 font-bold">U</span>
                            <span>Dashboard</span>
                        </a>
                        <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 group-hover:translate-y-1 transition-all duration-300 transform translate-y-0 invisible group-hover:visible z-10">
                            <a href="profil.php" class="block px-4 py-2 text-gray-800 hover:bg-blue-50">Profil Saya</a>
                            <a href="riwayat.php" class="block px-4 py-2 text-gray-800 hover:bg-blue-50">Riwayat Janji</a>
                            <div class="border-t border-gray-100"></div>
                            <a href="logout.php" class="block px-4 py-2 text-red-600 hover:bg-red-50">Keluar</a>
                        </div>
                    </div>
                <?php else: ?>

                <?php endif; ?>
            </div>
        </div>
    </nav>

    <header class="relative w-full h-[600px] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="klinik_gigi2.jpeg" alt="Latar Belakang Klinik" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/50"></div>
    </div>
    
    <div class="relative z-10 container mx-auto px-6 text-center text-white">
        <span class="text-white font-bold px-4 py-1.5 rounded-full text-sm inline-block mb-4 shadow-inner">
            Kesehatan Anda Adalah Prioritas Kami
        </span>
        
        <h2 class="text-5xl md:text-6xl font-extrabold tracking-tight leading-tight mb-8 text-white drop-shadow-md">
            Solusi Kesehatan Klinik Gigi Terpercaya Untuk Anda & Keluarga
        </h2>
        
        <p class="text-xl leading-relaxed max-w-2xl mx-auto mb-12 text-gray-100 drop-shadow">
            Dari pemeriksaan rutin hingga konsultasi dokter spesialis gigi, KlinikKu hadir untuk memberikan layanan medis berkualitas dengan kemudahan akses.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-5 justify-center items-center">
            <?php if (isset($_SESSION['login'])): ?>
                <a href="booking.php" class="bg-blue-600 text-white px-8 py-3.5 rounded-full text-lg font-semibold hover:bg-blue-700 transition flex items-center space-x-2">
                    <span>Buat Janji Temu</span>
                </a>
                <a href="riwayat.php" class="bg-white/10 border border-white/30 text-white px-8 py-3.5 rounded-full text-lg font-medium hover:bg-white/20 transition backdrop-blur-sm">
                    Lihat Riwayat
                </a>
            <?php else: ?>
                <a href="register.php" class="bg-blue-600 text-white px-8 py-3.5 rounded-full text-lg font-semibold hover:bg-blue-700 transition">
                    Daftar Sekarang
                </a>
                <a href="login.php" class="bg-white/10 border border-white/30 text-white px-8 py-3.5 rounded-full text-lg font-medium hover:bg-white/20 transition backdrop-blur-sm">
                    Sudah Punya Akun? Masuk
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>

    <section id="layanan" class="container mx-auto mt-24 mb-32 px-6">
        <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100 transform hover:-translate-y-2 transition duration-300">
                <div class="bg-blue-100 text-blue-700 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Pendaftaran Online Mudah</h3>
                <p class="text-gray-600">Pilih dokter dan jadwal yang Anda inginkan hanya dalam beberapa klik, tanpa perlu mengantre.</p>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100 transform hover:-translate-y-2 transition duration-300">
                <div class="bg-green-100 text-green-700 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Tim Dokter Berpengalaman</h3>
                <p class="text-gray-600">Didukung oleh dokter-dokter spesialis dan umum yang ahli dan berdedikasi di bidangnya.</p>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100 transform hover:-translate-y-2 transition duration-300">
                <div class="bg-yellow-100 text-yellow-700 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Rekam Medis Terintegrasi</h3>
                <p class="text-gray-600">Akses riwayat pemeriksaan dan rekam medis Anda secara digital kapan pun Anda butuhkan.</p>
            </div>
        </div>
    </section>

    <section id="tentang" class="bg-gray-50 py-24 px-6 border-t border-gray-100">
        <div class="container mx-auto max-w-4xl flex flex-col md:flex-row items-center gap-16">
            <div class="md:w-1/2">
                <div class="aspect-square bg-blue-100 rounded-3xl flex items-center justify-center p-12 shadow-inner">
                    <svg class="w-full h-full text-blue-500 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
            <div class="md:w-1/2">
                <h3 class="text-3xl font-extrabold text-gray-950 mb-6">Mitra Kesehatan Anda</h3>
                <p class="text-gray-700 leading-relaxed mb-6">KlinikKu berdiri dengan visi untuk memudahkan setiap individu mengakses layanan kesehatan berkualitas tanpa hambatan birokrasi. Kami percaya kesehatan adalah fondasi kebahagiaan.</p>
                <p class="text-gray-700 leading-relaxed">Komitmen kami didukung oleh fasilitas modern dan tenaga medis profesional yang siap memberikan perawatan terbaik untuk Anda dan orang-orang terdekat.</p>
            </div>
        </div>
    </section>

    <footer class="bg-white p-8 text-center text-gray-600 border-t border-gray-100">
        <p class="mb-2 font-semibold text-blue-700">KlinikKu - Solusi Kesehatan Anda</p>
        <p class="text-sm">&copy; <?php echo date("Y"); ?> Sistem Informasi Klinik. Semua hak dilindungi.</p>
    </footer>

</body>
</html>