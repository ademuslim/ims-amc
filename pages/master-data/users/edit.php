<!-- master-data/user/edit -->

<!-- Form untuk mengedit pengguna -->
<form action="process.php" method="POST">
  <div class="mb-3">
    <label for="nama_pengguna" class="form-label">Nama Pengguna:</label>
    <input type="text" class="form-control" id="nama_pengguna" name="nama_pengguna"
      value="<?php echo $pengguna['nama_pengguna']; ?>">
  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email:</label>
    <input type="email" class="form-control" id="email" name="email" value="<?php echo $pengguna['email']; ?>">
  </div>
  <input type="hidden" class="form-control" id="password" name="password" value="<?php echo $pengguna['password']; ?>">
  <div class="mb-3">
    <label for="tipe_pengguna" class="form-label">Tipe Pengguna:</label>
    <select class="form-select" id="tipe_pengguna" name="tipe_pengguna">
      <option value="superadmin" <?php echo ($pengguna['tipe_pengguna'] == 'superadmin') ? 'selected' : ''; ?>>
        Superadmin</option>
      <option value="staff" <?php echo ($pengguna['tipe_pengguna'] == 'staff') ? 'selected' : ''; ?>>Staff</option>
      <option value="kepala_perusahaan"
        <?php echo ($pengguna['tipe_pengguna'] == 'kepala_perusahaan') ? 'selected' : ''; ?>>Kepala Perusahaan</option>
    </select>
  </div>
  <!-- Tambahkan input hidden untuk mengirim ID pengguna yang akan diubah -->
  <input type="hidden" name="id_pengguna" value="<?php echo $pengguna['id_pengguna']; ?>">
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" name="edit">Simpan Perubahan</button>
</form>