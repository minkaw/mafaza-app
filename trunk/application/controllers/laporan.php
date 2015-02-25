<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Laporan extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('login');
		}
	}

	function index(){
		$data['budget'] = $this->db->query("SELECT * FROM budget_tbl")->result();
		$this->load->view('laporan/laporanbudget',$data);
	}
	
	function load_laporanbudget($bd,$sd,$ed){
		$data["bd"] = urldecode($bd);
		$data["sd"] = db_date(urldecode($sd));
		$data["ed"] = db_date(urldecode($ed));
		//echo $data["bd"].$data["sd"].$data["ed"];exit;
		$this->load->view('laporan/load_laporanbudget',$data);
	}
	
	function laporankoperasi(){
		$data['budget'] = $this->db->query("SELECT * FROM koperasi_tbl")->result();
		$this->load->view('laporan/laporankoperasi',$data);
	}
	
	function load_laporankoperasi($ket,$sd,$ed){
		$data["sd"]  = db_date(urldecode($sd));
		$data["ed"]  = db_date(urldecode($ed));
		$data["ket"] = $ket;
		$this->load->view('laporan/load_laporankoperasi',$data);
	}
	
	function laporan_iuran_siswa(){
		$this->load->view('laporan/laporan_iuran_siswa');
	}
	
	function load_laporan_iuran_siswa($iu,$sd,$ed){
		$data["iu"] = urldecode($iu);
		$data["sd"] = db_date(urldecode($sd));
		$data["ed"] = db_date(urldecode($ed));
		$this->load->view('laporan/load_laporan_iuran_siswa',$data);
	}
	
	function laporan_siklus_keuangan(){
		$this->load->view('laporan/siklus_keuangan');
	}
	
	function load_sikluskeuangan(){
		$this->load->view('laporan/load_sikluskeuangan');
	}
}