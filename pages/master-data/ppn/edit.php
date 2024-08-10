<!-- master-data/ppn/edit -->

<!-- Form edit ppn -->
<form action="process.php" method="POST">
  <div class="row mb-3">
    <label for="jenis_ppn" class="col-sm-3 col-form-label">Jenis PPN:</label>
    <div class="col-sm-9">
      <input type="text" class="auto-focus form-control form-control-sm" id="jenis_ppn" name="jenis_ppn"
        value="<?= $ppn['jenis_ppn']; ?>">
    </div>
  </div>
  <div class="row mb-3">
    <label for="tarif" class="col-sm-3 col-form-label">Tarif:</label>
    <div class="col-sm-9">
      <input type="number" class="form-control form-control-sm" id="tarif" name="tarif" value="<?= $ppn['tarif']; ?>"
        min="0">
    </div>
  </div>
  <div class="row mb-3">
    <label for="keterangan" class="col-sm-3 col-form-label">Keterangan:</label>
    <div class="col-sm-9">
      <textarea class="form-control form-control-sm form-control-sm" id="keterangan" name="keterangan"
        rows="3"><?= $ppn['keterangan']; ?></textarea>
    </div>
  </div>

  <!-- Tambahkan input hidden untuk mengirim ID ppn yang akan diubah -->
  <input type="hidden" name="id_ppn" value="<?= $ppn['id_ppn']; ?>">
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
    <button type="submit" class="btn btn-primary" name="edit">Simpan Perubahan</button>
</form>