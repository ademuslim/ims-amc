<!-- master-data/ppn/add -->

<!-- Modal -->
<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addModalLabel">Tambah Data PPN</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="process.php" method="POST">
          <div class="row mb-3">
            <label for="jenis_ppn" class="col-sm-3 col-form-label">Jenis PPN:</label>
            <div class="col-sm-9">
              <input type="text" class="auto-focus form-control form-control-sm" id="jenis_ppn" name="jenis_ppn"
                required placeholder="Misal: PPN 10%">
            </div>
          </div>
          <div class="row mb-3">
            <label for="tarif" class="col-sm-3 col-form-label">Tarif:</label>
            <div class="col-sm-9">
              <input type="number" class="form-control form-control-sm" id="tarif" min="0" name="tarif" required
                placeholder="Misal: 10 (Untuk PPN 10%)">
            </div>
          </div>
          <div class="row mb-3">
            <label for="keterangan" class="col-sm-3 col-form-label">Keterangan:</label>
            <div class="col-sm-9">
              <textarea class="form-control form-control-sm" id="keterangan" name="keterangan" rows="2"
                placeholder="Opsional."></textarea>
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