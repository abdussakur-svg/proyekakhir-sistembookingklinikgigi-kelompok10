<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Akses ditolak");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID dokter tidak valid");
}

$id = (int) $_GET['id'];


$stmt = mysqli_prepare($conn, "SELECT * FROM dokter WHERE id_dokter=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data dokter tidak ditemukan");
}

/* PARSING JADWAL */
$jadwal = $data['jadwal'] ?? '';
$pecah = explode(' ', $jadwal);

$hari = $pecah[0] ?? '';
$jam = $pecah[1] ?? '';

$waktu = explode('-', $jam);
$jam_mulai = $waktu[0] ?? '';
$jam_selesai = $waktu[1] ?? '';

$error_pesan = "";

/* UPDATE */
if (isset($_POST['update'])) {

    $nama = trim($_POST['nama']);
    $spesialis = trim($_POST['spesialis']);
    $alamat_klinik = trim($_POST['alamat_klinik']);
    $deskripsi = trim($_POST['deskripsi']);

    $hari = trim($_POST['hari']);
    $jam_mulai = trim($_POST['jam_mulai']);
    $jam_selesai = trim($_POST['jam_selesai']);

    /* FILE */
    $foto_baru = $_FILES['foto_dokter']['name'] ?? '';
    $tmp_foto = $_FILES['foto_dokter']['tmp_name'] ?? '';

    $sertifikat_baru = $_FILES['sertifikat']['name'] ?? '';
    $tmp_sertifikat = $_FILES['sertifikat']['tmp_name'] ?? '';
    if (
        empty($nama) ||
        empty($spesialis) ||
        empty($alamat_klinik) ||
        empty($deskripsi) ||
        empty($hari) ||
        empty($jam_mulai) ||
        empty($jam_selesai)
    ) {
        $error_pesan = "Semua field wajib diisi.";
    } else if (strlen($alamat_klinik) > 250) {
        $error_pesan = "Alamat klinik maksimal 250 karakter!";
    } else if (strlen($deskripsi) > 255) {
        $error_pesan = "Deskripsi maksimal 255 karakter!";
    } else if ($jam_mulai >= $jam_selesai) {
        $error_pesan = "Jam selesai harus lebih besar dari jam mulai!";
    } else {

        $jadwal = $hari . ' ' . $jam_mulai . '-' . $jam_selesai;

        /* FOTO */
        if (!empty($foto_baru)) {

            $ext_foto = strtolower(
                pathinfo($foto_baru, PATHINFO_EXTENSION)
            );

            $allowed_foto = ['jpg', 'jpeg', 'png'];

            if (!in_array($ext_foto, $allowed_foto)) {

                $error_pesan = "Foto hanya boleh JPG, JPEG, atau PNG.";
            } else {

                $nama_foto = time() . '_foto_' . basename($foto_baru);

                if (
                    move_uploaded_file(
                        $tmp_foto,
                        "gambar-dokter/" . $nama_foto
                    )
                ) {

                    if (
                        !empty($data['foto_dokter']) &&
                        file_exists("gambar-dokter/" . $data['foto_dokter'])
                    ) {
                        unlink("gambar-dokter/" . $data['foto_dokter']);
                    }

                    $foto_baru = $nama_foto;
                } else {

                    $error_pesan = "Gagal mengunggah foto dokter.";
                }
            }
        } else {

            $foto_baru = $data['foto_dokter'];
        }
        /* SERTIFIKAT */
        if (!empty($sertifikat_baru)) {

            $ext = strtolower(
                pathinfo($sertifikat_baru, PATHINFO_EXTENSION)
            );

            $allowed = ['pdf', 'jpg', 'jpeg', 'png'];

            if (!in_array($ext, $allowed)) {

                $error_pesan = "Sertifikat hanya boleh PDF, JPG, JPEG, atau PNG.";
            } else {

                $nama_sertifikat = time() . '_sertifikat_' . basename($sertifikat_baru);

                if (
                    move_uploaded_file(
                        $tmp_sertifikat,
                        "sertifikat-dokter/" . $nama_sertifikat
                    )
                ) {

                    if (
                        !empty($data['sertifikat']) &&
                        file_exists("sertifikat-dokter/" . $data['sertifikat'])
                    ) {
                        unlink("sertifikat-dokter/" . $data['sertifikat']);
                    }

                    $sertifikat_baru = $nama_sertifikat;
                } else {

                    $error_pesan = "Gagal mengunggah sertifikat.";
                }
            }
        } else {

            $sertifikat_baru = $data['sertifikat'];
        }


        /* UPDATE DATABASE */
        if (empty($error_pesan)) {

            $update = mysqli_prepare(
                $conn,
                "UPDATE dokter SET
                    nama_dokter=?,
                    spesialis=?,
                    jadwal=?,
                    alamat_klinik=?,
                    deskripsi=?,
                    foto_dokter=?,
                    sertifikat=?
                WHERE id_dokter=?"
            );

            mysqli_stmt_bind_param(
                $update,
                "sssssssi",
                $nama,
                $spesialis,
                $jadwal,
                $alamat_klinik,
                $deskripsi,
                $foto_baru,
                $sertifikat_baru,
                $id
            );

            if (mysqli_stmt_execute($update)) {

                header("Location: dokter.php");
                exit;
            } else {

                $error_pesan = "Gagal memperbarui data dokter.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dokter</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen text-slate-800">

    <!-- NAVBAR -->
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

    <!-- CONTENT -->
    <main class="container mx-auto px-4 py-10 max-w-2xl">

        <div class="bg-white rounded-[2rem] shadow-xl border border-slate-100 p-8">

            <!-- HEADER -->
            <div class="mb-8">

                <h2 class="text-2xl font-black text-slate-900">
                    Edit Dokter
                </h2>

                <p class="text-slate-500 text-sm mt-2">
                    Perbarui data dokter lengkap.
                </p>

            </div>

            <!-- ERROR -->
            <?php if (!empty($error_pesan)) { ?>
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-2xl text-sm">
                    <?= htmlspecialchars($error_pesan) ?>
                </div>
            <?php } ?>

            <!-- FORM -->
            <form method="POST" enctype="multipart/form-data" class="space-y-6">

                <!-- NAMA -->
                <div>
                    <label class="block text-sm font-semibold mb-2">
                        Nama Dokter
                    </label>

                    <input type="text"
                        name="nama"
                        value="<?= htmlspecialchars($data['nama_dokter']) ?>"
                        class="w-full border border-slate-300 rounded-2xl px-4 py-3">
                </div>

                <!-- SPESIALIS -->
                <div>

                    <label class="block text-sm font-semibold mb-2">
                        Spesialis
                    </label>

                    <select name="spesialis"
                        class="w-full border border-slate-300 rounded-2xl px-4 py-3">

                        <?php
                        $list = [
                            "Dokter Gigi Umum",
                            "Ortodonti",
                            "Konservasi Gigi",
                            "Bedah Mulut",
                            "Periodonsia",
                            "Prostodonti"
                        ];

                        foreach ($list as $sp) {
                            $sel = ($data['spesialis'] == $sp) ? "selected" : "";
                            echo "<option value='$sp' $sel>$sp</option>";
                        }
                        ?>

                    </select>

                </div>

                <!-- ALAMAT -->
                <div>

                    <label class="block text-sm font-semibold mb-2">
                        Alamat Klinik
                    </label>

                    <textarea name="alamat_klinik"
                        rows="3"
                        class="w-full border border-slate-300 rounded-2xl px-4 py-3"><?= htmlspecialchars($data['alamat_klinik']) ?></textarea>

                </div>

                <!-- DESKRIPSI -->
                <div>

                    <label class="block text-sm font-semibold mb-2">
                        Deskripsi
                    </label>

                    <textarea name="deskripsi"
                        rows="4"
                        class="w-full border border-slate-300 rounded-2xl px-4 py-3"><?= htmlspecialchars($data['deskripsi']) ?></textarea>

                </div>

                <!-- FOTO -->
                <div>

                    <label class="block text-sm font-semibold mb-2">
                        Foto Dokter
                    </label>

                    <?php if (
                        !empty($data['foto_dokter']) &&
                        file_exists("gambar-dokter/" . $data['foto_dokter'])
                    ) { ?>
                        <img src="gambar-dokter/<?= htmlspecialchars($data['foto_dokter']) ?>"
                            class="w-40 h-40 object-cover rounded-2xl border mb-3">
                    <?php } else { ?>
                        <p class="text-sm text-slate-500 mb-3">
                            Foto dokter tidak tersedia.
                        </p>
                    <?php } ?>

                    <input type="file"
                        name="foto_dokter"
                        accept="image/*"
                        class="w-full border border-slate-300 rounded-2xl px-4 py-3">

                </div>

                <!-- SERTIFIKAT -->
                <div>

                    <label class="block text-sm font-semibold mb-2">
                        Sertifikat Dokter
                    </label>

                    <?php
                    $ext_sertifikat = strtolower(
                        pathinfo($data['sertifikat'], PATHINFO_EXTENSION)
                    );
                    ?>

                    <?php if (
                        !empty($data['sertifikat']) &&
                        file_exists("sertifikat-dokter/" . $data['sertifikat'])
                    ) { ?>

                        <?php if ($ext_sertifikat === 'pdf') { ?>

                            <a href="sertifikat-dokter/<?= htmlspecialchars($data['sertifikat']) ?>"
                                target="_blank"
                                class="block w-full h-52 rounded-2xl border bg-slate-50 mb-3 flex items-center justify-center">

                                <div class="text-center">
                                    <i class="fas fa-file-pdf text-red-600 text-5xl mb-2"></i>
                                    <p class="text-sm text-slate-600">
                                        Klik untuk melihat PDF
                                    </p>
                                </div>

                            </a>

                        <?php } else { ?>

                            <img src="sertifikat-dokter/<?= htmlspecialchars($data['sertifikat']) ?>"
                                class="w-full h-52 object-contain rounded-2xl border bg-slate-50 mb-3">

                        <?php } ?>

                    <?php } else { ?>

                        <p class="text-sm text-slate-500 mb-3">
                            Sertifikat tidak tersedia.
                        </p>

                    <?php } ?>

                    <input type="file"
                        name="sertifikat"
                        accept=".pdf,image/*"
                        class="w-full border border-slate-300 rounded-2xl px-4 py-3">

                </div>

                <!-- HARI -->
                <div>

                    <label class="block text-sm font-semibold mb-2">
                        Hari Praktik
                    </label>

                    <select name="hari"
                        class="w-full border border-slate-300 rounded-2xl px-4 py-3">

                        <?php
                        $hariList = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat"];

                        foreach ($hariList as $h) {
                            $sel = ($hari == $h) ? "selected" : "";
                            echo "<option value='$h' $sel>$h</option>";
                        }
                        ?>

                    </select>

                </div>

                <!-- JAM -->
                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-semibold mb-2">
                            Jam Mulai
                        </label>

                        <input type="time"
                            name="jam_mulai"
                            value="<?= $jam_mulai ?>"
                            class="w-full border border-slate-300 rounded-2xl px-4 py-3">

                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">
                            Jam Selesai
                        </label>

                        <input type="time"
                            name="jam_selesai"
                            value="<?= $jam_selesai ?>"
                            class="w-full border border-slate-300 rounded-2xl px-4 py-3">

                    </div>

                </div>

                <!-- BUTTON -->
                <button type="submit"
                    name="update"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl">

                    Simpan Perubahan

                </button>

            </form>

        </div>

    </main>

</body>

</html>
