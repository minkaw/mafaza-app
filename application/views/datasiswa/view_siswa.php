<link rel="stylesheet" type="text/css" href="<?php echo site_url();?>assets/css/fotobox.css">
<!-- custome css -->
<style>
	table.custome_table tr td{
		text-align:left;
		font-family: "HelveticaNeue-Light","Helvetica Neue Light","Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;
		font-size:12px;
	}
	table.custome_table td,
	table.custome_table th{
		border:1px solid #efefef;
	}

	#ui-datepicker-div { font-size: 12px; } 
</style>
<!-- End -->
<script type="text/javascript" src="<?php echo site_url();?>assets/js/not-use/jquery-ui-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo site_url();?>assets/js/hello-tgl.js"></script>
<script type="text/javascript" src="<?php echo site_url();?>assets/ajaxupload/scripts/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo site_url();?>assets/js/datasiswa.js"></script>
<script type="text/javascript">
	$(document).keyup(function(e) {
		/* close by  ecs */
		if (e.keyCode == 27){
			if(confirm("Close ?")){
				$(".popup_close").trigger( "click" );
				window.location.href = "<?=site_url()?>datasiswa";
			}
		}
	});
		
	$(function(){
		<?php 
			if($result->foto_siswa){ 
				if(file_exists("assets/ajaxupload/slider/".$result->foto_siswa)){
		?>
					$("#preview1").html('<img src="<?php echo site_url();?>assets/ajaxupload/slider/<?=$result->foto_siswa;?>" height="110px" width="100px" />');
					$(".button-remove").show();
					$(".button-file").hide();
		<?php
				}
			} 
		?>
		
		
		$("#button_canc").click(function(){
			if(confirm("Close ?")){
				$(".popup_close").trigger( "click" );
				window.location.href = "<?=site_url()?>datasiswa";
			}
			return false;
		});
		/*
			default show-hide
		*/

		
	});
