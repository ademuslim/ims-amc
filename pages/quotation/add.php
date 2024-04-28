<!-- penawaran_harga/add.php -->

<!-- Modal -->
<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addModalLabel">Tambah Data Penawaran Harga</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="process.php" method="POST">
          <div class="mb-3">
            <label for="no_penawaran" class="form-label">Nomor Penawaran:</label>
            <input type="text" class="form-control" id="no_penawaran" name="no_penawaran" required>
          </div>
          <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal:</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
          </div>
          <div class="mb-3">
            <label for="total" class="form-label">Total:</label>
            <input type="number" class="form-control" id="total" name="total" min="0" required>
          </div>
          <div class="mb-3">
            <label for="catatan" class="form-label">Catatan:</label>
            <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
          </div>
          <div class="mb-3">
            <label for="id_penerima" class="form-label">ID Penerima:</label>
            <input type="text" class="form-control" id="id_penerima" name="id_penerima" required>
          </div>
          <div class="mb-3">
            <label for="diskon" class="form-label">Diskon:</label>
            <input type="number" class="form-control" id="diskon" name="diskon" min="0" required>
          </div>
          <div class="mb-3">
            <label for="id_ppn" class="form-label">ID PPN:</label>
            <input type="text" class="form-control" id="id_ppn" name="id_ppn" required>
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