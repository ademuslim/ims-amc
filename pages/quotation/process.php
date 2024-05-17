<?php
require '../../includes/function.php';
require '../../includes/vendor/autoload.php';
if (isset($_POST['add'])) {
    $pengirim = $_POST['pengirim'];
    $no_penawaran = strtolower($_POST['no_penawaran']);
    $tanggal = $_POST['tanggal'];
    $catatan = strtolower($_POST['catatan']);
    $penerima = $_POST['penerima'];
    $up = strtolower($_POST['up']);
    $diskon = $_POST['diskon'];
    $jenis_ppn = $_POST['jenis_ppn'];
    $grand_total = $_POST['grand_total'];

    // Inisialisasi nilai defaultLogoPath dan defaultSignaturePath
    $defaultLogoPath = "";
    $defaultSignaturePath = "";

    // Panggil fungsi selectData untuk mengambil path logo dan path signature dari tabel penawaran_harga
    $order_by = "tanggal DESC"; // Urutkan berdasarkan tanggal secara descending
    $limit = "1"; // Ambil hanya 1 hasil
    $data = selectData("penawaran_harga", "", $order_by, $limit);

    // Jika data ditemukan, ambil path logo dan signature
    if (!empty($data)) {
        $defaultLogoPath = $data[0]["logo"];
        $defaultSignaturePath = $data[0]["signature"];
    }

    // Inisialisasi variabel $file_destination logo dan signature
    $file_destination_logo = "";
    $file_destination_signature = "";

    // Memeriksa apakah file gambar logo diunggah
    if(isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['logo']['tmp_name'];
        $file_type = $_FILES['logo']['type'];
        $file_size = $_FILES['logo']['size'];
        $max_file_size = 2 * 1024 * 1024; // 2MB

        // Pastikan jenis file dan ukuran file sesuai
        if (in_array($file_type, array('image/jpeg', 'image/png', 'image/gif')) && $file_size <= $max_file_size) {
            // Generate nama file acak dan unik
            $file_extension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid() . '_' . date('Ymd') . '.' . $file_extension;
            $file_destination_logo = "../../assets/image/uploads/logo/" . $file_name;

            // Pindahkan file dari temp ke lokasi tujuan
            if (move_uploaded_file($file_tmp, $file_destination_logo)) {
                // File logo berhasil diunggah dan dipindahkan
            }else {
                $_SESSION['error_message'] = "Gagal menyimpan file gambar logo.";
                // Handle error, misalnya redirect ke halaman add.php dengan pesan error
            }
        } else {
            $_SESSION['error_message'] = "Ukuran file yang diunggah melebihi batas maksimal (2MB). Hanya gambar dengan format JPG, PNG, atau GIF yang diperbolehkan.";
            // Handle error, misalnya redirect ke halaman add.php dengan pesan error
        }
    } else {
        // Menggunakan nilai defaultLogoPath jika tersedia, jika tidak, biarkan logo kosong
        $file_destination_logo = $defaultLogoPath ? $defaultLogoPath : null; // Jika defaultLogoPath kosong, atur file_destination_logo menjadi NULL
    }

    // Memeriksa apakah file gambar signature diunggah
    if(isset($_FILES['signature']) && $_FILES['signature']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_signature = $_FILES['signature']['tmp_name'];
        $file_type_signature = $_FILES['signature']['type'];
        $file_size_signature = $_FILES['signature']['size'];
        $max_file_size_signature = 2 * 1024 * 1024; // 2MB

        // Pastikan jenis file dan ukuran file sesuai
        if (in_array($file_type_signature, array('image/jpeg', 'image/png', 'image/gif')) && $file_size_signature <= $max_file_size_signature) {
            // Generate nama file acak dan unik untuk signature
            $file_extension_signature = pathinfo($_FILES['signature']['name'], PATHINFO_EXTENSION);
            $file_name_signature = uniqid() . '_' . date('Ymd') . '.' . $file_extension_signature;
            $file_destination_signature = "../../assets/image/uploads/signature/" . $file_name_signature;

            // Pindahkan file signature dari temp ke lokasi tujuan
            if (move_uploaded_file($file_tmp_signature, $file_destination_signature)) {
                // File signature berhasil diunggah dan dipindahkan
            } else {
                $_SESSION['error_message'] = "Gagal menyimpan file gambar signature.";
                // Handle error, misalnya redirect ke halaman add.php dengan pesan error
            }
        } else {
            $_SESSION['error_message'] = "Ukuran file signature yang diunggah melebihi batas maksimal (2MB). Hanya gambar dengan format JPG, PNG, atau GIF yang diperbolehkan.";
            // Handle error, misalnya redirect ke halaman add.php dengan pesan error
        }
    } else {
        // Menggunakan nilai defaultSignaturePath jika tersedia, jika tidak, biarkan signature kosong
        $file_destination_signature = $defaultSignaturePath ? $defaultSignaturePath : null; // Jika defaultSignaturePath kosong, atur file_destination_signature menjadi NULL
    }

    // Ambil nilai-nilai dari input signature info
    $signing_location = strtolower($_POST['signing_location']);
    $signing_date = $_POST['signing_date'];
    $signer_name = strtolower($_POST['signer_name']);
    $signer_position = strtolower($_POST['signer_position']);

    // Susun data signature info sebagai string
    $signature_info = "Location: $signing_location, Date: $signing_date, Name: $signer_name, Position: $signer_position, Path: $file_destination_signature";

    // Buat ID penawaran baru
    $id_penawaran = Ramsey\Uuid\Uuid::uuid4()->toString();

    $data = [
        'id_penawaran' => $id_penawaran,
        'id_pengirim' => $pengirim,
        'no_penawaran' => $no_penawaran,
        'tanggal' => $tanggal,
        'catatan' => $catatan,
        'id_penerima' => $penerima,
        'up' => $up,
        'diskon' => $diskon,
        'id_ppn' => $jenis_ppn,
        'total' => $grand_total,
        'logo' => $file_destination_logo,
        'signature_info' => $signature_info
    ];

    // Lakukan insert data
    $result = insertData('penawaran_harga', $data);

    // Lanjut insert data detail jika insert data penawaran_harga berhasil
    if ($result) {
        $id_produk = $_POST['id_produk'];
        $jumlah = $_POST['jumlah'];
        $harga_satuan = $_POST['harga_satuan'];

        // Loop untuk menyimpan setiap detail produk ke dalam database
        for ($i = 0; $i < count($id_produk); $i++) {
            // Generate UUID untuk id_detail_penawaran
            $id_detail_penawaran = Ramsey\Uuid\Uuid::uuid4()->toString();

            // Data untuk disimpan
            $detail_produk = [
                'id_detail_penawaran' => $id_detail_penawaran,
                'id_penawaran' => $id_penawaran,
                'id_produk' => $id_produk[$i], // Menggunakan indeks yang sama untuk setiap array
                'jumlah' => $jumlah[$i], // Menggunakan indeks yang sama untuk setiap array
                'harga_satuan' => $harga_satuan[$i] // Menggunakan indeks yang sama untuk setiap array
            ];

            // Insert data detail produk ke dalam database
            $detail_result = insertData('detail_penawaran', $detail_produk);

            // Jika gagal menyimpan,
            if (!$detail_result) {
                $_SESSION['error_message'] = "Gagal menyimpan data detail produk.";
                header("Location: add.php");
                exit();
            }
        }
        // Jika berhasil disimpan, arahkan pengguna ke halaman detail
        header("Location: detail.php?id=" . $id_penawaran);
        exit();
    } else {
        // Gagal memindahkan file
        $_SESSION['error_message'] = "Gagal menyimpan file gambar logo.";
    }
// Edit Data
} elseif (isset($_POST['edit'])) {
    // Ambil nilai Data Penawaran
    $id_penawaran = $_POST['id_penawaran'];
    $pengirim = $_POST['pengirim'];
    $tanggal = $_POST['tanggal'];
    $no_penawaran = strtolower($_POST['no_penawaran']);
    $total = $_POST['grand_total'];
    $catatan = strtolower($_POST['catatan']);
    $penerima = $_POST['penerima'];
    $up = strtolower($_POST['up']);
    $diskon = isset($_POST['diskon']) ? $_POST['diskon'] : 0;
    $jenis_ppn = $_POST['jenis_ppn'];

    // Variabel untuk menyimpan path logo
    $logoPath = '';

    // Periksa apakah logo diunggah baru atau menggunakan default
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        // Logo baru diunggah, proses seperti biasa
        $file_tmp = $_FILES['logo']['tmp_name'];
        $file_type = $_FILES['logo']['type'];
        $file_size = $_FILES['logo']['size'];
        $max_file_size = 20 * 1024 * 1024; // 20MB
        $allowed_extensions = array('jpeg', 'jpg', 'png', 'gif');

        // Pastikan jenis file dan ukuran file sesuai
        if (in_array($file_type, array('image/jpeg', 'image/png', 'image/gif')) && $file_size <= $max_file_size) {
            // Menghasilkan nama file acak dan unik
            $file_extension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid() . '_' . date('Ymd') . '.' . $file_extension;
            $file_destination = "../../assets/image/uploads/logo/" . $file_name;

            // Pindahkan file dari temp ke lokasi tujuan
            if (move_uploaded_file($file_tmp, $file_destination)) {
                // Gunakan path logo yang baru diunggah
                $logoPath = $file_destination;
            } else {
                // Gagal memindahkan file
                $error_message = "Gagal menyimpan file gambar logo baru.";
            }
        } else {
            // Jenis file tidak diizinkan atau ukuran file melebihi batas maksimal
            $error_message = "Ukuran file yang diunggah melebihi batas maksimal (20MB). Hanya gambar dengan format JPG, PNG, atau GIF yang diperbolehkan.";
        }
    } else {
        // Gunakan path default logo jika tidak ada logo baru yang diunggah
        $defaultLogoPath = isset($_POST['defaultLogoPath']) ? $_POST['defaultLogoPath'] : '';
        $logoPath = $defaultLogoPath;
    }
    // Variabel untuk menyimpan path signature
    $signaturePath = '';

    // Periksa apakah signature diunggah baru atau menggunakan default
    if (isset($_FILES['signature']) && $_FILES['signature']['error'] === UPLOAD_ERR_OK) {
        // Signature baru diunggah, proses seperti biasa
        $file_tmp = $_FILES['signature']['tmp_name'];
        $file_type = $_FILES['signature']['type'];
        $file_size = $_FILES['signature']['size'];
        $max_file_size = 20 * 1024 * 1024; // 20MB
        $allowed_extensions = array('jpeg', 'jpg', 'png', 'gif');

        // Pastikan jenis file dan ukuran file sesuai
        if (in_array($file_type, array('image/jpeg', 'image/png', 'image/gif')) && $file_size <= $max_file_size) {
            // Menghasilkan nama file acak dan unik
            $file_extension = pathinfo($_FILES['signature']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid() . '_' . date('Ymd') . '.' . $file_extension;
            $file_destination = "../../assets/image/uploads/signature/" . $file_name;

            // Pindahkan file dari temp ke lokasi tujuan
            if (move_uploaded_file($file_tmp, $file_destination)) {
                // Gunakan path signature yang baru diunggah
                $signaturePath = $file_destination;
            } else {
                // Gagal memindahkan file
                $error_message = "Gagal menyimpan file gambar signature baru.";
            }
        } else {
            // Jenis file tidak diizinkan atau ukuran file melebihi batas maksimal
            $error_message = "Ukuran file yang diunggah melebihi batas maksimal (20MB). Hanya gambar dengan format JPG, PNG, atau GIF yang diperbolehkan.";
        }
    } else {
        // Gunakan path default signature jika tidak ada signature baru yang diunggah
        $defaultSignaturePath = isset($_POST['defaultSignaturePath']) ? $_POST['defaultSignaturePath'] : '';
        $signaturePath = $defaultSignaturePath;
    }

    // Bangun data signature untuk diperbarui dalam signature_info
    $signatureInfo = "Location: " . $_POST['signing_location'] . ", Date: " . $_POST['signing_date'] . ", Name: " . $_POST['signer_name'] . ", Position: " . $_POST['signer_position'] . ", Path: " . $signaturePath;


    // Bangun data untuk pembaruan penawaran_harga
    $data = [
        // 'id_penawaran' => $id_penawaran,
        'id_pengirim' => $pengirim,
        'no_penawaran' => $no_penawaran,
        'tanggal' => $tanggal,
        'total' => $total,
        'catatan' => $catatan,
        'id_penerima' => $penerima,
        'up' => $up,
        'diskon' => $diskon,
        'id_ppn' => $jenis_ppn,
        'logo' => $logoPath, // tambahkan lokasi file logo ke dalam data yang akan diupdate
        'signature_info' => $signatureInfo
    ];
    
    $conditions = "id_penawaran = '$id_penawaran'";

    $result = updateData('penawaran_harga', $data, $conditions);

    // Periksa apakah pembaruan penawaran_harga berhasil
    if (!$result) {
        // Jika gagal, tampilkan pesan kesalahan atau arahkan pengguna kembali ke halaman edit
        $_SESSION['error_message'] = "Gagal memperbarui penawaran harga.";
        header("Location: edit.php?id=$id_penawaran");
        exit();
    }
    
    // Lanjutkan edit detail
    // Peroleh nilai-nilai dari detail penawaran
    $id_detail = $_POST['id_detail_penawaran'];
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];
    $harga_satuan = $_POST['harga_satuan'];
    $deleted_rows = isset($_POST['deleted_rows']) ? $_POST['deleted_rows'] : [];

    // Loop melalui setiap detail produk yang diedit
    for ($i = 0; $i < count($id_detail); $i++) {
        // Jika ID detail penawaran ada dalam array $deleted_rows, lewati pengolahan ini
        if (in_array($id_detail[$i], $deleted_rows)) {
            continue;
        }

        // Bangun data untuk pembaruan
        $detail_data = [
            'id_produk' => $id_produk[$i],
            'jumlah' => $jumlah[$i],
            'harga_satuan' => $harga_satuan[$i]
        ];

        // Kondisi untuk pembaruan
        $detail_conditions = "id_detail_penawaran = '" . $id_detail[$i] . "'";

        // Lakukan pembaruan data detail penawaran
        $detail_result = updateData('detail_penawaran', $detail_data, $detail_conditions);
        // Periksa apakah pembaruan berhasil
        if (!$detail_result) {
            // Jika gagal, tampilkan pesan kesalahan atau arahkan pengguna kembali ke halaman edit
            $_SESSION['error_message'] = "Gagal memperbarui detail penawaran.";
            header("Location: edit.php?id=$id_penawaran");
            exit();
        }
    }

    // Lanjutkan dengan penghapusan baris detail yang ditandai
    foreach ($deleted_rows as $deleted_row) {
        // Lakukan penghapusan data detail penawaran
        $delete_result = deleteData('detail_penawaran', "id_detail_penawaran = '$deleted_row'");
        // Periksa apakah penghapusan berhasil
        if (!$delete_result) {
            // Jika gagal, tampilkan pesan kesalahan atau arahkan pengguna kembali ke halaman edit
            $_SESSION['error_message'] = "Gagal menghapus detail penawaran.";
            header("Location: edit.php?id=$id_penawaran");
            exit();
        }
    }

    // Redirect ke halaman index setelah proses edit selesai
    header("Location: index.php");
    exit();
} else {
    // Jika tidak ada data yang diterima, arahkan ke index.php
    header("Location: index.php");
    exit();
}

mysqli_close($conn);
?>