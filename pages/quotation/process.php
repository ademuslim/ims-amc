<?php
require '../../includes/function.php';
require '../../includes/vendor/autoload.php';
if (isset($_POST['add'])) {
    // Peroleh nilai dari $_POST
    $pengirim = $_POST['pengirim'];
    $no_penawaran = $_POST['no_penawaran'];
    $tanggal = $_POST['tanggal'];
    $catatan = $_POST['catatan'];
    $penerima = $_POST['penerima'];
    $up = $_POST['up'];
    $diskon = $_POST['diskon'];
    $jenis_ppn = $_POST['jenis_ppn'];

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
            $file_destination = "../../assets/image/uploads/" . $file_name;

            // Pindahkan file dari temp ke lokasi tujuan
            if (move_uploaded_file($file_tmp, $file_destination)) {
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
                    'logo' => $file_destination
                ];

                $result = insertData('penawaran_harga', $data);

                // Lanjut insert data detail
                // Tangkap data detail produk dari form
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
        } else {
            // Jenis file tidak diizinkan atau ukuran file melebihi batas maksimal
            $_SESSION['error_message'] = "Ukuran file yang diunggah melebihi batas maksimal (2MB). Hanya gambar dengan format JPG, PNG, atau GIF yang diperbolehkan.";
        }
    } else {
        // Tidak ada file yang diunggah atau terjadi kesalahan saat mengunggah
        $_SESSION['error_message'] = "Gagal mengunggah file gambar logo.";
    }
} elseif (isset($_POST['edit'])) {
    // Peroleh nilai-nilai dari formulir edit penawaran harga
    $id_penawaran = $_POST['id_penawaran'];
    $pengirim = $_POST['pengirim'];
    $tanggal = $_POST['tanggal'];
    $no_penawaran = strtolower($_POST['no_penawaran']);
    $total = $_POST['total'];
    $catatan = strtolower($_POST['catatan']);
    $penerima = $_POST['penerima'];
    $up = strtolower($_POST['up']);
    $diskon = isset($_POST['diskon']) ? $_POST['diskon'] : 0;
    $jenis_ppn = $_POST['jenis_ppn'];

    // Tentukan lokasi file logo sebelumnya
    $existing_logo = $_POST['existing_logo'];

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
            $file_destination = "../../assets/image/uploads/" . $file_name;

            // Pindahkan file dari temp ke lokasi tujuan
            if (move_uploaded_file($file_tmp, $file_destination)) {
                // Hapus logo sebelumnya jika ada
                if (!empty($existing_logo)) {
                    unlink($existing_logo);
                }
            } else {
                // Gagal memindahkan file
                $_SESSION['error_message'] = "Gagal menyimpan file gambar logo baru.";
                header("Location: edit.php?id=$id_penawaran");
                exit();
            }
        } else {
            // Jenis file tidak diizinkan atau ukuran file melebihi batas maksimal
            $_SESSION['error_message'] = "Ukuran file yang diunggah melebihi batas maksimal (2MB). Hanya gambar dengan format JPG, PNG, atau GIF yang diperbolehkan.";
            header("Location: edit.php?id=$id_penawaran");
            exit();
        }
    } else {
        // Gunakan lokasi file logo yang sudah ada jika tidak ada logo baru yang diunggah
        $file_destination = $existing_logo;
    }

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
        'logo' => $file_destination // tambahkan lokasi file logo ke dalam data yang akan diupdate
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
    $id_detail = $_POST['id_detail'];
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];
    $harga_satuan = $_POST['harga_satuan'];

    // Loop melalui setiap detail produk yang diedit
    for ($i = 0; $i < count($id_detail); $i++) {
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