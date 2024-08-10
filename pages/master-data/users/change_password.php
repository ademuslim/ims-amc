<!-- Form ubah password pengguna -->
<form action="process.php" method="POST">
  <div class="row mb-3">
    <label for="password_lama_<?= $pengguna['id_pengguna']; ?>" class="col-sm-3 col-form-label">Password Lama:</label>
    <div class="col-sm-9">
      <input type="password" class="form-control form-control-sm" id="password_lama_<?= $pengguna['id_pengguna']; ?>"
        name="password_lama" required>
    </div>
  </div>
  <div class="row mb-3">
    <label for="password_baru_<?= $pengguna['id_pengguna']; ?>" class="col-sm-3 col-form-label">Password Baru:</label>
    <div class="col-sm-9">
      <input type="password" class="form-control form-control-sm" id="password_baru_<?= $pengguna['id_pengguna']; ?>"
        name="password_baru" required>
    </div>
  </div>
  <div class="row mb-3">
    <label for="ulangi_password_<?= $pengguna['id_pengguna']; ?>" class="col-sm-3 col-form-label">Ulangi
      Password:</label>
    <div class="col-sm-9">
      <input type="password" class="form-control form-control-sm" id="ulangi_password_<?= $pengguna['id_pengguna']; ?>"
        name="ulangi_password" required>
    </div>
  </div>

  <input type="hidden" name="id_pengguna" value="<?= $pengguna['id_pengguna']; ?>">

  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
    <button type="submit" class="btn btn-primary" name="change_password">Simpan Perubahan</button>
  </div>
</form>