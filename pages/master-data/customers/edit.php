<!-- master-data/product/edit -->

<!-- Form untuk mengedit produk -->
<form action="process.php" method="POST">
  <div class="mb-3">
    <label for="nama_pelanggan" class="form-label">Nama:</label>
    <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan"
      value="<?= $pelanggan['nama_pelanggan']; ?>">
  </div>
  <div class="mb-3">
    <label for="alamat" class="form-label">Alamat:</label>
    <input type="text" class="form-control" id="alamat" name="alamat" value="<?= $pelanggan['alamat']; ?>">
  </div>
  <div class="mb-3">
    <label for="telepon" class="form-label">No. Handphone:</label>
    <input type="tel" class="form-control" id="telepon" name="telepon" value="<?= $pelanggan['telepon']; ?>"
      pattern="[0-9]{10,}">
  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email:</label>
    <input type="email" class="form-control" id="email" name="email" value="<?= $pelanggan['email']; ?>"
      placeholder="Opsional">
  </div>
  <div class="mb-3">
    <label for="keterangan">Keterangan:</label>
    <textarea id="keterangan" name="keterangan" rows="4" cols="50"><?= $pelanggan['keterangan']; ?></textarea>
  </div>


  <!-- Tambahkan input hidden untuk mengirim ID produk yang akan diubah -->
  <input type="hidden" name="id_pelanggan" value="<?= $pelanggan['id_pelanggan']; ?>">
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" name="edit">Simpan Perubahan</button>
</form>