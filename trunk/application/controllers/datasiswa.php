<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Datasiswa extends CI_Controller {
	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('logged_in')){
			redirect('login');
		}
	}
	
	function index(){
		
		$data['data_siswa'] = $this->db->query("SELECT 
														nis_siswa,
														nama_siswa,
														jenis_kelamin,
														alamat,
														telp_orangtua,
														telp_wali FROM siswa_tbl order by id_siswa desc")->result();
		
		$data['Myselect'] = "ALL";
		$data['Mychoice'] = "undefined";
		$this->load->view('datasiswa/datasiswa',$data);
	}
	
	function filter_class($a,$b){
		if($a=="all"){
			$WHERE = "";
		}else{
			$WHERE = " WHERE kelas_tbl.nama_kelas = '".$a."A' ";	//1A 2A etc
		}
		
		if($b=="undefined"){
		   $WHERE.= " OR kelas_tbl.nama_kelas = '".$a."B'";
		}else{
			if($a=="all"){
				$WHERE = " WHERE kelas_tbl.nama_kelas LIKE '%".$b."'";
			}else{
				$WHERE = " WHERE kelas_tbl.nama_kelas = '".$a.$b."'";
			}
		   
		}
		
		$data['data_siswa'] = $this->db->query("SELECT 
														siswa_tbl.nis_siswa,
														siswa_tbl.nama_siswa,
														siswa_tbl.jenis_kelamin,
														siswa_tbl.alamat,
														siswa_tbl.telp_orangtua,
														siswa_tbl.telp_wali  FROM siswa_tbl inner join kelas_tbl
														on siswa_tbl.`id_kelas` = kelas_tbl.`id_kelas` ".$WHERE."")->result();
		
		$data['Myselect'] = $a;
		$data['Mychoice'] = $b;
		$this->load->view('datasiswa/datasiswa',$data);
	}
	
	function get_biayapembayaran($id){

		$harga = $this->db->query("SELECT harga_pembayaran FROM account_pembayaran_tbl WHERE id_acc_pembayaran = '".$id."'")->row()->harga_pembayaran;
		echo $harga;
	}
	
	function post_searching($nis){
		$nis = urldecode($nis);
		$data = $this->db->query("SELECT nis_siswa,
													nama_siswa,
													jenis_kelamin,
													alamat,
													telp_orangtua,
													telp_wali FROM siswa_tbl WHERE nis_siswa = '".$nis."'")->row();
		echo json_encode($data);
	}
		
	function simpandata(){
		$nis = $this->input->post('nis'); 
		$img = $this->input->post('img'); 
		$kls = $this->input->post('kls');
		$ns  = $this->input->post('nama_siswa');
		$nk  = $this->input->post('nama_kecil');
		$gs  = $this->input->post('gender_siswa'); 
		$as  = $this->input->post('agama_siswa'); 
		$ls  = $this->input->post('lahir_siswa'); 
		$tls = db_date($this->input->post('tanggal_lahir_siswa')); 
		$ank = $this->input->post('anakke'); 
		$jms = $this->input->post('jml_sdr');
		$alt = $this->input->post('alamat'); 
		$pro = $this->input->post('provinsi');
		$kot = $this->input->post('kota'); 
		$kec = $this->input->post('kecamatan');
		$kel = $this->input->post('kelurahan');
		$na  = $this->input->post('nama_ayah');
		$la  = $this->input->post('lahir_ayah'); 
		$tla = db_date($this->input->post('tanggal_lahir_ayah')); 
		$ka  = $this->input->post('kerja_ayah'); 
		$pa  = $this->input->post('pendidikan_ayah'); 
		$ni  = $this->input->post('nama_ibu'); 
		$li  = $this->input->post('lahir_ibu'); 
		$tli = db_date($this->input->post('tanggal_lahir_ibu')); 
		$ki  = $this->input->post('kerja_ibu'); 
		$pi  = $this->input->post('pendidikan_ibu'); 
		$to1 = $this->input->post('telp_orangtua1'); 
		$to2 = $this->input->post('telp_orangtua2');
		$tny = $this->input->post('tnny');
		$nw  = $this->input->post('nama_wali'); 
		$lw  = $this->input->post('lahir_wali'); 
		$tlw = db_date($this->input->post('tanggal_lahir_wali')); 
		$kw  = $this->input->post('kerja_wali'); 
		$pw  = $this->input->post('pendidikan_wali'); 
		$tw1 = $this->input->post('telp_wali1'); 
		$tw2 = $this->input->post('telp_wali2');
		if($nis){
			$Q = "update siswa_tbl set 
					  `id_kelas`    = '".$kls."', `nama_siswa`= '".$ns."' , `nama_kecil`= '".$nk."' ,`agama` = '".$as."' ,
					  `foto_siswa`  = '".$img."' ,`jenis_kelamin`  = '".$gs."' ,  
					  `lahir_siswa` = '".$ls."' , `tanggal_lahir_siswa` = '".$tls."', `alamat` = '".$alt."' ,`propinsi` = '".$pro."',`kota` = '".$kot."',`kecamatan` = '".$kec."' ,`kelurahan` = '".$kel."' ,
					  `urutan_anak` = '".$ank."', `jumlah_saudara` = '".$jms."',
					  `nama_ayah` 	= '".$na."' , `lahir_ayah` = '".$la."' , `tanggal_lahir_ayah` = '".$tla."' , `pekerjaan_ayah` = '".$ka."' , `pendidikan_ayah` = '".$pa."' ,
					  `nama_ibu`  	= '".$ni."' , `lahir_ibu`  = '".$li."' , `tanggal_lahir_ibu`  = '".$tli."' , `pekerjaan_ibu`  = '".$ki."' , `pendidikan_ibu`  = '".$pi."' , `telp_orangtua` = '".$to1."-spr-".$to2."',
					  `tny` = '".$tny."' , `nama_wali` 	= '".$nw."' , `lahir_wali` = '".$lw."' , `tanggal_lahir_wali` = '".$tlw."' , `pekerjaan_wali` = '".$kw."' , `pendidikan_wali` = '".$pw."' , `telp_wali` 	 = '".$tw1."-spr-".$tw2."'
					   where nis_siswa = '".$nis."'";
			//var_dump($Q);exit;
			$this->db->query($Q);
			echo $nis;
		}else{
			$e = @$this->db->query("select periode_awal, periode_akhir from periode_akademik_tbl where is_periode = '1'")->row();
			//$f = explode("-",$e[0]->periode_awal);
			//$g = explode("-",$e[0]->periode_akhir);
			//$prefix = substr($f[0],2).substr($g[0],2);
			$prefix = substr($e->periode_awal,2).substr($e->periode_akhir,2);
			
			$h  = $this->db->query("select nis_siswa from siswa_tbl order by id_siswa desc limit 1")->row();
	
			if($h){
				$i 		= substr($h->nis_siswa,4);
				$j = $i + 1;
				if($j<=9){
					$suffix = "00".$j;
				}else if($j>9 and $j<=99){
					$suffix = "0".$j;
				}else{
					$suffix = $j;
				}
			}else{
				$suffix = "001";
			}
			$nis = $prefix.$suffix;
			
			$Q = "insert  into `siswa_tbl`
					( `nis_siswa`,`id_kelas`,`nama_siswa` , `nama_kecil` , `agama` ,`foto_siswa`,`jenis_kelamin`,`lahir_siswa`,`tanggal_lahir_siswa`,`alamat`,`propinsi`,`kota`,`kecamatan`,`kelurahan`,`urutan_anak`,`jumlah_saudara`,
					  `nama_ayah`,`lahir_ayah`,`tanggal_lahir_ayah`,`pekerjaan_ayah`,`pendidikan_ayah`,
					  `nama_ibu`,`lahir_ibu`,`tanggal_lahir_ibu`,`pekerjaan_ibu`,`pendidikan_ibu`,`telp_orangtua`,
					  `nama_wali`,`lahir_wali`,`tanggal_lahir_wali`,`pekerjaan_wali`,`pendidikan_wali`,`telp_wali`) values 
					( '".$nis."','".$kls."','".$ns."' ,'".$nk."' ,'".$as."' ,'".$img."','".$gs."','".$ls."','".$tls."','".$alt."','".$pro."','".$kot."','".$kec."','".$kel."','".$ank."','".$jms."',
					  '".$na."','".$la."','".$tla."','".$ka."','".$pa."',
					  '".$ni."','".$li."','".$tli."','".$ki."','".$pi."','".$to1."-spr-".$to2."',
					  '".$nw."','".$lw."','".$tlw."','".$kw."','".$pw."','".$tw1."-spr-".$tw2."')";
			$this->db->query($Q);
			echo $nis;
		}
	}
	
	function create_nis_sample(){
		$prefix = "1617";
		$h = "1617100";
		if($h){
			$i 		= substr($h,4);
			$j = $i + 1;
			if($j<=9){
				$suffix = "00".$j;
			}else if($j>9 and $j<=99){
				$suffix = "0".$j;
			}else{
				$suffix = $j;
			}
		}else{
			$suffix = "001";
		}
		
		echo $prefix.$suffix;
	}
	
	function import_datasiswa(){
		$this->load->view('datasiswa/import_datasiswa');
	}
	
	function importing_data(){
		echo $filename=$_FILES["file"]["tmp_name"];
		if($_FILES["file"]["size"] > 0)
		{
			$file = fopen($filename, "r");
			$count = 0;                                        
			while (($data = fgetcsv($file, 10000, ",")) !== FALSE)
			{
				$count++;                                      
				if($count>1){                                  
				  $sql = "INSERT into siswa_tbl 
						(nis_siswa, id_kelas, nama_siswa, nama_kecil, agama, jenis_kelamin, lahir_siswa, tanggal_lahir_siswa, alamat, nama_ayah, nama_ibu, telp_orangtua) 
						values 
						('".$data[0]."','".str_replace("'","\'",$data[1])."','".$data[2]."','".$data[3]."','".$data[4]."','".$data[5]."','".$data[6]."','".$data[7]."','".$data[8]."','".$data[9]."','".$data[10]."','".$data[11]."')";
						$this->db->query($sql);
				}                                              
			}

			fclose($file);
			redirect('datasiswa');
		}
		else
			echo 'Invalid File:Please Upload CSV File';
	}
	
	function post_biayareduksi($idred, $idp, $nis, $br, $chk){
		if($idred==0){
			#insert biaya_reduksi_tbl
			$flag = rand(111111,999999);
			$Q  = $this->db->query("insert into biaya_reduksi_tbl (nis_siswa,flag) values ('".$nis."','".$flag."')");
			$id = $this->db->query("select id_biaya_reduksi from biaya_reduksi_tbl where flag = '".$flag."'")->row()->id_biaya_reduksi;
			
			for($n=1; $n<=sizeof($chk); $n++){
				$R  = $this->db->query("insert into 
													alokasi_biaya_reduksi_tbl 
													(id_biaya_reduksi, id_acc_pembayaran, id_acc_bgt, biaya_reduksi) 
													values 
													('".$id."','".$idp."','".$chk."','".$br."')");
			}
			
		}else{
			#update
			$Q = $this->db->query("update alokasi_biaya_reduksi_tbl set 
																id_acc_pembayaran = '".$idp."', 
																id_acc_bgt		  = '".$chk."',
																biaya_reduksi	  = '".$br."'
																where id_biaya_reduksi = '".$idred."'");
		}
		echo $Q;
	}
	
	function remove_biayareduksi($idp, $nis){
		$c = $this->db->query("select 
									id_pembayaran_siswa 
									from pembayaran_siswa_tbl 
									where id_acc_pembayaran = '".$idp."' AND nis_siswa = '".$nis."'")->result();
		if($c){
			echo 0;
		}else{
			$id = $this->db->query("SELECT 
											biaya_reduksi_tbl.`id_biaya_reduksi` 
											FROM biaya_reduksi_tbl 
											INNER JOIN alokasi_biaya_reduksi_tbl 
											ON biaya_reduksi_tbl.`id_biaya_reduksi` = alokasi_biaya_reduksi_tbl.`id_biaya_reduksi`
											WHERE alokasi_biaya_reduksi_tbl.`id_acc_pembayaran` = '".$idp."' 
											AND biaya_reduksi_tbl.`nis_siswa`= '".$nis."'")->row()->id_biaya_reduksi;
			$d = $this->db->query("delete 
										biaya_reduksi_tbl, 
										alokasi_biaya_reduksi_tbl 
										from biaya_reduksi_tbl 
										inner join alokasi_biaya_reduksi_tbl
										on biaya_reduksi_tbl.`id_biaya_reduksi` = alokasi_biaya_reduksi_tbl.`id_biaya_reduksi`
										where biaya_reduksi_tbl.`id_biaya_reduksi` = '".$id."'");
			if($d){
				echo 1;
			}
		}
	}
	
	function edit_siswa($nis){
		$nis = urldecode($nis);
		
		$data['result'] = $this->db->query("select * from siswa_tbl where nis_siswa = '".$nis."'")->row();
		$data['reduce'] = $this->db->query("SELECT 
											alokasi_biaya_reduksi_tbl.`id_acc_pembayaran`,
											alokasi_biaya_reduksi_tbl.`biaya_reduksi`,
											account_pembayaran_tbl.`harga_pembayaran`,
											account_pembayaran_tbl.`nama_acc_pembayaran`
											FROM alokasi_biaya_reduksi_tbl
												INNER JOIN account_pembayaran_tbl ON alokasi_biaya_reduksi_tbl.`id_acc_pembayaran` = account_pembayaran_tbl.`id_acc_pembayaran`
												INNER JOIN biaya_reduksi_tbl ON biaya_reduksi_tbl.`id_biaya_reduksi` = alokasi_biaya_reduksi_tbl.`id_biaya_reduksi`
												WHERE biaya_reduksi_tbl.`nis_siswa` = '".$nis."'")->result();
		$this->load->view('datasiswa/edit_siswa',$data);
	}
	
	function view_siswa($nis){
		$nis = urldecode($nis);
		$data['result'] = $this->db->query("select * from siswa_tbl where nis_siswa = '".$nis."'")->row();
		$data['reduce'] = $this->db->query("SELECT 
											alokasi_biaya_reduksi_tbl.`id_acc_pembayaran`,
											alokasi_biaya_reduksi_tbl.`biaya_reduksi`,
											account_pembayaran_tbl.`harga_pembayaran`,
											account_pembayaran_tbl.`nama_acc_pembayaran`
											FROM alokasi_biaya_reduksi_tbl
												INNER JOIN account_pembayaran_tbl ON alokasi_biaya_reduksi_tbl.`id_acc_pembayaran` = account_pembayaran_tbl.`id_acc_pembayaran`
												INNER JOIN biaya_reduksi_tbl ON biaya_reduksi_tbl.`id_biaya_reduksi` = alokasi_biaya_reduksi_tbl.`id_biaya_reduksi`
												WHERE biaya_reduksi_tbl.`nis_siswa` = '".$nis."'")->result();
		$this->load->view('datasiswa/view_siswa',$data);
	}
	
	function dele_siswa($nis){
		$nis = urldecode($nis);
		$Q = $this->db->query("select id_pembayaran_siswa from pembayaran_siswa_tbl where nis_siswa = '".$nis."'")->row();
		if($Q){
			echo 1;
		}else{
			$this->db->query("delete from siswa_tbl where nis_siswa = '".$nis."'");
			$this->db->query("delete from biaya_reduksi_tbl where nis_siswa = '".$nis."'"); 	/* change trigger */
			echo 0;
		}
	}
	
	function edit_biayareduksi($idp,$nis){
		$c = $this->db->query("select 
									id_pembayaran_siswa 
									from pembayaran_siswa_tbl 
									where id_acc_pembayaran = '".$idp."' AND nis_siswa = '".$nis."'")->result();
		if($c){
			echo 1;
		}else{
			$data = $this->db->query("SELECT 	
										biaya_reduksi_tbl.`id_biaya_reduksi`,
										biaya_reduksi_tbl.`nis_siswa`,
										alokasi_biaya_reduksi_tbl.`id_acc_pembayaran`,
										alokasi_biaya_reduksi_tbl.`id_acc_bgt`,
										alokasi_biaya_reduksi_tbl.`biaya_reduksi`,
										account_pembayaran_tbl.`nama_acc_pembayaran`,
										account_pembayaran_tbl.`harga_pembayaran`,
										account_pembayaran_tbl.`harga_pembayaran` - alokasi_biaya_reduksi_tbl.`biaya_reduksi` AS beban_biaya 
										FROM 
											biaya_reduksi_tbl 
											INNER JOIN alokasi_biaya_reduksi_tbl ON biaya_reduksi_tbl.`id_biaya_reduksi` = alokasi_biaya_reduksi_tbl.`id_biaya_reduksi`
											INNER JOIN account_pembayaran_tbl ON alokasi_biaya_reduksi_tbl.`id_acc_pembayaran` = account_pembayaran_tbl.`id_acc_pembayaran`
											WHERE alokasi_biaya_reduksi_tbl.id_acc_pembayaran = '".$idp."' AND biaya_reduksi_tbl.nis_siswa = '".$nis."'")->row();
			echo json_encode($data);
		}
	}
	
	function button_view($nis){
		$view = "<a style='text-decoration:none' href='datasiswa/view_siswa/".$nis." class='default_popup'>
						<input type='button' class='btn-view button_view'>
					</a>";
		echo $view;
	}
	
	function auto_nis(){
		$term = $this->input->post('term');
		$Qry  = "SELECT nis_siswa, nama_siswa FROM siswa_tbl WHERE nis_siswa LIKE '".$term."%'
				OR nama_siswa LIKE '".$term."%' LIMIT 15";
		$Sql  = $this->db->query($Qry)->result();
		foreach ($Sql as $r){
			$r->value  = $r->nis_siswa." | ".$r->nama_siswa;
			$r->id	   = $r->nama_siswa;
			$row_set[] = $r; 		
		}
		echo json_encode($row_set); //data hasil query yang dikirim kembali dalam format json
	}
	
	function tambah_siswa(){		
		$this->load->view('datasiswa/add_siswa');
	}
	
	function unlink_images($images){
		unlink("./assets/ajaxupload/slider/".$images);
		unlink("./assets/ajaxupload/slider/thumb_".$images);
	}
	
	function upload_images(){
		$name=$_FILES['userfile']['name'];
		$ext = explode(".",$name);
		$rand=rand(1000000,9999999);						
		
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '10000';
		$config['max_width']  = '30240';
		$config['max_height']  = '30680';
		$config['file_name'] = date('mdyhis')."_".$rand.".".$ext[1];
		$config['upload_path'] = './assets/ajaxupload/slider/';
		
			
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());
			die("gagal");
		}
		else
		{			
			$data = array('upload_data' => $this->upload->data());
			$file_name =  $this->upload->data();
			$file_name = $file_name['file_name'];
			
			// Configuration Of Image Manipulation :: Static
			$this->load->library('image_lib') ;
			$img['image_library'] = 'GD2';
			$img['create_thumb']  = TRUE;
			$img['maintain_ratio']= TRUE;

			/// Limit Width Resize


			//// Making THUMBNAIL ///////
			$img['width']  = 100;
			$img['height'] = 110;

			// Configuration Of Image Manipulation :: Dynamic
			$img['quality']      = '100%' ;
			$img['source_image'] = "./assets/ajaxupload/slider/".$config['file_name']; //<-- //$source 
			$img['new_image']    = "./assets/ajaxupload/slider/".$config['file_name']; //<-- //$destination_thumb

			/* // Do Resizing
			$this->image_lib->initialize($img);
			$this->image_lib->resize();
			$this->image_lib->clear() ; */

			// Do Resizing
			$this->image_lib->initialize($img);
			$this->image_lib->resize();
			$this->image_lib->clear() ;
			/*end resize*/	
			die($file_name);			
		}
	}
	
	function get_kotamadya($id){
        $tmp = '';
        $data = $this->db->query("select * from kotamadya_tbl where id_propinsi = '".$id."'")->result();
        if (!empty($data)) {
            $tmp .= "<option value=''>-- Pilih Kota Madya --</option>";
            foreach ($data as $row) {
                $tmp .= "<option value='" . $row->id_kota . "'>" . $row->nama_kotamadya . "</option>";
            }
        } else {
            $tmp .= "<option value=''>-- Pilih Kota Madya --</option>";
        }
        die($tmp);
    }
	
	function get_kecamatan($id){
        $tmp = '';
        $data = $this->db->query("select * from kecamatan_tbl where id_kota = '".$id."'")->result();
        if (!empty($data)) {
            $tmp .= "<option value=''>-- Pilih Kecamatan --</option>";
            foreach ($data as $row) {
                $tmp .= "<option value='" . $row->id_kec . "'>" . $row->nama_kecamatan . "</option>";
            }
        } else {
            $tmp .= "<option value=''>-- Pilih Kecamatan --</option>";
        }
        die($tmp);
    }
	
	function get_kelurahan($id){
        $tmp = '';
        $data = $this->db->query("select * from kelurahan_tbl where id_kec = '".$id."'")->result();
        if (!empty($data)) {
            $tmp .= "<option value=''>-- Pilih Kelurahan --</option>";
            foreach ($data as $row) {
                $tmp .= "<option value='" . $row->id_kel . "'>" . $row->nama_kelurahan . "</option>";
            }
        } else {
            $tmp .= "<option value=''>-- Pilih Kelurahan --</option>";
        }
        die($tmp);
    }
	
}