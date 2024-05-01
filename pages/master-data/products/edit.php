<!-- master-data/product/edit -->

<!-- Form untuk mengedit produk -->
<form action="process.php" method="POST">
  <div class="row mb-3">
    <label for="no_produk" class="col-sm-3 col-form-label">Nomor Produk:</label>
    <div class="col-sm-9">
      <input type="text" class="auto-focusform-control form-control-sm" id="no_produk" name="no_produk"
        value="<?php echo $produk['no_produk']; ?>">
    </div>
  </div>
  <div class="row mb-3">
    <label for="nama_produk" class="col-sm-3 col-form-label">Nama Produk:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" id="nama_produk" name="nama_produk"
        value="<?php echo $produk['nama_produk']; ?>">
    </div>
  </div>
  <div class="row mb-3">
    <label for="satuan" class="col-sm-3 col-form-label">Satuan:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" id="satuan" name="satuan"
        value="<?php echo $produk['satuan']; ?>">
    </div>
  </div>
  <div class="row mb-3">
    <label for="harga" class="col-sm-3 col-form-label">Harga:</label>
    <div class="col-sm-9">
      <input type="number" class="form-control form-control-sm" id="harga" name="harga" min="0"
        value="<?php echo $produk['harga']; ?>">
    </div>
  </div>
  <div class="row mb-3">
    <label for="status" class="col-sm-3 col-form-label">Status:</label>
    <div class="col-sm-9">
      <select class="form-select form-select-sm" id="status" name="status">
        <option value="Baru" <?php echo ($produk['status'] == 'Baru') ? 'selected' : ''; ?>>Baru</option>
        <option value="Disetujui" <?php echo ($produk['status'] == 'Disetujui') ? 'selected' : ''; ?>>Disetujui</option>
        <option value="Ditolak" <?php echo ($produk['status'] == 'Ditolak') ? 'selected' : ''; ?>>Ditolak</option>
      </select>
    </div>
  </div>
  <!-- Tambahkan input hidden untuk mengirim ID produk yang akan diubah -->
  <input type="hidden" name="id_produk" value="<?php echo $produk['id_produk']; ?>">
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" name="edit">Simpan Perubahan</button>
</form>