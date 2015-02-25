<?php
	class Load_sikluskeuangan extends CI_Controller{
		function get_forbudget($id){
			if($id=="donasi"){
				$jml_transaksi   = $this->db->query("SELECT SUM(`jumlah_donatur`) as jml_tr
										FROM donatur_tbl")->result();
				$aloka_budget    = $this->db->query("SELECT SUM(`budget`) as jml_bgt
										FROM alokasi_budget_tbl WHERE id_acc_pembayaran = '".$id."'")->result();
				$budget_relokasi = $this->db->query("SELECT SUM(saldo) AS saldo FROM budget_relokasi_tbl WHERE id_acc_pembayaran = '".$id."'")->result();
				$biaya_reduksi   = $this->db->query("SELECT SUM(biaya_reduksi) AS biaya_reduksi
														FROM alokasi_biaya_reduksi_tbl WHERE id_acc_bgt = '".$id."'")->result();
				$biaya_hutang    = $this->db->query("SELECT SUM(jumlah_hutang) AS hutang FROM hutang_piutang_tbl WHERE id_acc_pembayaran = '".$id."'")->result();
				$biaya_piutang   = $this->db->query("SELECT SUM(jumlah_piutang) AS piutang FROM hutang_piutang_tbl WHERE id_acc_pembayaran = '".$id."'")->result();
				$hutang_koperasi = $this->db->query("SELECT SUM(jumlah_hutang) AS hutang_koperasi FROM piutang_koperasi_tbl WHERE id_acc_pembayaran = '".$id."'")->result();
				$piutang_koperasi = $this->db->query("SELECT SUM(jumlah_hutang) AS piutang_koperasi FROM piutang_koperasi_tbl WHERE id_acc_pembayaran = '".$id."'")->result();
			}else if($id=="koperasi"){
				$jml_transaksi = $this->db->query("SELECT 
													SUM(koperasi_tbl.`harga` * penjualan_tbl.`jumlah`) AS jml_tr
													FROM koperasi_tbl INNER JOIN penjualan_tbl
													ON koperasi_tbl.`id_item` = penjualan_tbl.`id_item`")->result();
				$aloka_budget  = $this->db->query("SELECT SUM(`budget`) as jml_bgt
										FROM alokasi_budget_tbl WHERE id_acc_pembayaran = '".$id."'")->result();
				$budget_relokasi = $this->db->query("SELECT SUM(saldo) AS saldo FROM budget_relokasi_tbl WHERE id_acc_pembayaran = '".$id."'")->result();
				$biaya_reduksi = $this->db->query("SELECT SUM(biaya_reduksi) AS biaya_reduksi
														FROM alokasi_biaya_reduksi_tbl WHERE id_acc_bgt = '".$id."'")->result();
				$biaya_hutang  = $this->db->query("SELECT SUM(jumlah_hutang) AS hutang FROM hutang_piutang_tbl WHERE id_acc_pembayaran = '".$id."'")->result();
				$biaya_piutang = $this->db->query("SELECT SUM(jumlah_piutang) AS piutang FROM hutang_piutang_tbl WHERE id_acc_pembayaran = '".$id."'")->result();
				$hutang_koperasi = $this->db->query("SELECT SUM(jumlah_hutang) AS hutang_koperasi FROM piutang_koperasi_tbl WHERE id_acc_pembayaran = '".$id."'")->result();
				$piutang_koperasi = $this->db->query("SELECT SUM(jumlah_hutang) AS piutang_koperasi FROM piutang_koperasi_tbl WHERE id_acc_pembayaran = '".$id."'")->result();
			}else{
				$jml_transaksi = $this->db->query("SELECT SUM(`jumlah_transaksi`) as jml_tr
										FROM pembayaran_siswa_tbl WHERE id_acc_pembayaran = '".$id."'")->result();
				$aloka_budget  = $this->db->query("SELECT SUM(`budget`) as jml_bgt
										FROM alokasi_budget_tbl WHERE id_acc_pembayaran = '".$id."'")->result();
				$budget_relokasi = $this->db->query("SELECT SUM(saldo) AS saldo FROM budget_relokasi_tbl WHERE id_acc_pembayaran = '".$id."'")->result();
				$biaya_reduksi = $this->db->query("SELECT SUM(biaya_reduksi) AS biaya_reduksi
														FROM alokasi_biaya_reduksi_tbl WHERE id_acc_bgt = '".$id."'")->result();													
				$biaya_hutang  = $this->db->query("SELECT SUM(jumlah_hutang) AS hutang FROM hutang_piutang_tbl WHERE id_acc_pembayaran = '".$id."'")->result();
				$biaya_piutang = $this->db->query("SELECT SUM(jumlah_piutang) AS piutang FROM hutang_piutang_tbl WHERE id_acc_pembayaran = '".$id."'")->result();
				$hutang_koperasi = $this->db->query("SELECT SUM(jumlah_hutang) AS hutang_koperasi FROM piutang_koperasi_tbl WHERE id_acc_pembayaran = '".$id."'")->result();
				$piutang_koperasi = $this->db->query("SELECT SUM(jumlah_hutang) AS piutang_koperasi FROM piutang_koperasi_tbl WHERE id_acc_pembayaran = '".$id."'")->result();
			}
			
			$debet_amount  = $jml_transaksi[0]->jml_tr + $budget_relokasi[0]->saldo + $biaya_piutang[0]->piutang + $piutang_koperasi[0]->piutang_koperasi;
			$kredit_amount = $aloka_budget[0]->jml_bgt + $biaya_reduksi[0]->biaya_reduksi + $biaya_hutang[0]->hutang + $hutang_koperasi[0]->hutang_koperasi;
			$saldo = $debet_amount - $kredit_amount;
			return $saldo;
		}
	}
	
	$e_class = new Load_sikluskeuangan();
	
	require_once("c:\\xampp\htdocs\accountingmafaza\assets\dompdf\dompdf_config.inc.php");

	$dompdf = new DOMPDF();
	
	$blank 		 = "";
	$html 		 = "";
	

	/* -- Header of Report -- */
	$html.= "<div style='text-align:center;font-size:17px;font-family:'Lucida Sans Unicode'>Siklus Keuangan Sekolah</div>";
	/* -- Header of Report -- */
	
	$html.= "<div style='margin:25px'></div>";
	
	/* -- Content of Report -- */
	$html.= "<div style='font-size:10px;font-family:Lucida Grande;margin:11px'><b><u>ASSET</u></b></div>";
	
	$account = $this->db->query("SELECT account_pembayaran_tbl.`id_acc_pembayaran`,
										account_pembayaran_tbl.`nama_acc_pembayaran`,
										SUM(pembayaran_siswa_tbl.`jumlah_transaksi`) AS total_transaksi
											FROM account_pembayaran_tbl INNER JOIN pembayaran_siswa_tbl
												ON account_pembayaran_tbl.`id_acc_pembayaran` = pembayaran_siswa_tbl.`id_acc_pembayaran` 
												GROUP BY  account_pembayaran_tbl.id_acc_pembayaran")->result();
	
	$html.= "<table border='1' cellpadding='2' cellspacing='2' style='page-break-after:always;font-size:10px;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;'>";
	$no=1;
	# Account in DB #
	foreach($account as $c){
		$html.= "<tr>";
		$html.= 	"<td style='width:10px;height:25px'>".$no.".</td>";
		$html.= 	"<td style='width:60px'>".$c->nama_acc_pembayaran."</td>";
		$html.= 	"<td > : ".number_format($c->total_transaksi)."</td>";
		$html.= 	"<td style='width:130px'>&nbsp;</td>";
		$html.= 	"<td style='width:60px'>&nbsp;</td>";
		$html.= 	"<td style='width:60px'>&nbsp;</td>";
		$html.= "</tr>";
		$html.= "<tr>";
		$html.= 	"<td colspan=3></td>";
		$html.= 	"<td align=left style='height:20px'>Saldo</td>";
		$html.= 	"<td align=right>".number_format($e_class->get_forbudget($c->id_acc_pembayaran))."</td>";
		$html.= 	"<td style='width:60px'>&nbsp;</td>";
		$html.= "</tr>";
			
			#check alokasi budget
			$budget = $this->db->query("SELECT * 
												FROM 
												budget_tbl INNER JOIN alokasi_budget_tbl
												ON budget_tbl.`id_budget`  = alokasi_budget_tbl.`id_budget`
												WHERE alokasi_budget_tbl.`id_acc_pembayaran` = '".$c->id_acc_pembayaran."'")->result();
			foreach($budget as $alokasi){
				$html.= "<tr>";
					$html.= 	"<td colspan=3></td>";	
					$html.= 	"<td align=left style='height:20px'>".$alokasi->nama_budget."</td>";
					$html.= 	"<td colspan=2 align=right>".number_format($alokasi->budget)."</td>";
					$html.= "</tr>";
			}
			#check pinjaman karyawan
			$hutang = $this->db->query("select sum(jumlah_hutang) as jumlah_hutang from hutang_piutang_tbl where id_acc_pembayaran = '".$c->id_acc_pembayaran."'")->row();
			if($hutang->jumlah_hutang){
				$piutang = $this->db->query("select sum(jumlah_piutang) as jumlah_piutang from hutang_piutang_tbl where id_acc_pembayaran = '".$c->id_acc_pembayaran."'")->row();
				$sisa_hutang = (float)$hutang->jumlah_hutang - (float)$piutang->jumlah_piutang;
				$html.= "<tr>";
				$html.= 	"<td colspan=3></td>";	
				$html.= 	"<td align=left style='height:20px'> Hutang - Piutang Karyawan </td>";
				$html.= 	"<td colspan=2 ></td>";
				$html.= "</tr>";
				$html.= "<tr>";
				$html.= 	"<td colspan=4 align=right style='height:15px'>Sisa Hutang</td>";
				$html.= 	"<td colspan=2 align=right>".number_format($sisa_hutang)."</td>";
				$html.= "</tr>";
			}
			
			#check subsidi reduksi biaya siswa
			$reduksi = $this->db->query("SELECT 
											SUM(biaya_reduksi) AS biaya_reduksi,
											account_pembayaran_tbl.`nama_acc_pembayaran`
											FROM 
											alokasi_biaya_reduksi_tbl INNER JOIN account_pembayaran_tbl
											ON alokasi_biaya_reduksi_tbl.`id_acc_pembayaran` = account_pembayaran_tbl.`id_acc_pembayaran`
											WHERE alokasi_biaya_reduksi_tbl.id_acc_bgt = '".$c->id_acc_pembayaran."'
											GROUP BY account_pembayaran_tbl.nama_acc_pembayaran")->result();
			if($reduksi){
				$html.= "<tr>";
				$html.= 	"<td colspan=3></td>";	
				$html.= 	"<td align=left style='height:20px'> Biaya Keringanan Siswa </td>";
				$html.= 	"<td colspan=2 ></td>";
				$html.= "</tr>";
				foreach($reduksi as $r){
					$html.= "<tr>";
					$html.= 	"<td colspan=4 align=right style='height:15px'>".$r->nama_acc_pembayaran."</td>";
					$html.= 	"<td colspan=2 align=right>".number_format($r->biaya_reduksi)."</td>";
					$html.= "</tr>";
				}
			}
		$no++;
	}
	
	# Account Donasi & Koperasi #
	$external_account = array('donasi','koperasi');
	$mo = $no;
	for($m=0; $m<sizeof($external_account); $m++){
		if($external_account[$m]=="donasi"){
			$account = $this->db->query("select sum(jumlah_donatur) as  total from donatur_tbl")->result();
		}else{
			$account = $this->db->query("SELECT 
											SUM(koperasi_tbl.`harga` * penjualan_tbl.`jumlah`) as total
											FROM koperasi_tbl LEFT JOIN penjualan_tbl
											ON koperasi_tbl.`id_item` = penjualan_tbl.`id_item`")->result();
		}	
			$html.= "<tr>";
			$html.= 	"<td >".$mo.".</td>";
			$html.= 	"<td >".$external_account[$m]."</td>";
			$html.= 	"<td > : ".number_format($account[0]->total)."</td>";
			$html.= 	"<td >&nbsp;</td>";
			$html.= 	"<td >&nbsp;</td>";
			$html.= 	"<td >&nbsp;</td>";
			$html.= "</tr>";
			$html.= "<tr>";
			$html.= 	"<td colspan=3 style='height:20px'></td>";
			$html.= 	"<td align=left>Saldo</td>";
			$html.= 	"<td align=right>".number_format($e_class->get_forbudget($external_account[$m]))."</td>";
			$html.= 	"<td >&nbsp;</td>";
			$html.= "</tr>";
		
		#check pinjaman karyawan
		$hutang = $this->db->query("select sum(jumlah_hutang) as jumlah_hutang from hutang_piutang_tbl where id_acc_pembayaran = '".$external_account[$m]."'")->row();
		if($hutang->jumlah_hutang){
			$piutang = $this->db->query("select sum(jumlah_piutang) as jumlah_piutang from hutang_piutang_tbl where id_acc_pembayaran = '".$external_account[$m]."'")->row();
			$sisa_hutang = (float)$hutang->jumlah_hutang - (float)$piutang->jumlah_piutang;
			$html.= "<tr>";
			$html.= 	"<td colspan=3 style='height:20px'></td>";
			$html.= 	"<td align=left> Hutang - Piutang Karyawan </td>";
			$html.= 	"<td colspan=2 ></td>";
			$html.= "</tr>";
			$html.= "<tr>";
			$html.= 	"<td colspan=4 align=right style='height:15px'>Sisa Hutang</td>";
			$html.= 	"<td colspan=2 align=right>".number_format($sisa_hutang)."</td>";
			$html.= "</tr>";
		}
		
		#check subsidi reduksi biaya siswa
		$reduksi = $this->db->query("SELECT 
										SUM(biaya_reduksi) AS biaya_reduksi,
										account_pembayaran_tbl.`nama_acc_pembayaran`
										FROM 
										alokasi_biaya_reduksi_tbl INNER JOIN account_pembayaran_tbl
										ON alokasi_biaya_reduksi_tbl.`id_acc_pembayaran` = account_pembayaran_tbl.`id_acc_pembayaran`
										WHERE alokasi_biaya_reduksi_tbl.id_acc_bgt = '".$external_account[$m]."'
										GROUP BY account_pembayaran_tbl.nama_acc_pembayaran")->result();
		if($reduksi){
			$html.= "<tr>";
			$html.= 	"<td colspan=3 style='height:20px'></td>";
			$html.= 	"<td align=left> Biaya Keringanan Siswa </td>";
			$html.= 	"<td colspan=2 ></td>";
			$html.= "</tr>";
			foreach($reduksi as $r){
				$html.= "<tr>";
				$html.= 	"<td colspan=4 align=right style='height:15px'>".$r->nama_acc_pembayaran."</td>";
				$html.= 	"<td colspan=2 align=right>".number_format($r->biaya_reduksi)."</td>";
				$html.= "</tr>";
			}
		}
		$mo++;
	}
	$html.= "</table>";
	
	/* 
	---------- ---------- ---------- -- 
	PAGE BREAK  PAGE BREAK  PAGE BREAK 
	---------- ---------- ---------- --
	*/
	
	
	#Pemasukan Keuangan(Bayaran Siswa)
	$query_accout_pembayaran = "SELECT 
								account_pembayaran_tbl.id_acc_pembayaran, 
								account_pembayaran_tbl.nama_acc_pembayaran 
								FROM account_pembayaran_tbl INNER JOIN pembayaran_siswa_tbl
								ON account_pembayaran_tbl.`id_acc_pembayaran` = pembayaran_siswa_tbl.`id_acc_pembayaran`
								GROUP BY account_pembayaran_tbl.id_acc_pembayaran";
	$accout_pembayaran = $this->db->query($query_accout_pembayaran)->result();
	foreach($accout_pembayaran as $account){
		
		$query_pembayaran_siswa = "SELECT 
								account_pembayaran_tbl.`id_acc_pembayaran`,
								account_pembayaran_tbl.`nama_acc_pembayaran`,
								account_pembayaran_tbl.`harga_pembayaran`,
								detail_acc_pemb_tbl.`buying_date`,
								pembayaran_siswa_tbl.`tgl_transaksi`,
								pembayaran_siswa_tbl.`jumlah_transaksi`,
								siswa_tbl.`nis_siswa`,
								siswa_tbl.`nama_siswa` 
								FROM account_pembayaran_tbl 
								INNER JOIN pembayaran_siswa_tbl ON account_pembayaran_tbl.`id_acc_pembayaran` = pembayaran_siswa_tbl.`id_acc_pembayaran`
								INNER JOIN detail_acc_pemb_tbl ON pembayaran_siswa_tbl.`id_detail_acc_pemb` = detail_acc_pemb_tbl.`id_detail_acc_pemb`
								INNER JOIN siswa_tbl ON pembayaran_siswa_tbl.`nis_siswa` = siswa_tbl.`nis_siswa`
								WHERE account_pembayaran_tbl.`id_acc_pembayaran` = '".$account->id_acc_pembayaran."'
								ORDER BY account_pembayaran_tbl.`id_acc_pembayaran` ASC , detail_acc_pemb_tbl.`buying_date` ASC ";
		$pembayaran_siswa = $this->db->query($query_pembayaran_siswa)->result();
		
		$no = 1;
		$html.= "<table width='100%' border='1' cellpadding='2' cellspacing='2' style='font-size:10px;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;'>";
		$html.= "<tr><td height=25px colspan=6>".$account->nama_acc_pembayaran."</td></tr>";
		$html.= "
				<tr>
					<td>NO</td>
					<td>NIS</td>
					<td>Nama Siswa</td>
					<td>Periode Pembayaran</td>
					<td>Tanggal Pembayaran</td>
					<td>Jumlah Pembayaran</td>
				</tr>
				";
		foreach($pembayaran_siswa as $ps){
			$html.= "<tr>";
			$html.= 	"<td>".$no."</td>";
			$html.= 	"<td>".$ps->nis_siswa."</td>";
			$html.= 	"<td>".$ps->nama_siswa."</td>";
			$html.= 	"<td>".substr(indo_date($ps->buying_date),3)."</td>";
			$html.= 	"<td>".indo_date($ps->tgl_transaksi)."</td>";
			$html.= 	"<td align=right>".number_format($ps->jumlah_transaksi)."</td>";
			$html.= "</tr>";
			
			$no++;
		}
		$html.= "</table>";
	}
	


	#Pemasukan Keuangan(Koperasi)
	$koperasi = $this->db->query("SELECT 
										koperasi_tbl.`nama_item`,
										koperasi_tbl.`satuan`,
										koperasi_tbl.`harga`,
										SUM(penjualan_tbl.`jumlah`) AS jumlah
											FROM koperasi_tbl INNER JOIN penjualan_tbl
											ON koperasi_tbl.`id_item` = penjualan_tbl.`id_item`
											GROUP BY koperasi_tbl.`id_item`
											ORDER BY koperasi_tbl.`id_item` ASC")->result();
										
	$no = 1;
	$html.= "<table width='100%' border='1' cellpadding='2' cellspacing='2' style='font-size:10px;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;'>";
	$html.= "<tr><td height=25px colspan=6>Koperasi</td></tr>";
	$html.= "
			<tr>
				<td>NO</td>
				<td>Nama Item</td>
				<td>Satuan</td>
				<td>Harga</td>
				<td>Jumlah Terjual</td>
				<td>Sub Total</td>
			</tr>
			";
	
	foreach($koperasi as $k){
		$subtotal = $k->harga * $k->jumlah;
		$html.= "
			<tr>
				<td>".$no."</td>
				<td>".$k->nama_item."</td>
				<td>".$k->satuan."</td>
				<td>".$k->harga."</td>
				<td>".$k->jumlah."</td>
				<td>".$subtotal."</td>
			</tr>
			";
		$no++;
	}
	$html.= "</table>";
	
	#Pemasukan Keuangan(Donasi)
	$no = 1;
	$donasi = $this->db->query("SELECT * FROM donatur_tbl")->result();
	$html.= "<table width='100%' border='1' cellpadding='2' cellspacing='2' style='font-size:10px;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;'>";
	$html.= "<tr><td height=25px colspan=6>Donasi</td></tr>";
	$html.= "
			<tr>
				<td>NO</td>
				<td>Tanggal</td>
				<td>Nama Donatur</td>
				<td>Nominal</td>
			</tr>
			";
	
	foreach($donasi as $d){
		$html.= "
			<tr>
				<td>".$no."</td>
				<td>".$d->tgl_donatur."</td>
				<td>".$d->nama_donatur."</td>
				<td>".$d->jumlah_donatur."</td>
			</tr>
			";
		$no++;
	}
	$html.= "</table>";
	
	
	#Pemasukan Keuangan(Piutang)
	$piutang_karyawan = $this->db->query("SELECT 
												hutang_pegawai_tbl.`nik_pegawai`,
												hutang_pegawai_tbl.`nama`,
												SUM(hutang_piutang_tbl.`jumlah_piutang`) AS jumlah_piutang
													FROM 
														hutang_pegawai_tbl INNER JOIN hutang_piutang_tbl
														ON hutang_pegawai_tbl.`nik_pegawai` = hutang_piutang_tbl.`nik_pegawai`
														GROUP BY hutang_pegawai_tbl.`nik_pegawai`")->result();
	$no = 1;
	$html.= "<table width='100%' border='1' cellpadding='2' cellspacing='2' style='font-size:10px;font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;'>";
	$html.= "<tr><td height=25px colspan=6>Piutang Karyawan</td></tr>";
	$html.= "
			<tr>
				<td>NO</td>
				<td>NIK Karyawan</td>
				<td>Nama Karyawan</td>
				<td>Nominal</td>
			</tr>
			";
			
	foreach($piutang_karyawan as $pk){
		$html.= "
			<tr>
				<td>".$no."</td>
				<td>".$pk->nik_pegawai."</td>
				<td>".$pk->nama."</td>
				<td>".$pk->jumlah_piutang."</td>
			</tr>
			";
		$no++;
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
	
	$dompdf->stream("Laporan Siklus Keuangan Sekolah.pdf", array("Attachment" => false));

	exit(0);
?>