</script>
<link rel="stylesheet" type="text/css" href="<?php echo site_url();?>assets/css/not-use/jquery-ui-1.11.0.css" />
<div style="overflow:auto;height:635px">
<table style="width:580px;" class="custome_table">
	<!-- -----------
	-- form siswa --
	------------ -->
	<tr class="formsiswa">
		<td colspan="4" style="background-color:#dedede"><b>Data Siswa</b></td>
	</tr>
	<tr class="formsiswa">
		<td style="width:130px;">NIS</td>
		<td style="width:240px"><?=$result->nis_siswa;?></td>
		<td colspan=2 rowspan=6 style="width:130px">
			<div class="inner-box-foto">
				<div id='preview1'></div>
			</div>
		</td>
	</tr>
	<tr class="formsiswa">
		<td>Kelas</td>
		<td>
			<?php 
				$nama_kelas=@$this->db->query("select nama_kelas from kelas_tbl where id_kelas = '".$result->id_kelas."'")->row()->nama_kelas;
				echo $nama_kelas;
			?>
		</td>
	</tr>
	<tr class="formsiswa">
		<td>Nama Lengkap</td>
		<td><?=$result->nama_siswa;?></td>
	</tr>
	<tr class="formsiswa">
		<td>Nama Panggilan</td>
		<td><?=$result->nama_kecil;?></td>
	</tr>
	<tr class="formsiswa">
		<td>Agama</td>
		<td><?=$result->agama;?></td>
	</tr>
	<tr class="formsiswa">
		<td>Tempat Lahir</td>
		<td colspan=3><?=$result->lahir_siswa;?></td>
	</tr>
	<tr class="formsiswa">
		<td>Tanggal Lahir</td>
		<td colspan=3><?=indo_date($result->tanggal_lahir_siswa);?></td>
	</tr>
	<tr class="formsiswa">
		<td>Jenis Kelamin</td>
		<td colspan=3>
				<?php if($result->jenis_kelamin=="L"){ ?>
				Laki-Laki
				<?php } else { ?>
				Perempuan
				<?php } ?>
		</td>
	</tr>
	<tr class="formsiswa">
		<td>Anak Ke / dari</td>
		<td colspan=3><?=$result->urutan_anak;?>
			&nbsp;&nbsp; dari &nbsp;&nbsp;
			<?=$result->jumlah_saudara;?>
			Bersaudara
		</td>
	</tr>
	<tr class="formsiswa">
		<td>Alamat</td>
		<td colspan="4">
			<div>
				<?=$result->alamat?>
				&nbsp;&nbsp;
				<?=@$this->db->query("select nama_kecamatan from kecamatan_tbl where '".$result->kecamatan."'")->row()->nama_kecamatan?>
				&nbsp;-&nbsp;
				<?=@$this->db->query("select nama_kotamadya from kotamadya_tbl where id_kota = '".$result->kota."'")->row()->nama_kotamadya;?>
			</div>
		</td>
	</tr>
	<tr rowspan=2 class="formsiswa">
		
	</tr>
	
	<!-- ------------- 
	-- form ORTU -----
	-------------- -->
	<tr class="formortu">
		<td colspan="4" style="background-color:#dedede"><b>Orang Tua Laki-Laki</b></td>
	</tr>
	
	<!-- AYAH -->
	<tr class="formortu">
		<td>Nama Ayah</td>
		<td colspan="4"><?=$result->nama_ayah;?></td>
	</tr>

	<tr class="formortu">
		<td>Tempat Lahir</td>
		<td colspan="4"><?=$result->lahir_ayah;?></td>
	</tr>
	<tr class="formortu">
		<td>Tanggal Lahir</td>
		<td colspan="4"><?=indo_date($result->tanggal_lahir_ayah);?></td>
	</tr>
	<tr class="formortu">
		<td>Pekerjaan</td>
		<td colspan="4"><?=$result->pekerjaan_ayah;?></td>
	</tr>
	<tr class="formortu">
		<td style="width:120px">Pendidikan</td>
		<td colspan="4"><?=$result->pendidikan_ayah?></td>
	</tr>
	
	<tr class="formortu">
		<td colspan="4" style="background-color:#dedede"><b>Orang Tua Perempuan</b></td>
	</tr>
	
	<!-- IBU -->
	<tr class="formortu">
		<td>Nama Ibu</td>
		<td colspan="4"><?=$result->nama_ibu;?></td>
	</tr>
	<tr class="formortu">
		<td>Tempat Lahir</td>
		<td colspan="4"><?=$result->lahir_ibu;?></td>
	</tr>
	<tr class="formortu">
		<td>Tanggal Lahir</td>
		<td colspan="4"><?=indo_date($result->tanggal_lahir_ibu);?></td>
	</tr>
	<tr class="formortu">
		<td style="width:120px">Pekerjaan</td>
		<td colspan="4"><?=$result->pekerjaan_ibu;?></td>
	</tr>
	<tr class="formortu">
		<td style="width:120px">Pendidikan</td>
		<td colspan="4"><?=$result->pendidikan_ibu?></td>
	</tr>
	<tr class="formortu">
		<td>No. Telp / HP </td>
		<td colspan="4">
			<?php
				if($result->telp_orangtua){
					if(strpos($result->telp_orangtua,'-spr-') !== false){
						$telp 	 = explode("-spr-",$result->telp_orangtua);
						$no_telp1 = $telp[0];
						$no_telp2 = $telp[1];
					}else{
						$no_telp1 = $result->telp_orangtua;
						$no_telp2 = "";
					}
				}
			?>
			<?=$no_telp1?>"
			&nbsp; <b>/</b> &nbsp;
			<?=$no_telp2?>
		</td>
	</tr>
	
	<!-- ----------------------
	-- form Total Biaya --
	----------------------- -->
	
	<tr class="formbiaya">
		<td colspan="4" style="background-color:#dedede"><b>Keterangan Beban Biaya Siswa</b></td>
	</tr>
	<tr>
		<td>Account Pembayaran</td>
		<td>Biaya Pembayaran</td>
		<td>Keringanan</td>
		<td>Beban Biaya</td>
	</tr>
	<!-- SPP Area -->
	<tr>
		<td>SPP Siswa</td>
		<td>
			<?php
				$spp = @$this->db->query("select id_acc_pembayaran, harga_pembayaran from account_pembayaran_tbl where nama_acc_pembayaran like '%Kelas ".substr($nama_kelas,0,1)."'")->result();
				echo number_format($spp[0]->harga_pembayaran);
			?>
		</td>
		<td>
			<?php 
			$reduc = @$this->db->query("SELECT alokasi_biaya_reduksi_tbl.`biaya_reduksi`
											FROM alokasi_biaya_reduksi_tbl 
											INNER JOIN biaya_reduksi_tbl ON alokasi_biaya_reduksi_tbl.`id_biaya_reduksi` = biaya_reduksi_tbl.`id_biaya_reduksi`
											WHERE biaya_reduksi_tbl.`nis_siswa` = '".$result->nis_siswa."' 
											AND alokasi_biaya_reduksi_tbl.`id_acc_pembayaran` = '".$spp[0]->id_acc_pembayaran."'")->row();
			if($reduc){
				echo number_format($reduc->biaya_reduksi);
			}else{
				$reduc = 0;
				echo 0;
			}
		?>
		</td>
		<td style="text-align:right">
			<?php
				$bbspp = $spp[0]->harga_pembayaran - $reduc;
				echo number_format($bbspp);
			?>
		</td>
	</tr>
	
	<!-- Uang Pangkal Area -->
	<tr>
		<td>Uang Pangkal</td>
		<td>
			<?php
				$up = @$this->db->query("select id_acc_pembayaran, harga_pembayaran from account_pembayaran_tbl where nama_acc_pembayaran like '%Pangkal%'")->result();
				echo number_format($up[0]->harga_pembayaran);
			?>
		</td>
		<td>
			<?php 
			$reduc = @$this->db->query("SELECT alokasi_biaya_reduksi_tbl.`biaya_reduksi`
											FROM alokasi_biaya_reduksi_tbl 
											INNER JOIN biaya_reduksi_tbl ON alokasi_biaya_reduksi_tbl.`id_biaya_reduksi` = biaya_reduksi_tbl.`id_biaya_reduksi`
											WHERE biaya_reduksi_tbl.`nis_siswa` = '".$result->nis_siswa."' 
											AND alokasi_biaya_reduksi_tbl.`id_acc_pembayaran` = '".$up[0]->id_acc_pembayaran."'")->row();
			if($reduc){
				echo number_format($reduc->biaya_reduksi);
			}else{
				$reduc = 0;
				echo 0;
			}
		?>
		</td>
		<td style="text-align:right">
			<?php
				$bbup = $up[0]->harga_pembayaran - $reduc;
				echo number_format($bbup);
			?>
		</td>
	</tr>
	
	<!-- Sarana & Prasarana Area -->
	<tr>
		<td>Sarana & Prasarana</td>
		<td>
			<?php
				$sp = @$this->db->query("select id_acc_pembayaran, harga_pembayaran from account_pembayaran_tbl where nama_acc_pembayaran like '%Prasarana%'")->result();
				echo number_format($sp[0]->harga_pembayaran);
			?>
		</td>
		<td>
			<?php 
			$reduc = @$this->db->query("SELECT alokasi_biaya_reduksi_tbl.`biaya_reduksi`
											FROM alokasi_biaya_reduksi_tbl 
											INNER JOIN biaya_reduksi_tbl ON alokasi_biaya_reduksi_tbl.`id_biaya_reduksi` = biaya_reduksi_tbl.`id_biaya_reduksi`
											WHERE biaya_reduksi_tbl.`nis_siswa` = '".$result->nis_siswa."' 
											AND alokasi_biaya_reduksi_tbl.`id_acc_pembayaran` = '".$sp[0]->id_acc_pembayaran."'")->row();
			if($reduc){
				echo $reduc->biaya_reduksi;
			}else{
				$reduc = 0;
				echo 0;
			}
		?>
		</td>
		<td style="text-align:right">
			<?php
				if($reduc){
					$bbsp = $sp[0]->harga_pembayaran - @$reduc->biaya_reduksi;
				}else{
					$bbsp = $sp[0]->harga_pembayaran - $reduc;
				}
				
				echo number_format($bbsp);
			?>
		</td>
	</tr>
	
	<!-- POMG Area -->
	<tr>
		<td>POMG</td>
		<td>
			<?php
				$pomg = @$this->db->query("select id_acc_pembayaran, harga_pembayaran from account_pembayaran_tbl where nama_acc_pembayaran like '%POMG%'")->result();
				echo number_format($pomg[0]->harga_pembayaran);
			?>
		</td>
		<td>
			<?php 
			$reduc = @$this->db->query("SELECT alokasi_biaya_reduksi_tbl.`biaya_reduksi`
											FROM alokasi_biaya_reduksi_tbl 
											INNER JOIN biaya_reduksi_tbl ON alokasi_biaya_reduksi_tbl.`id_biaya_reduksi` = biaya_reduksi_tbl.`id_biaya_reduksi`
											WHERE biaya_reduksi_tbl.`nis_siswa` = '".$result->nis_siswa."' 
											AND alokasi_biaya_reduksi_tbl.`id_acc_pembayaran` = '".$pomg[0]->id_acc_pembayaran."'")->row();
			if($reduc){
				echo ($reduc->biaya_reduksi);
			}else{
				$reduc = 0;
				echo 0;
			}
		?>
		</td>
		<td style="text-align:right">
			<?php
				if($reduc){
					$bbpomg = $pomg[0]->harga_pembayaran - $reduc->biaya_reduksi;
					echo number_format($bbpomg);
				}else{
					$bbpomg = $pomg[0]->harga_pembayaran - $reduc;
					echo number_format($bbpomg);
				}
				
			?>
		</td>
	</tr>
	
	<!-- paket Seragam Area -->
	<tr>
		<td>Paket Seragam Siswa</td>
		<td>
			<?php
				$ps = @$this->db->query("select id_acc_pembayaran, harga_pembayaran from account_pembayaran_tbl where nama_acc_pembayaran like '%Seragam Siswa%'")->result();
				echo number_format($ps[0]->harga_pembayaran);
			?>
		</td>
		<td>
			<?php 
			$reduc = @$this->db->query("SELECT alokasi_biaya_reduksi_tbl.`biaya_reduksi`
											FROM alokasi_biaya_reduksi_tbl 
											INNER JOIN biaya_reduksi_tbl ON alokasi_biaya_reduksi_tbl.`id_biaya_reduksi` = biaya_reduksi_tbl.`id_biaya_reduksi`
											WHERE biaya_reduksi_tbl.`nis_siswa` = '".$result->nis_siswa."' 
											AND alokasi_biaya_reduksi_tbl.`id_acc_pembayaran` = '".$ps[0]->id_acc_pembayaran."'")->row();
			if($reduc){
				echo number_format($reduc->biaya_reduksi);
			}else{
				$reduc = 0;
				echo 0;
			}
		?>
		</td>
		<td style="text-align:right">
			<?php
				if($reduc){
					$bbps = $ps[0]->harga_pembayaran - $reduc->biaya_reduksi;
				}else{
					$bbps = $ps[0]->harga_pembayaran - $reduc;
				}
				
				echo number_format($bbsp);
			?>
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<button id="button_canc"  class="btn-canc">Tutup</button>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="text-align:right">Total Biaya Siswa</td>
		<td style="text-align:right"><?=number_format($bbspp+$bbsp+$bbpomg+$bbps+$bbup);?></td>
	</tr>
</table>
</div>