<?php
require_once "../_config.php";

mysqli_query($conn, "DELETE FROM loket WHERE id_loket = '$_GET[id]'") or die(mysqli_error($conn));
echo "<script>window.location='index.php';</script>";