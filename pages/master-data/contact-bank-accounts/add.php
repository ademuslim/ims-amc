<!-- Modal Add Rekening Kontak -->
<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addModalLabel">Tambah Data Rekening Kontak</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="process.php" method="POST">
          <!-- Nama Kontak -->
          <div class="row mb-3">
            <label for="addKontak" class="col-sm-3 col-form-label">Nama Kontak:</label>
            <div class="col-sm-9">
              <select class="form-select form-select-sm" id="addKontak" name="id_kontak" required>
                <option value="" selected disabled>-- Pilih Kontak --</option>
                <?php
                  // Ambil data kontak yang aktif
                  $kontak = selectData("kontak", "status_hapus = 0");
                  foreach ($kontak as $row_kontak) {
                    echo "<option value='" . htmlspecialchars($row_kontak['id_kontak']) . "'>" . htmlspecialchars(strtoupper($row_kontak['nama_kontak'])) . "</option>";
                  }
                ?>
              </select>
            </div>
          </div>
          <!-- Nama Bank -->
          <div class="row mb-3">
            <label for="addNamaBank" class="col-sm-3 col-form-label">Nama Bank:</label>
            <div class="col-sm-9">
              <input type="text" class="auto-focus form-control form-control-sm" id="addNamaBank" name="nama_bank"
                required placeholder="Nama Bank">
            </div>
          </div>
          <!-- Nomor Rekening -->
          <div class="row mb-3">
            <label for="addNoRekening" class="col-sm-3 col-form-label">Nomor Rekening:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control form-control-sm" id="addNoRekening" name="nomor_rekening" required
                placeholder="Nomor Rekening">
            </div>
          </div>
          <!-- Pemegang Rekening -->
          <div class="row mb-3">
            <label for="addPemegangRekening" class="col-sm-3 col-form-label">Atas Nama:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control form-control-sm" id="addPemegangRekening" name="pemegang_rekening"
                required placeholder="Atas Nama">
            </div>
          </div>
          <!-- Kode SWIFT -->
          <div class="row mb-3">
            <label for="addKodeSWIFT" class="col-sm-3 col-form-label">Kode SWIFT:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control form-control-sm" id="addKodeSWIFT" name="kode_swift"
                placeholder="Kode SWIFT (Opsional)">
            </div>
          </div>
          <!-- Cabang Bank -->
          <div class="row mb-3">
            <label for="addCabangBank" class="col-sm-3 col-form-label">Cabang Bank:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control form-control-sm" id="addCabangBank" name="cabang_bank"
                placeholder="Cabang Bank (Opsional)">
            </div>
          </div>
          <!-- Keterangan -->
          <div class="row mb-3">
            <label for="addKeterangan" class="col-sm-3 col-form-label">Keterangan:</label>
            <div class="col-sm-9">
              <textarea class="form-control form-control-sm" id="addKeterangan" name="keterangan" rows="2"
                placeholder="Keterangan (Opsional)"></textarea>
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