<!-- Form Pembaruan Status -->
<form action="process.php" method="POST">
  <!-- Input kategori -->
  <input type="hidden" name="kategori" value="<?= htmlspecialchars($category) ?>">

  <div class="mb-3">
    <select class="form-select" name="status" required>
      <option value="" selected disabled>Pilih Status</option>
      <option value="tunggu kirim">Tunggu Kirim</option>
      <option value="belum dibayar">Belum Dibayar</option>
      <option value="dibayar">Dibayar</option>
    </select>
  </div>
  <input type="hidden" name="id_faktur" value="<?= $faktur['id_faktur'] ?>">
  <button type="submit" class="btn btn-primary" name="approve">Simpan</button>
</form>