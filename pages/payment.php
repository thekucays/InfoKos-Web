<div class="payment">
    <h1>PEMESANAN DAN PEMBAYARAN</h1>	
    <div class="section1">	
        <div class="article">
            <h3>Cara melakukan pemesanan dan pembayaran kamar kosan adalah :</h3>	
            <ul>
                <li><p>Pertama anda harus menjadi member di aplikasi untuk mempunya id.</p></li>
                <li><p>Pilih kamar kosan yang masih kosong untuk di pesan.</p></li>
                <li><p>Setelah mendapatkan kamar kosan yang diinginkan, tekan tombol "pesan sekarang" lengkapi semua form untuk melakukan pemesanan. Dari jenis kamar untuk bulanan, 6 bulan, atau tahunan.Dan tanggal masuknya.</p></li>
                <li><p>Setalah melakukan pemesanan kamar kosan. pemesanan anda akan aktif selama 2 hari, jika 2 hari belum melakukan konfrimasi pembayaran maka transaksi akan kami anggap batal.</p></li>
                <li><p>Daftar kamar kosan yang telah dipesan akan masuk di Daftar Transaksi.</p></li>
                <li><p>Lakukan pembayaran di rekening bank yang telah di cantumkan. Kirim biaya sewa kamar kosan di ATM untuk mendapatkan struk ATM. </p></li>
                <li><p>Untuk melakukan konfirmasi pembayaran tekan tombol bayar di Daftar Transaksi. Masukan nomer resi yang ada di struk ATM di form pembayaran dan lengkapi form pembayaran.</p></li>
                <li><p>Tunggu konfirmasi dari admin paling lambat 24jam. Untuk dapat mencetak kuitansi pembayaran sewa kamar kosan.</p></li>
                <li><p>Setelah di konfirmasi admin, kuitansi pembayaran sewa kamar kosan dapat di print oleh anda sebagai tanda bukti sewa kosan kepada pemilik kosan.</p></li>
                <li><p>Proses pembayaran selesai.</p></li>
            </ul>
            <h3>Kebijakan pemesanan dan pembayaran yang harus di ingat adalah : </h3>
            <ul>
                <li><p>Jika susah melakukan pemesanan kamar kosan, namun dilakukan pembatalan pemesanan kamar kosan. Kamar kosan yang telah di batalakan dapat di pesan oleh pelanggan lain.</p></li>
                <li><p>Jika pemesanan kamar kosan sudah dilakukan pembayaran, namun dilakukan pembatalan. Biaya yang sudah dikirimkan tidak dapat dikembalikan, maka pemesanan kamar kosan dianggap batal dan kamar kosan dapat dipesan pelanggan yang lain.</p></li>
                <li><p>Untuk harga kamar dan jenis kamar kosan untuk putra putri bisa berubah sewaktu-waktu.</p></li>
            </ul>
        </div>
    </div>
    <!--
	<div class="section2">
        <div class="article">
            <h3>Rekening Bank untuk melakukan transaksi : </h3>
            <?php
                $bank = mysql_query('SELECT * FROM bank WHERE aktif = 1');
                $i=0;
                while ($r = mysql_fetch_array($bank)){
                    $i++;
                    echo "<p>$i. $r[nama] ($r[no_rekening]) atas nama ".ucwords($r[nama_nasabah])." </p>";
                }
            ?>
        </div>
	!-->	
    </div>
</div>
