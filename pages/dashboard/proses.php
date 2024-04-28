<?php
require_once "../_config.php";

if (isset($_POST['add'])) {
    $nik = $_POST['nik'];
    $nama = strtolower($_POST['nama']);
    $no_hp = $_POST['no_hp'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $foto = $_FILES['foto'];

    // Validasi Format Nomor HP
    if (!preg_match('/^[0-9]{10,15}$/', $no_hp)) {
        echo "<script>alert('Format Nomor HP tidak valid. Harap masukkan nomor HP dengan benar.'); window.location='add.php';</script>";
    } else {
        // Cek apakah Nomor HP sudah terdaftar
        $sql_cek_no_hp = mysqli_query($conn, "SELECT * FROM admin WHERE no_hp = '$no_hp'") or die(mysqli_error($conn));

        if (mysqli_num_rows($sql_cek_no_hp) > 0) {
            echo "<script>alert('Nomor HP sudah terdaftar!'); window.location='add.php';</script>";
        } else {
            // Cek apakah NIK sudah pernah diinput
            $sql_cek_nik = mysqli_query($conn, "SELECT * FROM admin WHERE nik = '$nik'") or die(mysqli_error($conn));

            if (mysqli_num_rows($sql_cek_nik) > 0) {
                echo "<script>alert('Nomor Identitas sudah pernah diinput!'); window.location='add.php';</script>";
            } else {
                // Memeriksa dan membatasi jenis file yang diizinkan (hanya gambar)
                $allowed_types = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF);
                $detected_type = exif_imagetype($foto['tmp_name']);

                if (in_array($detected_type, $allowed_types)) {
                    // Menghasilkan nama unik untuk file foto
                    $foto_name = uniqid('foto_') . '.' . pathinfo($foto['name'], PATHINFO_EXTENSION);

                    // Menentukan lokasi penyimpanan untuk file foto
                    $upload_path = '../_assets/image/photo/';
                    $upload_destination = $upload_path . $foto_name;

                    // Memindahkan file foto ke lokasi penyimpanan
                    if (move_uploaded_file($foto['tmp_name'], $upload_destination)) {
                        // Lanjutkan dengan proses insert ke database
                        $query = "INSERT INTO admin (nik, nama, no_hp, password, foto) VALUES ('$nik', '$nama', '$no_hp', '$password', '$foto_name')";
                        $result = mysqli_query($conn, $query);

                        if ($result) {
                            echo "<script>alert('Tambah admin berhasil.'); window.location='index.php';</script>";
                        } else {
                            echo "<script>alert('Tambah admin gagal.'); window.location='add.php';</script>";
                        }
                    } else {
                        echo "<script>alert('Gagal mengunggah foto.'); window.location='add.php';</script>";
                    }
                } else {
                    echo "<script>alert('Format file foto tidak valid. Harap pilih file gambar (JPEG, PNG, atau GIF).'); window.location='add.php';</script>";
                }
            }
        }
    }
} elseif (isset($_POST['update_status'])) {
    $id_antrian = $_POST['id_antrian'];
    $status = $_POST['status'];
    $current_time = date('H:i:s');

    // Query untuk memperbarui status_antrian
    $query = "UPDATE antrian SET status_antrian = '$status', waktu_selesai = '$current_time' WHERE id_antrian = '$id_antrian'";
    
    // Eksekusi query
    $result = mysqli_query($conn, $query);

    // Periksa apakah query berhasil dijalankan
    if ($result) {
        echo "<script>alert('Update status berhasil.'); window.location='index.php';</script>";
    } else {
        echo "Gagal memperbarui status antrian: " . mysqli_error($conn);
    }
} elseif (isset($_POST['edit'])) {
    $id = $_POST['id_admin'];
    $nik = $_POST['nik'];
    $nama = strtolower($_POST['nama']);
    $no_hp = $_POST['no_hp'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql_cek_nik = mysqli_query($conn, "SELECT * FROM admin WHERE nik = '$nik' AND id_admin != '$id'") or die(mysqli_error($conn));
    if (mysqli_num_rows($sql_cek_nik) > 0) {
        echo "<script>alert('Nomor Identitas sudah pernah diinput!'); window.location='add.php';</script>";
    } else {
        mysqli_query($conn, "UPDATE admin SET nik = '$nik', nama = '$nama', no_hp = '$no_hp', password = '$password' WHERE id_admin = '$id'") or die(mysqli_error($conn));
        echo "<script>window.location='index.php';</script>";
    }
}
?>
