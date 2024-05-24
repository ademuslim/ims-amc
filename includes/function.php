<?php
session_start();
require_once 'config.php';

// Fungsi untuk mendapatkan base URL
function base_url($url = null) {
  // Mengambil base URL dari variabel lingkungan jika tersedia, jika tidak, gunakan base URL default
  $base_url = getenv('BASE_URL') ? getenv('BASE_URL') : "http://ims-amc.test";
  if ($url != null) {
      return rtrim($base_url, '/') . '/' . ltrim($url, '/');
  } else {
      return $base_url;
  }
}

function dateID($date) {
    // Set timezone ke Asia/Jakarta agar sesuai dengan waktu Indonesia Barat
    date_default_timezone_set('Asia/Jakarta');

    // Array untuk nama bulan dalam bahasa Indonesia
    $bulanIndonesia = array(
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );

    // Mendapatkan indeks bulan dari tanggal yang diberikan (0-11)
    $indexBulan = date('n', strtotime($date)) - 1;

    // Format tanggal dengan nama bulan dalam bahasa Indonesia
    $dateFormatted = date('j', strtotime($date)) . ' ' . $bulanIndonesia[$indexBulan] . ' ' . date('Y', strtotime($date));

    // Kembalikan tanggal yang telah diformat
    return $dateFormatted;
}

// Fungsi aktif link
function setActivePage($page) {
    $current_page = $_SERVER['REQUEST_URI'];
    $active_class = '';

    // Periksa apakah halaman saat ini mengandung string yang sesuai dengan halaman yang ditentukan
    if (strpos($current_page, $page) !== false) {
        $active_class = 'active';
    }

    return $active_class;
}

// Fungsi mengarahkan pengguna
function redirectUser($role) {
  if ($role === 'superadmin' || $role === 'staff') {
      // Arahkan superadmin dan staff ke dashboard
      header("Location: " . base_url('pages/dashboard'));
      exit();
  } elseif ($role === 'kepala_perusahaan') {
      // Arahkan kepala_perusahaan ke dashboard_kepala_perusahaan
      header("Location: " . base_url('pages/dashboard-views'));
      exit();
  } else {
      // Jika role tidak valid, arahkan pengguna ke halaman login
      header("Location: " . base_url('auth/login.php'));
      exit();
  }
}

// Fungsi memeriksa pengguna sudah login
function checkLoginStatus() {
  // Periksa apakah ada sesi peran_pengguna
  if (isset($_SESSION['peran_pengguna'])) {
      return true; // Pengguna sudah login
  } else {
      return false; // Pengguna belum login
  }
}

// Fungsi sanitasi input
function sanitizeInput($input) {
  // Cek apakah $input adalah array
  if (is_array($input)) {
      // Jika $input adalah array, iterasi melalui setiap elemen array dan rekursif bersihkan
      foreach ($input as $key => $value) {
          $input[$key] = sanitizeInput($value);
      }
      return $input;
  }

  // Gunakan htmlspecialchars untuk membersihkan input dari karakter berbahaya
  $clean_input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
  
  // Hapus karakter yang berlebihan
  $clean_input = preg_replace('/\s+/', ' ', $clean_input);

  return $clean_input;
}

// Fungsi mendapatkan peran pengguna berdasarkan ID pengguna
function getUserRoleById($user_id) {
  global $conn;
  $query = "SELECT tipe_pengguna FROM pengguna WHERE id_pengguna = ?";
  $stmt = mysqli_prepare($conn, $query);
  
  mysqli_stmt_bind_param($stmt, "i", $user_id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);
  mysqli_stmt_bind_result($stmt, $tipe_pengguna);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);

  return $tipe_pengguna;
}

// Fungsi otentikasi pengguna berdasarkan email dan password
function authenticateUser($email, $password) {
  global $conn; // Gunakan koneksi database dari objek global

  // Ambil haquotationsh password dari database berdasarkan email
  $query = "SELECT id_pengguna, tipe_pengguna, nama_pengguna, password FROM pengguna WHERE email = ?";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "s", $email);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);

  // Bind result variables
  mysqli_stmt_bind_result($stmt, $user_id, $user_role, $user_name, $hashed_password);

  // Fetch value
  mysqli_stmt_fetch($stmt);

  // Periksa apakah ada baris yang cocok dengan email yang diberikan
  if (mysqli_stmt_num_rows($stmt) > 0) {
      // Verifikasi password
      if (password_verify($password, $hashed_password)) {
          // Jika password cocok, kembalikan informasi pengguna
          return array(
              'id_pengguna' => $user_id,
              'tipe_pengguna' => $user_role,
              'nama_pengguna' => $user_name
          );
      } else {
          // Jika password tidak cocok, kembalikan null
          return null;
      }
  } else {
      // Jika tidak ada baris yang cocok dengan email yang diberikan, kembalikan null
      return null;
  }
}

