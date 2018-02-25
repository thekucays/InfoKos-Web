<?php

session_start();
include "../../../config/koneksi.php";

if ($_GET[act] == 'konfirmasi') {
    mysql_query('UPDATE pembayaran SET konfirmasi = 1 WHERE id='.$_GET[id]);
}

header("Location:../../media.php?page=pembayaran&p=".$_SESSION['admin']['pagination']['pembayaran']);
?>
