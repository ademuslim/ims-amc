<?php
require '../../includes/function.php';
require '../../includes/vendor/autoload.php';
if (isset($_POST['add'])) {
    $pengirim = $_POST['pengirim'];
    $no_pesanan = strtolower($_POST['no_po']);
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

    // Buat ID PO baru
    $id_pesanan = Ramsey\Uuid\Uuid::uuid4()->toString();

    $data = [
        'id_pesanan' => $id_pesanan,
        'id_pengirim' => $pengirim,
        'no_pesanan' => $no_pesanan,
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

    $result = insertData('pesanan_pembelian', $data);

    // Lanjut insert data detail jika insert data po berhasil

    if ($result) {
        $id_produk = $_POST['id_produk'];
        $jumlah = $_POST['jumlah'];
        $harga_satuan = $_POST['harga_satuan'];
        $id_penawaran = $_POST['id_penawaran'];

        // Penanganan PO Open
        $jumlah_dikirim = 0;

        // Loop untuk menyimpan setiap detail produk ke dalam database
        for ($i = 0; $i < count($id_produk); $i++) {
            // Generate UUID untuk id_detail_pesanan
            $id_detail_pesanan = Ramsey\Uuid\Uuid::uuid4()->toString();

            // Unformat harga satuan sebelum menyimpan ke database
            $harga_satuan_unformatted = unformatRupiah($harga_satuan[$i]);

            // Data untuk disimpan
            $detail_produk = [
                'id_detail_pesanan' => $id_detail_pesanan,
                'id_pesanan' => $id_pesanan,
                'id_produk' => $id_produk[$i], // Menggunakan untuk mencegah masalah
                'jumlah' => $jumlah[$i],
                'harga_satuan' => $harga_satuan_unformatted,
                
                'jumlah_dikirim' => $jumlah_dikirim,
                'sisa_pesanan' => $jumlah[$i], // Set default karena belum ada pengiriman maka sisa = jumlah
                'id_penawaran' => $id_penawaran[$i]
            ];

            try {
                insertData('detail_pesanan', $detail_produk);
            } catch (mysqli_sql_exception $e) {
                $_SESSION['error_message'] = "Gagal menyimpan data detail produk: " . $e->getMessage();
                header("Location: add.php?category=$category_param");
                exit();
            }
        }
        header("Location: detail.php?category=$category_param&id=$id_pesanan");
        exit();
    } else {
        $_SESSION['error_message'] = "Gagal menyimpan data pesanan pembelian.";
        header("Location: add.php?category=$category_param");
        exit();
    }
// Edit Data
} elseif (isset($_POST['edit'])) {
    $id_pesanan = $_POST['id_pesanan'];
    $pengirim = $_POST['pengirim'];
    $tanggal = $_POST['tanggal'];
    $no_pesanan = strtolower($_POST['no_po']);
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

    // Bangun data untuk pembaruan po
    $data = [
        'id_pengirim' => $pengirim,
        'no_pesanan' => $no_pesanan,
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
    
    $conditions = "id_pesanan = '$id_pesanan'";

    $result = updateData('pesanan_pembelian', $data, $conditions);

    // Periksa apakah pembaruan pesanan_harga berhasil
    if (!$result) {
        // Jika gagal, tampilkan pesan kesalahan atau arahkan pengguna kembali ke halaman edit
        $_SESSION['error_message'] = "Gagal memperbarui pesanan harga.";
        header("Location: index.php?category=$category_param");
        exit();
    }
    
    // Lanjutkan edit detail
    // Peroleh nilai-nilai dari detail pesanan
    $id_detail = $_POST['id_detail_pesanan'];
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];
    $harga_satuan = $_POST['harga_satuan'];

    $id_penawaran = $_POST['id_penawaran'];

    $deleted_rows = isset($_POST['deleted_rows']) ? $_POST['deleted_rows'] : [];

    // Simpan semua data detail ke dalam array utama
    $all_details = [];
    foreach ($id_detail as $index => $detail_id) {
        $all_details[] = [
            'id_detail_pesanan' => $detail_id,
            'id_produk' => $id_produk[$index],
            'jumlah' => $jumlah[$index],
            'harga_satuan' => unformatRupiah($harga_satuan[$index]),
            'id_penawaran' => $id_penawaran[$index]
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
        // Hanya hapus baris jika id_detail_pesanan tidak mengandung "newId" di awal id-nya
        if (strpos($deleted_row, "newId") !== 0) {
            deleteData('detail_pesanan', "id_detail_pesanan = '$deleted_row'");
        }
    }

    // Persiapkan data untuk update dan insert
    $add_detail = [];
    $update_detail = [];

    // Kelompokkan detail berdasarkan id
    foreach ($all_details as $detail) {
        if (strpos($detail['id_detail_pesanan'], "newId") === 0) {
            // Jika id_detail_pesanan mengandung "newId", itu adalah baris baru
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
            'id_penawaran' => $detail['id_penawaran'],
            // tambahkan kolom lain yang diperlukan sesuai dengan struktur tabel
            'id_detail_pesanan' => $new_id_detail,
            'id_pesanan' => $id_pesanan,
        ];
        insertData('detail_pesanan', $data);
    }

    // Lakukan operasi ubah data
    foreach ($update_detail as $detail) {
        $data = [
            'id_produk' => $detail['id_produk'],
            'jumlah' => $detail['jumlah'],
            'harga_satuan' => $detail['harga_satuan'],
            'id_penawaran' => $detail['id_penawaran'],
            // tambahkan kolom lain yang diperlukan sesuai dengan struktur tabel
        ];
        updateData('detail_pesanan', $data, "id_detail_pesanan = '{$detail['id_detail_pesanan']}'");
    }

    // echo "Operasi tambah dan ubah data selesai.";

    // Redirect ke halaman detail setelah proses edit selesai
    header("Location: detail.php?category=$category_param&id=$id_pesanan");
    exit();
} else {
    // Jika tidak ada data yang diterima, arahkan ke index.php
    header("Location: index.php?category=" . $category_param);
    exit();
}

mysqli_close($conn);
?>