// Fungsi otentikasi berdasarkan ID pengguna
function authenticateByUserId($user_id) {
  global $conn;
  
  // Query untuk mengambil pengguna berdasarkan ID pengguna
  $query = "SELECT * FROM pengguna WHERE id_pengguna = ?";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "i", $user_id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);
  
  // Periksa apakah ada pengguna dengan ID yang diberikan
  if (mysqli_stmt_num_rows($stmt) > 0) {
      return true; // Otentikasi berhasil
  } else {
      return false; // Otentikasi gagal
  }
}


// Fungsi tambah pengguna
function register($id_pengguna, $nama_pengguna, $email, $password, $tipe_pengguna) {
  global $conn;

  $id_pengguna = $id_pengguna;
  $nama_pengguna = $nama_pengguna;
  $email = $email;
  $tipe_pengguna = $tipe_pengguna;

  // Hash password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Insert user data into database
  $query = "INSERT INTO pengguna (id_pengguna, nama_pengguna, email, password, tipe_pengguna) VALUES (?, ?, ?, ?, ?)";
  $stmt = mysqli_prepare($conn, $query);
  
  // Bind parameters
  mysqli_stmt_bind_param($stmt, "sssss", $id_pengguna, $nama_pengguna, $email, $hashed_password, $tipe_pengguna);
  
  // Execute statement
  $result = mysqli_stmt_execute($stmt);

  // Check if registration was successful
  if ($result) {
      return true; // Registration successful
  } else {
      return false; // Registration failed
  }
}

// // Fungsi tambah data
// function insertData($table, $data) {
//   global $conn;

//   // Sanitasi data sebelum dimasukkan ke dalam database
//   $sanitized_data = sanitizeInput($data);

//   // Bangun pernyataan SQL
//   $columns = implode(", ", array_keys($sanitized_data));
//   $placeholders = implode(", ", array_fill(0, count($sanitized_data), "?"));
//   $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

//   // Persiapkan statement
//   $stmt = mysqli_prepare($conn, $sql);

//   // Bind parameter
//   $types = str_repeat("s", count($sanitized_data));
//   mysqli_stmt_bind_param($stmt, $types, ...array_values($sanitized_data));

//   // Eksekusi statement
//   mysqli_stmt_execute($stmt); //baris 220

//   // Ambil hasil
//   $result = mysqli_stmt_affected_rows($stmt);

//   // Tutup statement
//   mysqli_stmt_close($stmt);

//   return $result;
// }

function insertData($table, $data) {
    global $conn;

    // Sanitasi data sebelum dimasukkan ke dalam database
    $sanitized_data = sanitizeInput($data);

    // Bangun pernyataan SQL
    $columns = implode(", ", array_keys($sanitized_data));
    $placeholders = implode(", ", array_fill(0, count($sanitized_data), "?"));
    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

    // Persiapkan statement
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // Bind parameter secara manual berdasarkan jenis data
        $types = str_repeat("s", count($sanitized_data));
        $values = array_values($sanitized_data);
        mysqli_stmt_bind_param($stmt, $types, ...$values);

        // Eksekusi statement
        mysqli_stmt_execute($stmt);

        // Ambil hasil
        $result = mysqli_stmt_affected_rows($stmt);

        // Tutup statement
        mysqli_stmt_close($stmt);

        return $result;
    } else {
        // Handle error jika persiapan statement gagal
        return false;
    }
}


// Fungsi tampil data dengan kemampuan sorting, limit, dan parameter terikat untuk prepared statement
function selectData($table, $conditions = "", $order_by = "", $limit = "", $bind_params = array()) {
  global $conn;
  // Bangun pernyataan SQL
  $sql = "SELECT * FROM $table";
  if (!empty($conditions)) {
      $sql .= " WHERE $conditions";
  }
  if (!empty($order_by)) {
      $sql .= " ORDER BY $order_by";
  }
  if (!empty($limit)) {
      $sql .= " LIMIT $limit";
  }
  // Persiapkan statement
  $stmt = mysqli_prepare($conn, $sql);
  // Bind parameters jika ada
  if (!empty($bind_params)) {
      $types = "";
      $bind_values = array();
      foreach ($bind_params as $param) {
          $types .= $param['type'];
          $bind_values[] = $param['value'];
      }
      mysqli_stmt_bind_param($stmt, $types, ...$bind_values);
  }
  // Eksekusi query
  mysqli_stmt_execute($stmt);
  // Ambil hasil
  $result = mysqli_stmt_get_result($stmt);
  $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
  // Bebaskan hasil dan tutup statement
  mysqli_free_result($result);
  mysqli_stmt_close($stmt);
  return $rows;
}

