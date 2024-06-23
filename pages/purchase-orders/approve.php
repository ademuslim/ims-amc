<!-- Form Pembaruan Status -->
<form action="process.php" method="POST">
  <input type="hidden" name="id_pesanan" value="<?= $po['id_pesanan'] ?>">
  <input type="hidden" name="kategori" value="<?= htmlspecialchars($category) ?>">

  <div class="mb-3">
    <select class="form-select" name="status" required>
      <option value="" disabled <?= $po['status'] == '' ? 'selected' : '' ?>>Pilih Status</option>
      <option value="draft" <?= $po['status'] == 'draft' ? 'selected' : '' ?>>Draft</option>
      <option value="terkirim" <?= $po['status'] == 'terkirim' ? 'selected' : '' ?>>Terkirim</option>
      <option value="diproses" <?= $po['status'] == 'diproses' ? 'selected' : '' ?>>Diproses</option>
      <option value="selesai" <?= $po['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
    </select>
  </div>

  <button type="submit" class="btn btn-primary" name="approve">Simpan</button>
</form>