<!-- master-data/product/add -->

<!-- Modal -->
<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addModalLabel">Tambah Data Produk</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="process.php" method="POST">
          <div class="row mb-3">
            <label for="no_produk" class="col-sm-3 col-form-label">Nomor Produk:</label>
            <div class="col-sm-9">
              <input type="text" class="auto-focus form-control form-control-sm" id="no_produk" name="no_produk"
                required>
            </div>
          </div>
          <div class="row mb-3">
            <label for="nama_produk" class="col-sm-3 col-form-label">Nama Produk:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control form-control-sm" id="nama_produk" name="nama_produk" required>
            </div>
          </div>
          <div class="row mb-3">
            <label for="satuan" class="col-sm-3 col-form-label">Satuan:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control form-control-sm" id="satuan" name="satuan" required>
            </div>
          </div>
          <div class="row mb-3">
            <label for="harga" class="col-sm-3 col-form-label">Harga:</label>
            <div class="col-sm-9">
              <input type="number" class="form-control form-control-sm" id="harga" name="harga" min="0" required>
            </div>
          </div>
          <div class="row mb-3">
            <label for="keterangan" class="col-sm-3 col-form-label">Keterangan:</label>
            <div class="col-sm-9">
              <textarea class="form-control" id="keterangan" name="keterangan" placeholder="Opsional."></textarea>
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