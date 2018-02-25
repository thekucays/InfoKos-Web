<?php
$edit = mysql_query("SELECT * FROM pemilik WHERE id='" . $_SESSION['pemilik']['id'] . "'");
$r = mysql_fetch_array($edit);
?>
<h2>Profile Pemilik</h2>
<table>
    <tr>
        <th width="180px;">Username</th>
        <td> : <b><?php echo $r['id'] ?></b></td>
    </tr>
    <tr>
        <th>Email</th>
        <td> : <?php echo $r['email'] ?></td>
    </tr>
    <tr>
        <th>Nama Pemilik</th>
        <td> : <?php echo $r['nama'] ?></td>
    </tr>
    <tr>
        <th>No. Handphone</th>
        <td> : <?php echo $r['no_hp'] ?></td>
    </tr>
    <tr>
        <th>Jenis Kelamin</th>
        <td> :
            <?php
            if ($r['jenis_kelamin'] == 'L') {
                echo 'Laki - laki';
            } else {
                echo 'Perempuan';
            }
            ?>
        </td>
    </tr>
    <tr>
        <th>Alamat</th>
        <td> : <?php echo $r['alamat'] ?></td>
    </tr>
    <tr>
        <th>Status</th>
        <td> : 
            <?php
            if ($r['aktif']) {
                echo 'Aktif';
            } else {
                echo 'Non-Aktif';
            }
            ?>
        </td>
    </tr>
    <tr>
        <th></th>
        <td>
            <input type=button class='tombol' value=Edit onclick="window.location = 'media.php?page=edit_pemilik' "/>
        </td>
    </tr>
</table>
