<!-- master-data/penawaran_harga/edit.php -->

<!-- Form untuk mengedit penawaran harga -->
<form action="process.php" method="POST">
  <div class="mb-3">
    <label for="no_penawaran" class="form-label">Nomor Penawaran:</label>
    <input type="text" class="form-control" id="no_penawaran" name="no_penawaran"
      value="<?= $penawaran['no_penawaran']; ?>" required>
  </div>
  <div class="mb-3">
    <label for="tanggal" class="form-label">Tanggal:</label>
    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= $penawaran['tanggal']; ?>" required>
  </div>
  <div class="mb-3">
    <label for="total" class="form-label">Total:</label>
    <input type="number" class="form-control" id="total" name="total" value="<?= $penawaran['total']; ?>" min="0"
      required>
  </div>
  <div class="mb-3">
    <label for="catatan" class="form-label">Catatan:</label>
    <textarea class="form-control" id="catatan" name="catatan" rows="3"><?= $penawaran['catatan']; ?></textarea>
  </div>
  <div class="mb-3">
    <label for="id_penerima" class="form-label">ID Penerima:</label>
    <input type="text" class="form-control" id="id_penerima" name="id_penerima"
      value="<?= $penawaran['id_penerima']; ?>" required>
  </div>
  <div class="mb-3">
    <label for="diskon" class="form-label">Diskon:</label>
    <input type="number" class="form-control" id="diskon" name="diskon" value="<?= $penawaran['diskon']; ?>" min="0"
      required>
  </div>
  <div class="mb-3">
    <label for="id_ppn" class="form-label">ID PPN:</label>
    <input type="text" class="form-control" id="id_ppn" name="id_ppn" value="<?= $penawaran['id_ppn']; ?>" required>
  </div>

  <!-- Tambahkan input hidden untuk mengirim ID penawaran harga yang akan diubah -->
  <input type="hidden" name="id_penawaran" value="<?= $penawaran['id_penawaran']; ?>">
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" name="edit">Simpan Perubahan</button>
</form>