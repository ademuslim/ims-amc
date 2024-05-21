<?php
require '../../includes/function.php';
require '../../includes/vendor/autoload.php';
if (isset($_POST['add'])) {
    $pengirim = $_POST['pengirim'];
    $no_faktur = strtolower($_POST['no_faktur']);
    $tanggal = $_POST['tanggal'];
    $catatan = strtolower($_POST['catatan']);
    $penerima = $_POST['penerima'];
    $diskon = $_POST['diskon'];
    $jenis_ppn = $_POST['jenis_ppn'];
    $grand_total = $_POST['grand_total'];
    $kategori = $_POST['kategori'];

    // Tentukan $category_param berdasarkan nilai $kategori
    if ($kategori == "keluar") {
        $category_param = "outgoing";
    } elseif ($kategori == "masuk") {
        $category_param = "incoming";
    } else {
        // Berikan penanganan jika nilai kategori tidak valid
        die("Invalid category");
    }

    // Inisialisasi nilai defaultLogoPath dan defaultSignaturePath
    $defaultLogoPath = "";
    $defaultSignaturePath = "";

    // Panggil fungsi selectData untuk mengambil path logo dan path signature dari tabel penawaran
    $order_by = "tanggal DESC"; // Urutkan berdasarkan tanggal secara descending
    $limit = "1"; // Ambil hanya 1 hasil
    $data = selectData("penawaran_harga", "", $order_by, $limit);

    // Jika data ditemukan, ambil path logo dan signature
    if (!empty($data)) {
        $defaultLogoPath = $data[0]["logo"];
        $signature_info = $data[0]["signature_info"];
    
        // Pisahkan data signature_info berdasarkan koma (,) untuk mendapatkan setiap elemen
        $signature_info_parts = explode(", ", $signature_info);
    
        // Loop melalui setiap elemen untuk mencari bagian 'Path'
        foreach ($signature_info_parts as $part) {
            // Pecah setiap elemen menjadi pasangan kunci dan nilai
            $pair = explode(": ", $part);
            
            // Jika pasangan kunci dan nilai sesuai dengan 'Path', simpan nilainya
            if ($pair[0] == 'Path') {
                $defaultSignaturePath = $pair[1];
                break; // Keluar dari loop setelah menemukan path
            }
        }
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

    // Buat ID faktur baru
    $id_faktur = Ramsey\Uuid\Uuid::uuid4()->toString();

    $data = [
        'id_faktur' => $id_faktur,
        'id_pengirim' => $pengirim,
        'no_faktur' => $no_faktur,
        'tanggal' => $tanggal,
        'catatan' => $catatan,
        'id_penerima' => $penerima,
        'diskon' => $diskon,
        'id_ppn' => $jenis_ppn,
        'total' => $grand_total,
        'logo' => $file_destination_logo,
        'signature_info' => $signature_info,
        'kategori' => $kategori,
        'status' => 'draft'
    ];

    // Lakukan insert data
    $result = insertData('faktur', $data);

    // Lanjut insert data detail jika insert data faktur berhasil
    function unformatRupiah($rupiah) {
        return (int) preg_replace('/[^0-9]/', '', $rupiah);
    }

    if ($result) {
        $id_produk = $_POST['id_produk'];
        $jumlah = $_POST['jumlah'];
        $harga_satuan = $_POST['harga_satuan'];

        // Loop untuk menyimpan setiap detail produk ke dalam database
        for ($i = 0; $i < count($id_produk); $i++) {
            // Generate UUID untuk id_detail_faktur
            $id_detail_faktur = Ramsey\Uuid\Uuid::uuid4()->toString();

            // Unformat harga satuan sebelum menyimpan ke database
            $harga_satuan_unformatted = unformatRupiah($harga_satuan[$i]);

            // Data untuk disimpan
            $detail_produk = [
                'id_detail_faktur' => $id_detail_faktur,
                'id_faktur' => $id_faktur,
                'id_produk' => $id_produk[$i], // Menggunakan indeks yang sama untuk setiap array
                'jumlah' => $jumlah[$i], // Menggunakan indeks yang sama untuk setiap array
                'harga_satuan' => $harga_satuan_unformatted // Menggunakan nilai unformat untuk harga satuan
            ];

            // Insert data detail produk ke dalam database
            $detail_result = insertData('detail_faktur', $detail_produk);

            // Jika gagal menyimpan,
            if (!$detail_result) {
                $_SESSION['error_message'] = "Gagal menyimpan data detail produk.";
                header("Location: add.php");
                exit();
            }
        }
        // Jika berhasil disimpan, arahkan pengguna ke halaman detail
        header("Location: detail.php?category=" . $category_param . "&id=" . $id_faktur);
        exit();
    } else {
        // Gagal memindahkan file
        $_SESSION['error_message'] = "Gagal menyimpan file gambar logo.";
    }
// Edit Data
} elseif (isset($_POST['edit'])) {
    $id_faktur = $_POST['id_faktur'];
    $pengirim = $_POST['pengirim'];
    $tanggal = $_POST['tanggal'];
    $no_faktur = strtolower($_POST['no_faktur']);
    $total = $_POST['grand_total'];
    $catatan = strtolower($_POST['catatan']);
    $penerima = $_POST['penerima'];
    $diskon = isset($_POST['diskon']) ? $_POST['diskon'] : 0;
    $jenis_ppn = $_POST['jenis_ppn'];
    $kategori = $_POST['kategori'];

    // Tentukan $category_param berdasarkan nilai $kategori
    if ($kategori == "keluar") {
        $category_param = "outgoing";
    } elseif ($kategori == "masuk") {
        $category_param = "incoming";
    } else {
        // Berikan penanganan jika nilai kategori tidak valid
        die("Invalid category");
    }

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


    // Bangun data untuk pembaruan faktur
    $data = [
        'id_pengirim' => $pengirim,
        'no_faktur' => $no_faktur,
        'tanggal' => $tanggal,
        'total' => $total,
        'catatan' => $catatan,
        'id_penerima' => $penerima,
        'diskon' => $diskon,
        'id_ppn' => $jenis_ppn,
        'logo' => $logoPath, // tambahkan lokasi file logo ke dalam data yang akan diupdate
        'signature_info' => $signatureInfo
    ];
    
    $conditions = "id_faktur = '$id_faktur'";

    $result = updateData('faktur', $data, $conditions);

    // Periksa apakah pembaruan faktur berhasil
    if (!$result) {
        // Jika gagal, tampilkan pesan kesalahan atau arahkan pengguna kembali ke halaman edit
        $_SESSION['error_message'] = "Gagal memperbarui faktur.";
        // header("Location: edit.php?id=$id_faktur");
        exit();
    }
    
    // Lanjutkan edit detail
    // Fungsi untuk mengubah format Rupiah ke dalam bentuk integer
    function unformatRupiah($rupiah) {
        return (int) preg_replace('/[^0-9]/', '', $rupiah);
    }
    // Peroleh nilai-nilai dari detail penawaran
    $id_detail = $_POST['id_detail_faktur'];
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];
    $harga_satuan = $_POST['harga_satuan'];
    $deleted_rows = isset($_POST['deleted_rows']) ? $_POST['deleted_rows'] : [];

    // Simpan semua data detail ke dalam array utama
    $all_details = [];
    foreach ($id_detail as $index => $detail_id) {
        $all_details[] = [
            'id_detail_faktur' => $detail_id,
            'id_produk' => $id_produk[$index],
            'jumlah' => $jumlah[$index],
            'harga_satuan' => unformatRupiah($harga_satuan[$index])
        ];
    }

    // // Tampilkan isi dari array $all_details untuk debugging
    // echo "Isi dari all_details sebelum penghapusan:";
    // echo "<pre>";
    // var_dump($all_details);
    // echo "</pre>";
    // // exit();

    // // Tampilkan isi dari array $deleted_rows untuk debugging
    // echo "Isi dari deleted_rows:";
    // echo "<pre>";
    // var_dump($deleted_rows);
    // echo "</pre>";
    // // exit();

    // Hapus baris yang ada dalam deleted_rows
    foreach ($deleted_rows as $deleted_row) {
        // Hanya hapus baris jika id_detail_faktur tidak mengandung "newId" di awal id-nya
        if (strpos($deleted_row, "newId") !== 0) {
            deleteData('detail_faktur', "id_detail_faktur = '$deleted_row'");
        }
    }

    // Persiapkan data untuk update dan insert
    $add_detail = [];
    $update_detail = [];

    // Kelompokkan detail berdasarkan id
    foreach ($all_details as $detail) {
        if (strpos($detail['id_detail_faktur'], "newId") === 0) {
            // Jika id_detail_faktur mengandung "newId", itu adalah baris baru
            $add_detail[] = $detail;
        } else {
            // Jika tidak, itu adalah baris yang harus diperbarui
            $update_detail[] = $detail;
        }
    }

    // // Tampilkan isi dari array $add_detail dan $update_detail untuk debugging
    // echo "Isi dari add_detail (baris baru):";
    // echo "<pre>";
    // var_dump($add_detail);
    // echo "</pre>";

    // echo "Isi dari update_detail (baris yang harus diperbarui):";
    // echo "<pre>";
    // var_dump($update_detail);
    // echo "</pre>";

    // Lakukan operasi tambah data
    foreach ($add_detail as $detail) {
        // Generate UUID untuk ID baru
        $new_id_detail = Ramsey\Uuid\Uuid::uuid4()->toString();
        $data = [
            'id_produk' => $detail['id_produk'],
            'jumlah' => $detail['jumlah'],
            'harga_satuan' => $detail['harga_satuan'],
            // tambahkan kolom lain yang diperlukan sesuai dengan struktur tabel
            'id_detail_faktur' => $new_id_detail,
            'id_faktur' => $id_faktur,
        ];
        insertData('detail_faktur', $data);
    }

    // Lakukan operasi ubah data
    foreach ($update_detail as $detail) {
        $data = [
            'id_produk' => $detail['id_produk'],
            'jumlah' => $detail['jumlah'],
            'harga_satuan' => $detail['harga_satuan'],
            // tambahkan kolom lain yang diperlukan sesuai dengan struktur tabel
        ];
        updateData('detail_faktur', $data, "id_detail_faktur = '{$detail['id_detail_faktur']}'");
    }

    // echo "Operasi tambah dan ubah data selesai.";

    // Redirect ke halaman detail setelah proses edit selesai
    header("Location: detail.php?category=" . $category_param . "&id=" . $id_faktur);
    exit();
} else {
    // Jika tidak ada data yang diterima, arahkan ke index.php
    header("Location: index.php");
    exit();
}

mysqli_close($conn);
?>