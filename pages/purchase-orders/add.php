<?php
include '../header.php';
?>
    <main>
        <section class="form-section">
            <div class="form-info">
                <h2 class="form-title">Tambah Loket</h2>
                <a href="index.php"><span class="material-symbols-outlined">arrow_back</span>KEMBALI</a>
            </div>
            <form action="proses.php" method="post">
                <div class="form-group">
                    <label for="kode_loket">Kode Loket</label>
                    <input type="text" id="kode_loket" name="kode_loket" placeholder="Kode loket" required>

                    <label for="nama_loket">Nama Loket</label>
                    <input type="text" id="nama_loket" name="nama_loket" placeholder="Nama loket" required>
                </div>

                <div class="form-group">
                    <label for="id_karyawan">Karyawan</label>
                    <select name="id_karyawan" id="id_karyawan" class="form-control" required>
                        <option value="" disabled selected>-- Pilih Karyawan --</option>
                        <!-- Looping data karyawan-->
                        <?php
                        $sql_karyawan = mysqli_query($conn, "SELECT id_karyawan, nama, bagian FROM karyawan WHERE id_karyawan NOT IN (SELECT id_karyawan FROM loket)") or die(mysqli_error($conn));
                        while ($data = mysqli_fetch_array($sql_karyawan)) {
                            echo '<option value="' . $data['id_karyawan'] . '">
                            ' . ucwords($data['nama']) . ' ( ' . $data['bagian'] . ' )' . '</option>';
                        }
                        ?>
                    </select>

                    <button class="success-btn mt" type="submit" name="add">Simpan</button>
                </div>
            </form>
        </section>
    </main>

<!-- footer -->
<?php
include '../footer.php';