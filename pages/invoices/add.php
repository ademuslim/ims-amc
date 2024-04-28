<?php
include '../header.php';
?>
    <main>
        <section class="form-container">
            <div class="form-info">
                <h3 class="form-title">Tambah Nasabah</h3>
                <a href="index.php"><span class="material-symbols-outlined">arrow_back</span>KEMBALI</a>
            </div>
            <form action="proses.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nik">NIK</label>
                    <input type="text" id="nik" name="nik" placeholder="NIK" required>

                    <label for="nama">Nama lengkap</label>
                    <input type="text" id="nama" name="nama" placeholder="Nama lengkap" required>

                    <label for="jenis_kelamin">Jenis kelamin</label>
                    <div class="form-radio">
                        <div class="form-group-radio">
                            <input type="radio" id="jenis_kelamin_pria" name="jenis_kelamin" value="laki-laki" required>
                            <label for="jenis_kelamin_pria">Laki-laki</label>
                        </div>
                        <div class="form-group-radio">
                            <input type="radio" id="jenis_kelamin_wanita" name="jenis_kelamin" value="perempuan" required>
                            <label for="jenis_kelamin_wanita">Perempuan</label>
                        </div>
                    </div>

                    <label for="tanggal_lahir">Tanggal lahir</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" placeholder="Tanggal lahir" required>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" id="alamat" name="alamat" placeholder="Alamat" required>

                    <label for="no_hp">Nomor handphone</label>
                    <input type="text" id="no_hp" name="no_hp" placeholder="Contoh: 012345678901" required>

                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>

                    <label for="foto">Foto (Opsional)</label>
                    <input type="file" id="foto" name="foto" accept="image/*">

                    <button class="success-btn" type="submit" name="add">Simpan</button>
                </div>
            </form>
        </section>
    </main>

<!-- footer -->
<?php
include '../footer.php';