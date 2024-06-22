<?php
require '../../includes/function.php';
require '../../includes/vendor/autoload.php';

// Ambil id_pengguna dari sesi atau cookie untuk pencatatan log aktivitas
$id_pengguna = $_SESSION['id_pengguna'] ?? $_COOKIE['ingat_user_id'] ?? '';

// Pastikan form sudah dikirim dengan metode POST dan variabel yang diperlukan ada
if (isset($_POST['approve'])) {
    $id_detail_faktur = $_POST['id_detail_faktur'] ?? null;
    $approved_name = $_POST['approved_name'] ?? '';
    $checked_name = $_POST['checked_name'] ?? '';
    $issued_name = $_POST['issued_name'] ?? '';
    $category = $_POST['category'] ?? '';

    // Direktori untuk menyimpan file signature
    $uploadDir = '../../assets/image/uploads/signature/';
    // Maksimum ukuran file yang diizinkan
    $maxFileSize = 2 * 1024 * 1024; // 2MB

    // Fungsi untuk mengunggah file signature
    function uploadSignature($fileInputName, $uploadDir, $maxFileSize, $type) {
        // Memastikan direktori tujuan ada
        if (!file_exists($uploadDir) && !is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                error_log("Gagal membuat direktori: $uploadDir");
                $_SESSION['error_message'] = "Gagal membuat direktori untuk menyimpan file signature.";
                return null;
            }
        }

        if (!empty($_FILES[$fileInputName]['name'])) {
            $file_tmp = $_FILES[$fileInputName]['tmp_name'];
            $file_type = $_FILES[$fileInputName]['type'];
            $file_size = $_FILES[$fileInputName]['size'];

            // Validasi tipe dan ukuran file
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($file_type, $allowedTypes) && $file_size <= $maxFileSize) {
                // Generate nama file acak dan unik
                $file_extension = pathinfo($_FILES[$fileInputName]['name'], PATHINFO_EXTENSION);
                $file_name = uniqid() . '_' . date('Ymd') . '.' . $file_extension;
                $file_destination = $uploadDir . $file_name;

                // Pindahkan file ke lokasi tujuan
                if (move_uploaded_file($file_tmp, $file_destination)) {
                    return $file_name; // Mengembalikan nama file untuk disimpan di database
                } else {
                    error_log("Gagal menyimpan file signature $type ke $file_destination.");
                    $_SESSION['error_message'] = "Gagal menyimpan file signature $type.";
                    return null;
                }
            } else {
                error_log("Ukuran atau tipe file signature $type tidak sesuai.");
                $_SESSION['error_message'] = "Ukuran atau tipe file signature $type tidak sesuai.";
                return null;
            }
        }
        return null;
    }

    // Fungsi untuk mendapatkan nama file dari path yang ada
    function normalizePath($path, $uploadDir) {
        if (empty($path)) {
            return null;
        }
        // Mengambil nama file dari path yang ada
        return basename($path);
    }

    // Unggah file tanda tangan atau gunakan path yang sudah ada jika tidak ada file yang diunggah
    $approved_signature = uploadSignature('approved_signature', $uploadDir, $maxFileSize, 'approved') ?? normalizePath($_POST['existing_approved_path'] ?? '', $uploadDir);
    $checked_signature = uploadSignature('checked_signature', $uploadDir, $maxFileSize, 'checked') ?? normalizePath($_POST['existing_checked_path'] ?? '', $uploadDir);
    $issued_signature = uploadSignature('issued_signature', $uploadDir, $maxFileSize, 'issued') ?? normalizePath($_POST['existing_issued_path'] ?? '', $uploadDir);
    
    // Ambil data lama sebelum status diubah
    $oldDataDetailInv = selectData('detail_faktur', 'id_detail_faktur = ?', '', '', [['type' => 's', 'value' => $id_detail_faktur]]);

    // Buat string signature_confirm baru
    $signature_confirm = '';

    // Proses data Approved
    if ($approved_name || $approved_signature) {
        $signature_confirm .= "Approved Name: $approved_name";
        if ($approved_signature) {
            $signature_confirm .= ", Approved Path: $uploadDir$approved_signature";
        }
    }

    // Proses data Checked
    if ($checked_name || $checked_signature) {
        if ($signature_confirm) {
            $signature_confirm .= ", ";
        }
        $signature_confirm .= "Checked Name: $checked_name";
        if ($checked_signature) {
            $signature_confirm .= ", Checked Path: $uploadDir$checked_signature";
        }
    }

    // Proses data Issued
    if ($issued_name || $issued_signature) {
        if ($signature_confirm) {
            $signature_confirm .= ", ";
        }
        $signature_confirm .= "Issued Name: $issued_name";
        if ($issued_signature) {
            $signature_confirm .= ", Issued Path: $uploadDir$issued_signature";
        }
    }

    // Simpan data ke dalam database
    $data = [
        'signature_confirm' => $signature_confirm
    ];

    $conditions = "id_detail_faktur = '$id_detail_faktur'";
    $result = updateData('detail_faktur', $data, $conditions);

    $newDataDetailInv = selectData('detail_faktur', 'id_detail_faktur = ?', '', '', [['type' => 's', 'value' => $id_detail_faktur]]);

    // Ambil signature_confirm sebelum dan sesudah perubahan
    $confirm_before = $oldDataDetailInv[0]['signature_confirm']; // Ambil signature_confirm sebelum perubahan
    $confirm_after = $newDataDetailInv[0]['signature_confirm']; // Ambil signature_confirm setelah perubahan

    // Keterangan perubahan
    $changeDescription = "Perubahan konfirmasi dokumen detail faktur dengan ID $id_detail_faktur: \"konfirmasi\" diubah dari \"$confirm_before\" menjadi \"$confirm_after\".";
    
    // Catat aktivitas
    $logData = [
        'id_pengguna' => $id_pengguna,
        'aktivitas' => 'Berhasil memperbarui konfirmasi delivery order.',
        'tabel' => 'Faktur',
        'keterangan' => $changeDescription,
    ];
    insertData('log_aktivitas', $logData);

    $_SESSION['success_message'] = "Berhasil memperbarui konfirmasi delivery order.";
    header("Location: " . base_url("pages/delivery-order/detail/$category/{$id_detail_faktur}"));
    exit();
} else {
    header("Location: " . base_url("pages/delivery-order/detail/$category/{$id_detail_faktur}"));
    exit();
}

mysqli_close($conn);
?>