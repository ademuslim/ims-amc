<?php
// master-data/contact-bank-accounts/process.php
require '../../../includes/function.php';
require '../../../includes/vendor/autoload.php';

// Ambil id_pengguna dari sesi atau cookie untuk pencatatan log aktivitas
$id_pengguna = $_SESSION['id_pengguna'] ?? $_COOKIE['ingat_user_id'] ?? '';

if (isset($_POST['add'])) {
    $id_kontak = $_POST['id_kontak'];
    $nama_bank = strtolower($_POST['nama_bank']);
    $nomor_rekening = $_POST['nomor_rekening'];
    $pemegang_rekening = strtolower($_POST['pemegang_rekening']);
    $kode_swift = $_POST['kode_swift'] ?? '';
    $cabang_bank = strtolower($_POST['cabang_bank'] ?? '');
    $keterangan = strtolower($_POST['keterangan'] ?? '');

    // Periksa apakah nomor rekening sudah ada
    if (isValueExists('rekening_kontak', 'nomor_rekening', $nomor_rekening)) {
        $_SESSION['error_message'] = "Nomor rekening sudah ada dalam database.";
            header("Location: " . base_url("pages/master-data/contact-bank-accounts"));

        exit();
    }

    // Generate UUID untuk kolom id_rekening
    $id_rekening = Ramsey\Uuid\Uuid::uuid4()->toString();

    // Data yang akan dimasukkan ke dalam tabel rekening_kontak
    $data = [
        'id_rekening' => $id_rekening,
        'id_kontak' => $id_kontak,
        'nama_bank' => $nama_bank,
        'nomor_rekening' => $nomor_rekening,
        'pemegang_rekening' => $pemegang_rekening,
        'kode_swift' => $kode_swift,
        'cabang_bank' => $cabang_bank,
        'keterangan' => $keterangan,
        'status_hapus' => 0
    ];

    // Panggil fungsi insertData untuk menambahkan data ke dalam tabel rekening_kontak
    $result = insertData('rekening_kontak', $data);

    // Periksa apakah data berhasil ditambahkan
    if ($result > 0) {
        $_SESSION['success_message'] = "Rekening kontak berhasil ditambahkan!";

        // Pencatatan log aktivitas
        $aktivitas = 'Berhasil tambah data';
        $tabel = 'Rekening Kontak';
        $keterangan_log = "Berhasil tambah rekening kontak dengan ID $id_rekening";
        $log_data = [
            'id_pengguna' => $id_pengguna,
            'aktivitas' => $aktivitas,
            'tabel' => $tabel,
            'keterangan' => $keterangan_log
        ];
        insertData('log_aktivitas', $log_data);
    } else {
        $_SESSION['error_message'] = "Terjadi kesalahan saat menambahkan rekening kontak.";
    }
}
// Edit Data Rekening Kontak
elseif (isset($_POST['edit'])) {
    // Ambil nilai-nilai dari form edit
    $id_rekening = $_POST['id_rekening'];
    $id_kontak = $_POST['id_kontak'];
    $nama_bank = strtolower($_POST['nama_bank']);
    $nomor_rekening = $_POST['nomor_rekening'];
    $pemegang_rekening = strtolower($_POST['pemegang_rekening']);
    $kode_swift = $_POST['kode_swift'] ?? '';
    $cabang_bank = strtolower($_POST['cabang_bank'] ?? '');
    $keterangan = strtolower($_POST['keterangan'] ?? '');

    // Periksa apakah nomor rekening sudah ada (kecuali untuk rekening yang sedang diedit)
    if (isValueExists('rekening_kontak', 'nomor_rekening', $nomor_rekening, $id_rekening, 'id_rekening')) {
        $_SESSION['error_message'] = "Nomor rekening sudah ada dalam database.";
            header("Location: " . base_url("pages/master-data/contact-bank-accounts"));

        exit();
    }

    // Ambil data lama sebelum diubah
    $oldData = selectData('rekening_kontak', 'id_rekening = ?', '', '', [['type' => 's', 'value' => $id_rekening]]);

    // Data yang akan diupdate di tabel rekening_kontak
    $data = [
        'id_kontak' => $id_kontak,
        'nama_bank' => $nama_bank,
        'nomor_rekening' => $nomor_rekening,
        'pemegang_rekening' => $pemegang_rekening,
        'kode_swift' => $kode_swift,
        'cabang_bank' => $cabang_bank,
        'keterangan' => $keterangan
    ];

    // Kondisi untuk menentukan rekening kontak mana yang akan diupdate
    $conditions = "id_rekening = '$id_rekening'";

    // Panggil fungsi updateData untuk mengupdate data di tabel rekening_kontak
    $result = updateData('rekening_kontak', $data, $conditions);

    // Periksa apakah data berhasil diupdate
    if ($result > 0) {
        $_SESSION['success_message'] = "Rekening kontak berhasil diupdate!";

        // Ambil data setelah diubah
        $newData = selectData('rekening_kontak', 'id_rekening = ?', '', '', [['type' => 's', 'value' => $id_rekening]]);

        // Data sebelum dan sesudah perubahan untuk log
        $before = $oldData[0]; // Ambil baris pertama dari hasil query
        $after = $newData[0]; // Ambil baris pertama dari hasil query

        // Keterangan perubahan
        $changeDescription = "Perubahan data rekening kontak dengan ID $id_rekening: | ";

        // Nomor urut untuk tanda "-"
        $counter = 1;
        // Periksa setiap kolom untuk menemukan perubahan
        foreach ($before as $column => $value) {
            if ($value !== $after[$column]) {
                $changeDescription .= "$counter. $column: \"$value\" diubah menjadi \"$after[$column]\" | ";
                $counter++;
            }
        }
        
        // Catat aktivitas
        $logData = [
            'id_pengguna' => $id_pengguna,
            'aktivitas' => 'Berhasil ubah data',
            'tabel' => 'Rekening Kontak',
            'keterangan' => $changeDescription,
        ];

        insertData('log_aktivitas', $logData);
    } else {
        $_SESSION['error_message'] = "Terjadi kesalahan saat mengupdate rekening kontak.";
    }
} else {
    // Jika tidak ada permintaan tambah atau edit, simpan pesan error ke dalam session
    $_SESSION['error_message'] = "Permintaan tidak valid!";
}

// Tutup koneksi ke database
mysqli_close($conn);

// Redirect kembali ke index.php setelah proses selesai
    header("Location: " . base_url("pages/master-data/contact-bank-accounts"));

exit();