<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Koperasi extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('login');
		}
	}
	
	
	function index(){
		$data['items'] = $this->db->query("select * from koperasi_tbl")->result();
		$this->load->view('koperasi/koperasi',$data);
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
		
		$this->db->query("insert into transaksi_tbl 
							(nama_transaksi, biaya, tgl_transaksi, id_budget) 
							values 
							('".$nama_tran."','".$biaya."','".$tgl."','".$id_budget."')");
		
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
	
	function tambah_items_koperasi(){
		$this->load->view('koperasi/add_items_koperasi');
	}
	
	function save_itemkoperasi(){
		$n = $this->input->post('n');
		$s = $this->input->post('s');
		$h = str_replace(",","",$this->input->post('h'));
		$this->db->query("insert into koperasi_tbl (nama_item, satuan, harga) values ('".$n."','".$s."','".$h."')");
		//echo $n."-spr-".$s."-spr-".$h;
	}
	
	function edit_item($id){
		$data['item'] = $this->db->query("select * from koperasi_tbl where id_item = '".$id."'")->row();
		$this->load->view('koperasi/edit_items',$data);
	}
	
	function update_itemkoperasi(){
		$i = $this->input->post('i');
		$n = $this->input->post('n');
		$s = $this->input->post('s');
		$h = str_replace(",","",$this->input->post('h'));
		$u = $this->db->query("update koperasi_tbl set nama_item = '".$n."', satuan = '".$s."', harga = '".$h."' where id_item = '".$i."'");
		return $u;
	}
	
	function delete_item($id){
		$this->db->query("delete from koperasi_tbl where id_item = '".$id."'");
		redirect('koperasi');
	}
}