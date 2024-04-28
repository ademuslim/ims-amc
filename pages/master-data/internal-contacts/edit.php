<!-- master-data/product/edit -->

<!-- Form untuk mengedit produk -->
<form action="process.php" method="POST">
  <div class="mb-3">
    <label for="nama_pengirim" class="form-label">Nama:</label>
    <input type="text" class="form-control" id="nama_pengirim" name="nama_pengirim"
      value="<?php echo $kontak['nama_pengirim']; ?>">
  </div>
  <div class="mb-3">
    <label for="alamat_pengirim" class="form-label">Alamat:</label>
    <input type="text" class="form-control" id="alamat_pengirim" name="alamat_pengirim"
      value="<?php echo $kontak['alamat_pengirim']; ?>">
  </div>
  <div class="mb-3">
    <label for="telepon" class="form-label">No. Handphone:</label>
    <input type="tel" class="form-control" id="telepon" name="telepon" value="<?php echo $kontak['telepon']; ?>"
      pattern="[0-9]{10,}">
  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email:</label>
    <input type="email" class="form-control" id="email" name="email" value="<?php echo $kontak['email']; ?>"
      placeholder="Opsional">
  </div>

  <!-- Tambahkan input hidden untuk mengirim ID produk yang akan diubah -->
  <input type="hidden" name="id_pengirim" value="<?php echo $kontak['id_pengirim']; ?>">
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" name="edit">Simpan Perubahan</button>
</form>