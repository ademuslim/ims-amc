<!-- master-data/user/add -->

<!-- Modal -->
<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addModalLabel">Tambah Data Pengguna</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="process.php" method="POST">
          <div class="mb-3">
            <label for="nama_pengguna" class="form-label">Nama Pengguna:</label>
            <input type="text" class="form-control" id="nama_pengguna" name="nama_pengguna" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <div class="mb-3">
            <label for="confirm_password" class="form-label">Ulangi Password:</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
          </div>
          <div class="mb-3">
            <label for="tipe_pengguna" class="form-label">Tipe Pengguna:</label>
            <select class="form-select" id="tipe_pengguna" name="tipe_pengguna" required>
              <option value="staff">Staff</option>
              <option value="kepala_perusahaan">Kepala Perusahaan</option>
              <option value="superadmin">Superadmin</option>
            </select>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" name="add">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>