<!-- master-data/product/edit -->

<!-- Form untuk mengedit produk -->
<form action="process.php" method="POST">
  <div class="row mb-3">
    <label for="nama_pengirim" class="col-sm-3 col-form-label">Nama:</label>
    <div class="col-sm-9">
      <input type="text" class="auto-focus form-control form-control-sm" id="nama_pengirim" name="nama_pengirim"
        value="<?php echo $kontak['nama_pengirim']; ?>">
    </div>
  </div>
  <div class="row mb-3">
    <label for="alamat_pengirim" class="col-sm-3 col-form-label">Alamat:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" id="alamat_pengirim" name="alamat_pengirim"
        value="<?php echo $kontak['alamat_pengirim']; ?>">
    </div>
  </div>
  <div class="row mb-3">
    <label for="telepon" class="col-sm-3 col-form-label">No. Handphone:</label>
    <div class="col-sm-9">
      <input type="tel" class="form-control form-control-sm" id="telepon" name="telepon"
        value="<?php echo $kontak['telepon']; ?>" pattern="[0-9]{10,}">
    </div>
  </div>
  <div class="row mb-3">
    <label for="email" class="col-sm-3 col-form-label">Email:</label>
    <div class="col-sm-9">
      <input type="email" class="form-control form-control-sm" id="email" name="email"
        value="<?php echo $kontak['email']; ?>" placeholder="Opsional">
    </div>
  </div>

  <!-- Tambahkan input hidden untuk mengirim ID produk yang akan diubah -->
  <input type="hidden" name="id_pengirim" value="<?php echo $kontak['id_pengirim']; ?>">
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" name="edit">Simpan Perubahan</button>
</form>