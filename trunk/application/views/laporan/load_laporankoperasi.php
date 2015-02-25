<?php
	require_once("c:\\xampp\htdocs\accountingmafaza\assets\dompdf\dompdf_config.inc.php");

	$dompdf = new DOMPDF();
	if($bd == "all"){
		$where = "";
	}else{
		$where = " where id_budget = '".$bd."'";
	}
	$blank 		 = "";
	$html 		 = "";
	
	
	#Laporan Penjualan
	if($ket=="penjualan"){
		$laporan = $this->db->query("SELECT 
									penjualan_tbl.`id_penjualan`,
									penjualan_tbl.`jumlah`,
									penjualan_tbl.`tgl_terjual`,
									koperasi_tbl.`nama_item`,
									koperasi_tbl.`satuan`,
									koperasi_tbl.`harga` 
									FROM penjualan_tbl INNER JOIN koperasi_tbl
									ON penjualan_tbl.`id_item` = koperasi_tbl.`id_item`
									ORDER BY penjualan_tbl.`tgl_terjual` ASC")->result();

		$no = 1;
		/* -- Header of Report -- */
		$html.= "<div style='text-align:center;font-size:17px;font-family:'Lucida Sans Unicode'>Laporan Penjualan Koperasi Sekolah</div>";
		$html.= "<div style='text-align:center;font-size:15px;font-family:'Lucida Sans Unicode'>Periode ".indo_date($sd). " sampai " .indo_date($ed)."</div>";
		/* -- Header of Report -- */

		$html.= "<div style='margin:25px'></div>";

		/* -- Content of Report -- */
		/* -- Row Content -- */
		$html.= "<table align='center' border='1' cellpadding='0' cellspacing='0' style='font-size:13px;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;'>";
		$html.= "<tr style='font-weight:bold'>";
		$html.= 	"<td style='height:30px;width:25px;text-align:center;background-color:#dedede'>No</td>";
		$html.= 	"<td style='width:100px;text-align:center;background-color:#dedede'>Tanggal</td>";
		$html.= 	"<td style='width:200px;text-align:center;background-color:#dedede'>Nama Item</td>";
		$html.= 	"<td style='width:50px;text-align:center;background-color:#dedede'>Satuan</td>";
		$html.= 	"<td style='width:100px;text-align:center;background-color:#dedede'>Harga Satuan</td>";
		$html.= 	"<td style='width:100px;text-align:center;background-color:#dedede'>Jumlah Terjual</td>";
		$html.= 	"<td style='width:100px;text-align:center;background-color:#dedede'>Sub Total</td>";
		$html.= "</tr>";

		foreach($laporan as $L){
		$html.= '<tr style="height:50px;">';
		$html.= 	"<td style='height:22px;text-align:center'>".$no."</td>";
		$html.= 	"<td style='text-align:center'>".indo_date($L->tgl_terjual)."</td>";
		$html.= 	"<td style='text-align:left'><span style='marign-left:2'>".strtoupper($L->nama_item)."</span></td>";
		$html.= 	"<td style='text-align:center'>".strtoupper($L->satuan)."</td>";
		$html.= 	"<td style='text-align:right;'><span style='margin-right:2px;'>".number_format($L->harga)."</span></td>";
		$html.= 	"<td style='text-align:right;'><span style='margin-right:2px;'>".$L->jumlah."</span></td>";
		$html.= 	"<td style='text-align:right;'><span style='margin-right:2px;'>".number_format($L->harga*$L->jumlah)."</span></td>";
		$html.= "</tr>";
		$no++;
		$total_items[] = $L->jumlah;
		$total_harga[] = $L->harga*$L->jumlah;
		}
		$html.= "<tr>";
		$html.= 	"<td colspan='5' style='height:23;text-align:right'><i><b>Total&nbsp;&nbsp;</b></i></td>";
		$html.= 	"<td style='text-align:right;background-color:#dddddd'><span style='margin-right:2px'>".array_sum($total_items)."</span></td>";
		$html.= 	"<td style='text-align:right;background-color:#dddddd'><span style='margin-right:2px'>".number_format(array_sum($total_harga))."</span></td>";
		$html.= "</tr>";
		$html.= "</table>";

		/* -- Row Content -- */
		/* -- Desciption Row Content -- */
		$jumlah = $this->db->query("SELECT 
									SUM(penjualan_tbl.`jumlah`) AS total_item,
									koperasi_tbl.`nama_item`,
									koperasi_tbl.`satuan`
									FROM koperasi_tbl LEFT JOIN penjualan_tbl
									ON koperasi_tbl.`id_item` = penjualan_tbl.`id_item`
									GROUP BY koperasi_tbl.`nama_item`")->result();
									
		$html.= "<table style='margin:15px;font-size:13px;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;'>";
		$html.= "<tr><td colspan='4'><b>Keterangan</b></td></tr>";
		foreach($jumlah as $J){
		$html.= "<tr>";
		$html.= 	"<td style='width:100px'><span style='marign-left:2'>".strtoupper($J->nama_item)."</td>";
		$html.= 	"<td style='width:15px'> : </td>";
						if($J->total_item){
							$html.= "<td style='text-align:right;'><span style='margin-right:2px;'>".$J->total_item."</span></td>";
							$html.= "<td style='text-align:left;'><span style='margin-right:2px;'>" .$J->satuan."</span></td>";
						}else{
							$html.= "<td style='text-align:right;'><span style='margin-right:2px;'>0</span></td>";
							$html.= "<td style='text-align:left;'><span style='margin-right:2px;'>" .$J->satuan."</span></td>";
						}
		$html.= "</tr>";
		}
		$html.= "</table>";
		/* -- Desciption Row Content -- */
		/* -- Content of Report -- */
	
	}else{
	#Laporan Pembelian
		$html.= "<div style='text-align:center;font-size:17px;font-family:'Lucida Sans Unicode'>Laporan Pembelian Koperasi Sekolah</div>";
		$html.= "<div style='text-align:center;font-size:15px;font-family:'Lucida Sans Unicode'>Periode ".indo_date($sd). " sampai " .indo_date($ed)."</div>";
		
		$html.= "<div style='margin:25px'></div>";
		
		$hutang_koperasi = $this->db->query("SELECT * FROM hutang_koperasi_tbl")->result();
		foreach($hutang_koperasi as $hk){
			$detail = $this->db->query("SELECT 
												piutang_koperasi_tbl.`jumlah_hutang`,
												piutang_koperasi_tbl.`jumlah_piutang`,
												piutang_koperasi_tbl.`id_acc_pembayaran`,
												account_pembayaran_tbl.`nama_acc_pembayaran`
												FROM hutang_koperasi_tbl INNER JOIN piutang_koperasi_tbl
												ON hutang_koperasi_tbl.`id_hutang_koperasi` = piutang_koperasi_tbl.`id_hutang_koperasi`
												OR hutang_koperasi_tbl.`id_hutang_koperasi` = piutang_koperasi_tbl.`id_hutang_pembayaran`
												LEFT JOIN account_pembayaran_tbl ON account_pembayaran_tbl.`id_acc_pembayaran` = piutang_koperasi_tbl.`id_acc_pembayaran`
												WHERE hutang_koperasi_tbl.`id_hutang_koperasi` = '".$hk->id_hutang_koperasi."'")->result();
			
			$html.= "<table border=0 cellpadding=2 cellspacing=2 style='margin:15px;font-size:13px;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;'>";
			$html.= "<tr bgcolor='#dfdfdf'><td height=25px colspan=4>".$hk->keterangan."</td></tr>";
			$html.= "<tr>
						<td width=130px>Account Pinjaman</td>
						<td width=130px>Account Pengembalian</td>
						<td width=110px>Pinjaman</td>
						<td width=110px>Pengembalian</td>
					</tr>";
			foreach($detail as $d){			
				$html.=	"<tr>";
				if($d->jumlah_hutang){
					if($d->nama_acc_pembayaran){
						$html.= "<td>".ucwords($d->nama_acc_pembayaran)."</td>";
						$html.= "<td></td>";
					}else{
						$html.= "<td>".ucwords($d->id_acc_pembayaran)."</td>";
						$html.= "<td></td>";
					}
				}else{
					if($d->nama_acc_pembayaran){
						$html.= "<td></td>";
						$html.= "<td>".ucwords($d->nama_acc_pembayaran)."</td>";
					}else{
						$html.= "<td></td>";
						$html.= "<td>".ucwords($d->id_acc_pembayaran)."</td>";
					}
				}				
				$html.=			"<td>".number_format($d->jumlah_hutang)."</td>
								 <td>".number_format($d->jumlah_piutang)."</td>
							</tr>";
				$no++;
			}
			
		}
		
	}
	
	
	$dompdf->load_html($html);
	$dompdf->render();
	$dompdf->stream("Laporan ".$laporan[0]->nama_budget.".pdf", array("Attachment" => false));

	exit(0);
?>