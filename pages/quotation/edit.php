<!-- master-data/penawaran_harga/edit.php -->

<!-- Form untuk mengedit penawaran harga -->
<form action="edit_process.php" method="POST">
  <!-- Form untuk mengedit data penawaran harga -->
  <input type="hidden" name="id_penawaran" value="<?= $ph['id_penawaran']; ?>">
  <div class="row mb-3">
    <label for="edit_id_pengirim" class="col-sm-2 col-form-label">ID Pengirim</label>
    <div class="col-sm-10">
      <select class="form-select form-select-sm" id="edit_id_pengirim" name="edit_id_pengirim" required>
        <option value="" disabled>-- Pilih Pengirim --</option>
        <?php
        $kontak_internal = selectData("kontak_internal");
        foreach ($kontak_internal as $row_pengirim) {
          $selected = ($row_pengirim['id_pengirim'] == $ph['id_pengirim']) ? 'selected' : '';
          echo '<option value="' . $row_pengirim['id_pengirim'] . '" ' . $selected . '>' . ucwords($row_pengirim['nama_pengirim']) . '</option>';
        }
        ?>
      </select>
    </div>
  </div>

  <div class="row row mb-3">
    <label for="tanggal" class="col-sm-2 col-form-label">Tanggal</label>
    <div class="col-sm-10">
      <input type="datetime-local" class="form-control form-control-sm" id="tanggal" name="tanggal"
        value="<?= $ph['tanggal']; ?>" readonly required>
    </div>
  </div>

  <div class="row mb-3">
    <label for="edit_no_penawaran" class="col-sm-2 col-form-label">Nomor Penawaran</label>
    <div class="col-sm-10">
      <input type="text" class="form-control form-control-sm" id="edit_no_penawaran" name="edit_no_penawaran"
        value="<?= strtoupper($ph['no_penawaran']); ?>" readonly required>
    </div>
  </div>

  <div class="row mb-3">
    <label for="edit_total" class="col-sm-2 col-form-label">Total</label>
    <div class="col-sm-10">
      <input type="text" class="form-control form-control-sm" id="edit_total" name="edit_total"
        value="<?= $ph['total']; ?>" required>
    </div>
  </div>

  <div class="row mb-3">
    <label for="edit_catatan" class="col-sm-2 col-form-label">Catatan</label>
    <div class="col-sm-10">
      <textarea class="form-control form-control-sm" id="edit_catatan" name="edit_catatan"
        rows="3"><?= $ph['catatan']; ?></textarea>
    </div>
  </div>

  <div class="row mb-3">
    <label for="edit_id_penerima" class="col-sm-2 col-form-label">ID Penerima</label>
    <div class="col-sm-10">
      <select class="form-select form-select-sm" id="edit_id_penerima" name="edit_id_penerima" required>
        <option value="" disabled>-- Pilih Penerima --</option>
        <?php
              $pelanggan = selectData("pelanggan");
              foreach ($pelanggan as $row_penerima) {
                $selected = ($row_penerima['id_pelanggan'] == $ph['id_penerima']) ? 'selected' : '';
                echo '<option value="' . $row_penerima['id_pelanggan'] . '" ' . $selected . '>' . ucwords($row_penerima['nama_pelanggan']) . '</option>';
              }
              ?>
      </select>
    </div>
  </div>

  <div class="row mb-3">
    <label for="up" class="col-sm-2 col-form-label">U.P.</label>
    <div class="col-sm-10">
      <input type="text" class="form-control form-control-sm" id="up" name="up" value="<?= ucwords($ph['up']); ?>"
        required>
    </div>
  </div>

  <div class="row mb-3">
    <label for="edit_diskon" class="col-sm-2 col-form-label">Diskon</label>
    <div class="col-sm-10">
      <input type="text" class="form-control form-control-sm" id="edit_diskon" name="edit_diskon"
        value="<?= $ph['diskon']; ?>">
    </div>
  </div>

  <div class="row mb-3">
    <label for="edit_jenis_ppn" class="col-sm-2 col-form-label">Jenis PPN</label>
    <div class="col-sm-10">
      <select class="form-select form-select-sm" id="edit_jenis_ppn" name="edit_jenis_ppn" required>
        <option value="" disabled>-- Pilih Jenis PPN --</option>
        <?php
              $ppn = selectData("ppn");
              foreach ($ppn as $row_ppn) {
                $selected = ($row_ppn['id_ppn'] == $ph['id_ppn']) ? 'selected' : '';
                echo '<option value="' . $row_ppn['id_ppn'] . '" ' . $selected . '>' . ucwords($row_ppn['jenis_ppn']) . '</option>';
              }
              ?>
      </select>
    </div>
  </div>

  <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</form>