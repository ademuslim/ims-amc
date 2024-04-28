<!-- master-data/product/add -->

<!-- Modal -->
<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addModalLabel">Tambah Data Kontak Internal</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="process.php" method="POST">
          <div class="mb-3">
            <label for="nama_pengirim" class="form-label">Nama:</label>
            <input type="text" class="form-control" id="nama_pengirim" name="nama_pengirim" required>
          </div>
          <div class="mb-3">
            <label for="alamat_pengirim" class="form-label">Alamat:</label>
            <input type="text" class="form-control" id="alamat_pengirim" name="alamat_pengirim" required>
          </div>
          <div class="mb-3">
            <label for="telepon" class="form-label">No. Handphone:</label>
            <input type="telp" class="form-control" id="telepon" name="telepon" pattern="[0-9]{10,}" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Opsional">
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