<!-- master-data/user/add -->

<!-- Modal -->
<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addModalLabel">Tambah Data Pengguna</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="process.php" method="POST">
          <div class="row mb-3">
            <label for="nama_lengkap" class="col-sm-3 col-form-label">Nama Lengkap:</label>
            <div class="col-sm-9">
              <input type="text" class="auto-focus form-control form-control-sm" id="nama_lengkap" name="nama_lengkap"
                required>
            </div>
          </div>
          <div class="row mb-3">
            <label for="nama_pengguna" class="col-sm-3 col-form-label">Username:</label>
            <div class="col-sm-9">
              <input type="nama_pengguna" class="form-control form-control-sm" id="nama_pengguna" name="nama_pengguna"
                required>
            </div>
          </div>
          <div class="row mb-3">
            <label for="password" class="col-sm-3 col-form-label">Password:</label>
            <div class="col-sm-9">
              <input type="password" class="form-control form-control-sm" id="password" name="password" required>
            </div>
          </div>
          <div class="row mb-3">
            <label for="confirm_password" class="col-sm-3 col-form-label">Ulangi Password:</label>
            <div class="col-sm-9">
              <input type="password" class="form-control form-control-sm" id="confirm_password" name="confirm_password"
                required>
            </div>
          </div>
          <div class="row mb-3">
            <label for="tipe_pengguna" class="col-sm-3 col-form-label">Tipe Pengguna:</label>
            <div class="col-sm-9">
              <select class="form-select form-select-sm" id="tipe_pengguna" name="tipe_pengguna" required>
                <option value="staff">Staff</option>
                <option value="kepala_perusahaan">Kepala Perusahaan</option>
                <option value="superadmin">Superadmin</option>
              </select>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary" name="add">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>