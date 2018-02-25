<?php
    $tempat = mysql_fetch_array(mysql_query('SELECT kost.*, kost.id AS kosan_id, tempat.id AS tempat_id, tempat.lintang, tempat.bujur,tempat.kost_id FROM kost LEFT JOIN tempat ON kost.id = tempat.kost_id WHERE kost.id='.$_GET[id]));
?>
<style>
    li.list-img{
        float:left;
        width:50%;
        height:64px;
        list-style: none; 
        vertical-align: middle;
    }
    div.hide{
        z-index: 999;
        width: 700px;
        height: auto;
        position: absolute;
        top: 500px;
        left: 200px;
        font-size: 12px;
        display: none;
    }
</style>
<div style="clear:both;height: 30px;"></div>
<div style="margin: 10px auto;width: 100%;height: 600px; border: 2px #C30075 solid;border-radius: 10px;-moz-border-radius: 10px;-webkit-border-radius: 10px; background: #C30075;box-shadow: 0 0 5px #000;">
    <div id="maps" style="float: left;width: 100%;height: 100%;border-bottom-left-radius: 10px;border-top-left-radius: 10px;-moz-border-bottom-left-radius: 10px;-moz-border-top-left-radius: 10px;-webkit-border-top-left-radius: 10px;-webkit-border-bottom-left-radius: 10px; background: #C30075;"></div>
</div>
<div style="clear:both;height: 30px;"></div>

<h2>Tambah Lokasi</h2>
<form method='post' action="modul/<?php echo substr($_GET[page], 4); ?>/aksi.php?act=input" onSubmit="return validasi(this)" id="form_data"/>
<input type="hidden" name="id" id="id_tempat" value="<?php echo $tempat[tempat_id]; ?>"/>
<input type="hidden" name="kost_id" id="kost" value="<?php echo $tempat[kosan_id]; ?>"/>
<table>
    <tr>
        <td>Lintang</td>
        <td> : <input type="text" name='lintang' size='40' readonly="readonly" id="lintang" value="<?php echo $tempat[lintang]; ?>"/></td>
    </tr>
    <tr>
        <td>Bujur</td>
        <td> : <input type="text" name="bujur" size="40" readonly="readonly" id="bujur" value="<?php echo $tempat[bujur]; ?>"></td>
    </tr>
    <tr style="display:none;">
        <td>Nama</td>
        <td> : <input type="text" name="nama" size="40" id="nama" value="<?php echo $tempat[nama]; ?>"></td>
    </tr>
    <tr style="display:none;">
        <td>Keterangan</td>
        <td> : <textarea id="ket" cols="52" rows="5"><?php echo $tempat[keterangan]; ?></textarea></td>
    </tr>
    <tr style="display:none;">
        <td>Alamat</td>
        <td> : <textarea id="alamat" cols="52" rows="5"><?php echo $tempat[alamat_kost]; ?></textarea></td>
    </tr>
    <tr>
        <td colspan=2>
            <input type=button name=submit class='tombol' value="Simpan" id="simpan">
            <input type=button class='tombol' value=Batal onclick="window.location = 'media.php?page=kost' "/>
        </td>
    </tr>
</table>
</form>

