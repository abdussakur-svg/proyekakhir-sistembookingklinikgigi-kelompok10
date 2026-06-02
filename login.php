<?php
session_start();
include 'koneksi.php';

// Jika pengguna sudah login, langsung alihkan ke dashboard
if(isset($_SESSION['id_users'])) {
    header("Location: dashboard.php");
    exit;
}

$error_login = false;

if(isset($_POST['login'])) {
    // Memperbaiki undefined index dengan menggunakan null coalescing operator (??)
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email=?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    // Memastikan $data ada, lalu memastikan kolom password ada dan tidak null sebelum memverifikasi
    if($data && isset($data['password']) && password_verify($password, $data['password'])) {
        $_SESSION['id_users'] = $data['id_users'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role'] = $data['role'];

        header("Location: dashboard.php");
        exit;
    } else {
        $error_login = true;
    } 
} 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Aplikasi - CareApps</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen text-slate-800 antialiased flex flex-col justify-center items-center p-4">


    <div class="w-full max-w-md bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/60 border border-slate-100 p-8 md:p-10 relative overflow-hidden">
        <div class="absolute -right-12 -top-12 w-36 h-36 bg-blue-500/5 rounded-full pointer-events-none"></div>
        <div class="absolute -left-12 -bottom-12 w-36 h-36 bg-indigo-500/5 rounded-full pointer-events-none"></div>

        
        <div class="text-center mb-8 relative z-10">
            <div class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4 shadow-lg shadow-blue-500/30">
                <i class="fas fa-heartbeat"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Selamat Datang</h2>
            <p class="text-slate-400 text-sm mt-1">Masuk untuk mengelola jadwal atau melakukan booking dokter.</p>
        </div>

        <?php if($error_login) { ?>
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3.5 rounded-2xl flex items-center gap-2.5 text-sm font-medium animate-pulse">
                <i class="fas fa-exclamation-circle text-rose-500 text-base"></i>
                <span>Email atau password salah. Silakan coba lagi.</span>
            </div>
        <?php } ?>

        <form method="POST" class="space-y-5 relative z-10">
            
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Alamat Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <i class="far fa-envelope"></i>
                    </span>
                    <input type="email" name="email" required
                        class="w-full bg-slate-50 border border-slate-200 p-4 pl-11 rounded-2xl text-sm font-medium text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all placeholder:text-slate-400"
                        placeholder="nama@email.com">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password" placeholder="••••••••" required
                        class="w-full bg-slate-50 border border-slate-200 p-4 pl-11 rounded-2xl text-sm font-medium text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all placeholder:text-slate-400">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" name="login" 
                    class="w-full bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white font-bold py-4 rounded-2xl text-sm transition-all duration-150 shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2">
                    Masuk ke Akun <i class="fas fa-arrow-right text-xs"></i>
                </button>
            </div>

        </form>

        <div class="mt-8 text-center text-sm font-medium text-slate-500 border-t border-slate-100 pt-5 relative z-10">
            <span>Belum memiliki akun?</span> 
            <a href="register.php" class="text-blue-600 hover:text-blue-700 font-bold ml-1 transition hover:underline">
                Daftar Sekarang
            </a>
        </div>

    </div>

    <p class="mt-8 text-xs text-slate-400 font-medium">
        &copy; 2026 CareApps Medical System. All Rights Reserved.
    </p>

</body>
</html>