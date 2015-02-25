<?php
	require_once("c:\\xampp\htdocs\accountingmafaza\assets\dompdf\dompdf_config.inc.php");

	$dompdf = new DOMPDF();
	$blank 		 = "";
		
	$e = explode("--spr--",$iu);
	$id_acc   = $e[0];
	$nama_acc = $e[1];
	
	$html 		 = "";
	$biaya_dasar 	  = @$this->db->query("SELECT harga_pembayaran FROM account_pembayaran_tbl WHERE id_acc_pembayaran = '".$id_acc."'")->result();
	$pembayaran_siswa = @$this->db->query("SELECT 
											pembayaran_siswa_tbl.`id_pembayaran_siswa`,
											pembayaran_siswa_tbl.`id_acc_pembayaran`,
											pembayaran_siswa_tbl.`nis_siswa`,
											pembayaran_siswa_tbl.`tgl_transaksi`,
											pembayaran_siswa_tbl.`jumlah_transaksi` AS transaksi_pembayaran,
											siswa_tbl.`nama_siswa`
											FROM pembayaran_siswa_tbl INNER JOIN siswa_tbl
											ON pembayaran_siswa_tbl.`nis_siswa` = siswa_tbl.`nis_siswa`
												WHERE pembayaran_siswa_tbl.`id_acc_pembayaran` = '".$id_acc."'
												AND tgl_transaksi BETWEEN '".db_date($sd)."' AND  '".db_date($ed)."'
												GROUP BY pembayaran_siswa_tbl.`nis_siswa`")->result();

	$no = 1;
	/* -- Header of Report -- */
	
	$html.= "<div style='text-align:center;font-size:17px;font-family:'Lucida Sans Unicode'>Laporan Iuran Siswa</div>";
	$html.= "<div style='text-align:center;font-size:17px;font-family:'Lucida Sans Unicode'>".strtoupper($nama_acc)."</div>";
	$html.= "<div style='text-align:center;font-size:17px;font-family:'Lucida Sans Unicode'>Laporan Iuran Siswa</div>";
	
	//$html.= "<div style='text-align:center;font-size:15px;font-family:'Lucida Sans Unicode'>Periode ".indo_date($sd). " sampai " .indo_date($ed)."</div>";
	/* -- Header of Report -- */
	
	$html.= "<div style='margin:25px'></div>";
	
	/* -- Content of Report -- */
		/* -- Row Content -- */
	$html.= "<table  border='0' cellpadding='2' cellspacing='2' style='margin-bottom:10px;font-size:12px;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;'>
			<tr>
				<td>Periode</td>
				<td>".indo_date($sd)." - ".indo_date($ed)."</td>
			</tr>
			<tr>
				<td>Biaya Dasar</td>
				<td>".@number_format($biaya_dasar[0]->harga_pembayaran)."</td>
			</tr>
			</table>";
	
	$html.= "<table align='center' border='1' cellpadding='0' cellspacing='0' style='font-size:10px;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;'>";
	$html.= "<tr style='font-weight:bold'>";
	$html.= 	"<td style='height:30px;width:20px;text-align:center;background-color:#dedede'>No</td>";
	$html.= 	"<td style='width:75px;text-align:center;background-color:#dedede'>Tanggal Bayar</td>";
	$html.= 	"<td style='width:75px;text-align:center;background-color:#dedede'>NIS Siswa</td>";
	$html.= 	"<td style='width:140px;text-align:center;background-color:#dedede'>Nama Siswa</td>";
	$html.= 	"<td style='width:100px;text-align:center;background-color:#dedede'>Keringanan Biaya</td>";
	$html.= 	"<td style='width:100px;text-align:center;background-color:#dedede'>Beban Biaya</td>";
	$html.= 	"<td style='width:100px;text-align:center;background-color:#dedede'>Terbayar</td>";
	$html.= 	"<td style='width:100px;text-align:center;background-color:#dedede'>Tunggakan</td>";
	$html.= "</tr>";

											
	foreach($pembayaran_siswa as $L){
		$biaya_keringanan = @$this->db->query("SELECT 
													biaya_reduksi 
													FROM alokasi_biaya_reduksi_tbl INNER JOIN biaya_reduksi_tbl
													ON alokasi_biaya_reduksi_tbl.`id_biaya_reduksi` = biaya_reduksi_tbl.`id_biaya_reduksi` 
													WHERE alokasi_biaya_reduksi_tbl.`id_acc_pembayaran` = '".$id_acc."' and biaya_reduksi_tbl.`nis_siswa` = '".$L->nis_siswa."'")->row();
		
		$beban_biaya = @$biaya_dasar[0]->harga_pembayaran  - $biaya_keringanan->biaya_reduksi;
		$tunggakan   = @$beban_biaya - $L->transaksi_pembayaran;
		
		$html.= '<tr style="height:50px;font-size:11px">';
		$html.= 	"<td style='height:22px;text-align:center'>".$no."</td>";
		$html.= 	"<td style='text-align:center'>".indo_date($L->tgl_transaksi)."</td>";
		$html.= 	"<td style='text-align:center'><span style='marign-left:2'>".@$L->nis_siswa."</span></td>";
		$html.= 	"<td style='text-align:left'><span style='margin-left:5px'>".@$L->nama_siswa."</span></td>";
		$html.= 	"<td style='text-align:right;'><span style='margin-right:2px;'>".@number_format($biaya_keringanan->biaya_reduksi)."</span></td>";
		if($biaya_keringanan){
			$html.= "<td style='text-align:right;'><span style='margin-right:2px;'>".@number_format($beban_biaya)."</span></td>";
		}else{
			$html.= "<td style='text-align:right;'><span style='margin-right:2px;'>".@number_format($beban_biaya)."</span></td>";
		}
		$html.= 	"<td style='text-align:right;'><span style='margin-right:2px;'>".@number_format($L->transaksi_pembayaran)."</span></td>";
		$html.= 	"<td style='text-align:right;'><span style='margin-right:2px;'>".@number_format($tunggakan)."</span></td>";
		$html.= "</tr>";
		$no++;
		$get_nis_siswa[]   = $L->nis_siswa;
		$total_transaksi[] = $L->jumlah_transaksi;
	}
	
	$get_siswa = @$this->db->query("SELECT nis_siswa, nama_siswa FROM siswa_tbl")->result();
	$mo = $no;
	foreach($get_siswa as $g){
		if(!in_array($g->nis_siswa,$get_nis_siswa)){
			$html.= '<tr style="height:50px;font-size:11px">';
			$html.= 	"<td style='height:22px;text-align:center'>".$mo."</td>";
			$html.= 	"<td style='text-align:center'>-</td>";
			$html.= 	"<td style='text-align:center'><span style='marign-left:2'>".@$g->nis_siswa."</span></td>";
			$html.= 	"<td style='text-align:left'><span style='margin-left:5px'>".@$g->nama_siswa."</span></td>";
			$html.= 	"<td style='text-align:right;'><span style='margin-right:2px;'>0</span></td>";
			$html.= 	"<td style='text-align:right;'><span style='margin-right:2px;'>".@number_format($biaya_dasar[0]->harga_pembayaran)."</span></td>";
			$html.= 	"<td style='text-align:right;'><span style='margin-right:2px;'>0</span></td>";
			$html.= 	"<td style='text-align:right;'><span style='margin-right:2px;'>".@number_format($biaya_dasar[0]->harga_pembayaran)."</span></td>";
			$html.= "</tr>";
		}
		$mo++;
	}
	
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