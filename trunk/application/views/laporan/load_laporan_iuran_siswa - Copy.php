<?php
	require_once("c:\\xampp\htdocs\accountingmafaza\assets\dompdf\dompdf_config.inc.php");

	$dompdf = new DOMPDF();
	$blank 		 = "";
		
	$e = explode("--spr--",$iu);
	$id_acc   = $e[0];
	$nama_acc = $e[1];
	
	$html 		 = "";
	$harga_dasar = $this->db->query("select harga_pembayaran from account_pembayaran_tbl where id_acc_pembayaran = '".$id_acc."'")->row()->harga_pembayaran;
	$pembayaran_siswa = $this->db->query("SELECT 
											pembayaran_siswa_tbl.`id_pembayaran_siswa`,
											pembayaran_siswa_tbl.`id_acc_pembayaran`,
											pembayaran_siswa_tbl.`nis_siswa`,
											pembayaran_siswa_tbl.`tgl_transaksi`,
											pembayaran_siswa_tbl.`jumlah_transaksi`,
											siswa_tbl.`nama_siswa`
											FROM pembayaran_siswa_tbl INNER JOIN siswa_tbl
											ON pembayaran_siswa_tbl.`nis_siswa` = siswa_tbl.`nis_siswa`
												WHERE pembayaran_siswa_tbl.`id_acc_pembayaran` = '".$id_acc."'")->result();

	$no = 1;
	/* -- Header of Report -- */
	$html.= "<div style='text-align:center;font-size:17px;font-family:'Lucida Sans Unicode'>Laporan Iuran Siswa</div>";
	$html.= "<div style='text-align:center;font-size:17px;font-family:'Lucida Sans Unicode'>".strtoupper($nama_acc)."</div>";
	//$html.= "<div style='text-align:center;font-size:15px;font-family:'Lucida Sans Unicode'>Periode ".indo_date($sd). " sampai " .indo_date($ed)."</div>";
	/* -- Header of Report -- */
	
	$html.= "<div style='margin:25px'></div>";
	
	/* -- Content of Report -- */
		/* -- Row Content -- */
	
		
	$html.= "<table cellpadding='0' cellspacing='0' style='font-size:11px;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;'>";
	foreach($pembayaran_siswa as $L){
		$biaya_keringanan = $this->db->query("SELECT biaya_reduksi FROM alokasi_biaya_reduksi_tbl INNER JOIN biaya_reduksi_tbl ON alokasi_biaya_reduksi_tbl.`id_biaya_reduksi` = biaya_reduksi_tbl.`id_biaya_reduksi` 
													WHERE alokasi_biaya_reduksi_tbl.`id_acc_pembayaran` = '".$id_acc."' and biaya_reduksi_tbl.`nis_siswa` = '".$L->nis_siswa."'")->result();
		$hrg  = $harga_dasar - $biaya_keringanan[0]->biaya_reduksi;
		$html.= "<tr style=''>";
		$html.= 	"<td style='height:17px;width:90px'>NIS</td>";
		$html.= 	"<td>".$L->nis_siswa."</td>";
		$html.= 	"<td style='width:90px'></td>";
		$html.= "</tr>";
		$html.= "<tr>";
		$html.= 	"<td style='height:17px'>Nama</td>";
		$html.= 	"<td>".$L->nama_siswa."</td>";
		$html.= 	"<td></td>";
		$html.= "</tr>";
		$html.= "<tr>";
		$html.= 	"<td style='height:17px'>Keringanan Biaya</td>";
		$html.= 	"<td></td>";
		$html.= 	"<td>".$biaya_keringanan[0]->biaya_reduksi."</td>";
		$html.= "</tr>";
		$html.= "<tr>";
		$html.= 	"<td style='height:17px'>Beban Biaya</td>";
		$html.= 	"<td></td>";
		$html.= 	"<td>".$hrg."</td>";
		$html.= "</tr>";
		$html.= "<tr><td colspan=3 style='height:5px'></td></tr>";
	}
	$html.="</html>";
	
	foreach($pembayaran_siswa as $L){
		
		$html.= '<tr style="height:50px;font-size:11px">';
		$html.= 	"<td style='height:22px;text-align:center'>".$no."</td>";
		$html.= 	"<td style='text-align:center'>".indo_date($L->tgl_transaksi)."</td>";
		$html.= 	"<td style='text-align:center'><span style='marign-left:2'>".$L->nis_siswa."</span></td>";
		$html.= 	"<td style='text-align:left'><span style='margin-left:5px'>".$L->nama_siswa."</span></td>";
		$html.= 	"<td style='text-align:right;'><span style='margin-right:2px;'>".number_format($L->jumlah_transaksi)."</span></td>";
		if($biaya_keringanan){
			$html.= 	"<td style='text-align:right;'><span style='margin-right:2px;'>".number_format($biaya_keringanan->biaya_reduksi)."</span></td>";
			$html.= 	"<td style='text-align:right;'><span style='margin-right:2px;'>".number_format($L->jumlah_transaksi - $biaya_keringanan->biaya_reduksi)."</span></td>";
		}else{
			$html.= 	"<td style='text-align:right;'><span style='margin-right:2px;'>0</span></td>";
			$html.= 	"<td style='text-align:right;'><span style='margin-right:2px;'>".number_format($L->jumlah_transaksi)."</span></td>";
		}
		
		$html.= "</tr>";
		$no++;
		$total[] = $L->jumlah_transaksi;
	}
	$html.= "<tr>";
	$html.= 	"<td colspan='6' style='height:23;text-align:right'><i><b>Total&nbsp;&nbsp;</b></i></td>";
	if($total){
		$html.= 	"<td style='text-align:right;background-color:#dddddd'><span style='margin-right:2px'>".number_format(array_sum($total))."</span></td>";
	}else{
		$html.= 	"<td style='text-align:right;background-color:#dddddd'><span style='margin-right:2px'>0</span></td>";
	}
	
	$html.= "</tr>";
	$html.= "</table>";
	
	/* -- Content of Report -- */
	
	$dompdf->load_html($html);
	$dompdf->render();
	
	/* page number */
	$canvas = $dompdf->get_canvas();
	$font = Font_Metrics::get_font("helvetica", "bold");
	$canvas->page_text(72, 18, "page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
	/* page number */
	
	$dompdf->stream("Laporan ".$nama_acc.".pdf", array("Attachment" => false));

	exit(0);
?>