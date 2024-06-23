<!-- Form Pembaruan Status -->
<form action="process.php" method="POST">
  <input type="hidden" name="id_faktur" value="<?= $faktur['id_faktur'] ?>">
  <input type="hidden" name="kategori" value="<?= htmlspecialchars($category) ?>">

  <div class="mb-3">
    <select class="form-select" name="status" required>
      <option value="" disabled <?= $faktur['status'] == '' ? 'selected' : '' ?>>Pilih Status</option>
      <option value="tunggu kirim" <?= $faktur['status'] == 'tunggu kirim' ? 'selected' : '' ?>>Tunggu Kirim</option>
      <option value="belum dibayar" <?= $faktur['status'] == 'belum dibayar' ? 'selected' : '' ?>>Belum Dibayar</option>
      <option value="dibayar" <?= $faktur['status'] == 'dibayar' ? 'selected' : '' ?>>Dibayar</option>
    </select>
  </div>
  <button type="submit" class="btn btn-primary" name="approve">Simpan</button>
</form>