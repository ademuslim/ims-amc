<form action="process.php" method="POST">
  <div class="row mb-3">
    <label for="no_produk" class="col-sm-3 col-form-label">Nomor Produk:</label>
    <div class="col-sm-9">
      <input type="text" class="auto-focus form-control form-control-sm" id="no_produk" name="no_produk"
        value="<?= $produk['no_produk']; ?>">
    </div>
  </div>
  <div class="row mb-3">
    <label for="nama_produk" class="col-sm-3 col-form-label">Nama Produk:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" id="nama_produk" name="nama_produk"
        value="<?= $produk['nama_produk']; ?>">
    </div>
  </div>
  <div class="row mb-3">
    <label for="satuan" class="col-sm-3 col-form-label">Satuan:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" id="satuan" name="satuan"
        value="<?= $produk['satuan']; ?>">
    </div>
  </div>
  <div class="row mb-3">
    <label for="harga" class="col-sm-3 col-form-label">Harga:</label>
    <div class="col-sm-9">
      <input type="number" class="form-control form-control-sm" id="harga" name="harga" min="0"
        value="<?= $produk['harga']; ?>">
    </div>
  </div>
  <div class="row mb-3">
    <label for="status" class="col-sm-3 col-form-label">Status:</label>
    <div class="col-sm-9">
      <select class="form-select form-select-sm" id="status" name="status">
        <option value="draft" <?= ($produk['status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
        <option value="pending" <?= ($produk['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
        <option value="ditolak" <?= ($produk['status'] == 'ditolak') ? 'selected' : ''; ?>>Ditolak</option>
        <option value="disetujui" <?= ($produk['status'] == 'disetujui') ? 'selected' : ''; ?>>Disetujui</option>
      </select>
    </div>
  </div>
  <div class="row mb-3">
    <label for="keterangan" class="col-sm-3 col-form-label">Keterangan:</label>
    <div class="col-sm-9">
      <textarea class="form-control" id="keterangan" name="keterangan" placeholder="Opsional."></textarea>
    </div>
  </div>
  <!-- Tambahkan input hidden untuk mengirim ID produk yang akan diubah -->
  <input type="hidden" name="id_produk" value="<?= $produk['id_produk']; ?>">
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" name="edit">Simpan Perubahan</button>
</form>