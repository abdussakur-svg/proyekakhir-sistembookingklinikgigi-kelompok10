<?php
session_start();

date_default_timezone_set('Asia/Jakarta');

include 'koneksi.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'pasien') {
    die("Akses ditolak");
}

$error_hari = "";
$error_jam = "";
$error_booking = "";

if(isset($_POST['booking'])) {

    $user_id = $_SESSION['id_users'];
    $dokter_id = $_POST['dokter_id'];
    $tanggal = $_POST['tanggal'];
    $jam = $_POST['jam'];
    $keluhan = $_POST['keluhan']; // Mengambil data keluhan

    $ambil = mysqli_prepare($conn, "SELECT * FROM dokter WHERE id_dokter=?");
    mysqli_stmt_bind_param($ambil, "i", $dokter_id);
    mysqli_stmt_execute($ambil);
    $result = mysqli_stmt_get_result($ambil);
    $dokter_data = mysqli_fetch_assoc($result);

    $jadwal = $dokter_data['jadwal'];
    $pecah = explode(' ', $jadwal);

    if(count($pecah) < 2) {
        $error_jam = "Format jadwal dokter salah!";
    } else {

        $hari = strtolower(trim($pecah[0]));
        $jam_range = explode('-', $pecah[1]);

        $jam_mulai = $jam_range[0];
        $jam_selesai = $jam_range[1];

        $hari_booking = date('N', strtotime($tanggal));

        $map_hari = [
            'senin'=>1,'selasa'=>2,'rabu'=>3,'kamis'=>4,
            'jumat'=>5,'sabtu'=>6,'minggu'=>7
        ];

        if(!isset($map_hari[$hari]) || $hari_booking != $map_hari[$hari]) {
            $error_hari = "Tanggal tidak sesuai jadwal dokter ($hari)";
        }

        if($jam < $jam_mulai || $jam > $jam_selesai) {
            $error_jam = "Jam di luar jam praktik ($pecah[1])";
        }

        if($error_hari == "" && $error_jam == "") {

            $cek = mysqli_prepare($conn, "SELECT * FROM booking WHERE id_dokter=? AND tanggal=? AND jam=?");
            mysqli_stmt_bind_param($cek, "iss", $dokter_id, $tanggal, $jam);
            mysqli_stmt_execute($cek);
            $res = mysqli_stmt_get_result($cek);

            if(mysqli_num_rows($res) > 0) {
                $error_booking = "Jadwal sudah dibooking!";
            }
        }

        if($error_hari=="" && $error_jam=="" && $error_booking=="") {
            // Menambahkan keluhan ke dalam query INSERT
            $stmt = mysqli_prepare($conn, "INSERT INTO booking(id_users,id_dokter,tanggal,jam,keluhan) VALUES(?,?,?,?,?)");
            mysqli_stmt_bind_param($stmt, "iisss", $user_id, $dokter_id, $tanggal, $jam, $keluhan);
            mysqli_stmt_execute($stmt);

            echo "<script>alert('Booking berhasil!');window.location='booking.php';</script>";
            exit;
        }
    }
}

$dokter = mysqli_query($conn, "SELECT * FROM dokter");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking Dokter</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>

<body class="bg-slate-50 min-h-screen text-slate-800 antialiased flex flex-col justify-between">

<nav class="bg-white border-b border-slate-200 py-4 px-6 sticky top-0 z-50">
    <div class="container mx-auto flex justify-between items-center max-w-4xl">
        <div class="flex items-center space-x-2 text-blue-600 font-bold text-xl tracking-tight">
            <i class="fas fa-heartbeat text-2xl"></i>
            <span>KlinikKu</span>
        </div>
        <a href="dashboard.php" class="text-sm font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-2 transition">
            <i class="fas fa-arrow-left text-xs"></i> Kembali ke Dashboard
        </a>
    </div>
</nav>

<main class="container mx-auto px-4 py-10 max-w-3xl flex-grow flex flex-col justify-center">
<div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 md:p-10 w-full">

<div class="mb-8">
    <span class="bg-blue-50 text-blue-600 text-[11px] font-bold uppercase px-3 py-1.5 rounded-lg inline-block mb-3">Reservasi Online</span>
    <h2 class="text-2xl font-bold">Buat Janji Temu Dokter</h2>
    <p class="text-slate-500 text-sm mt-1">Pilih dokter & waktu kunjungan</p>
</div>

<?php if($error_hari || $error_jam || $error_booking){ ?>
<div class="bg-rose-50 text-rose-600 p-3 rounded-xl mb-4 text-sm"><?= $error_hari." ".$error_jam." ".$error_booking; ?></div>
<?php } ?>

<form method="POST" class="space-y-6">
    <div>
        <label class="text-sm font-bold">Pilih Dokter</label>
        <div onclick="toggleBox()" class="mt-2 p-4 bg-slate-50 border rounded-2xl cursor-pointer" id="selectedText">Klik untuk pilih dokter</div>
        <input type="hidden" name="dokter_id" id="dokter_id" required>
        <div id="box" class="hidden mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[500px] overflow-auto">
            <?php while($d = mysqli_fetch_assoc($dokter)) { ?>
            <div onclick="selectDokter('<?= $d['id_dokter']; ?>','<?= $d['nama_dokter']; ?>')"
                class="border rounded-2xl p-3 bg-white hover:shadow cursor-pointer">

                <!-- FOTO -->
                <img src="gambar-dokter/<?= $d['foto_dokter']; ?>"
                    class="h-32 w-full object-cover rounded-xl">

                <!-- NAMA -->
                <div class="font-bold mt-2">
                    <?= $d['nama_dokter']; ?>
                </div>

                <!-- SPESIALIS -->
                <div class="text-xs text-blue-600">
                    <?= $d['spesialis']; ?>
                </div>

                <!-- DESKRIPSI -->
                <div class="text-xs text-slate-500 mt-1 line-clamp-2">
                    <?= $d['deskripsi']; ?>
                </div>

                <!-- ALAMAT -->
                <div class="text-xs text-slate-500 mt-1">
                    📍 <?= $d['alamat_klinik']; ?>
                </div>

                <!-- JADWAL -->
                <div class="text-xs text-slate-500 mt-1">
                    🕒 <?= $d['jadwal']; ?>
                </div>

                <!-- SERTIFIKAT -->
                <img src="sertifikat-dokter/<?= $d['sertifikat']; ?>"
                    class="h-24 w-full object-contain rounded-lg mt-2 border">

            </div>
            <?php } ?>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="text-sm font-bold">Tanggal</label>
            <input type="date" name="tanggal" required class="w-full p-3 border rounded-xl">
        </div>
        <div>
            <label class="text-sm font-bold">Jam</label>
            <input type="time" name="jam" required class="w-full p-3 border rounded-xl">
        </div>
    </div>

    <div>
        <label class="text-sm font-bold">Keluhan</label>
        <textarea name="keluhan" required rows="3" class="w-full p-3 border rounded-xl mt-1 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Tuliskan keluhan Anda..."></textarea>
    </div>

    <button type="submit" name="booking" class="w-full bg-blue-600 text-white py-3 rounded-2xl font-bold">Booking Sekarang</button>
</form>

</div>
</main>

<script>
function toggleBox(){ document.getElementById('box').classList.toggle('hidden'); }
function selectDokter(id, nama){
    document.getElementById('dokter_id').value = id;
    document.getElementById('selectedText').innerText = nama;
    document.getElementById('box').classList.add('hidden');
}
</script>
</body>
</html>