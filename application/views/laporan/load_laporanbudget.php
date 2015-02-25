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
	$temp_tgltra = "";
	$budget = $this->db->query("select id_budget, nama_budget from budget_tbl ".$where)->result();
	
		$laporan = $this->db->query("SELECT 
											* 
											FROM budget_tbl LEFT JOIN transaksi_tbl
											ON budget_tbl.`id_budget` = transaksi_tbl.`id_budget`
											WHERE budget_tbl.`id_budget` = '".$bd."'")->result();
		
		
		$no = 1;
		/* -- Header of Report -- */
		$html.= "<div style='text-align:center;font-size:17px;font-family:'Lucida Sans Unicode'>Laporan Transaksi Budget</div>";
		$html.= "<div style='text-align:center;font-size:15px;font-family:'Lucida Sans Unicode'>&quot;".strtoupper($laporan[0]->nama_budget)."&quot;</div>";
		$html.= "<div style='text-align:center;font-size:15px;font-family:'Lucida Sans Unicode'>Periode ".indo_date($sd). " sampai " .indo_date($ed)."</div>";
		/* -- Header of Report -- */
		
		$html.= "<div style='margin:25px'></div>";
		
		/* -- Content of Report -- */
			/* -- Row Content -- */
		$html.= "<table align='center' border='1' cellpadding='0' cellspacing='0' style='font-size:13px;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;'>";
		$html.= "<tr style='font-weight:bold'>";
		$html.= 	"<td style='height:30px;width:25px;text-align:center;background-color:#dedede'>No</td>";
		$html.= 	"<td style='width:100px;text-align:center;background-color:#dedede'>Tanggal</td>";
		$html.= 	"<td style='width:300px;text-align:center;background-color:#dedede'>Uraian</td>";
		$html.= 	"<td style='width:110px;text-align:center;background-color:#dedede'>Biaya Pengeluaran</td>";
		$html.= "</tr>";
	
		foreach($laporan as $L){
			$html.= "<tr>";
			$html.= 	"<td style='height:22px;text-align:center'>".$no."</td>";
			$html.= 	"<td style='text-align:center'>".indo_date($L->tgl_transaksi)."</td>";
			$html.= 	"<td><span style='margin-left:2px;'>".$L->nama_transaksi."</span></td>";
			$html.= 	"<td style='text-align:right;'><span style='margin-right:2px;'>".number_format($L->biaya)."</span></td>";
			$html.= "</tr>";
			$no++;
			$total_biaya[] = $L->biaya;
			
		}
		$html.= "<tr>
					<td colspan='3' style='height:23;text-align:right'><i><b>Total&nbsp;&nbsp;</b></i></td>
					<td style='text-align:right;background-color:#dddddd'><span style='margin-right:2px'>".number_format(array_sum($total_biaya))."</span></td>
				</tr>";
		$html.= "</table>";
		
		/* -- Row Content -- */
		/* -- Desciption Row Content -- */
	$alokasi_budget = $this->db->query("SELECT budget FROM alokasi_budget_tbl WHERE id_budget = '".$bd."'")->row()->budget;						
	$html.= "<table style='margin-top:20px;margin-left:70px;font-size:13px;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;'>
			<tr><td colspan='3'><b>Keterangan</b></td></tr>
			<tr>
				<td style='width:100px'><span style='marign-left:2'>Alokasi Budget</td>
				<td style='width:15px'> : </td>
				<td style='text-align:right;'><span style='margin-right:2px;'>".number_format($alokasi_budget)."</span></td>
			</tr>
			<tr>
				<td style='width:100px'><span style='marign-left:2'>Sisa Budget</td>
				<td style='width:15px'> : </td>
				<td style='text-align:right;'><span style='margin-right:2px;'>".number_format($alokasi_budget - array_sum($total_biaya))."</span></td>
			</tr>
			</table>";
		/* -- Desciption Row Content -- */
	/* -- Content of Report -- */	
	$dompdf->load_html($html);
	$dompdf->render();
	
	/* page number */
	$canvas = $dompdf->get_canvas();
	$font = Font_Metrics::get_font("helvetica", "bold");
	$canvas->page_text(72, 18, "page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
	/* page number */
	
	$dompdf->stream("Laporan ".$laporan[0]->nama_budget.".pdf", array("Attachment" => false));

	exit(0);
?>