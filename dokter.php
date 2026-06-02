<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Akses ditolak");
}

$error_pesan = "";

if(isset($_POST['tambah'])) {

    $nama = $_POST['nama'];
    $spesialis = $_POST['spesialis'];
    $alamat_klinik = $_POST['alamat_klinik'];
    $deskripsi = $_POST['deskripsi'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    // UPLOAD FILE (SUDAH BENAR)
    $foto_dokter = $_FILES['foto_dokter']['name'];
    $tmp_foto = $_FILES['foto_dokter']['tmp_name'];

    $sertifikat = $_FILES['sertifikat']['name'];
    $tmp_sertifikat = $_FILES['sertifikat']['tmp_name'];

    // VALIDASI
    if(strlen($alamat_klinik) > 250){
        $error_pesan = "Alamat maksimal 250 karakter!";
    }
    else if(strlen($deskripsi) > 255){
        $error_pesan = "Deskripsi maksimal 255 karakter!";
    }
    else if($jam_mulai >= $jam_selesai){
        $error_pesan = "Jam selesai harus lebih besar dari jam mulai!";
    }
    else {

        // UPLOAD KE FOLDER (TANPA MERUBAH CSS)
        move_uploaded_file($tmp_foto, "gambar-dokter/" . $foto_dokter);
        move_uploaded_file($tmp_sertifikat, "sertifikat-dokter/" . $sertifikat);

        // JADWAL
        $jadwal = $hari . ' ' . $jam_mulai . '-' . $jam_selesai;

        // INSERT DATABASE
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO dokter
            (
                nama_dokter,
                spesialis,
                jadwal,
                alamat_klinik,
                deskripsi,
                foto_dokter,
                sertifikat
            )
            VALUES(?,?,?,?,?,?,?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sssssss",
            $nama,
            $spesialis,
            $jadwal,
            $alamat_klinik,
            $deskripsi,
            $foto_dokter,
            $sertifikat
        );

        mysqli_stmt_execute($stmt);

        header("Location: dokter.php");
        exit;
    }
}

// AMBIL DATA
$dokter = mysqli_query($conn, "SELECT * FROM dokter");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Dokter - Panel Admin</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
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

    <!-- Navbar -->
    <nav class="bg-white border-b border-slate-200 py-4 px-6 sticky top-0 z-50">

        <div class="container mx-auto flex justify-between items-center max-w-7xl">

            <div class="flex items-center space-x-2 text-blue-600 font-bold text-xl tracking-tight">

                <i class="fas fa-user-shield text-2xl"></i>

                <span>
                    CareApps
                    <span class="text-xs font-semibold bg-blue-100 text-blue-700 px-2 py-0.5 rounded ml-1">
                        Admin
                    </span>
                </span>

            </div>

            <a href="dashboard.php"
               class="text-sm font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-2 transition">

                <i class="fas fa-arrow-left text-xs"></i>
                Kembali ke Dashboard

            </a>

        </div>

    </nav>

    <!-- Konten -->
    <main class="container mx-auto px-4 py-10 max-w-7xl flex-grow">

        <?php if(!empty($error_pesan)) { ?>
        <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-2xl flex items-center gap-2 text-sm">
            <i class="fas fa-exclamation-circle text-rose-500"></i>
            <span><?php echo $error_pesan; ?></span>
        </div>
        <?php } ?>

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">

            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">
                    Kelola Data Dokter
                </h2>
                <p class="text-slate-500 text-sm mt-1">
                    Profil dokter profesional CareApps Medical Center.
                </p>
            </div>

            <a href="tambah-dokter.php"
               class="bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold px-5 py-3 rounded-2xl text-sm transition-all duration-150 flex items-center justify-center gap-2 shadow-lg shadow-blue-500/10 w-fit">

                <i class="fas fa-plus text-xs"></i>
                Tambah Dokter Baru

            </a>

        </div>

        <!-- CARD -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            <?php if(mysqli_num_rows($dokter) == 0) { ?>

                <div class="col-span-full bg-white rounded-3xl p-10 text-center border border-slate-200">
                    <i class="fas fa-folder-open text-4xl text-slate-300 mb-4"></i>
                    <p class="text-slate-500">Belum ada data dokter terdaftar.</p>
                </div>

            <?php } else { ?>

                <?php while($d = mysqli_fetch_assoc($dokter)) { ?>

                    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden hover:shadow-xl transition-all duration-300">

                        <div class="h-64 bg-slate-100 overflow-hidden">
                            <img src="gambar-dokter/<?php echo htmlspecialchars($d['foto_dokter']); ?>"
                                 class="w-full h-full object-cover">
                        </div>

                        <div class="p-6">

                            <h3 class="text-xl font-black text-slate-900 mb-2">
                                <?php echo htmlspecialchars($d['nama_dokter']); ?>
                            </h3>

                            <div class="mb-4">
                                <span class="bg-blue-50 text-blue-700 border border-blue-100 px-3 py-1 rounded-xl text-xs font-bold">
                                    <?php echo htmlspecialchars($d['spesialis']); ?>
                                </span>
                            </div>

                            <p class="text-sm text-slate-600 leading-relaxed mb-5">
                                <?php echo htmlspecialchars($d['deskripsi']); ?>
                            </p>

                            <div class="flex items-center gap-2 text-sm text-slate-600 mb-3">
                                <i class="far fa-clock text-blue-500"></i>
                                <?php echo htmlspecialchars($d['jadwal']); ?>
                            </div>

                            <div class="flex items-start gap-2 text-sm text-slate-600 mb-5">
                                <i class="fas fa-map-marker-alt text-rose-500 mt-1"></i>
                                <span><?php echo htmlspecialchars($d['alamat_klinik']); ?></span>
                            </div>

                            <div class="mb-5">
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                    Sertifikasi Dokter
                                </p>

                                <img src="sertifikat-dokter/<?php echo htmlspecialchars($d['sertifikat']); ?>"
                                     class="w-full h-40 object-cover rounded-2xl border border-slate-200">
                            </div>

                            <div class="flex gap-2">

                                <a href="edit-dokter.php?id=<?php echo $d['id_dokter']; ?>"
                                   class="flex-1 bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-blue-600 border border-slate-200 rounded-2xl py-3 text-sm font-bold text-center transition">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <a href="hapus-dokter.php?id=<?php echo $d['id_dokter']; ?>"
                                   onclick="return confirm('Yakin ingin menghapus dokter ini?')"
                                   class="flex-1 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white border border-rose-100 rounded-2xl py-3 text-sm font-bold text-center transition">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </a>

                            </div>

                        </div>
                    </div>

                <?php } ?>

            <?php } ?>

        </div>

    </main>

    <footer class="py-6 text-center text-xs text-slate-400 border-t border-slate-200/60 bg-white">
        &copy; 2026 CareApps Medical Center. Hak Cipta Dilindungi.
    </footer>

</body>
</html>