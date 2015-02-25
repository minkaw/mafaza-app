<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Password extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('login');
		}
	}

	function index(){
		$this->load->view('change_password/password');
	}
	
	function check_password(){
		$L = $this->input->post('L');
		$B = $this->input->post('B');
		$U = $this->input->post('U');
		
		if($B==$U){
			$nik = $this->session->userdata('nik_pegawai');
			$c = $this->db->query("select password_login from pegawai_tbl where nik_pegawai = '".$nik."'")->row()->password_login;
			if($c == md5(sha1($L))){
				$this->db->query("update pegawai_tbl set password_login = '".md5(sha1($U))."'");
				echo "success";
			}else{
				echo 4; //code for old password not match
			}
		}else{
			echo 5; //code for new password not match 
		}
		return;
	}
	
}