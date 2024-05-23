<!-- Form untuk mengedit produk -->
<form action="process.php" method="POST">
  <div class="row mb-3">
    <label for="nama_kontak" class="col-sm-3 col-form-label">Nama:</label>
    <div class="col-sm-9">
      <!-- Perbarui nilai default dengan data yang ada -->
      <input type="text" class="auto-focus form-control form-control-sm" id="nama_kontak" name="nama_kontak"
        value="<?php echo $kontak['nama_kontak']; ?>">
    </div>
  </div>
  <div class="row mb-3">
    <label for="email" class="col-sm-3 col-form-label">Email:</label>
    <div class="col-sm-9">
      <!-- Perbarui nilai default dengan data yang ada -->
      <input type="email" class="form-control form-control-sm" id="email" name="email"
        value="<?php echo $kontak['email']; ?>" placeholder="Opsional">
    </div>
  </div>
  <!-- Tambahkan kontrol untuk nomor telepon -->
  <div class="row mb-3">
    <label for="telepon" class="col-sm-3 col-form-label">Telepon:</label>
    <div class="col-sm-9">
      <!-- Perbarui nilai default dengan data yang ada -->
      <input type="tel" class="form-control form-control-sm" id="telepon" name="telepon"
        value="<?php echo $kontak['telepon']; ?>" pattern="[0-9]{10,}" placeholder="Opsional">
    </div>
  </div>
  <!-- Tambahkan kontrol untuk alamat -->
  <div class="row mb-3">
    <label for="alamat" class="col-sm-3 col-form-label">Alamat:</label>
    <div class="col-sm-9">
      <!-- Perbarui nilai default dengan data yang ada -->
      <input type="text" class="form-control form-control-sm" id="alamat" name="alamat"
        value="<?php echo $kontak['alamat']; ?>" required>
    </div>
  </div>
  <div class="row mb-3">
    <label for="keterangan" class="col-sm-3 col-form-label">Keterangan:</label>
    <div class="col-sm-9">
      <textarea class="form-control" id="keterangan" name="keterangan"
        style="height: 100px"><?php echo $kontak['keterangan']; ?></textarea>
    </div>
  </div>
  <!-- Tambahkan input hidden untuk mengirim ID produk yang akan diubah -->
  <input type="hidden" name="id_kontak" value="<?php echo $kontak['id_kontak']; ?>">
  <!-- Tambahkan input hidden untuk menyimpan kategori -->
  <input type="hidden" name="category" value="<?php echo $kontak['kategori']; ?>">
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" name="edit">Simpan Perubahan</button>
</form>