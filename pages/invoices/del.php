<?php
require_once "../_config.php";

mysqli_query($conn, "DELETE FROM nasabah WHERE id_nasabah = '$_GET[id]'") or die(mysqli_error($conn));
echo "<script>window.location='index.php';</script>";