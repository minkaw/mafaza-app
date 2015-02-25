<?php
class Konfigurasi extends CI_Controller{
	
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('login');
		}
	}
	
	function index(){
		$data['account'] = $this->db->query("select * from pegawai_tbl where is_aktif = '1' order by id_pegawai asc ")->result(); 
		$this->load->view("config/user_account",$data);
	}
	
	function edit_account($id){
		$data = $this->db->query("select * from pegawai_tbl where nik_pegawai = '".$id."'")->row();
		echo json_encode($data);
		
	}
	
	function update_account($nik,$p,$w){
		$nik = urldecode($nik);
		$p   = urldecode($p);
		$w   = urldecode($w);
		if($p==$w){
			$p = md5(sha1($p));
			$up = $this->db->query("update pegawai_tbl set password_login = '".$p."' where nik_pegawai = '".$nik."'");
			if($up){
				echo 1;
			}else{
				echo 0;
			}
		}else{
			echo 0;
		}
		
	}
	
	function nonaktif_account($nik){
		$nik = urldecode($nik);
		if($nik=="admin"){
			exit;
		}else{
			$del = $this->db->query("delete from pegawai_tbl where nik_pegawai = '".$nik."'");
			if($del){
				echo 1;
			}else{
				echo 0;
			}
		}
	}
	
	function add_account(){
		$nik = $this->input->post('nik');
		$nmu = $this->input->post('nmu');
		$psb = $this->input->post('psb');
		$psu = $this->input->post('psu');
		if($psb==$psu){
			$psb = md5(sha1($psb));
			$in  = $this->db->query("insert into pegawai_tbl (nik_pegawai, nama_pegawai, password_login) values ('".$nik."','".$nmu."','".$psb."')");
			if($in){
				echo $nik;
			}else{
				echo 0;
			}
		}else{
			echo 0;
		}
		
	}
	
	function account_pembayaran(){
		$data['acpem'] = $this->db->query("select * from account_pembayaran_tbl")->result();
		$this->load->view('config/account_pembayaran',$data);
	}
	
	function edit_pembayaran($id){
		$Q = "select * from account_pembayaran_tbl where id_acc_pembayaran = '".$id."'";
		$data = $this->db->query($Q)->row();
		echo json_encode($data);
	}
	
	function delete_pembayaran($id){
		$isdel = $this->checking_usedid($id);
		if($isdel){
			$this->db->query("delete from account_pembayaran_tbl where id_acc_pembayaran = '".$id."'"); #table account_pembayaran_tbl
			$this->db->query("delete from detail_acc_pemb_tbl where id_acc_pembayaran  = '".$id."'");	#table detail_acc_pemb_tbl
			echo $isdel;
		}else{
			echo $isdel;
		}
	}
	
	function checking_usedid($id){
		#is id used in table pembayaran_siswa_tbl  
		$a = $this->db->query("select id_acc_pembayaran from pembayaran_siswa_tbl where id_acc_pembayaran = '".$id."'")->result();
		if($a){
			return false;	#data tidak bisa di hapus
		}else{
			return true;	#data bisa di hapus
		}
	}
	
	function tambah_pembayaran(){
		$uid = $this->input->post('uid');
		$nap = $this->input->post('nap');
		$jep = $this->input->post('jep');
		$bip = str_replace(",","",$this->input->post('bip'));
		$ket = $this->input->post('ket');
		$red = $this->input->post('red');
		
		if($uid){
		/**
			UPDATE
		**/ 
			$usedid = $this->checking_usedid($uid);
			if($usedid){
				#can update
				
				#update table account_pembayaran
				$this->db->query("update account_pembayaran_tbl 
									set nama_acc_pembayaran = '".$nap."',
										jenis_pembayaran = '".$jep."',
										harga_pembayaran = '".$bip."',
										keterangan = '".$ket."',
										cost_reduction = '".$red."' where id_acc_pembayaran = '".$uid."'");
				
				#delete
				$this->db->query("delete from detail_acc_pemb_tbl where id_acc_pembayaran = '".$uid."'");
				
				#update table account_pembayaran
				$this->period_date($jep,$uid);
				echo $usedid;
			}else{						
				#canot update
				echo $usedid;
			}
		
		
		}else{
		/**
			SAVE
		**/
			/* into account_pembayaran_tbl */
			$Q = "insert into account_pembayaran_tbl 
					(nama_acc_pembayaran, jenis_pembayaran, harga_pembayaran, keterangan, cost_reduction) values 
					('".$nap."','".$jep."','".$bip."','".$ket."','".$red."')";
			$this->db->query($Q);
			
			/* get id afret save */
			$G = $this->db->query("select id_acc_pembayaran from account_pembayaran_tbl order by id_acc_pembayaran desc limit 1")->row()->id_acc_pembayaran;
			
			$this->period_date($jep,$G);
			echo 1;
		}
		
	}
	
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
		echo $saldo;
	}
	
	function period_date($jep,$G){
		/* get periode akademik */
		$P = $this->db->query("select periode_awal, periode_akhir from periode_akademik_tbl where is_periode = 1")->result();

		/* into detail_acc_pemb_tbl */		
		$date1 = new DateTime($P[0]->periode_awal);
		$date2 = new DateTime($P[0]->periode_akhir);

		/* Biaya Tetap Perbulan */
		if($jep == "pb"){	
			while($date1 <= $date2){
				$M = $date1->format('Y-m-d');
				$this->db->query("insert into detail_acc_pemb_tbl (buying_date, id_acc_pembayaran) values ('".$M."','".$G."')");
				$date1->modify("+1 month");
			}

		/* Biaya Tetap Persemester */
		}else if($jep == "ps"){
			while($date1 <= $date2){
				$M = $date1->format('Y-m-d');
				$this->db->query("insert into detail_acc_pemb_tbl (buying_date, id_acc_pembayaran) values ('".$M."','".$G."')");
				$date1->modify("+6 month");
			}
		/* Biaya Tetap Pertahun */
		}else if($jep == "pt"){		
			$M = $date1->format('Y-m-d');
			$this->db->query("insert into detail_acc_pemb_tbl (buying_date, id_acc_pembayaran) values ('".$M."','".$G."')");

		/* Biaya Tidak Tetap */
		}else{
			$M = $date1->format('Y-m-d');
			$this->db->query("insert into detail_acc_pemb_tbl (buying_date, id_acc_pembayaran) values ('".$M."','".$G."')");
		}
	}
	
	function tahun_akademik(){
		$data['ta_akademik'] = $this->db->query("select * from periode_akademik_tbl order by id_periode_akademik desc")->result();
		$this->load->view('config/tahun_akademik',$data);
	}
	
	function add_tahunakademik(){
		$ta_awal  = $this->input->post('ta_awal');
		$ta_akhir = $this->input->post('ta_akhir');
		$this->db->query("insert into periode_akademik_tbl (periode_awal,periode_akhir) value ('".$ta_awal."','".$ta_akhir."')");
	}
	
	function set_aktif_tahunakademik($id){
		#first set zero all
		$this->db->query("update periode_akademik_tbl set is_periode = 0");
		$up = $this->db->query("update periode_akademik_tbl set is_periode = 1 where id_periode_akademik = '".$id."'");
		if($up){
			echo 1;
		}
	}
}
