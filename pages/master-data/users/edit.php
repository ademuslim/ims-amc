<!-- Form edit pengguna -->
<form action="process.php" method="POST">
  <div class="row mb-3">
    <label for="nama_lengkap_<?= $pengguna['id_pengguna']; ?>" class="col-sm-3 col-form-label">Nama Lengkap:</label>
    <div class="col-sm-9">
      <input type="text" class="auto-focus form-control form-control-sm"
        id="nama_lengkap_<?= $pengguna['id_pengguna']; ?>" name="nama_lengkap"
        value="<?= ucwords($pengguna['nama_lengkap']); ?>" required>
    </div>
  </div>

  <div class="row mb-3">
    <label for="nama_pengguna_<?= $pengguna['id_pengguna']; ?>" class="col-sm-3 col-form-label">Nama Pengguna:</label>
    <div class="col-sm-9">
      <input type="text" class="auto-focus form-control form-control-sm"
        id="nama_pengguna_<?= $pengguna['id_pengguna']; ?>" name="nama_pengguna"
        value="<?= $pengguna['nama_pengguna']; ?>" required>
    </div>
  </div>

  <div class="row mb-3">
    <label for="password_saat_ini_<?= $pengguna['id_pengguna']; ?>" class="col-sm-3 col-form-label">Password Saat
      Ini:</label>
    <div class="col-sm-9">
      <input type="password" class="auto-focus form-control form-control-sm"
        id="password_saat_ini_<?= $pengguna['id_pengguna']; ?>" name="password_saat_ini" required>
    </div>
  </div>

  <!-- Tambahkan ID unik untuk input password -->
  <input type="hidden" name="id_pengguna" value="<?= $pengguna['id_pengguna']; ?>">

  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
    <button type="submit" class="btn btn-primary" name="edit">Simpan Perubahan</button>
  </div>
</form>