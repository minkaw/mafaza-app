<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan extends CI_Controller {
	
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
		$this->load->view('keuangan/accountpos',$data);
	}
	
	function post_budgeting(){
		$sd  = db_date($this->input->post('sd'));
		$nm  = $this->input->post('nm');
		$idp = $this->input->post('idp');
		$bgt = $this->input->post('bgt');
		$fl  = rand(111111,999999);
		$a = sizeof($idp);
		$b = sizeof($bgt);
		
		/* periode tahun akademik */
		$periode = @$this->db->query("SELECT periode_awal, periode_akhir FROM periode_akademik_tbl WHERE is_periode = 1")->row();
		$p = $periode->periode_awal.$periode->periode_akhir;
		
		if($a===$b){
			$this->db->query("insert into budget_tbl (`nama_budget`,`start_date`,`flager`, `periode`) values ('".$nm."','".$sd."','".$fl."','".$p."')");
			$id = $this->db->query("select id_budget from budget_tbl where flager = '".$fl."'")->row()->id_budget;
			for($m=0; $m<$a; $m++){
				$this->db->query("insert into alokasi_budget_tbl 
									(id_acc_pembayaran, id_budget, budget) 
										values 
											('".$idp[$m]."','".$id."','".$bgt[$m]."')");
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
					budget_tbl.`start_date`
					from alokasi_budget_tbl inner join account_pembayaran_tbl
					on alokasi_budget_tbl.`id_acc_pembayaran` = account_pembayaran_tbl.`id_acc_pembayaran`
					inner join budget_tbl on budget_tbl.`id_budget` = alokasi_budget_tbl.`id_budget` 
					where alokasi_budget_tbl.`id_budget` = '".$id."'";
		$data['detail']  = $this->db->query($Q)->result();
		$data['account'] = $name;
		$this->load->view('keuangan/detail_pos_budget',$data);
	}
	
	function transaksi(){
		$this->load->view('keuangan/transaksi');
	}
	
	function data_budget(){
		$tmp = '';
        $data = $this->db->query("SELECT * FROM budget_tbl")->result();
        if (!empty($data)) {
            $tmp .= "<option value=''>-- Pilih Budget --</option>";
            foreach ($data as $row) {
                $tmp .= "<option value='".$row->id_budget."'>".$row->nama_budget."</option>";
            }
        } else {
            $tmp .= "<option value=''>-- Pilih Budget --</option>";
        }
        die($tmp);
	}
	
	function data_koperasi(){
		$tmp = '';
        $data = $this->db->query("SELECT * FROM koperasi_tbl")->result();
        if (!empty($data)) {
            $tmp .= "<option value=''>-- Pilih Item --</option>";
            foreach ($data as $row) {
                $tmp .= "<option value='".$row->id_item."'>".$row->nama_item."</option>";
            }
        } else {
            $tmp .= "<option value=''>-- Pilih Item --</option>";
        }
        die($tmp);
	}
	
	function save_transakasi(){
		$id_budget = $this->input->post('id_budget');
		$nama_tran = $this->input->post('nama_tran');
		$biaya 	   = str_replace(",","",$this->input->post('biaya'));
		$tgl 	   = date('Y-m-d H:i:s');
		
		//check periode tahun akademik for budget
		$p = $this->db->query("SELECT periode_awal, periode_akhir FROM periode_akademik_tbl WHERE is_periode = 1")->row();
		$b = $this->db->query("SELECT periode FROM budget_tbl WHERE id_budget = '".$id_budget."'")->row();
		
		if($b->periode == $p->periode_awal.$p->periode_akhir){
			$this->db->query("insert into transaksi_tbl 
							(nama_transaksi, biaya, tgl_transaksi, id_budget) 
							values 
							('".$nama_tran."','".$biaya."','".$tgl."','".$id_budget."')");
			echo "success";
		}else{
			echo "Budget tidak bisa di gunakan";
		}
		exit;
		
		
	}
	
	function get_transakasi_budget($id){
		$data = $this->db->query("select * from transaksi_tbl where id_budget = '".$id."' order by id_transaksi desc limit 15")->result();
		if($data){
			foreach($data as $r){
				$r->id_trans    = $r->id_transaksi;
				$r->nama_trans  = $r->nama_transaksi;
				$r->biaya_trans = $r->biaya;
				$r->tgl_trans   = indo_date($r->tgl_transaksi);
				$row_set[] 	    = $r;
			}
			echo json_encode($row_set);
		}else{
			echo json_encode(0);
		}
	}
	
	function get_budget($id){
		$data = $this->db->query("select sum(budget) as budget from alokasi_budget_tbl where id_budget = '".$id."'")->row();		
		echo json_encode($data);
		
	}
	
	function save_penjualan($id,$jml){
		$date = date('Y-m-d H:i:s');
		$data = $this->db->query("insert into penjualan_tbl (id_item, jumlah, tgl_terjual) values ('".$id."','".$jml."','".$date."')");
		if($data){
			echo 1;
		}
	}
	
	function get_koperasi($id){
		$data = $this->db->query("SELECT 
									SUM(penjualan_tbl.`jumlah`) AS jml_terjual,
									koperasi_tbl.`harga` 
									FROM koperasi_tbl LEFT JOIN penjualan_tbl
									ON koperasi_tbl.`id_item` = penjualan_tbl.`id_item`
									WHERE koperasi_tbl.`id_item` = '".$id."'")->row();
		echo json_encode($data);
	}
	
	function get_transakasi_koperasi($id){
		$data = $this->db->query("SELECT 
									koperasi_tbl.`nama_item`,
									koperasi_tbl.`satuan`,
									koperasi_tbl.`harga`,
									penjualan_tbl.`jumlah`,
									penjualan_tbl.`tgl_terjual`
									FROM koperasi_tbl LEFT JOIN penjualan_tbl
									ON koperasi_tbl.`id_item` = penjualan_tbl.`id_item`
									WHERE koperasi_tbl.`id_item` = '".$id."'
									ORDER BY penjualan_tbl.`id_penjualan` DESC 
									LIMIT 15")->result();
		foreach($data as $r){
				$r->nama_item   = $r->nama_item;
				$r->satuan  	= $r->satuan;
				$r->harga 		= $r->harga;
				$r->jumlah   	= $r->jumlah;
				$r->tgl_terjual	= $r->tgl_terjual;
				$row_set[] 	    = $r;
		}
		echo json_encode($row_set);
	}
	
	function koperasi(){
		$data['items'] = $this->db->query("select * from koperasi_tbl")->result();
		$this->load->view('keuangan/koperasi',$data);
	}
	
	function tambah_items_koperasi(){
		$this->load->view('keuangan/add_items_koperasi');
	}
	
	function save_itemkoperasi(){
		$n = $this->input->post('n');
		$s = $this->input->post('s');
		$h = str_replace(",","",$this->input->post('h'));
		$this->db->query("insert into koperasi_tbl (nama_item, satuan, harga) values ('".$n."','".$s."','".$h."')");
		//echo $n."-spr-".$s."-spr-".$h;
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
		
		$this->load->view('keuangan/alltransaction',$data);
		
	}
	
	function reset_account_buget(){
		$idb = $this->input->post('idb');
		$idp = $this->input->post('idp');
		$sb  = $this->input->post('sb');
		$per = $this->input->post('per');
		$tgl = date('Y-m-d');
		
		/* calculating */
		$jum = sizeof($idp);
		$cur = $sb/$jum;
		
		//var_dump(sizeof($idp)."--".$idp[0]."--".$idp[1]);
		//exit;
		
		#kembalikan jika ada sisa saldo
		for($L=0; $L<=$jum-1; $L++){
			$Q = "insert into 
							budget_relokasi_tbl 
							(id_budget, id_acc_pembayaran, periode_akademik, saldo, tgl_relokasi) values 
							('".$idb."','".$idp[$L]."','".$per."','".$cur."','".$tgl."') ";
		
			$this->db->query($Q);
		}		
		
		#non aktifkan account
		$this->db->query("update budget_tbl set is_aktif = 0 where id_budget = '".$idb."'");
		$this->db->query("update alokasi_budget_tbl set is_aktif = 0 where id_budget = '".$idb."'");
	}
	
	function relokasi_account_buget(){
		$idb = $this->input->post('idb');
		$idp = $this->input->post('idp');
		$ida = $this->input->post('ida');
		$bgt = $this->input->post('bgt');
		$sb  = $this->input->post('sb');
		$per = $this->input->post('per');
		
		//var_dump($bgt);exit;
		
		/* calculating */
		$jum = sizeof($idp);
		$cur = $sb/$jum;
		
		#kembalikan jika ada sisa saldo
		for($L=0; $L<=$jum-1; $L++){
			$Q = "insert into 
							budget_relokasi_tbl 
							(id_budget, id_acc_pembayaran, periode_akademik, saldo, tgl_relokasi) values 
							('".$idb."','".$idp[$L]."','".$per."','".$cur."','".$tgl."') ";
		
			$this->db->query($Q);
		}
		
		$a = sizeof($idp);
		$b = sizeof($bgt);
		for($m=0; $m<sizeof($idp); $m++){
			$Q = "update alokasi_budget_tbl
											set budget = '".$bgt[$m]."'
											where id_alokasi = '".$ida[$m]."'";
			$this->db->query($Q);
		}
		
	}
}