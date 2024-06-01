<?php
require '../../includes/function.php';
require '../../includes/vendor/autoload.php';

// Ambil id_pengguna dari sesi atau cookie untuk pencatatan log aktivitas
$id_pengguna = $_SESSION['id_pengguna'] ?? $_COOKIE['ingat_user_id'] ?? '';

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
        
        // Inisialisasi variabel $file_destination_logo dan $file_destination_signature
        $file_destination_logo = $file_destination_signature = null;
        
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
                    $file_destination_logo = "../../assets/image/uploads/logo/" . $file_name;

                    // Pindahkan file dari temp ke lokasi tujuan
                    if (move_uploaded_file($file_tmp, $file_destination_logo)) {
                        // File logo berhasil diunggah dan dipindahkan
                    } else {
                        $_SESSION['error_message'] = "Gagal menyimpan file gambar logo.";
                        // Handle error, misalnya redirect ke halaman add.php dengan pesan error
                    }
                } else {
                    $_SESSION['error_message'] = "Ukuran file yang diunggah melebihi batas maksimal (2MB). Hanya gambar dengan format JPG, PNG, atau GIF yang diperbolehkan.";
                    // Handle error, misalnya redirect ke halaman add.php dengan pesan error
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
                    $file_destination_signature = "../../assets/image/uploads/signature/" . $file_name;

                    // Pindahkan file dari temp ke lokasi tujuan
                    if (move_uploaded_file($file_tmp, $file_destination_signature)) {
                        // File logo berhasil diunggah dan dipindahkan
                    } else {
                        $_SESSION['error_message'] = "Gagal menyimpan file signature.";
                        // Handle error, misalnya redirect ke halaman add.php dengan pesan error
                    }
                } else {
                    $_SESSION['error_message'] = "Ukuran file yang diunggah melebihi batas maksimal (2MB). Hanya gambar dengan format JPG, PNG, atau GIF yang diperbolehkan.";
                    // Handle error, misalnya redirect ke halaman add.php dengan pesan error
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
        'status' => 'tunggu kirim'
    ];

    $result = insertData('faktur', $data);

    if ($result) {
        $id_produk = $_POST['id_produk'];
        $jumlah = $_POST['jumlah'];
        $harga_satuan = $_POST['harga_satuan'];
        $id_pesanan = $_POST['id_pesanan'];

        // Loop untuk menyimpan setiap detail produk ke dalam database
        for ($i = 0; $i < count($id_produk); $i++) {
            // Generate UUID untuk id_detail_faktur
            $id_detail_faktur = Ramsey\Uuid\Uuid::uuid4()->toString();

            // Unformat harga satuan sebelum menyimpan ke database
            $harga_satuan_unformatted = unformatRupiah($harga_satuan[$i]);

            // Data detail untuk disimpan
            $detail_produk = [
                'id_detail_faktur' => $id_detail_faktur,
                'id_faktur' => $id_faktur,
                'id_produk' => $id_produk[$i], // Menggunakan indeks yang sama untuk setiap array
                'jumlah' => $jumlah[$i], // Menggunakan indeks yang sama untuk setiap array
                'harga_satuan' => $harga_satuan_unformatted, // Menggunakan nilai unformat untuk harga satuan
                
                'id_pesanan' => $id_pesanan[$i]
            ];

            try {
                // Tambah detail faktur
                insertData('detail_faktur', $detail_produk);
            } catch (mysqli_sql_exception $e) {
                $_SESSION['error_message'] = "Gagal menyimpan data detail produk: " . $e->getMessage();
                header("Location: add.php?category=$category_param");
                exit();
            }
        }

        // Ambil data detail faktur
        $detail_faktur = selectData('detail_faktur', "id_faktur = '$id_faktur'");

        // Loop melalui detail faktur
        foreach ($detail_faktur as $detail) {
            $id_produk_faktur = $detail['id_produk'];
            $jumlah_dikirim_baru = $detail['jumlah']; // Jumlah yang dikirim sesuai dengan detail faktur
            
            $id_pesanan = $detail['id_pesanan'];

            // Debugging: Periksa data yang diambil
            // echo "ID Produk Faktur: $id_produk_faktur, Jumlah Dikirim Baru: $jumlah_dikirim_baru, ID Pesanan: $id_pesanan<br>";

            // Ambil data detail pesanan pembelian berdasarkan id pesanan dan id produk
            $detail_pesanan = selectData('detail_pesanan', "id_pesanan = '$id_pesanan' AND id_produk = '$id_produk_faktur'");
            
            if (!empty($detail_pesanan)) {

                // Update tabel detail_pesanan
                $jumlah_pesanan = $detail_pesanan[0]['jumlah']; // Asumsi jumlah dari indeks pertama
                $jumlah_dikirim_sebelumnya = $detail_pesanan[0]['jumlah_dikirim']; // Asumsi jumlah dikirim sebelumnya dari indeks pertama

                // Debugging: Periksa jumlah pesanan dan jumlah dikirim sebelumnya
                // echo "Jumlah Pesanan: $jumlah_pesanan, Jumlah Dikirim Sebelumnya: $jumlah_dikirim_sebelumnya<br>";

                $jumlah_dikirim_total = $jumlah_dikirim_sebelumnya + $jumlah_dikirim_baru; // Jumlah total yang telah dikirim
                $sisa_pesanan = $jumlah_pesanan - $jumlah_dikirim_total; // Hitung sisa pesanan

                // Debugging: Periksa jumlah dikirim total dan sisa pesanan
                // echo "Jumlah Dikirim Total: $jumlah_dikirim_total, Sisa Pesanan: $sisa_pesanan<br>";

                // Pastikan sisa pesanan tidak negatif
                if ($sisa_pesanan < 0) {
                    $sisa_pesanan = 0;
                }

                // Lakukan update pada tabel detail_pesanan
                $update_data = [
                    'jumlah_dikirim' => $jumlah_dikirim_total,
                    'sisa_pesanan' => $sisa_pesanan
                ];

                // Lakukan update menggunakan fungsi updateData dari library Anda
                updateData('detail_pesanan', $update_data, "id_pesanan = '$id_pesanan' AND id_produk = '$id_produk_faktur'");
            } else {
                // Debugging: Jika tidak ada data detail pesanan yang ditemukan
                echo "Data detail pesanan tidak ditemukan untuk ID Pesanan: $id_pesanan dan ID Produk: $id_produk_faktur<br>";
            }
        }

        // Pencatatan log aktivitas
        $id_log = Ramsey\Uuid\Uuid::uuid4()->toString();
        $aktivitas = 'Berhasil membuat invoice baru';
        $tabel = 'faktur';
        $keterangan = 'Pengguna dengan ID ' . $id_pengguna . ' berhasil membuat invoice baru dengan ID ' . $id_faktur;
        $log_data = [
            'id_log' => $id_log,
            'id_pengguna' => $id_pengguna,
            'aktivitas' => $aktivitas,
            'tabel' => $tabel,
            'keterangan' => $keterangan
        ];
        insertData('log_aktivitas', $log_data);

        $_SESSION['success_message'] = "Invoice berhasil dibuat.";
        header("Location: detail.php?category=$category_param&id=$id_faktur");
        exit();
    } else {
        $_SESSION['error_message'] = "Gagal menyimpan data faktur.";
        header("Location: add.php?category=$category_param");
        exit();
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
                    $file_destination_logo = "../../assets/image/uploads/logo/" . $file_name;

                    // Pindahkan file dari temp ke lokasi tujuan
                    if (move_uploaded_file($file_tmp, $file_destination_logo)) {
                        // File logo berhasil diunggah dan dipindahkan
                    } else {
                        $_SESSION['error_message'] = "Gagal menyimpan file gambar logo.";
                        // Handle error, misalnya redirect ke halaman add.php dengan pesan error
                        header("Location: add.php?category=$category_param");
                    }
                } else {
                    $_SESSION['error_message'] = "Ukuran file yang diunggah melebihi batas maksimal (2MB). Hanya gambar dengan format JPG, PNG, atau GIF yang diperbolehkan.";
                    // Handle error, misalnya redirect ke halaman add.php dengan pesan error
                    header("Location: add.php?category=$category_param");
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
                    $file_destination_signature = "../../assets/image/uploads/signature/" . $file_name;

                    // Pindahkan file dari temp ke lokasi tujuan
                    if (move_uploaded_file($file_tmp, $file_destination_signature)) {
                        // File logo berhasil diunggah dan dipindahkan
                    } else {
                        $_SESSION['error_message'] = "Gagal menyimpan file signature.";
                        // Handle error, misalnya redirect ke halaman add.php dengan pesan error
                        header("Location: add.php?category=$category_param");
                    }
                } else {
                    $_SESSION['error_message'] = "Ukuran file yang diunggah melebihi batas maksimal (2MB). Hanya gambar dengan format JPG, PNG, atau GIF yang diperbolehkan.";
                    // Handle error, misalnya redirect ke halaman add.php dengan pesan error
                    header("Location: add.php?category=$category_param");
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
    $oldDataFaktur = selectData('faktur', 'id_faktur = ?', '', '', [['type' => 's', 'value' => $id_faktur]]);

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
        'logo' => $file_destination_logo, // tambahkan lokasi file logo ke dalam data yang akan diupdate
        'signature_info' => $signature_info,
        'kategori' => $kategori,
        'status' => $status
    ];
    
    $conditions = "id_faktur = '$id_faktur'";

    $result = updateData('faktur', $data, $conditions);

    // Periksa apakah pembaruan faktur berhasil
    if (!$result) {
        // Jika gagal, tampilkan pesan kesalahan atau arahkan pengguna kembali ke halaman edit
        $_SESSION['error_message'] = "Gagal memperbarui faktur.";
        header("Location: index.php?category=$category_param");
        exit();
    }

    $newDataFaktur = selectData('faktur', 'id_faktur = ?', '', '', [['type' => 's', 'value' => $id_faktur]]);

    // Data sebelum dan sesudah perubahan untuk log
    $before = $oldDataFaktur[0]; // Ambil baris pertama dari hasil query
    $after = $newDataFaktur[0]; // Ambil baris pertama dari hasil query

    // Keterangan perubahan
    $changeDescription = "Perubahan data invoice: | ";

    // Periksa setiap kolom untuk menemukan perubahan
    $counter = 1;
    foreach ($before as $column => $value) {
        if ($value !== $after[$column]) {
            $changeDescription .= "$counter. $column: \"$value\" diubah menjadi \"$after[$column]\" | ";
            $counter++;
        }
    }
    
    // Catat aktivitas
    $id_log = Ramsey\Uuid\Uuid::uuid4()->toString();
    $logData = [
      'id_log' => $id_log,
      'id_pengguna' => $_SESSION['id_pengguna'], // pastikan ini sesuai dengan session atau cara penyimpanan ID pengguna di aplikasi kamu
      'aktivitas' => 'Ubah Data Invoice',
      'tabel' => 'faktur',
      'keterangan' => $changeDescription,
    ];

    insertData('log_aktivitas', $logData);
    
    // Lanjutkan edit detail
    // Peroleh nilai-nilai dari detail penawaran
    $id_detail = $_POST['id_detail_faktur'];
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];
    $harga_satuan = $_POST['harga_satuan'];
    $id_pesanan = $_POST['id_pesanan'];

    $deleted_rows = isset($_POST['deleted_rows']) ? $_POST['deleted_rows'] : [];

    // Simpan semua data detail ke dalam array utama
    $all_details = [];
    foreach ($id_detail as $index => $detail_id) {
        $all_details[] = [
            'id_detail_faktur' => $detail_id,
            'id_produk' => $id_produk[$index],
            'jumlah' => $jumlah[$index],
            'harga_satuan' => unformatRupiah($harga_satuan[$index]),
            'id_pesanan' => $id_pesanan[$index]
        ];
    }

    // Hapus baris yang ada dalam deleted_rows
    foreach ($deleted_rows as $deleted_row) {
        // Hanya hapus baris jika id_detail_faktur tidak mengandung "newId" di awal id-nya
        if (strpos($deleted_row, "newId") !== 0) {
            // Ambil detail faktur sebelum dihapus
            $query = "SELECT * FROM detail_faktur WHERE id_detail_faktur = '$deleted_row'";
            $result = mysqli_query($conn, $query);
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $jumlah_dikirim = $row['jumlah'];
                $id_produk = $row['id_produk'];
                $id_pesanan = $row['id_pesanan'];

                // Kembalikan jumlah_dikirim dan sisa_pesanan pada detail_pesanan
                updateDetailPesanan($id_produk, $id_pesanan, -$jumlah_dikirim);
            }

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

    // Lakukan operasi tambah data
    foreach ($add_detail as $detail) {
        // Generate UUID untuk ID baru
        $new_id_detail = Ramsey\Uuid\Uuid::uuid4()->toString();
        $data = [
            'id_produk' => $detail['id_produk'],
            'jumlah' => $detail['jumlah'],
            'harga_satuan' => $detail['harga_satuan'],
            'id_pesanan' => $detail['id_pesanan'],
            // tambahkan kolom lain yang diperlukan sesuai dengan struktur tabel
            'id_detail_faktur' => $new_id_detail,
            'id_faktur' => $id_faktur,
        ];
        insertData('detail_faktur', $data);

        // Update detail_pesanan untuk penambahan baru
        updateDetailPesanan($detail['id_produk'], $detail['id_pesanan'], $detail['jumlah']);
    }
    

    // Lakukan operasi ubah data
    foreach ($update_detail as $detail) {
        $query = "SELECT jumlah FROM detail_faktur WHERE id_detail_faktur = '{$detail['id_detail_faktur']}'";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $jumlah_sebelumnya = $row['jumlah'];
            $perbedaan_jumlah = $detail['jumlah'] - $jumlah_sebelumnya;

            $data = [
                'id_produk' => $detail['id_produk'],
                'jumlah' => $detail['jumlah'],
                'harga_satuan' => $detail['harga_satuan'],
                'id_pesanan' => $detail['id_pesanan'],
            ];
            updateData('detail_faktur', $data, "id_detail_faktur = '{$detail['id_detail_faktur']}'");

            // Update jumlah dikirim dan sisa pesanan di tabel detail_pesanan
            updateDetailPesanan($detail['id_produk'], $detail['id_pesanan'], $perbedaan_jumlah);
        }
    }

    // echo "Operasi tambah dan ubah data selesai.";
    $_SESSION['success_message'] = "Invoice berhasil diupdate.";
    // Redirect ke halaman detail setelah proses edit selesai
    header("Location: detail.php?category=$category_param&id=$id_faktur");
    exit();

} else {
    // Jika tidak ada data yang diterima, arahkan ke index.php
    header("Location: index.php?category=" . $category_param);
    exit();
}

mysqli_close($conn);
?>