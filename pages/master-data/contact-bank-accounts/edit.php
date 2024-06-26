<!-- Form edit rek -->
<form action="process.php" method="POST">
  <!-- Nama Kontak -->
  <div class="row mb-3">
    <label for="editKontak" class="col-sm-3 col-form-label">Nama Kontak:</label>
    <div class="col-sm-9">
      <select class="form-select form-select-sm" id="editKontak" name="id_kontak" required>
        <option value="" disabled>-- Pilih Kontak --</option>
        <?php
        // Ambil data kontak yang aktif
        $kontak = selectData("kontak", "status_hapus = 0");
        foreach ($kontak as $row_kontak) {
          $selected = ($rek['id_kontak'] == $row_kontak['id_kontak']) ? 'selected' : '';
          echo "<option value='" . htmlspecialchars($row_kontak['id_kontak']) . "' $selected>" . htmlspecialchars(strtoupper($row_kontak['nama_kontak'])) . "</option>";
        }
        ?>
      </select>
    </div>
  </div>

  <div class="row mb-3">
    <label for="editNamaBank" class="col-sm-3 form-label">Nama Bank</label>
    <div class="col-sm-9">
      <input type="text" class="auto-focus form-control form-control-sm" id="editNamaBank" name="nama_bank"
        value="<?= strtoupper($rek['nama_bank']); ?>">
    </div>
  </div>
  <div class="row mb-3">
    <label for="editNoRekening" class="col-sm-3 form-label">Nomor Rekening</label>
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" id="editNoRekening" name="nomor_rekening"
        value="<?= $rek['nomor_rekening']; ?>">
    </div>
  </div>
  <div class="row mb-3">
    <label for="editPemegangRekening" class="col-sm-3 form-label">Pemegang
      Rekening:
    </label>
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" id="editPemegangRekening" name="pemegang_rekening"
        value="<?= strtoupper($rek['pemegang_rekening']); ?>">
    </div>
  </div>
  <div class="row mb-3">
    <label for="editKodeSWIFT" class="col-sm-3 form-label">Kode SWIFT:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" id="editKodeSWIFT" name="kode_swift"
        value="<?= $rek['kode_swift']; ?>">
    </div>
  </div>
  <div class="row mb-3">
    <label for="editCabangBank" class="col-sm-3 form-label">Cabang Bank:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" id="editCabangBank" name="cabang_bank"
        value="<?= strtoupper($rek['cabang_bank']); ?>">
    </div>
  </div>
  <div class="row mb-3">
    <label for="editKeterangan" class="col-sm-3 form-label">Keterangan:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" id="editKeterangan" name="keterangan"
        value="<?= ucfirst($rek['keterangan']); ?>">
    </div>
  </div>

  <input type="hidden" name="id_rekening" value="<?= $rek['id_rekening']; ?>">
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" name="edit">Simpan Perubahan</button>
</form>