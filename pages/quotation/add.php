<?php // penawaran_harga/add.php
require '../../includes/header.php';

?>
<div class="container">
  <form action="process.php" method="POST" class="row needs-validation" novalidate>
    <!-- Form untuk menambahkan data penawaran harga -->
    <div class="row mb-3">
      <label for="pengirim" class="col-sm-2 col-form-label">ID Pengirim</label>
      <div class="col-sm-10">
        <select class="form-select form-select-sm" id="pengirim" name="pengirim" required>
          <?php
        $kontak_internal = selectData("kontak_internal");
        foreach ($kontak_internal as $row_pengirim) {
          $selected = ""; // variabel untuk menentukan apakah opsi saat ini harus dipilih
          // Tentukan pengirim mana yang akan menjadi default
          if ($row_pengirim['nama_pengirim'] == "pt. mitra tehno gemilang") {
            $selected = "selected";
          }
          echo '<option value="' . $row_pengirim['id_pengirim'] . '" ' . $selected . '>' . $row_pengirim['nama_pengirim'] . '</option>';
        }
      ?>
        </select>
      </div>
    </div>

    <div class="row mb-3">
      <label for="tanggal" class="col-sm-2 col-form-label">Tanggal</label>
      <div class="col-sm-10">
        <input type="datetime-local" class="form-control form-control-sm" id="tanggal" name="tanggal" required>
      </div>
    </div>

    <div class="row mb-3">
      <label for="no_penawaran" class="col-sm-2 col-form-label">No:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm" id="no_penawaran" name="no_penawaran" readonly required>
      </div>
    </div>

    <div class="row mb-3">
      <label for="penerima" class="col-sm-2 col-form-label">ID Penerima</label>
      <div class="col-sm-10">
        <select class="form-select form-select-sm" id="penerima" name="penerima" required>
          <option value="" selected disabled>-- Pilih Penerima --</option>
          <?php
        $pelanggan = selectData("pelanggan");
        foreach ($pelanggan as $row_penerima) {
          echo '<option value="' . $row_penerima['id_pelanggan'] . '">' . $row_penerima['nama_pelanggan'] . '</option>';
        }
        ?>
        </select>
      </div>
    </div>

    <div class="row mb-3">
      <label for="up" class="col-sm-2 col-form-label">U.P.</label>
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm" id="up" name="up" value="-" required>
      </div>
    </div>

    <div class="row mb-3">
      <label for="diskon" class="col-sm-2 col-form-label">Diskon</label>
      <div class="col-sm-10">
        <input type="number" class="form-control form-control-sm" id="diskon" name="diskon" min="0" step="0.01"
          value="0" required>
      </div>
    </div>

    <div class="row mb-3">
      <label for="jenis_ppn" class="col-sm-2 col-form-label">PPN</label>
      <div class="col-sm-10">
        <select class="form-select form-select-sm" id="jenis_ppn" name="jenis_ppn">
          <option value="" selected disabled>-- Pilih Jenis PPN --</option>
          <?php
        $ppn = selectData("ppn");
        foreach ($ppn as $row_ppn) {
            echo '<option value="' . $row_ppn['id_ppn'] . '">' . $row_ppn['jenis_ppn'] . '</option>';
        }
        ?>
        </select>
      </div>
    </div>

    <div class="row mb-3">
      <label for="catatan" class="col-sm-2 col-form-label">Catatan</label>
      <div class="col-sm-10">
        <textarea class="form-control form-control-sm" id="catatan" name="catatan" row="1"></textarea>
      </div>
    </div>

    <button type="submit" class="btn btn-primary btn-sm" name="add">Simpan</button>
  </form>
</div>
<script>
document.getElementById("tanggal").addEventListener("change", function() {
  var tanggalInput = document.getElementById("tanggal").value;
  if (tanggalInput) {
    var date = new Date(tanggalInput);
    var month = date.getMonth() + 1;
    var year = date.getFullYear();

    // Format bulan menjadi dua digit jika nilainya kurang dari 10
    if (month < 10) {
      month = '0' + month;
    }

    // Request nomor penawaran berdasarkan bulan dan tahun ke getDocumentNumber.php
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("no_penawaran").value = this.responseText;
      }
    };
    xhttp.open("GET", "getDocumentNumber.php?month=" + month + "&year=" + year, true);
    xhttp.send();
  } else {
    document.getElementById("no_penawaran").value = ""; // Kosongkan nilai nomor penawaran jika tanggal tidak diisi
  }
});
</script>


<?php
require '../../includes/footer.php';
?>