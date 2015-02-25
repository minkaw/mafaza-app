<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class pembayaran extends CI_Controller {
	
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
														AND budget_tbl.`is_aktif` = '1'
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
			$this->db->query("insert into hutang_pegawai_tbl (`nik_pegawai`,`nama`,`tgl_hutang`,`flag`) values ('".$nik."','".$nm."','".$sd."','".$fl."')");
			$id = $this->db->query("select id_hutang_pegawai from hutang_pegawai_tbl where flag = '".$fl."'")->row()->id_hutang_pegawai;
			for($m=0; $m<$a; $m++){
				$this->db->query("insert into hutang_piutang_tbl 
									(id_hutang_pegawai, nik_pegawai, jumlah_hutang, tgl_transaksi, id_acc_pembayaran) 
										values 
											('".$id."','".$nik."','".$bgt[$m]."','".$sd."','".$idp[$m]."')");
			}
		}else{
			echo "Fail";
		}
	}
	
	function budget_koperasi(){
		$this->load->view('pengeluaran/budget_koperasi');
	}
	
	function piutang_koperasi(){
		$this->load->view('pembayaran/piutang_koperasi');
	}
	
	function koperasi(){
		$data['items'] = $this->db->query("select * from koperasi_tbl")->result();
		$this->load->view('pembayaran/koperasi',$data);
	}
	
	function post_pinjaman_koperasi(){
		$ket = $this->input->post('ket');
		$idp = $this->input->post('idp');
		$bgt = $this->input->post('bgt');
		$sd  = db_date($this->input->post('sd'));
		$fl  = rand(111111,999999);
		$a = sizeof($idp);
		$b = sizeof($bgt);
		
		if($a===$b){
			$this->db->query("insert into hutang_koperasi_tbl (`keterangan`,`tgl_hutang`,`flag`) values ('".$ket."','".$sd."','".$fl."')");
			$id = $this->db->query("select id_hutang_koperasi from hutang_koperasi_tbl where flag = '".$fl."'")->row()->id_hutang_koperasi;
			for($m=0; $m<$a; $m++){
				$this->db->query("insert into piutang_koperasi_tbl
									(id_hutang_koperasi, jumlah_hutang, tgl_transaksi, id_acc_pembayaran) 
										values 
											('".$id."','".$bgt[$m]."','".$sd."','".$idp[$m]."')");
			}
		}else{
			echo "Fail";
		}
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
					budget_tbl.`start_date`,
					budget_tbl.`periode`
					from alokasi_budget_tbl left join account_pembayaran_tbl
					on alokasi_budget_tbl.`id_acc_pembayaran` = account_pembayaran_tbl.`id_acc_pembayaran`
					inner join budget_tbl on budget_tbl.`id_budget` = alokasi_budget_tbl.`id_budget` 
					where alokasi_budget_tbl.`id_budget` = '".$id."' AND budget_tbl.`is_aktif` = '1'";
		
		$data['detail']    = $this->db->query($Q)->result();
		$data['account']   = $name;
		$data['id_budget'] = $id;
		//check periode tahun akademik for budget
		$p = $this->db->query("SELECT periode_awal, periode_akhir FROM periode_akademik_tbl WHERE is_periode = 1")->row();
		$b = $this->db->query("SELECT periode FROM budget_tbl WHERE id_budget = '".$id."'")->row();
		$data['budget_periode']  = $b->periode;
		$data['current_periode'] = $p->periode_awal.$p->periode_akhir;
		
		$this->load->view('pengeluaran/detail_pos_budget',$data);
	}
	
	function get_alltransaction($b,$id){
		if($b=="koperasi"){
			$data['content']   = "koperasi";
			$data['transaksi'] = $this->db->query("SELECT 
										koperasi_tbl.`nama_item`,
										koperasi_tbl.`satuan`,
										koperasi_tbl.`harga`,
										penjualan_tbl.`jumlah`,
										penjualan_tbl.`tgl_terjual`
										FROM koperasi_tbl LEFT JOIN penjualan_tbl
										ON koperasi_tbl.`id_item` = penjualan_tbl.`id_item`
										WHERE koperasi_tbl.`id_item` = '".$id."'
										ORDER BY penjualan_tbl.`id_penjualan` DESC")->result();
		}else{
			$data['content']   = "budget";
			$data['transaksi'] = $this->db->query("select * from transaksi_tbl where id_budget = '".$id."'")->result();
		}
		
		$this->load->view('pengeluaran/alltransaction',$data);
		
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
						nama_acc_pembayaran NOT LIKE  '%KELAS 3%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 4%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 5%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 6%'";
		}else if($kls=='2A' OR $kls=='2B'){
			$Q = "SELECT *  FROM account_pembayaran_tbl 
					WHERE 
						nama_acc_pembayaran NOT LIKE  '%KELAS 1%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 3%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 4%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 5%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 6%'";
		}else if($kls=='3A' OR $kls=='3B'){
			$Q = "SELECT *  FROM account_pembayaran_tbl 
					WHERE 
						nama_acc_pembayaran NOT LIKE  '%KELAS 1%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 2%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 4%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 5%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 6%'";
		}else if($kls=='4A' OR $kls=='4B'){
			$Q = "SELECT *  FROM account_pembayaran_tbl 
					WHERE 
						nama_acc_pembayaran NOT LIKE  '%KELAS 1%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 2%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 3%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 5%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 6%'";
		}else if($kls=='5A' OR $kls=='5B'){
			$Q = "SELECT *  FROM account_pembayaran_tbl 
					WHERE 
						nama_acc_pembayaran NOT LIKE  '%KELAS 1%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 2%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 3%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 4%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 6%'";
		}else if($kls=='6A' OR $kls=='6B'){
			$Q = "SELECT *  FROM account_pembayaran_tbl 
					WHERE 
						nama_acc_pembayaran NOT LIKE  '%KELAS 1%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 2%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 3%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 4%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 5%'";
		}else{
			$Q = "SELECT *  FROM account_pembayaran_tbl 
					WHERE 
						nama_acc_pembayaran NOT LIKE  '%KELAS 1%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 2%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 3%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 4%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 5%' AND
						nama_acc_pembayaran NOT LIKE  '%KELAS 6%'";
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
	
	function detail_pinjaman_koperasi($id){
		$data['detail_pinjaman'] = $this->db->query("SELECT 
															hutang_koperasi_tbl.`id_hutang_koperasi`,
															hutang_koperasi_tbl.`keterangan`,
															hutang_koperasi_tbl.`tgl_hutang`,
															piutang_koperasi_tbl.`id_hutang_pembayaran`,
															piutang_koperasi_tbl.`jumlah_hutang`,
															piutang_koperasi_tbl.`jumlah_piutang`,
															piutang_koperasi_tbl.`tgl_transaksi`,
															piutang_koperasi_tbl.`id_acc_pembayaran`
															FROM hutang_koperasi_tbl INNER JOIN piutang_koperasi_tbl
															ON hutang_koperasi_tbl.`id_hutang_koperasi` = piutang_koperasi_tbl.`id_hutang_koperasi`
															OR hutang_koperasi_tbl.`id_hutang_koperasi` = piutang_koperasi_tbl.`id_hutang_pembayaran`
															WHERE hutang_koperasi_tbl.`id_hutang_koperasi` = '".$id."'")->result();
		$this->load->view('pembayaran/detail_pinjaman_koperasi',$data);
	}
	
	/* piutang koperasi */
	function pembayaran_piutang_koperasi(){
		$data['piutang'] = $this->db->query("SELECT 
												hutang_koperasi_tbl.`id_hutang_koperasi`,
												hutang_koperasi_tbl.`keterangan`,
												hutang_koperasi_tbl.`tgl_hutang`,
												SUM(piutang_koperasi_tbl.`jumlah_hutang`)  AS hutang,
												SUM(piutang_koperasi_tbl.`jumlah_piutang`) AS piutang,
												SUM(piutang_koperasi_tbl.`jumlah_hutang`) - SUM(piutang_koperasi_tbl.`jumlah_piutang`) AS sisa
												FROM hutang_koperasi_tbl INNER JOIN piutang_koperasi_tbl
												ON hutang_koperasi_tbl.`id_hutang_koperasi` = piutang_koperasi_tbl.`id_hutang_koperasi`
												OR hutang_koperasi_tbl.`id_hutang_koperasi` = piutang_koperasi_tbl.`id_hutang_pembayaran`
												GROUP BY hutang_koperasi_tbl.`keterangan`")->result();
		$this->load->view('pembayaran/pembayaran_piutang_koperasi',$data);
	}
	
	function detail_piutang_koperasi($id_hut_koperasi,$ket){
		$data['id_hut_koperasi']= $id_hut_koperasi;
		$data['keterangan']   	= $ket;
		$data['saldo_koperasi'] = @$this->db->query("SELECT SUM(koperasi_tbl.`harga` *  penjualan_tbl.`jumlah`) AS total_penjualan
																FROM koperasi_tbl INNER JOIN penjualan_tbl
																ON koperasi_tbl.`id_item` = penjualan_tbl.`id_item`")->row();  
		$data['account']   		= $this->db->query("select id_acc_pembayaran from piutang_koperasi_tbl where id_hutang_koperasi = '".$id_hut_koperasi."'")->result();
		
		$this->load->view('pembayaran/detail_piutang_koperasi',$data);
	}
	
	function post_piutang_koperasi(){
		#idhk = id_hutang_koperasi
		$idhk = $this->input->post('idhk');
		$idp  = $this->input->post('idp');
		$p 	  = $this->input->post('p');
		
		$d = date('Y-m-d');
		$n = sizeof($idp);
		$m = $p/$n;
		for($x=0; $x<$n; $x++){
			$i = $this->db->query("INSERT INTO 
										piutang_koperasi_tbl 
										(id_hutang_pembayaran, jumlah_piutang, tgl_transaksi, id_acc_pembayaran) 
											values 
												('".$idhk."','".$m."','".$d."','".$idp[$x]."')");
		}
		if($i){
			echo 1;
		}
	}
	
	/* Piutang Karyawan */
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
	
	function detail_piutang($nik,$id,$nama){
		$data['nik']  	   = $nik;
		$data['idh']   	   = $id;
		$data['nama'] 	   = $nama;
		$data['account']   = $this->db->query("select id_acc_pembayaran from hutang_piutang_tbl where id_hutang_pegawai = '".$id."' and nik_pegawai = '".$nik."'")->result();
		
		$this->load->view('pembayaran/detail_piutang',$data);
	}

	
	function post_piutang(){
		#idh = id_hutang_pegawai
		
		$nik = $this->input->post('nik');
		$idh = $this->input->post('idh');
		$idp = $this->input->post('idp');
		$p = $this->input->post('p');
		
		$d = date('Y-m-d');
		$n = sizeof($idp);
		$m = $p/$n;
		for($x=0; $x<$n; $x++){
			$i = $this->db->query("INSERT INTO 
										hutang_piutang_tbl 
										(nik_pegawai, jumlah_piutang, tgl_transaksi, id_acc_pembayaran) 
											values 
												('".$nik."','".$m."','".$d."','".$idp[$x]."')");
		}
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
			/*
			$get_transaksi = $this->db->query("SELECT 
												SUM(jumlah_transaksi) AS jml_trans,
												pembayaran_siswa_tbl.`tgl_transaksi`,
												alokasi_biaya_reduksi_tbl.`biaya_reduksi`,
												biaya_reduksi_tbl.nis_siswa
												FROM pembayaran_siswa_tbl LEFT JOIN biaya_reduksi_tbl ON pembayaran_siswa_tbl.`nis_siswa` = biaya_reduksi_tbl.`nis_siswa` 
													LEFT JOIN alokasi_biaya_reduksi_tbl ON alokasi_biaya_reduksi_tbl.`id_alokasi_biaya_reduksi` = biaya_reduksi_tbl.`id_biaya_reduksi`		
													AND pembayaran_siswa_tbl.`id_acc_pembayaran` = alokasi_biaya_reduksi_tbl.`id_acc_pembayaran`
													WHERE 
													pembayaran_siswa_tbl.id_acc_pembayaran = '".$idp."' AND
													pembayaran_siswa_tbl.id_detail_acc_pemb = '".$r->id_detail_acc_pemb."' AND 
													pembayaran_siswa_tbl.nis_siswa = '".$nis."'")->result();
			*/
			$get_reduce = $this->db->query("SELECT  alokasi_biaya_reduksi_tbl.`biaya_reduksi`
												FROM 
												biaya_reduksi_tbl INNER JOIN alokasi_biaya_reduksi_tbl
												ON biaya_reduksi_tbl.`id_biaya_reduksi` = alokasi_biaya_reduksi_tbl.`id_biaya_reduksi`
												WHERE 
												biaya_reduksi_tbl.`nis_siswa` = '".$nis."' AND  alokasi_biaya_reduksi_tbl.`id_acc_pembayaran` = '".$r->id_acc_pembayaran."'")->num_rows();
			if($get_reduce){
				$get_transaksi = $this->db->query("SELECT 
													SUM(pembayaran_siswa_tbl.jumlah_transaksi) AS jml_trans,
													pembayaran_siswa_tbl.`tgl_transaksi`,
													alokasi_biaya_reduksi_tbl.`biaya_reduksi`,
													pembayaran_siswa_tbl.`nis_siswa`
													FROM 
													pembayaran_siswa_tbl 
														INNER JOIN alokasi_biaya_reduksi_tbl ON pembayaran_siswa_tbl.`id_acc_pembayaran` = alokasi_biaya_reduksi_tbl.`id_acc_pembayaran`
													WHERE
													pembayaran_siswa_tbl.id_acc_pembayaran = '".$idp."' AND
													pembayaran_siswa_tbl.id_detail_acc_pemb = '".$r->id_detail_acc_pemb."' AND 
													pembayaran_siswa_tbl.nis_siswa = '".$nis."'")->result();
				$r->jml_trans = $get_transaksi[0]->jml_trans;
				$r->tgl_trans = $get_transaksi[0]->tgl_transaksi;
				$r->reduksi   = $get_transaksi[0]->biaya_reduksi;
				$row_set[] = $r;
			}else{
				$get_transaksi = $this->db->query("SELECT 
													SUM(pembayaran_siswa_tbl.jumlah_transaksi) AS jml_trans,
													pembayaran_siswa_tbl.`tgl_transaksi`,
													pembayaran_siswa_tbl.`nis_siswa`
													FROM pembayaran_siswa_tbl
													WHERE
													pembayaran_siswa_tbl.id_acc_pembayaran = '".$idp."' AND
													pembayaran_siswa_tbl.id_detail_acc_pemb = '".$r->id_detail_acc_pemb."' AND 
													pembayaran_siswa_tbl.nis_siswa = '".$nis."'")->result();
				$r->jml_trans = $get_transaksi[0]->jml_trans;
				$r->tgl_trans = $get_transaksi[0]->tgl_transaksi;
				$r->reduksi   = 0;
				$row_set[] = $r;
			}
			
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
		$data['bired'] = $this->db->query("SELECT biaya_reduksi 
											FROM alokasi_biaya_reduksi_tbl INNER JOIN biaya_reduksi_tbl
											ON alokasi_biaya_reduksi_tbl.`id_biaya_reduksi` = biaya_reduksi_tbl.`id_biaya_reduksi`
											WHERE alokasi_biaya_reduksi_tbl.`id_acc_pembayaran` = '".$idp."'AND biaya_reduksi_tbl.`nis_siswa` = '".$nis."'")->result();
		$this->load->view('pembayaran/post_pembayaran',$data);
	}
	
	function save_postpembayaran(){
		$nis  = $this->input->post('nis');
		$jb   = str_replace(",","",$this->input->post('jb'));		// jumlah bayar
		$idpe = $this->input->post('idpe');							//id_pembayaran [pembayaran_siswa]
		$idap = $this->input->post('idap');							//id_detail_acc_pemb [detail_acc_pemb]
		//var_dump($nis);
		$tglt = date('Y-m-d');
		
		#bukan cicilan
		$Q = "insert into pembayaran_siswa_tbl 
				(`id_acc_pembayaran`,`id_detail_acc_pemb`,`nis_siswa`,`tgl_transaksi`,`jumlah_transaksi`,`status_pembayaran`) values 
				('".$idpe."','".$idap."','".$nis."','".$tglt."','".$jb."','L')";
		//var_dump($Q);
		$this->db->query($Q);
	}

	function detail_pembayaran($nis,$idp,$idacp,$hp,$ket){
		//var_dump($nis,$idp,$idacp,$hp,$ket);exit;
		/*
			$idp   = id_pembayaran
			$idacp = id_detail_acc_pemb
			$hp	   = harga_pembayaran
		*/
		
		$data['nis'] 	= $nis;
		$data['idp'] 	= $idp;
		$data['idacp']  = $idacp;
		$data['hapem']  = $hp;
		
		$get_reduce = $this->db->query("SELECT  alokasi_biaya_reduksi_tbl.`biaya_reduksi`
												FROM 
												biaya_reduksi_tbl INNER JOIN alokasi_biaya_reduksi_tbl
												ON biaya_reduksi_tbl.`id_biaya_reduksi` = alokasi_biaya_reduksi_tbl.`id_biaya_reduksi`
												WHERE 
												biaya_reduksi_tbl.`nis_siswa` = '".$nis."' AND  alokasi_biaya_reduksi_tbl.`id_acc_pembayaran` = '".$idp."'")->num_rows();
		if($get_reduce){
			$data['get_pembayaran'] = $this->db->query("SELECT 
															jumlah_transaksi,
															pembayaran_siswa_tbl.`tgl_transaksi`,
															alokasi_biaya_reduksi_tbl.`biaya_reduksi`,
															biaya_reduksi_tbl.nis_siswa
															
															FROM alokasi_biaya_reduksi_tbl INNER JOIN biaya_reduksi_tbl
															ON alokasi_biaya_reduksi_tbl.`id_biaya_reduksi` = biaya_reduksi_tbl.`id_biaya_reduksi`
															LEFT JOIN pembayaran_siswa_tbl ON pembayaran_siswa_tbl.`nis_siswa` = biaya_reduksi_tbl.`nis_siswa` 
															WHERE 
																biaya_reduksi_tbl.`nis_siswa` = '".$nis."' AND 
																alokasi_biaya_reduksi_tbl.`id_acc_pembayaran` = '".$idp."' AND
																pembayaran_siswa_tbl.id_detail_acc_pemb = '".$idacp."'
																		ORDER BY pembayaran_siswa_tbl.`id_pembayaran_siswa` ASC")->result();
		}else{
			$data['get_pembayaran'] = $this->db->query("SELECT 
																jumlah_transaksi,
																pembayaran_siswa_tbl.`tgl_transaksi`

																FROM pembayaran_siswa_tbl 
																WHERE 
																pembayaran_siswa_tbl.`nis_siswa` = '".$nis."' AND 
																pembayaran_siswa_tbl.`id_acc_pembayaran` = '".$idp."' AND
																pembayaran_siswa_tbl.id_detail_acc_pemb = '".$idacp."'
																		ORDER BY pembayaran_siswa_tbl.`id_pembayaran_siswa` ASC")->result();
		}
		/*
		$data['get_pembayaran'] = $this->db->query("SELECT 
															jumlah_transaksi,
															pembayaran_siswa_tbl.`tgl_transaksi`,
															alokasi_biaya_reduksi_tbl.`biaya_reduksi`,
															biaya_reduksi_tbl.nis_siswa
																FROM pembayaran_siswa_tbl LEFT JOIN biaya_reduksi_tbl ON pembayaran_siswa_tbl.`nis_siswa` = biaya_reduksi_tbl.`nis_siswa` 
																LEFT JOIN alokasi_biaya_reduksi_tbl ON alokasi_biaya_reduksi_tbl.`id_alokasi_biaya_reduksi` = biaya_reduksi_tbl.`id_biaya_reduksi`		
																AND pembayaran_siswa_tbl.`id_acc_pembayaran` = alokasi_biaya_reduksi_tbl.`id_acc_pembayaran`
																WHERE 
																	pembayaran_siswa_tbl.id_acc_pembayaran = '".$idp."' AND
																	pembayaran_siswa_tbl.id_detail_acc_pemb = '".$idacp."' AND 
																	pembayaran_siswa_tbl.nis_siswa = '".$nis."'
																	ORDER BY pembayaran_siswa_tbl.`id_pembayaran_siswa` ASC")->result();
		*/
		
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