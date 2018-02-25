<?php

require('C:\xampp\htdocs\infokos-master\config\fpdf\fpdf.php');

class PDF extends FPDF {

    //Page footer
    function Footer() {
        //Position at 1.5 cm from bottom
        $this->SetY(-15);
        //Arial italic 8
        $this->SetFont('Arial', '', 9);
        //Page number
        $this->Cell(0, 10, '' . $this->PageNo() . '', 0, 0, 'C');
    }

//Colored table
    function FancyTable() {
        require_once "C:/xampp/htdocs/infokos-master/config/koneksi.php";
        require_once 'C:/xampp/htdocs/infokos-master/config/fungsi_indotgl.php';
        $pembayaran = mysql_fetch_array(mysql_query('
            SELECT pembayaran.*,
                pemesanan.tgl_masuk,
                pemesanan.tgl_keluar,
                pemesanan.tanggal AS tgl_pemesanan,
                harga_kamar.harga,
                harga_kamar.type AS type_sewa,
                kamar.nama AS nama_kamar,
                kamar.jenis,
                kost.nama AS nama_kost,
                kost.alamat,
                pemilik.nama AS nama_pemilik,
                pemilik.no_hp AS no_hp_pemilik,
                pelanggan.nama AS nama_pelanggan,
                pelanggan.no_hp AS no_hp_pelanggan,
                pelanggan.ktp,
                bank.nama AS nama_bank,
                bank.no_rekening,
                bank.nama_nasabah,
                periode.nama AS nama_periode
            FROM pembayaran
                LEFT JOIN pemesanan
                    ON pemesanan.id = pembayaran.pemesanan_id
                LEFT JOIN harga_kamar
                    ON harga_kamar.id = pemesanan.harga_kamar_id
                LEFT JOIN kamar
                    ON kamar.id = harga_kamar.kamar_id
                LEFT JOIN kost
                    ON kost.id = kamar.kost_id
                LEFT JOIN pemilik
                    ON pemilik.id = kost.pemilik_id
                LEFT JOIN pelanggan
                    ON pelanggan.id = pemesanan.pelanggan_id
                LEFT JOIN bank
                    ON bank.id = pembayaran.bank_id
                LEFT JOIN periode
                    ON periode.id = harga_kamar.periode_id
            WHERE pembayaran.id =' . $_GET[id]));
        $this->Ln(4);
        $this->SetX(45);
        $this->SetFont('Arial', 'B', 12);
        $title1 = "KUITANSI PEMBAYARAN SEWA KAMAR KOST";
        $this->Write(7, $title1);
        $this->Ln();
        $this->SetX(45);
        $title2 = 'PERIODE ' . $pembayaran['nama_periode'];
        $this->Write(7, $title2);
        $this->Ln();
        $this->SetX(45);
        $title3 = 'Rajadesa - OK - Punya - Ciamis';
        $this->Write(7, $title3);
        $this->Ln();
        $this->SetX(45);
        $title4 = 'INFOKOS.NET';
        $this->Write(7, $title4);
        $logo = "C:/xampp/htdocs/infokos-master/images/logo.jpg";
        $this->Image($logo, 10, 18, 30, 0);
        $this->Ln(17);

        $this->SetFont('Arial', '', 9);
        if($pembayaran['jenis'] == 'L'){
            $jenis = 'Putra';
        }else{
            $jenis = 'Putri';
        }
        $data = array(
            array('title' => 'Nama Kamar', 'value' => $pembayaran['nama_kamar']),
            array('title' => 'Jenis Kamar', 'value' => $jenis),
            array('title' => 'Nama Kost', 'value' => $pembayaran['nama_kost']),
            array('title' => 'Nama Pemilik', 'value' => $pembayaran['nama_pemilik']),
            array('title' => 'No. HP Pemilik', 'value' => $pembayaran['no_hp_pemilik']),
            array('title' => 'Nama Pelanggan', 'value' => $pembayaran['nama_pelanggan']),
            array('title' => 'No. KTP', 'value' => $pembayaran['ktp']),
            array('title' => 'No. HP Pelanggan', 'value' => $pembayaran['no_hp_pelanggan']),
            array('title' => 'Periode', 'value' => $pembayaran['nama_periode']),
            array('title' => 'Harga Kamar', 'value' => 'Rp. '.number_format($pembayaran['harga'], 2, ',', '.').' / '.$pembayaran['type_sewa']),
            array('title' => 'Tanggal Kontrak', 'value' => tgl_indo($pembayaran['tgl_masuk']) . ' s.d ' . tgl_indo($pembayaran['tgl_keluar'])),
            array('title' => 'Tanggal Pemesanan', 'value' => $pembayaran['tgl_pemesanan']),
            array('title' => 'Tanggal Transfer (Pembayaran)', 'value' => tgl_indo($pembayaran['tanggal'])),
            array('title' => 'Tanggal Konfirmasi Pembayaran', 'value' => $pembayaran['tgl_transaksi']),
            array('title' => 'No. Rekening Tujuan', 'value' => $pembayaran['no_rekening']),
            array('title' => 'Nama Bank Tujuan', 'value' => $pembayaran['nama_bank']),
            array('title' => 'Nama Nasabah Bank Tujuan', 'value' => $pembayaran['nama_nasabah']),
            array('title' => 'Kode Transfer', 'value' => $pembayaran['kode_transfer']),
            array('title' => 'Jenis Pembayaran', 'value' => $pembayaran['type']),
            array('title' => 'Jumlah Transfer', 'value' => 'Rp. '.number_format($pembayaran['jumlah'], 2, ',', '.')),
        );

        //Colors, line width and bold font
        $this->SetFillColor(255);
        $this->SetTextColor(0);
        $this->SetDrawColor(0);
        $this->SetLineWidth(.3);
        //Header
        foreach ($data as $item){
            $this->SetFont('', 'B');
            $this->Cell(60, 7, $item['title'], 1, 0, 'L', true);
            $this->SetFont('', '');
            $this->Cell(130, 7, $item['value'], 1, 0, 'L', false);
            $this->Ln();
        }
        $this->Ln(12);
        $this->SetX(138);
        $this->Write(7, 'Bandung, '.  tgl_indo(date('Y-m-d')));
        $this->Ln(4);
        $this->SetX(150);
        $this->Write(7, 'Hormat Kami');
        $this->Ln(17);
        $this->SetX(145);
        $this->SetFont('', 'BI');
        $this->Write(7, 'Admin InfoKos.Net');
    }

}

$pdf = new PDF();
$pdf->AliasNbPages();
//Column titles
$pdf->AddPage(P, A4);
$pdf->FancyTable();
$pdf->Output("Pembayaran.pdf", D);
?>

