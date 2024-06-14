<?php
require '../../includes/function.php';
require '../../includes/vendor/autoload.php';

// Ambil id_pengguna dari sesi atau cookie untuk pencatatan log aktivitas
$id_pengguna = $id_pengguna ?? $_COOKIE['ingat_user_id'] ?? '';

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
    $kategori = $_POST['kategori'];

    // Tentukan $category_param berdasarkan nilai $kategori
    $category_param = $kategori === "keluar" ? "outgoing" : ($kategori === "masuk" ? "incoming" : die("Invalid category"));

    // Inisialisasi variabel $file_destination_logo dan $file_destination_signature
    $file_destination_logo = $file_destination_signature = null;
    
    if ($category_param === 'outgoing') {

        // Inisialisasi nilai defaultLogoPath dan defaultSignaturePath untuk dokumen outgoing
        $defaultLogoPath = $defaultSignaturePath = null;

        // Panggil fungsi selectData untuk mengambil path logo dan path signature dari tabel penawaran_harga
        $data = selectData("penawaran_harga", "kategori = 'keluar' AND logo IS NOT NULL AND logo != ''", "tanggal DESC", "1");

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
        
        // Memeriksa apakah user menghapus logo
        if (isset($_POST['remove_logo']) && $_POST['remove_logo'] === 'true') {
            // Jika user menghapus logo, jangan menetapkan path logo
            $file_destination_logo = null;
        } else {
            // Jika user tidak menghapus logo, periksa apakah ada file logo yang diunggah
            if (!empty($_FILES['logo']['name'])) {
                // Proses upload logo baru
                $file_tmp = $_FILES['logo']['tmp_name'];
                $file_type = $_FILES['logo']['type'];
                $file_size = $_FILES['logo']['size'];
                $max_file_size = 2 * 1024 * 1024; // 2MB

                // Pastikan jenis file dan ukuran file sesuai
                if (in_array($file_type, array('image/jpeg', 'image/png', 'image/gif')) && $file_size <= $max_file_size) {
                    // Generate nama file acak dan unik
                    $file_extension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
                    $file_name = uniqid() . '_' . date('Ymd') . '.' . $file_extension;
                    $file_destination_logo = "assets/image/uploads/logo/" . $file_name;

                    // Pindahkan file dari temp ke lokasi tujuan
                    if (move_uploaded_file($file_tmp, $file_destination_logo)) {
                        // File logo berhasil diunggah dan dipindahkan
                    } else {
                        $_SESSION['error_message'] = "Gagal menyimpan file gambar logo.";
                        // Handle error, misalnya redirect ke halaman add.php dengan pesan error
                        header("Location: " . base_url("pages/quotation/add/$category_param"));
                    }
                } else {
                    $_SESSION['error_message'] = "Ukuran file yang diunggah melebihi batas maksimal (2MB). Hanya gambar dengan format JPG, PNG, atau GIF yang diperbolehkan.";
                    // Handle error, misalnya redirect ke halaman add.php dengan pesan error
                    header("Location: " . base_url("pages/quotation/add/$category_param"));
                }
            } else {
                // Jika input file logo kosong dan logo tidak dihapus
                // Set nilai file_destination_logo sesuai dengan default logo path
                $file_destination_logo = $defaultLogoPath;
            }
        }

        // Memeriksa apakah user menghapus signature
        if (isset($_POST['remove_signature']) && $_POST['remove_signature'] === 'true') {
            // Jika user menghapus signature, jangan menetapkan path signature
            $file_destination_signature = null;
        } else {
            // Jika user tidak menghapus signature, periksa apakah ada file signature yang diunggah
            if (!empty($_FILES['signature']['name'])) {
                // Proses upload signature baru
                $file_tmp = $_FILES['signature']['tmp_name'];
                $file_type = $_FILES['signature']['type'];
                $file_size = $_FILES['signature']['size'];
                $max_file_size = 2 * 1024 * 1024; // 2MB

                // Pastikan jenis file dan ukuran file sesuai
                if (in_array($file_type, array('image/jpeg', 'image/png', 'image/gif')) && $file_size <= $max_file_size) {
                    // Generate nama file acak dan unik
                    $file_extension = pathinfo($_FILES['signature']['name'], PATHINFO_EXTENSION);
                    $file_name = uniqid() . '_' . date('Ymd') . '.' . $file_extension;
                    $file_destination_signature = "assets/image/uploads/signature/" . $file_name;

                    // Pindahkan file dari temp ke lokasi tujuan
                    if (move_uploaded_file($file_tmp, $file_destination_signature)) {
                        // File logo berhasil diunggah dan dipindahkan
                    } else {
                        $_SESSION['error_message'] = "Gagal menyimpan file signature.";
                        // Handle error, misalnya redirect ke halaman add.php dengan pesan error
                        header("Location: " . base_url("pages/quotation/add/$category_param"));
                    }
                } else {
                    $_SESSION['error_message'] = "Ukuran file yang diunggah melebihi batas maksimal (2MB). Hanya gambar dengan format JPG, PNG, atau GIF yang diperbolehkan.";
                    // Handle error, misalnya redirect ke halaman add.php dengan pesan error
                    header("Location: " . base_url("pages/quotation/add/$category_param"));
                }
            } else {
                // Jika input file signature kosong dan signature tidak dihapus
                // Set nilai file_destination_signature sesuai dengan default signature path
                $file_destination_signature = $defaultSignaturePath;
            }
        }
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
        'signature_info' => $signature_info,
        'kategori' => $kategori,
        'status' => 'draft'
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

            // Unformat harga satuan sebelum menyimpan ke database
            $harga_satuan_unformatted = unformatRupiah($harga_satuan[$i]);

            // Data untuk disimpan
            $detail_produk = [
                'id_detail_penawaran' => $id_detail_penawaran,
                'id_penawaran' => $id_penawaran,
                'id_produk' => $id_produk[$i], // Menggunakan indeks yang sama untuk setiap array
                'jumlah' => $jumlah[$i], // Menggunakan indeks yang sama untuk setiap array
                'harga_satuan' => $harga_satuan_unformatted // Menggunakan nilai unformat untuk harga satuan
            ];

            // Insert data detail produk ke dalam database
            $detail_result = insertData('detail_penawaran', $detail_produk);

            // Jika gagal menyimpan,
            if (!$detail_result) {
                $_SESSION['error_message'] = "Gagal menyimpan data detail produk.";
                header("Location: " . base_url("pages/quotation/add/$category_param"));
                exit();
            }
        }

        // Pencatatan log aktivitas
        $aktivitas = 'Berhasil membuat penawaran harga baru';
        $tabel = 'penawaran_harga';
        $keterangan = 'Pengguna dengan ID ' . $id_pengguna . ' berhasil membuat penawaran harga baru dengan ID ' . $id_penawaran;
        $log_data = [
            'id_pengguna' => $id_pengguna,
            'aktivitas' => $aktivitas,
            'tabel' => $tabel,
            'keterangan' => $keterangan
        ];
        insertData('log_aktivitas', $log_data);
        
        // Jika berhasil disimpan, arahkan pengguna ke halaman detail
        $_SESSION['success_message'] = "Berhasil menyimpan data penawaran harga.";
        header("Location: " . base_url("pages/quotation/detail/$category_param/$id_penawaran"));
        exit();
    } else {
        $_SESSION['error_message'] = "Gagal menyimpan data penawaran harga.";
        header("Location: " . base_url("pages/quotation/add/$category_param"));
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
    $kategori = $_POST['kategori'];
    $status = $_POST['status'];

    // Tentukan $category_param berdasarkan nilai $kategori
    $category_param = $kategori === "keluar" ? "outgoing" : ($kategori === "masuk" ? "incoming" : die("Invalid category"));
        
    // Inisialisasi variabel $file_destination_logo dan $file_destination_signature
    $file_destination_logo = $file_destination_signature = null;

    if ($category_param === 'outgoing') {
        // Inisialisasi nilai defaultLogoPath dan defaultSignaturePath untuk dokumen outgoing
        $defaultLogoPath = $defaultSignaturePath = null;
        
        // Cek apakah nilai-nilai ini ada di POST
        $defaultLogoPath = isset($_POST['default_logo_path']) ? $_POST['default_logo_path'] : null;
        $signature_info = isset($_POST['signature_info']) ? $_POST['signature_info'] : '';

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
        
        // Memeriksa apakah user menghapus logo
        if (isset($_POST['remove_logo']) && $_POST['remove_logo'] === 'true') {
            // Jika user menghapus logo, jangan menetapkan path logo
            $file_destination_logo = null;
        } else {
            // Jika user tidak menghapus logo, periksa apakah ada file logo yang diunggah
            if (!empty($_FILES['logo']['name'])) {
                // Proses upload logo baru
                $file_tmp = $_FILES['logo']['tmp_name'];
                $file_type = $_FILES['logo']['type'];
                $file_size = $_FILES['logo']['size'];
                $max_file_size = 2 * 1024 * 1024; // 2MB

                // Pastikan jenis file dan ukuran file sesuai
                if (in_array($file_type, array('image/jpeg', 'image/png', 'image/gif')) && $file_size <= $max_file_size) {
                    // Generate nama file acak dan unik
                    $file_extension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
                    $file_name = uniqid() . '_' . date('Ymd') . '.' . $file_extension;
                    $file_destination_logo = "assets/image/uploads/logo/" . $file_name;

                    // Pindahkan file dari temp ke lokasi tujuan
                    if (move_uploaded_file($file_tmp, $file_destination_logo)) {
                        // File logo berhasil diunggah dan dipindahkan
                    } else {
                        $_SESSION['error_message'] = "Gagal menyimpan file gambar logo.";
                        // Handle error, misalnya redirect ke halaman add.php dengan pesan error
                        header("Location: " . base_url("pages/quotation/add/$category_param"));
                    }
                } else {
                    $_SESSION['error_message'] = "Ukuran file yang diunggah melebihi batas maksimal (2MB). Hanya gambar dengan format JPG, PNG, atau GIF yang diperbolehkan.";
                    // Handle error, misalnya redirect ke halaman add.php dengan pesan error
                    header("Location: " . base_url("pages/quotation/add/$category_param"));
                }
            } else {
                // Jika input file logo kosong dan logo tidak dihapus
                // Set nilai file_destination_logo sesuai dengan default logo path
                $file_destination_logo = $defaultLogoPath;
            }
        }

        // Memeriksa apakah user menghapus signature
        if (isset($_POST['remove_signature']) && $_POST['remove_signature'] === 'true') {
            // Jika user menghapus signature, jangan menetapkan path signature
            $file_destination_signature = null;
        } else {
            // Jika user tidak menghapus signature, periksa apakah ada file signature yang diunggah
            if (!empty($_FILES['signature']['name'])) {
                // Proses upload signature baru
                $file_tmp = $_FILES['signature']['tmp_name'];
                $file_type = $_FILES['signature']['type'];
                $file_size = $_FILES['signature']['size'];
                $max_file_size = 2 * 1024 * 1024; // 2MB

                // Pastikan jenis file dan ukuran file sesuai
                if (in_array($file_type, array('image/jpeg', 'image/png', 'image/gif')) && $file_size <= $max_file_size) {
                    // Generate nama file acak dan unik
                    $file_extension = pathinfo($_FILES['signature']['name'], PATHINFO_EXTENSION);
                    $file_name = uniqid() . '_' . date('Ymd') . '.' . $file_extension;
                    $file_destination_signature = "assets/image/uploads/signature/" . $file_name;

                    // Pindahkan file dari temp ke lokasi tujuan
                    if (move_uploaded_file($file_tmp, $file_destination_signature)) {
                        // File logo berhasil diunggah dan dipindahkan
                    } else {
                        $_SESSION['error_message'] = "Gagal menyimpan file signature.";
                        // Handle error, misalnya redirect ke halaman add.php dengan pesan error
                        header("Location: " . base_url("pages/quotation/add/$category_param"));
                    }
                } else {
                    $_SESSION['error_message'] = "Ukuran file yang diunggah melebihi batas maksimal (2MB). Hanya gambar dengan format JPG, PNG, atau GIF yang diperbolehkan.";
                    // Handle error, misalnya redirect ke halaman add.php dengan pesan error
                    header("Location: " . base_url("pages/quotation/add/$category_param"));
                }
            } else {
                // Jika input file signature kosong dan signature tidak dihapus
                // Set nilai file_destination_signature sesuai dengan default signature path
                $file_destination_signature = $defaultSignaturePath;
            }
        }
    }

    // Ambil nilai-nilai dari input signature info
    $signing_location = strtolower($_POST['signing_location']);
    $signing_date = $_POST['signing_date'];
    $signer_name = strtolower($_POST['signer_name']);
    $signer_position = strtolower($_POST['signer_position']);

    // Susun data signature info sebagai string
    $signature_info = "Location: $signing_location, Date: $signing_date, Name: $signer_name, Position: $signer_position, Path: $file_destination_signature";

    // Ambil data lama sebelum diubah
    $oldDataPH = selectData('penawaran_harga', 'id_penawaran = ?', '', '', [['type' => 's', 'value' => $id_penawaran]]);


    // Bangun data untuk pembaruan penawaran_harga
    $data = [
        'id_pengirim' => $pengirim,
        'no_penawaran' => $no_penawaran,
        'tanggal' => $tanggal,
        'total' => $total,
        'catatan' => $catatan,
        'id_penerima' => $penerima,
        'up' => $up,
        'diskon' => $diskon,
        'id_ppn' => $jenis_ppn,
        'logo' => $file_destination_logo, // tambahkan lokasi file logo ke dalam data yang akan diupdate
        'signature_info' => $signature_info,
        'kategori' => $kategori,
        'status' => $status
    ];
    
    $conditions = "id_penawaran = '$id_penawaran'";

    $result = updateData('penawaran_harga', $data, $conditions);

    $newDataPH = selectData('penawaran_harga', 'id_penawaran = ?', '', '', [['type' => 's', 'value' => $id_penawaran]]);

    // Data sebelum dan sesudah perubahan untuk log
    $before = $oldDataPH[0]; // Ambil baris pertama dari hasil query
    $after = $newDataPH[0]; // Ambil baris pertama dari hasil query

    // Keterangan perubahan
    $changeDescription = "Perubahan data penawaran harga: | ";

    // Periksa setiap kolom untuk menemukan perubahan
    $counter = 1;
    foreach ($before as $column => $value) {
        if ($value !== $after[$column]) {
            $changeDescription .= "$counter. $column: \"$value\" diubah menjadi \"$after[$column]\" | ";
            $counter++;
        }
    }
    
    // Catat aktivitas
    $logData = [
      'id_pengguna' => $id_pengguna, // pastikan ini sesuai dengan session atau cara penyimpanan ID pengguna di aplikasi kamu
      'aktivitas' => 'Ubah Data PH',
      'tabel' => 'penawaran harga',
      'keterangan' => $changeDescription,
    ];

    insertData('log_aktivitas', $logData);

    // Periksa apakah pembaruan penawaran_harga berhasil
    if (!$result) {
        // Jika gagal, tampilkan pesan kesalahan atau arahkan pengguna kembali ke halaman edit
        $_SESSION['error_message'] = "Gagal memperbarui penawaran harga.";
        header("Location: " . base_url("pages/quotation/edit/$category_param/$id_penawaran"));
        exit();
    }
    
    // Lanjutkan edit detail

    // Peroleh nilai-nilai dari detail penawaran
    $id_detail = $_POST['id_detail_penawaran'];
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];
    $harga_satuan = $_POST['harga_satuan'];
    $deleted_rows = isset($_POST['deleted_rows']) ? $_POST['deleted_rows'] : [];

    // Simpan semua data detail ke dalam array utama
    $all_details = [];
    foreach ($id_detail as $index => $detail_id) {
        $all_details[] = [
            'id_detail_penawaran' => $detail_id,
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
        // Hanya hapus baris jika id_detail_penawaran tidak mengandung "newId" di awal id-nya
        if (strpos($deleted_row, "newId") !== 0) {
            deleteData('detail_penawaran', "id_detail_penawaran = '$deleted_row'");
        }
    }

    // Persiapkan data untuk update dan insert
    $add_detail = [];
    $update_detail = [];

    // Kelompokkan detail berdasarkan id
    foreach ($all_details as $detail) {
        if (strpos($detail['id_detail_penawaran'], "newId") === 0) {
            // Jika id_detail_penawaran mengandung "newId", itu adalah baris baru
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
        try {
            // Generate UUID untuk ID baru
            $new_id_detail = Ramsey\Uuid\Uuid::uuid4()->toString();
            $data = [
                'id_produk' => $detail['id_produk'],
                'jumlah' => $detail['jumlah'],
                'harga_satuan' => $detail['harga_satuan'],
                // tambahkan kolom lain yang diperlukan sesuai dengan struktur tabel
                'id_detail_penawaran' => $new_id_detail,
                'id_penawaran' => $id_penawaran,
            ];
            insertData('detail_penawaran', $data);
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Gagal menambah data detail: " . $e->getMessage();
            header("Location: " . base_url("pages/quotation/$category_param"));
            exit();
        }
    }

    // Lakukan operasi ubah data
    foreach ($update_detail as $detail) {
        try {
            $data = [
                'id_produk' => $detail['id_produk'],
                'jumlah' => $detail['jumlah'],
                'harga_satuan' => $detail['harga_satuan'],
                // tambahkan kolom lain yang diperlukan sesuai dengan struktur tabel
            ];
            updateData('detail_penawaran', $data, "id_detail_penawaran = '{$detail['id_detail_penawaran']}'");
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Gagal mengubah data detail: " . $e->getMessage();
            header("Location: " . base_url("pages/quotation/$category_param"));
            exit();
        }
    }

    // Jika berhasil disimpan, arahkan pengguna ke halaman detail
    $_SESSION['success_message'] = "Berhasil mengubah data penawaran harga.";
    header("Location: " . base_url("pages/quotation/detail/$category_param/$id_penawaran"));
    exit();
} else {
    // Jika tidak ada data yang diterima, arahkan ke index.php
    header("Location: " . base_url("pages/quotation/$category_param"));
    exit();
}

mysqli_close($conn);
?>