// Fungsi tampil data dengan join tabel dan fitur order by
function selectDataJoin($mainTable, $joinTables = [], $columns = '*', $conditions = "", $orderBy = "") {
  global $conn;
  
  // Bangun pernyataan SQL untuk select
  $sql = "SELECT $columns FROM $mainTable";
  
  // Tambahkan join clause jika ada
  foreach ($joinTables as $joinTable) {
      $sql .= " JOIN $joinTable[0] ON $joinTable[1]";
  }
  
  // Tambahkan kondisi jika ada
  if (!empty($conditions)) {
      $sql .= " WHERE $conditions";
  }
  
  // Tambahkan klausa ORDER BY jika ada
  if (!empty($orderBy)) {
      $sql .= " ORDER BY $orderBy";
  }
  
  // Eksekusi query dan ambil hasil
  $result = mysqli_query($conn, $sql);
  $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
  
  // Bebaskan hasil
  mysqli_free_result($result);
  
  return $rows;
}

// Fungsi ubah data
function updateData($table, $data, $conditions) {
  global $conn;
  // Bangun pernyataan SQL
  $set = [];
  $params = [];
  foreach ($data as $key => $value) {
      $set[] = "$key = ?";
      $params[] = $value;
  }
  $setClause = implode(", ", $set);
  $sql = "UPDATE $table SET $setClause WHERE $conditions";
  // Persiapkan statement
  $stmt = mysqli_prepare($conn, $sql);
  // Bind parameter
  mysqli_stmt_bind_param($stmt, str_repeat("s", count($data)), ...$params);
  // Eksekusi statement
  mysqli_stmt_execute($stmt);
  // Ambil hasil
  $result = mysqli_stmt_affected_rows($stmt);
  // Tutup statement
  mysqli_stmt_close($stmt);
  return $result;
}

// Fungsi hapus data
function deleteData($table, $conditions) {
  global $conn;
  // Bangun pernyataan SQL
  $sql = "DELETE FROM $table WHERE $conditions";
  // Persiapkan statement
  $stmt = mysqli_prepare($conn, $sql);
  // Eksekusi statement
  mysqli_stmt_execute($stmt);
  // Ambil hasil
  $result = mysqli_stmt_affected_rows($stmt);
  // Tutup statement
  mysqli_stmt_close($stmt);
  return $result;
}
// Fungsi untuk memeriksa apakah nilai sudah ada dalam tabel dan kolom tertentu
function isValueExists($table, $column, $value, $excludeId = null, $idColumn = 'id') {
    global $conn;
  
    // Persiapkan query SQL
    $sql = "SELECT COUNT(*) as count FROM $table WHERE $column = ?";
    
    // Jika excludeId diberikan, tambahkan kondisi untuk mengecualikan id tertentu
    if ($excludeId !== null) {
        $sql .= " AND $idColumn != ?";
    }
  
    // Persiapkan statement
    $stmt = mysqli_prepare($conn, $sql);
  
    // Bind parameter
    if ($excludeId !== null) {
        mysqli_stmt_bind_param($stmt, "ss", $value, $excludeId);
    } else {
        mysqli_stmt_bind_param($stmt, "s", $value);
    }
  
    // Eksekusi statement
    mysqli_stmt_execute($stmt);
  
    // Ambil hasil
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
  
    // Tutup statement
    mysqli_stmt_close($stmt);
  
    // Return true jika jumlah baris lebih dari 0 (nilai sudah ada), false jika tidak
    return $count > 0;
  }

  function isDataInUse($value, $tableColumnPairs) {
    global $conn;

    // Inisialisasi variabel untuk menyimpan status penggunaan data
    $dataInUse = false;

    // Loop melalui setiap pasangan tabel-kolom
    foreach ($tableColumnPairs as $tableColumns) {
        $table = $tableColumns['table'];
        $columns = $tableColumns['columns']; // Array of columns to check

        // Buat bagian WHERE dari query SQL dengan banyak kolom
        $whereClauses = [];
        foreach ($columns as $column) {
            $whereClauses[] = "$column = ?";
        }
        $whereClause = implode(' OR ', $whereClauses);

        // Persiapkan query SQL untuk mengecek relasi di tabel lain
        $sql = "SELECT COUNT(*) as count FROM $table WHERE $whereClause";

        // Persiapkan statement
        $stmt = mysqli_prepare($conn, $sql);

        // Bind parameter
        $params = array_fill(0, count($columns), $value);
        mysqli_stmt_bind_param($stmt, str_repeat('s', count($columns)), ...$params);

        // Eksekusi statement
        mysqli_stmt_execute($stmt);

        // Ambil hasil
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);

        // Tutup statement
        mysqli_stmt_close($stmt);

        // Jika jumlah baris lebih dari 0 (data sedang digunakan dalam tabel lain), set status menjadi true
        if ($count > 0) {
            $dataInUse = true;
            // Hentikan loop karena data sudah ditemukan digunakan dalam salah satu tabel lain
            break;
        }
    }

    // Return status penggunaan data
    return $dataInUse;
}

