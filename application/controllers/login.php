<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	function __construct(){
		parent::__construct();
		if($this->session->userdata('logged_in')){
			redirect('home');
		}
	}
	
	function index(){
		$this->load->view('login/login');
	}
	
	function auth_false(){
		$data['auth_false'] = "NIK atau password anda salah !!!";
		$this->load->view('login/login',$data);
	}
	
	function check_login(){
		$a = $this->input->post('nik');
		$b = md5(sha1($this->input->post('password')));
		$c = $this->db->query("select * from pegawai_tbl where nik_pegawai = '".$a."' and password_login = '".$b."'")->result();
		if($c){
			$newdata = array(
						'nik_pegawai'	=> $c[0]->nik_pegawai,
						'nama_pegawai'  => $c[0]->nama_pegawai,
						'logged_in' => TRUE
						);

			$this->session->set_userdata($newdata);
			redirect('home');
		}else{
			redirect('login/auth_false');
		}
		
	}
}