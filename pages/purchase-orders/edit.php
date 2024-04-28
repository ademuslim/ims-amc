<?php
include '../header.php';
?>
    <main>
        <section class="form-section">
            <div class="form-info">
                <h2 class="form-title">Edit Data Loket</h2>
                <a href="index.php"><span class="material-symbols-outlined">arrow_back</span>KEMBALI</a>
            </div>

            <?php
            $id = @$_GET['id'];
            $sql_loket = mysqli_query($conn, "SELECT * FROM loket WHERE id_loket = '$id'") or die(mysqli_error($conn));
            $data = mysqli_fetch_array($sql_loket);

            ?>

            <form action="proses.php" method="post">
                <div class="form-group">
                    <input type="hidden" name="id" value="<?= $data['id_loket'] ?>">

                    <label for="kode_loket">Kode Loket</label>
                    <input type="text" id="kode_loket" name="kode_loket" value="<?= $data['kode_loket'] ?>" required autofocus>

                    <label for="nama_loket">Nama Loket</label>
                    <input type="text" id="nama_loket" name="nama_loket" value="<?= $data['nama_loket'] ?>" required>
                </div>

                <div class="form-group">
                    <label for="id_karyawan">Karyawan</label>
                    <select name="id_karyawan" id="id_karyawan" class="form-control" required>
                        <option value="" disabled>-- Pilih Karyawan --</option>
                        <?php
                        $sql_karyawan = mysqli_query($conn, "SELECT id_karyawan, nama, bagian FROM karyawan WHERE id_karyawan NOT IN (SELECT id_karyawan FROM loket WHERE id_loket != '$id')") or die(mysqli_error($conn));
                        while ($data_karyawan = mysqli_fetch_array($sql_karyawan)) {
                            $selected = ($data_karyawan['id_karyawan'] == $data_loket['id_karyawan']) ? 'selected' : '';
                            echo '<option value="' . $data_karyawan['id_karyawan'] . '" ' . $selected . '>
                                ' . ucwords($data_karyawan['nama']) . ' ( ' . $data_karyawan['bagian'] . ' )' . '</option>';
                        }
                        ?>
                    </select>
                    
                    <button class="success-btn mt" type="submit" name="edit">Simpan</button>
                </div>
            </form>
        </section>
    </main>

<!-- footer -->
<?php
include '../footer.php';