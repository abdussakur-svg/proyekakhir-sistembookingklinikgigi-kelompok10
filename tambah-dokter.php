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

    // FILE UPLOAD
    $foto_dokter = $_FILES['foto_dokter']['name'];
    $tmp_foto = $_FILES['foto_dokter']['tmp_name'];

    $sertifikat = $_FILES['sertifikat']['name'];
    $tmp_sertifikat = $_FILES['sertifikat']['tmp_name'];

    // VALIDASI ALAMAT
    if(strlen($alamat_klinik) > 250) {

        $error_pesan = "Alamat klinik maksimal 250 karakter!";

    }

    // VALIDASI DESKRIPSI
    else if(strlen($deskripsi) > 255) {

        $error_pesan = "Deskripsi maksimal 255 karakter!";

    }

    // VALIDASI JAM
    else if($jam_mulai >= $jam_selesai) {

        $error_pesan = "Jam selesai harus lebih besar dari jam mulai!";

    }

    else {

        // UPLOAD FOTO
        move_uploaded_file(
            $tmp_foto,
            "gambar-dokter/" . $foto_dokter
        );

        // UPLOAD SERTIFIKAT
        move_uploaded_file(
            $tmp_sertifikat,
            "sertifikat-dokter/" . $sertifikat
        );

        // GABUNGKAN JADWAL
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
                foto_dokter,
                sertifikat,
                deskripsi
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
            $foto_dokter,
            $sertifikat,
            $deskripsi
        );

        mysqli_stmt_execute($stmt);

        header("Location: dokter.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Dokter - Panel Admin</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen text-slate-800">

    <!-- Navbar -->
    <nav class="bg-white border-b border-slate-200 py-4 px-6">

        <div class="container mx-auto flex justify-between items-center max-w-2xl">

            <div class="flex items-center gap-2 text-blue-600 font-bold text-xl">

                <i class="fas fa-user-shield"></i>

                <span>
                    CareApps
                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">
                        Admin
                    </span>
                </span>

            </div>

            <a href="dokter.php"
               class="text-sm font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-2 transition">

                <i class="fas fa-arrow-left text-xs"></i>
                Kembali ke Dokter

            </a>

        </div>

    </nav>

    <!-- Content -->
    <main class="container mx-auto px-4 py-10 max-w-2xl">

        <div class="bg-white rounded-[2rem] shadow-xl border border-slate-100 p-8">

            <!-- Header -->
            <div class="mb-8">

                <h2 class="text-2xl font-black text-slate-900">
                    Tambah Dokter Baru
                </h2>

                <p class="text-slate-500 text-sm mt-2">
                    Tambahkan data dokter lengkap beserta foto dan sertifikat.
                </p>

            </div>

            <!-- Error -->
            <?php if(!empty($error_pesan)) { ?>

                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-2xl text-sm">

                    <?php echo $error_pesan; ?>

                </div>

            <?php } ?>

            <!-- FORM -->
            <form method="POST" enctype="multipart/form-data" class="space-y-6">

                <!-- Nama -->
                <div>

                    <label class="block text-sm font-semibold mb-2">
                        Nama Dokter
                    </label>

                    <input type="text" name="nama" required
                        class="w-full border border-slate-300 rounded-2xl px-4 py-3">

                </div>

                <!-- Spesialis -->
                <div>

                    <label class="block text-sm font-semibold mb-2">
                        Spesialis
                    </label>

                    <select name="spesialis" required
                        class="w-full border border-slate-300 rounded-2xl px-4 py-3">

                        <option value="">-- Pilih Spesialis --</option>
                        <option value="Dokter Gigi Umum">Dokter Gigi Umum</option>
                        <option value="Ortodonti">Ortodonti</option>
                        <option value="Konservasi Gigi">Konservasi Gigi</option>
                        <option value="Bedah Mulut">Bedah Mulut</option>
                        <option value="Periodonsia">Periodonsia</option>
                        <option value="Prostodonti">Prostodonti</option>

                    </select>

                </div>

                <!-- Alamat -->
                <div>

                    <label class="block text-sm font-semibold mb-2">
                        Alamat Klinik
                    </label>

                    <textarea name="alamat_klinik" rows="3" maxlength="250" required
                        class="w-full border border-slate-300 rounded-2xl px-4 py-3"></textarea>

                </div>

                <!-- Deskripsi -->
                <div>

                    <label class="block text-sm font-semibold mb-2">
                        Deskripsi Dokter
                    </label>

                    <textarea name="deskripsi" rows="4" maxlength="255" required
                        class="w-full border border-slate-300 rounded-2xl px-4 py-3"></textarea>

                </div>

                <!-- Foto -->
                <div>

                    <label class="block text-sm font-semibold mb-2">
                        Foto Dokter
                    </label>

                    <input type="file" name="foto_dokter" accept="image/*" required
                        class="w-full border border-slate-300 rounded-2xl px-4 py-3">

                </div>

                <!-- Sertifikat -->
                <div>

                    <label class="block text-sm font-semibold mb-2">
                        Sertifikat
                    </label>

                    <input type="file" name="sertifikat" accept="image/*" required
                        class="w-full border border-slate-300 rounded-2xl px-4 py-3">

                </div>

                <!-- Hari -->
                <div>

                    <label class="block text-sm font-semibold mb-2">
                        Hari Praktik
                    </label>

                    <select name="hari" required
                        class="w-full border border-slate-300 rounded-2xl px-4 py-3">

                        <option value="">-- Pilih Hari --</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>

                    </select>

                </div>

                <!-- Jam -->
                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-semibold mb-2">Jam Mulai</label>
                        <input type="time" name="jam_mulai" required
                            class="w-full border border-slate-300 rounded-2xl px-4 py-3">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Jam Selesai</label>
                        <input type="time" name="jam_selesai" required
                            class="w-full border border-slate-300 rounded-2xl px-4 py-3">
                    </div>

                </div>

                <!-- Button -->
                <button type="submit" name="tambah"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl">

                    Tambah Dokter

                </button>

            </form>

        </div>

    </main>

</body>
</html>