function getLastDocumentNumber($tabel, $column, $order_by, $prefix, $suffix, $month, $year) {
  global $conn;

  // Query untuk mengambil nomor dokumen terbaru dari tabel tertentu berdasarkan kolom tertentu
  // dan berdasarkan bulan dan tahun yang disediakan
  $query = "SELECT $column FROM $tabel WHERE YEAR($order_by) = $year AND MONTH($order_by) = $month AND kategori = 'keluar' ORDER BY $column DESC LIMIT 1";

  // Eksekusi query
  $result = mysqli_query($conn, $query);

  // Periksa apakah query berhasil dieksekusi
  if (!$result) {
      die("Error: " . mysqli_error($conn));
  }

  // Inisialisasi nomor dokumen terbaru
  $new_doc_number = '';

  // Periksa apakah ada hasil dari query
  if (mysqli_num_rows($result) > 0) {
      // Ambil nomor dokumen terbaru
      $row = mysqli_fetch_assoc($result);
      $last_number = $row[$column];

      // Split nomor dokumen terbaru untuk mendapatkan nomor
      $doc_parts = explode('/', $last_number);
      $last_doc_digits = intval($doc_parts[0]);

      // Tambahkan 1 pada nomor dokumen terbaru
      $new_doc_digits = sprintf('%03d', $last_doc_digits + 1);
      $new_doc_number = $new_doc_digits . '/' . $prefix . '/' . $suffix . '/' . $month . '/' . $year;
  } else {
      // Jika tidak ada nomor dokumen terbaru untuk bulan dan tahun yang sama,
      // buat nomor dokumen baru dimulai dari 001
      $new_doc_number = '001/' . $prefix . '/' . $suffix . '/' . $month . '/' . $year;
  }

  return $new_doc_number;
}

// Fungsi menangani upload gambar
function handleLogoUpload($file, $allowed_types, $max_file_size, $upload_path)
{
    // Memeriksa apakah file gambar logo diunggah
    if(isset($file['logo']) && $file['logo']['error'] === UPLOAD_ERR_OK) {
        // Tentukan jenis MIME yang diizinkan
        $file_type = $file['logo']['type'];
        
        // Memeriksa apakah jenis file diizinkan
        if (!in_array($file_type, $allowed_types)) {
            return "Jenis file yang diunggah tidak diizinkan. Hanya gambar dengan format JPG, PNG, atau GIF yang diperbolehkan.";
        }
        
        // Memeriksa apakah ukuran file tidak melebihi batas maksimal
        if ($file['logo']['size'] > $max_file_size) {
            return "Ukuran file yang diunggah melebihi batas maksimal (2MB).";
        }

        // Generate nama file acak dan unik
        $file_extension = pathinfo($file['logo']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '_' . date('Ymd') . '.' . $file_extension;
        $file_destination = $upload_path . $file_name;

        // Pindahkan file dari temp ke lokasi tujuan
        if (!move_uploaded_file($file['logo']['tmp_name'], $file_destination)) {
            return "Gagal mengunggah file gambar logo.";
        }

        return $file_destination; // Kembalikan lokasi file gambar logo yang berhasil diunggah
    } else {
        // Tidak ada file yang diunggah atau terjadi kesalahan saat mengunggah
        return "Gagal mengunggah file gambar logo.";
    }
}

function formatRupiah($number) {
    return 'Rp ' . number_format($number, 0, ',', '.');
}

// Fungsi untuk mendapatkan opsi enum dari kolom
function getEnum($column_name, $table_name) {
    global $conn; // Menggunakan variabel koneksi global

    $enum_values = [];

    // Buat kueri untuk mendapatkan informasi tentang kolom
    $query = "SHOW COLUMNS FROM $table_name LIKE '$column_name'";

    // Jalankan kueri
    $result = mysqli_query($conn, $query);

    // Periksa apakah kueri berhasil
    if ($result) {
        // Ambil informasi kolom
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        
        // Periksa apakah tipe kolom adalah ENUM
        if (isset($row['Type']) && strpos($row['Type'], 'enum') !== false) {
            // Ekstrak nilai-nilai ENUM
            $enum_values = explode(",", str_replace("'", "", substr($row['Type'], 5, -1)));
        }
    }

    return $enum_values;
}