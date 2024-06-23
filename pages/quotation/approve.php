<!-- Form Pembaruan Status -->
<form action="process.php" method="POST">
  <input type="hidden" name="id_penawaran" value="<?= $ph['id_penawaran'] ?>">
  <input type="hidden" name="kategori" value="<?= htmlspecialchars($category) ?>">

  <div class="mb-3">
    <select class="form-select" name="status" required>
      <option value="" disabled <?= $ph['status'] == '' ? 'selected' : '' ?>>Pilih Status</option>
      <option value="draft" <?= $ph['status'] == 'draft' ? 'selected' : '' ?>>Draft</option>
      <option value="ditolak" <?= $ph['status'] == 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
      <option value="disetujui" <?= $ph['status'] == 'disetujui' ? 'selected' : '' ?>>Disetujui
      </option>
    </select>
  </div>
  <button type="submit" class="btn btn-primary" name="approve">Simpan</button>
</form>