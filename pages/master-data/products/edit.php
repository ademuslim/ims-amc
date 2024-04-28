<!-- master-data/product/edit -->

<!-- Form untuk mengedit produk -->
<form action="process.php" method="POST">
  <div class="mb-3">
    <label for="no_produk" class="form-label">Nomor Produk:</label>
    <input type="text" class="form-control" id="no_produk" name="no_produk" value="<?php echo $produk['no_produk']; ?>">
  </div>
  <div class="mb-3">
    <label for="nama_produk" class="form-label">Nama Produk:</label>
    <input type="text" class="form-control" id="nama_produk" name="nama_produk"
      value="<?php echo $produk['nama_produk']; ?>">
  </div>
  <div class="mb-3">
    <label for="satuan" class="form-label">Satuan:</label>
    <input type="text" class="form-control" id="satuan" name="satuan" value="<?php echo $produk['satuan']; ?>">
  </div>
  <div class="mb-3">
    <label for="harga" class="form-label">Harga:</label>
    <input type="number" class="form-control" id="harga" name="harga" min="0" value="<?php echo $produk['harga']; ?>">
  </div>
  <div class="mb-3">
    <label for="status" class="form-label">Status:</label>
    <select class="form-select" id="status" name="status">
      <option value="Baru" <?php echo ($produk['status'] == 'Baru') ? 'selected' : ''; ?>>Baru</option>
      <option value="Disetujui" <?php echo ($produk['status'] == 'Disetujui') ? 'selected' : ''; ?>>Disetujui</option>
      <option value="Ditolak" <?php echo ($produk['status'] == 'Ditolak') ? 'selected' : ''; ?>>Ditolak</option>
    </select>
  </div>
  <!-- Tambahkan input hidden untuk mengirim ID produk yang akan diubah -->
  <input type="hidden" name="id_produk" value="<?php echo $produk['id_produk']; ?>">
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" name="edit">Simpan Perubahan</button>
</form>