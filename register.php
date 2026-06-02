<?php
session_start();
include 'koneksi.php';

if(isset($_SESSION['id_users'])) {
    header("Location: dashboard.php");
    exit;
}

$sukses_register = false;
$error_message = ""; 

if(isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt_cek = mysqli_prepare($conn, "SELECT id_users FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt_cek, "s", $email);
    mysqli_stmt_execute($stmt_cek);
    $result = mysqli_stmt_get_result($stmt_cek);

    if(mysqli_num_rows($result) > 0) {
        $error_message = "Email sudah terdaftar. Silakan gunakan email lain atau login.";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO users(nama,email,password) VALUES(?,?,?)");
        mysqli_stmt_bind_param($stmt, "sss", $nama, $email, $password);
        
        if(mysqli_stmt_execute($stmt)) {
            $sukses_register = true;
        } else {
            $error_message = "Terjadi kesalahan sistem, silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - CareApps</title>
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
<body class="bg-slate-50 min-h-screen text-slate-800 antialiased flex flex-col justify-center items-center p-4">

    <?php if($sukses_register) { ?>
        <script>
            window.location.href = 'login.php';
        </script>
    <?php } ?>

    <!-- Container Utama Card Register -->
    <div class="w-full max-w-md bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/60 border border-slate-100 p-8 md:p-10 relative overflow-hidden">
        
        <!-- Dekorasi Latar Belakang Lingkaran Halus -->
        <div class="absolute -right-12 -top-12 w-36 h-36 bg-emerald-500/5 rounded-full pointer-events-none"></div>
        <div class="absolute -left-12 -bottom-12 w-36 h-36 bg-blue-500/5 rounded-full pointer-events-none"></div>

        <!-- Identitas Aplikasi / Logo -->
        <div class="text-center mb-8 relative z-10">
            <div class="w-14 h-14 bg-emerald-600 text-white rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4 shadow-lg shadow-emerald-500/30">
                <i class="fas fa-user-plus"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Daftar Akun Pasien</h2>
            <p class="text-slate-400 text-sm mt-1">Buat akun baru untuk mulai menjadwalkan kunjungan dokter Anda.</p>
        </div>

        <!-- Notifikasi Error -->
        <?php if($error_message != "") { ?>
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3.5 rounded-2xl text-sm font-medium">
                <div class="flex items-center gap-2.5">
                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                    <span><?php echo $error_message; ?></span>
                </div>
            </div>
        <?php } ?>

        <!-- Formulir Input -->
        <form method="POST" class="space-y-5 relative z-10">
            
            <!-- INPUT NAMA LENGKAP -->
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Nama Lengkap</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <i class="far fa-user"></i>
                    </span>
                    <input type="text" name="nama" required
                        class="w-full bg-slate-50 border border-slate-200 p-4 pl-11 rounded-2xl text-sm font-medium text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all placeholder:text-slate-400">
                </div>
            </div>

            <!-- INPUT EMAIL -->
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <i class="far fa-envelope"></i>
                    </span>
                    <input type="email" name="email" required
                        class="w-full bg-slate-50 border border-slate-200 p-4 pl-11 rounded-2xl text-sm font-medium text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all placeholder:text-slate-400"
                        placeholder="nama@email.com">
                </div>
            </div>

            <!-- INPUT PASSWORD -->
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password" placeholder="••••••••" required
                        class="w-full bg-slate-50 border border-slate-200 p-4 pl-11 rounded-2xl text-sm font-medium text-slate-800 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all placeholder:text-slate-400">
                </div>
            </div>

            <!-- TOMBOL SUBMIT REGISTER -->
            <div class="pt-2">
                <button type="submit" name="register" 
                    class="w-full bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white font-bold py-4 rounded-2xl text-sm transition-all duration-150 shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2">
                    Buat Akun Baru <i class="fas fa-user-plus text-xs"></i>
                </button>
            </div>

        </form>

        <!-- Tautan Navigasi Tambahan -->
        <div class="mt-8 text-center text-sm font-medium text-slate-500 border-t border-slate-100 pt-5 relative z-10">
            <span>Sudah memiliki akun?</span> 
            <a href="login.php" class="text-blue-600 hover:text-blue-700 font-bold ml-1 transition hover:underline">
                Masuk Di Sini
            </a>
        </div>

    </div>

    <!-- Informasi Hak Cipta Gantung -->
    <p class="mt-8 text-xs text-slate-400 font-medium">
        &copy; 2026 CareApps Medical System. All Rights Reserved.
    </p>

</body>
</html>