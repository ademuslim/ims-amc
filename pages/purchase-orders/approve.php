<!-- Form Pembaruan Status -->
<form action="process.php" method="POST">
  <!-- Input kategori -->
  <input type="hidden" name="kategori" value="<?= htmlspecialchars($category) ?>">

  <div class="mb-3">
    <select class="form-select" name="status" required>
      <option value="" selected disabled>Pilih Status</option>
      <!-- <option value="draft">Draft</option> -->
      <option value="terkirim">Terkirim</option>
      <option value="diproses">Diproses</option>
      <option value="selesai">Selesai</option>
    </select>
  </div>
  <input type="hidden" name="id_pesanan" value="<?= $po['id_pesanan'] ?>">
  <button type="submit" class="btn btn-primary" name="approve">Simpan</button>
</form>