<!-- master-data/product/add -->

<!-- Modal -->
<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addModalLabel">Tambah Data Pelanggan</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="process.php" method="POST">
          <div class="row mb-3">
            <label for="nama_pelanggan" class="col-sm-3 col-form-label">Nama:</label>
            <div class="col-sm-9">
              <input type="text" class="auto-focus form-control form-control-sm" id="nama_pelanggan"
                name="nama_pelanggan" required>
            </div>
          </div>
          <div class="row mb-3">
            <label for="alamat" class="col-sm-3 col-form-label">Alamat:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control form-control-sm" id="alamat" name="alamat" required>
            </div>
          </div>
          <div class="row mb-3">
            <label for="telepon" class="col-sm-3 col-form-label">Telepon</label>
            <div class="col-sm-9">
              <input type="tel" class="form-control form-control-sm" id="telepon" name="telepon" pattern="[0-9]{10,}"
                required>
            </div>
          </div>
          <div class="row mb-3">
            <label for="email" class="col-sm-3 col-form-label">Email:</label>
            <div class="col-sm-9">
              <input type="email" class="form-control form-control-sm" id="email" name="email" placeholder="Opsional">
            </div>
          </div>
          <div class="row mb-3">
            <label for="keterangan" class="col-sm-3 col-form-label">Keterangan:</label>
            <div class="col-sm-9">
              <textarea class="form-control form-control-sm" id="keterangan" name="keterangan" rows="3"></textarea>
            </div>
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