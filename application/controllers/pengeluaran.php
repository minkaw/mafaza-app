<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengeluaran extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('login');
		}
	}
	
	function index()
	{
		$data['account_pemasukan'] = $this->db->query("select 
														pembayaran_siswa_tbl.`id_acc_pembayaran` as idp,
														sum(pembayaran_siswa_tbl.`jumlah_transaksi`) as trans_pemb,
														account_pembayaran_tbl.`id_acc_pembayaran` as idpMaster,
														account_pembayaran_tbl.`nama_acc_pembayaran`
														from pembayaran_siswa_tbl inner join account_pembayaran_tbl
														on pembayaran_siswa_tbl.`id_acc_pembayaran` = account_pembayaran_tbl.`id_acc_pembayaran`
														group by pembayaran_siswa_tbl.`id_acc_pembayaran`")->result();
		$data['account_budget'] = $this->db->query("SELECT 
														budget_tbl.`id_budget`,
														budget_tbl.`nama_budget`,
														budget_tbl.`start_date`,
														SUM(alokasi_budget_tbl.`budget`) AS budget
														FROM budget_tbl INNER JOIN alokasi_budget_tbl
														ON budget_tbl.`id_budget` = alokasi_budget_tbl.`id_budget`
														GROUP BY budget_tbl.`id_budget`")->result();
		$this->load->view('pembayaran/accountpos',$data);
	}
	
	function transaksi_pengeluaran(){
		$this->load->view('pembayaran/transaksi');
	}
	
	function pinjaman_karyawan(){
		$this->load->view('pembayaran/pinjaman_karyawan');
	}
	
	function post_pinjaman(){
		$nik = $this->input->post('nik');
		$nm  = $this->input->post('nm');
		$idp = $this->input->post('idp');
		$bgt = $this->input->post('bgt');
		$sd  = db_date($this->input->post('sd'));
		$fl  = rand(111111,999999);
		$a = sizeof($idp);
		$b = sizeof($bgt);
		
		if($a===$b){
			$this->db->query("insert into hutang_pegawai_tbl (`nik_pegawai`,`nama`,`tgl_hutang`) values ('".$nik."','".$nm."','".$sd."')");
			for($m=0; $m<$a; $m++){
				$this->db->query("insert into hutang_piutang_tbl 
									(nik_pegawai, jumlah_hutang, tgl_transaksi, id_acc_pembayaran) 
										values 
											('".$nik."','".$bgt[$m]."','".$sd."','".$idp[$m]."')");
			}
		}else{
			echo "Fail";
		}
	}
	
	
	function koperasi(){
		$data['items'] = $this->db->query("select * from koperasi_tbl")->result();
		$this->load->view('pembayaran/koperasi',$data);
	}
	
	function alokasi_budget($id,$name){
		$id   = urldecode($id);
		$name = urldecode($name);
		$Q = "select 
					alokasi_budget_tbl.`id_alokasi`,
					alokasi_budget_tbl.`id_budget`,
					alokasi_budget_tbl.`id_acc_pembayaran`,
					alokasi_budget_tbl.`budget`,
					account_pembayaran_tbl.`nama_acc_pembayaran`,
					budget_tbl.`start_date`
					from alokasi_budget_tbl inner join account_pembayaran_tbl
					on alokasi_budget_tbl.`id_acc_pembayaran` = account_pembayaran_tbl.`id_acc_pembayaran`
					inner join budget_tbl on budget_tbl.`id_budget` = alokasi_budget_tbl.`id_budget` 
					where alokasi_budget_tbl.`id_budget` = '".$id."'";
		$data['detail']  = $this->db->query($Q)->result();
		$data['account'] = $name;
		$this->load->view('pengeluaran/detail_pos_budget',$data);
	}
	
	function detail_account($id,$bayar,$biaya){
		
		$data['pembayaran'] = urldecode($bayar);
		$data['biaya'] = urldecode($biaya);
		$data['get_detail']=$this->db->query("select * from detail_acc_pemb_tbl where id_acc_pembayaran = '".$id."'")->result();
		//var_dump($data['get_detail']);exit;
		$this->load->view('pembayaran/detail_acc_pemb',$data);
	}
	
	function change_pembayaran($kls){
		//$kls = $this->input->post('kls');
		if($kls=='1A' OR $kls=='1B'){
			$Q = "SELECT *  FROM account_pembayaran_tbl 
					WHERE 
						nama_acc_pembayaran NOT LIKE  '%KELAS 2%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 3%'";
		}else if($kls=='2A' OR $kls=='2B'){
			$Q = "SELECT *  FROM account_pembayaran_tbl 
					WHERE 
						nama_acc_pembayaran NOT LIKE  '%KELAS 1%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 3%'";
		}else{
			$Q = "SELECT *  FROM account_pembayaran_tbl 
					WHERE 
						nama_acc_pembayaran NOT LIKE  '%KELAS 1%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 2%'";
		}	
		$data = $this->db->query($Q)->result();
		
		foreach($data as $r){
			$r->id_pembayaran   = $r->id_acc_pembayaran;
			$r->nama_pembayaran = $r->nama_acc_pembayaran;
			$r->jenis 			= $r->jenis_pembayaran;
			$r->harga 			= $r->harga_pembayaran;
			$row_set[] 			= $r;
		}
		echo json_encode($row_set);
	}
	
	function account_pembayaran(){
		$data = $this->db->query("select * from account_pembayaran_tbl")->result();
		foreach($data as $r){
			$r->id_pembayaran   = $r->id_acc_pembayaran;
			$r->nama_pembayaran = $r->nama_acc_pembayaran;
			$r->jenis 			= $r->jenis_pembayaran;
			$r->harga 			= $r->harga_pembayaran;
			$row_set[] 			= $r;
		}
		echo json_encode($row_set);
	}
	
	function pembayaran_siswa(){
		$this->load->view('pembayaran/pembayaran_siswa');
	}
	
	function pembayaran_koperasi(){
		$this->load->view('pembayaran/pembayaran_koperasi');
	}
	
	/* Donasi */
	function pembayaran_donasi(){
		$data['donasi'] = $this->db->query("select * from donatur_tbl order by id_donatur desc")->result();
		$this->load->view('pembayaran/pembayaran_donasi',$data);
	}
	
	function input_donatur(){
		$this->load->view('pembayaran/input_donatur');
	}
	
	function save_donasi(){
		$n = $this->input->post('n');
		$j = str_replace(",","",$this->input->post('h'));
		$d = date('Y-m-d');
		$this->db->query("insert into donatur_tbl (nama_donatur, tgl_donatur, jumlah_donatur) values ('".$n."','".$d."','".$j."')");
	}
	
	/* Hutang */
	function pembayaran_hutang(){
		$data['hutang'] = $this->db->query("select * from hutang_pegawai_tbl order by id_hutang desc")->result();
		$this->load->view('pembayaran/pembayaran_hutang',$data);
	}
	
	function input_hutang(){
		$this->load->view('pembayaran/input_hutang');
	}
	
	function save_hutang(){
		$nik  	 = $this->input->post('nik');
		$nama 	 = $this->input->post('nama');
		$amount  = $this->input->post('amount');
		$date 	 = date('Y-m-d');
		
		
		$this->db->query("insert into hutang_pegawai_tbl 
										(nik_pegawai, nama, tgl_hutang) 
										values 
										('".$nik."','".$nama."','".$date."')");
		
		$this->db->query("insert into hutang_piutang_tbl 
										(nik_pegawai, jumlah_hutang, tgl_transaksi) 
										values 
										('".$nik."','".$amount."','".$date."')");
	}
	
	function detail_pinjaman($nik){
		$data['detail_pinjaman'] = $this->db->query("SELECT 
															* 
															FROM hutang_pegawai_tbl INNER JOIN hutang_piutang_tbl
															ON hutang_pegawai_tbl.`nik_pegawai` = hutang_piutang_tbl.`nik_pegawai`
															WHERE hutang_pegawai_tbl.`nik_pegawai` = '".$nik."'")->result();
		$this->load->view('pembayaran/detail_pinjaman',$data);
	}
	
	/* Piutang */
	function pembayaran_piutang(){
		$data['piutang'] = $this->db->query("SELECT 
												hutang_pegawai_tbl.`id_hutang_pegawai`,
												hutang_pegawai_tbl.`nik_pegawai`,
												hutang_pegawai_tbl.`nama`,
												SUM(hutang_piutang_tbl.`jumlah_hutang`) AS hutang,
												SUM(hutang_piutang_tbl.`jumlah_piutang`) AS piutang,
												SUM(hutang_piutang_tbl.`jumlah_hutang`) - SUM(hutang_piutang_tbl.`jumlah_piutang`) AS sisa 	 
												FROM hutang_pegawai_tbl INNER JOIN hutang_piutang_tbl
												ON hutang_pegawai_tbl.`nik_pegawai` = hutang_piutang_tbl.`nik_pegawai`
												GROUP BY hutang_pegawai_tbl.`nik_pegawai`")->result();
		$this->load->view('pembayaran/pembayaran_piutang',$data);
	}
	
	function detail_piutang($nik,$nama){
		$data['nik']  = $nik;
		$data['nama'] = $nama;
		$data['sisa'] = $sisa;
		$this->load->view('pembayaran/detail_piutang',$data);
	}
	
	function post_piutang($nik, $p){
		
		$i = $this->db->query("INSERT INTO hutang_piutang_tbl (nik_pegawai, jumlah_piutang) values ('".$nik."','".$p."')");
		if($i){
			echo 1;
		}
	}
	
	function auto_nis(){
		$term = $this->input->post('term');
		$Qry  = "SELECT 
						siswa_tbl.nis_siswa, 
						siswa_tbl.nama_siswa,
						kelas_tbl.nama_kelas
						FROM siswa_tbl INNER JOIN kelas_tbl
						ON siswa_tbl.id_kelas = kelas_tbl.id_kelas
						WHERE siswa_tbl.nis_siswa LIKE '".$term."%' 
						OR nama_siswa LIKE '".$term."%' LIMIT 15";
		$Sql  = $this->db->query($Qry)->result();
		foreach ($Sql as $r){
			$r->value  = $r->nis_siswa." | ".$r->nama_siswa;
			$r->id	   = $r->nama_siswa;
			$r->kelas  = $r->nama_kelas;
			$row_set[] = $r; 		
		}
		echo json_encode($row_set); //data hasil query yang dikirim kembali dalam format json
	}
	
	function get_siswabayar(){
		$nis = $this->input->post('nis');
		$idp = $this->input->post('idp');
		
		$Q = "SELECT 	
					account_pembayaran_tbl.`id_acc_pembayaran`,
					account_pembayaran_tbl.`harga_pembayaran`,
					detail_acc_pemb_tbl.`id_detail_acc_pemb`,
					detail_acc_pemb_tbl.`buying_date`
				FROM 
					account_pembayaran_tbl inner join detail_acc_pemb_tbl
					on detail_acc_pemb_tbl.`id_acc_pembayaran` =  account_pembayaran_tbl.`id_acc_pembayaran`
					where account_pembayaran_tbl.`id_acc_pembayaran` = '".$idp."'
					ORDER BY detail_acc_pemb_tbl.`buying_date` ASC";
		
		$data = $this->db->query($Q)->result();
		foreach ($data as $r){
			$r->id_acc_pembayaran  = $r->id_acc_pembayaran;
			$r->harga_pembayaran   = $r->harga_pembayaran;
			$r->id_detail_acc_pemb = $r->id_detail_acc_pemb;
			$r->buying_date 	   = $r->buying_date;
			
			
			/* Menghitung Jumlah pembayaran siswa yg sudah di cicil/lunas  */
			$get_transaksi = $this->db->query("SELECT 
												sum(jumlah_transaksi) as jml_trans,
												pembayaran_siswa_tbl.`tgl_transaksi`,
												biaya_reduksi_tbl.`biaya_reduksi`
												FROM pembayaran_siswa_tbl LEFT JOIN biaya_reduksi_tbl
													ON pembayaran_siswa_tbl.`nis_siswa` = biaya_reduksi_tbl.`nis_siswa` 
													and pembayaran_siswa_tbl.`id_acc_pembayaran` = biaya_reduksi_tbl.`id_acc_pembayaran`
													WHERE 
													pembayaran_siswa_tbl.id_acc_pembayaran = '".$idp."' AND
													pembayaran_siswa_tbl.id_detail_acc_pemb = '".$r->id_detail_acc_pemb."' AND 
													pembayaran_siswa_tbl.nis_siswa = '".$nis."'")->result();
			$r->jml_trans = $get_transaksi[0]->jml_trans;
			$r->tgl_trans = $get_transaksi[0]->tgl_transaksi;
			$r->reduksi   = $get_transaksi[0]->biaya_reduksi;
			$row_set[] = $r;
		}
		
		echo json_encode($row_set);
	}
	
	function get_transaksi($idp,$idacp,$nis){
		$get_transaksi = $this->db->query("select jumlah_transaksi 
											from pembayaran_siswa_tbl 
											where 
												id_acc_pembayaran = '".$idp."' and
												id_detail_acc_pemb = '".$idacp."' and 
												nis_siswa = '".$nis."'")->result();
		foreach($get_transaksi as $t){
			$sum[] = $t->jumlah_transaksi; 
		}
		
		echo array_sum($sum);
	}
	

	function post_pembayaran($nm_pemb, $peiode, $nis, $nama, $kelas, $idp, $id_dap, $hp){
		$data['nmp']   = $nm_pemb;
		$data['per']   = $peiode;
		$data['nis']   = $nis;
		$data['nama']  = $nama;
		$data['kelas'] = $kelas;
		$data['idpe']  = $idp; 					//id_pembayaran [pembayaran_siswa]
		$data['idap']  = $id_dap;				//id_detail_acc_pemb [detail_acc_pemb]
		$data['hapem'] = $hp;					//harga_pembayaran [account_pembayaran]
		$data['bired'] = $this->db->query("select biaya_reduksi from biaya_reduksi_tbl where id_acc_pembayaran = '".$idp."' and nis_siswa = '".$nis."'")->result();
		$this->load->view('pembayaran/post_pembayaran',$data);
	}
	
	function save_postpembayaran(){
		$nis  = $this->input->post('nis');
		$jb   = str_replace(",","",$this->input->post('jb'));		// jumlah bayar
		$idpe = $this->input->post('idpe');							//id_pembayaran [pembayaran_siswa]
		$idap = $this->input->post('idap');							//id_detail_acc_pemb [detail_acc_pemb]
		var_dump($nis);
		$tglt = date('Y-m-d');
		
		#bukan cicilan
		$Q = "insert into pembayaran_siswa_tbl 
				(`id_acc_pembayaran`,`id_detail_acc_pemb`,`nis_siswa`,`tgl_transaksi`,`jumlah_transaksi`,`status_pembayaran`) values 
				('".$idpe."','".$idap."','".$nis."','".$tglt."','".$jb."','L')";
		var_dump($Q);
		$this->db->query($Q);
	}

	function detail_pembayaran($nis,$idp,$idacp,$hp,$ket){
		
		/*
			$idp   = id_pembayaran
			$idacp = id_detail_acc_pemb
			$hp	   = harga_pembayaran
		*/
		
		$data['nis'] 	= $nis;
		$data['idp'] 	= $idp;
		$data['idacp']  = $idacp;
		$data['hapem']  = $hp;
		$data['get_pembayaran'] = $this->db->query("SELECT 
														jumlah_transaksi,
														pembayaran_siswa_tbl.`tgl_transaksi`,
														biaya_reduksi_tbl.`biaya_reduksi`
														FROM pembayaran_siswa_tbl LEFT JOIN biaya_reduksi_tbl
															ON pembayaran_siswa_tbl.`nis_siswa` = biaya_reduksi_tbl.`nis_siswa` 
															and pembayaran_siswa_tbl.`id_acc_pembayaran` = biaya_reduksi_tbl.`id_acc_pembayaran`
															WHERE 
															pembayaran_siswa_tbl.id_acc_pembayaran = '".$idp."' AND
															pembayaran_siswa_tbl.id_detail_acc_pemb = '".$idacp."' AND 
															pembayaran_siswa_tbl.nis_siswa = '".$nis."'")->result();
		$this->load->view('pembayaran/detail_pembayaran_siswa',$data);
	}
	
	function add_cicilan(){
		$cicilan = str_replace(",","",$this->input->post('cicilan'));
		$idacp = $this->input->post('idacp');
		$idp = $this->input->post('idp');
		$nis = $this->input->post('nis');
		$tglt = date('Y-m-d');
		//var_dump($cicilan."--".$idacp."--".$idp);
		
		$Q = "insert into cicilan_pembayaran_tbl (`id_acc_pembayaran`,`id_detail_acc_pemb`) values ('".$idp."','".$idacp."')";
		$this->db->query($Q);
		
		$id = $this->db->query("select id_cicilan 
									from cicilan_pembayaran_tbl 
									where id_acc_pembayaran = '".$idp."' and id_detail_acc_pemb = '".$idacp."'
									order by id_cicilan desc limit 1")->row()->id_cicilan;
		
		$R = "insert into pembayaran_siswa_tbl 
				(`id_acc_pembayaran`,`id_cicilan_pembayaran`,`id_detail_acc_pemb`,`nis_siswa`,`tgl_transaksi`,`jumlah_transaksi`) values 
				('".$idp."','".$id."','".$idacp."','".$nis."','".$tglt."','".$cicilan."')";
		$this->db->query($R);
		
	}
	
	/* 
		monthDiff function 
		for get amount of month
		from start month to end month
	*/
	
	function monthsDif($start, $end){
		$splitStart = explode('-', $start);
		$splitEnd = explode('-', $end);

		if (is_array($splitStart) && is_array($splitEnd)) {
			$startYear = $splitStart[0];
			$startMonth = $splitStart[1];
			$endYear = $splitEnd[0];
			$endMonth = $splitEnd[1];

			$difYears = $endYear - $startYear;
			$difMonth = $endMonth - $startMonth;

			if (0 == $difYears && 0 == $difMonth) { // month and year are same
				return 0;
			}
			else if (0 == $difYears && $difMonth > 0) { // same year, dif months
				return $difMonth;
			}
			else if (1 == $difYears) {
				$startToEnd = 13 - $startMonth; // months remaining in start year(13 to include final month
				return ($startToEnd + $endMonth); // above + end month date
			}
			else if ($difYears > 1) {
				$startToEnd = 13 - $startMonth; // months remaining in start year 
				$yearsRemaing = $difYears - 2;  // minus the years of the start and the end year
				$remainingMonths = 12 * $yearsRemaing; // tally up remaining months
				$totalMonths = $startToEnd + $remainingMonths + $endMonth; // Monthsleft + full years in between + months of last year
				return $totalMonths;
			}
		}
		else {
			return false;
		}
	}
	
	/*
		
	*/
	
		
}