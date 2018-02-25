<?php

session_start();
include "../../../config/koneksi.php";

$password = md5($_POST[password]);
if ($_GET[act] == 'update') {
    if ($password == $_SESSION['pemilik']['password'] && $_POST[password_new] == $password[password_conf]) {
        $password = md5($_POST[password_new]);
        $_SESSION['pemilik']['password'] = $password;
    } else {
        $_POST[password] = '';
    }

    if ($_POST[password] != "") {
        mysql_query("UPDATE pemilik SET nama='$_POST[nama]',
               password='$password',
               no_hp = '$_POST[no_hp]',
               alamat = '$_POST[alamat]',
               jenis_kelamin = '$_POST[jenis_kelamin]',
               aktif = '$_POST[aktif]',
               email = '$_POST[email]'
               WHERE id = '" . $_SESSION['pemilik']['id'] . "'");
    } else {
        mysql_query("UPDATE pemilik SET nama='$_POST[nama]',
               no_hp = '$_POST[no_hp]',
               alamat = '$_POST[alamat]',
               jenis_kelamin = '$_POST[jenis_kelamin]',
               aktif = '$_POST[aktif]',
               email = '$_POST[email]'
               WHERE id = '" . $_SESSION['pemilik']['id'] . "'");
    }
}
header("Location:../../media.php?page=pemilik");
?>
