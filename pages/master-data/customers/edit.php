<!-- master-data/product/edit -->

<!-- Form untuk mengedit produk -->
<form action="process.php" method="POST">
  <div class="row mb-3">
    <label for="nama_pelanggan" class="col-sm-3 col-form-label">Nama:</label>
    <div class="col-sm-9">
      <input type="text" class="auto-focus form-control form-control-sm" id="nama_pelanggan" name="nama_pelanggan"
        value="<?= $pelanggan['nama_pelanggan']; ?>">
    </div>
  </div>
  <div class="row mb-3">
    <label for="alamat" class="col-sm-3 col-form-label">Alamat:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" id="alamat" name="alamat"
        value="<?= $pelanggan['alamat']; ?>">
    </div>
  </div>
  <div class="row mb-3">
    <label for="telepon" class="col-sm-3 col-form-label">No. Handphone:</label>
    <div class="col-sm-9">
      <input type="tel" class="form-control form-control-sm" id="telepon" name="telepon"
        value="<?= $pelanggan['telepon']; ?>" pattern="[0-9]{10,}">
    </div>
  </div>
  <div class="row mb-3">
    <label for="email" class="col-sm-3 col-form-label">Email:</label>
    <div class="col-sm-9">
      <input type="email" class="form-control form-control-sm" id="email" name="email"
        value="<?= $pelanggan['email']; ?>" placeholder="Opsional">
    </div>
  </div>

  <div class="row mb-3">
    <label for="keterangan" class="col-sm-3 col-form-label">Keterangan:</label>
    <div class="col-sm-9">
      <textarea class="form-control form-control-sm" id="keterangan" name="keterangan"
        rows="3"><?= $pelanggan['keterangan']; ?></textarea>
    </div>
  </div>

  <!-- Tambahkan input hidden untuk mengirim ID produk yang akan diubah -->
  <input type="hidden" name="id_pelanggan" value="<?= $pelanggan['id_pelanggan']; ?>">
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" name="edit">Simpan Perubahan</button>
</form>