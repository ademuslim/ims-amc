<!-- Form Pembaruan Status -->
<form action="process.php" method="POST">
  <!-- Input kategori -->
  <input type="hidden" name="kategori" value="<?= htmlspecialchars($category) ?>">

  <div class="mb-3">
    <select class="form-select" name="status" required>
      <option value="" selected disabled>Pilih Status</option>
      <option value="disetujui">Disetujui</option>
      <option class="text-danger" value="ditolak">Ditolak</option>
    </select>
  </div>
  <input type="hidden" name="id_penawaran" value="<?= $ph['id_penawaran'] ?>">
  <button type="submit" class="btn btn-primary" name="approve">Simpan</button>
</form>