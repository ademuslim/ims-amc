<!-- Form edit pengguna -->
<form action="process.php" method="POST">
  <div class="row mb-3">
    <label for="nama_lengkap_<?= $pengguna['id_pengguna']; ?>" class="col-sm-3 col-form-label">Nama Lengkap:</label>
    <div class="col-sm-9">
      <input type="text" class="auto-focus form-control form-control-sm"
        id="nama_lengkap_<?= $pengguna['id_pengguna']; ?>" name="nama_lengkap"
        value="<?= ucwords($pengguna['nama_lengkap']); ?>">
    </div>
  </div>

  <div class="row mb-3">
    <label for="nama_pengguna_<?= $pengguna['id_pengguna']; ?>" class="col-sm-3 col-form-label">Nama Pengguna:</label>
    <div class="col-sm-9">
      <input type="text" class="auto-focus form-control form-control-sm"
        id="nama_pengguna_<?= $pengguna['id_pengguna']; ?>" name="nama_pengguna"
        value="<?= $pengguna['nama_pengguna']; ?>">
    </div>
  </div>

  <!-- Tambahkan ID unik untuk input password -->
  <input type="hidden" class="form-control form-control-sm" id="password_<?= $pengguna['id_pengguna']; ?>"
    name="password" value="<?= $pengguna['password']; ?>">

  <div class="row mb-3">
    <label for="tipe_pengguna_<?= $pengguna['id_pengguna']; ?>" class="col-sm-3 col-form-label">Tipe Pengguna:</label>
    <div class="col-sm-9">
      <select class="form-select form-select-sm" id="tipe_pengguna_<?= $pengguna['id_pengguna']; ?>"
        name="tipe_pengguna">
        <option value="superadmin" <?= ($pengguna['tipe_pengguna'] == 'superadmin') ? 'selected' : ''; ?>>Superadmin
        </option>
        <option value="staff" <?= ($pengguna['tipe_pengguna'] == 'staff') ? 'selected' : ''; ?>>Staff</option>
        <option value="kepala_perusahaan" <?= ($pengguna['tipe_pengguna'] == 'kepala_perusahaan') ? 'selected' : ''; ?>>
          Kepala Perusahaan</option>
      </select>
    </div>
  </div>

  <!-- Tambahkan input hidden untuk mengirim ID pengguna yang akan diubah -->
  <input type="hidden" name="id_pengguna" value="<?= $pengguna['id_pengguna']; ?>">
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" name="edit">Simpan Perubahan</button>
</form>