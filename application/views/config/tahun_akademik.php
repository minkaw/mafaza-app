<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><link rel="shortcut icon" href="<?=site_url()?>assets/img-web/pavicon_sd.png"/><title>Sekolah Dasar</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Untitled Document</title>
	<script type="text/javascript" src="<?php echo site_url();?>assets/js/not-use/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="<?php echo site_url();?>assets/js/not-use/jquery-ui-1.11.0.js"></script>
	<script type="text/javascript" src="<?php echo site_url();?>assets/js/jquery.popup.js"></script>
	<script src="<?php echo site_url();?>assets/js/jPages.js"></script>
	<script>
		$(function(){
			/* Paging */
			$("div.holder").jPages({
			containerID : "account_budget",
			previous : " ← previous",
			next : "next → ",
			links: "numeric",
			perPage : 10,
			delay : 20
			});
			
			/* popup */
			$('.default_popup').popup();
		});
		
		function numToCurr(num){
			num = num.toString().replace(/\$|\,/g,'');
			if(isNaN(num))
			num = "0";
			sign = (num == (num = Math.abs(num)));
			num = Math.floor(num*100+0.50000000001);
			cents = num%100;
			num = Math.floor(num/100).toString();
			if(cents<10)
			cents = "0" + cents;
			for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
			num = num.substring(0,num.length-(4*i+3))+','+
			num.substring(num.length-(4*i+3));
			return (((sign)?'':'-') + num);
		}
		
		/* custome script */
		$(function(){
			
			$("#for_update").hide();
			$(".set_aktif").click(function(){
				var c = $(this).attr('val_id');
				$.getJSON('<?=site_url()?>konfigurasi/set_aktif_tahunakademik/'+c,function(data){
					if(data){
						window.location.href = '<?=site_url()?>konfigurasi/tahun_akademik';
					}
				});
				return false;
			});
			
			$("#for_cancel").click(function(){
				
			});
			
			$("#button_update").click(function(){
			
			});
			
			$(".nonaktif_account").click(function(){
				
			});
			
			$("#button_save").click(function(){
				var ta_awal  = $("#tahun_awal").val();
				var ta_akhir = $("#tahun_akhir").val();
				if(ta_awal==""){
					$("#tahun_awal").css("background-color","#ff6666");
					$("#tahun_awal").focus();
					return false;
				}
				if(ta_akhir==""){
					$("#tahun_akhir").css("background-color","#ff6666");
					$("#tahun_akhir").focus();
					return false;
				}
				
				$.ajax({
					url 	: '<?=site_url()?>konfigurasi/add_tahunakademik',
					type 	: 'post',
					data 	: {'ta_awal':ta_awal,
							   'ta_akhir':ta_akhir
					},
					success : function(data){
						window.location.href = '<?=site_url()?>konfigurasi/tahun_akademik';
					}
				})
			});
			
		});
		
		
	</script>
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo site_url();?>assets/css/css-table.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo site_url();?>assets/css/css-button.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo site_url();?>assets/css/popup.css">
	<link rel="stylesheet" type="text/css" href="<?php echo site_url();?>assets/css/not-use/jquery-ui-1.11.0.css" />
	<style type="text/css">
	body {
		margin:0;
		background-color:#369;
	}
	#content{
		overflow:auto;
		padding:10px;
		font-size:12px;
		font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif;
	}	
	#caption{
		color:#FFF;
		font-size:25px;
		font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif;
	}
	
	
	.ui-widget {
		font-family: Verdana,Arial,sans-serif;
		font-size: 12px;
	}
	
	.ui-menu-item{
		margin-top:10px;
	}
	</style>
</head>

<body link="#FFFFFF">
<!--header-->
<div style="width:100%; height:50px; overflow:hidden; margin: 0 auto; background-color:#333">
  <div style="width:100%; height:50px; margin: 0 auto; overflow:hidden; position:relative">
        <div id="caption" style="height:44px; margin-top:10px;float:left; margin-left:10px;"> Sistem Informasi Mafaza </div>
		<div style="float:right;margin-right:20px">
		<a head-tooltip="Data Siswa" style="z-index:15" href="<?=site_url()?>datasiswa"><img src="<?=site_url()?>assets/img-web/datasiswa.png"></a>
		<a head-tooltip="Account Pengeluaran" style="z-index:13" href="<?=site_url()?>pembayaran"><img src="<?=site_url()?>assets/img-web/pengeluaran.png"></a>
		<a head-tooltip="Account Pemasukan" style="z-index:14" href="<?=site_url()?>pembayaran/pembayaran_siswa"><img src="<?=site_url()?>assets/img-web/pemasukan.png"></a>
		<a head-tooltip="Koperasi" style="z-index:12" href="<?=site_url()?>koperasi"><img src="<?=site_url()?>assets/img-web/koperasi.png"></a>
		<a head-tooltip="Konfigurasi" style="z-index:11" href="<?=site_url()?>konfigurasi"><img src="<?=site_url()?>assets/img-web/konfigurasi.png"></a>
		<a head-tooltip="Laporan" style="z-index:10" href="<?=site_url()?>laporan"><img src="<?=site_url()?>assets/img-web/laporan.png"></a>
		<a href="<?=site_url()?>home"><img src="<?=site_url()?>assets/img-web/index.png"></a>
		<a class="default_popup" href="<?=site_url()?>password"><img src="<?=site_url()?>assets/img-web/change_password.png"></a>
		<a href="<?=site_url()?>logout"><img src="<?=site_url()?>assets/img-web/logout.png"></a>
		</div>
  </div>
</div>
<script type="text/javascript">
	$(function(){
		$("#user-account").click(function(){
			window.location.href = "<?=site_url()?>konfigurasi";
		});
		$("#account-pembayaran").click(function(){
			window.location.href = "<?=site_url()?>konfigurasi/account_pembayaran";
		});
		$("#tahun-akademik").click(function(){
			window.location.href = "<?=site_url()?>konfigurasi/tahun_akademik";
		});
	});
</script>
<div id="content" style="width:100%; height:530px;">
	<table>
	<tr style="height:40px;">
		<th colspan="7">
			<button id="user-account" 		class="btn-add2" style="width:170px;height:27px;background-color:#4545ab;text-align:right">User Account</button>
			<button id="account-pembayaran" class="btn-add2" style="width:170px;height:27px;background-color:#4545ab;text-align:right">Account Pembayaran</button>
			<button id="tahun-akademik" 	class="btn-add2" style="width:170px;height:27px;background-color:#4545ab;text-align:right">Tahun Akademik</button>
		</th>
	</tr>
	</table>
	<!-- 
		left side 
		-->
	<span style="height:530px;">
	<table align="" style="width:25%;margin-right:25px">
			<tr>
				<td style="text-align:left">
				Tahun Awal 
				<span style="float:right">: <input type="text" id="tahun_awal" class="class_textbox1"></span>
				</td>
			</tr>
			<tr>
				<td style="text-align:left">
				Tahun Akhir
				<span style="float:right">: <input type="text" id="tahun_akhir" class="class_textbox1"></span>
				</td>
			</tr>
			<tr id="for_update">
				<td style="text-align:right">
					<button id="button_update" class="btn-save">Update</button>
					<button id="for_cancel" class="btn-canc" >Cancel</button>
				</td>
			</tr>
			<tr id="for_save">
				<td style="text-align:right">
					<button id="button_save" class="btn-save">Simpan</button>
				</td>
			</tr>
	</table>
	</span>
	
	<!-- 
		Right side
	-->
	<span id="table_transakasi" style="position:absolute;top:115px;left:400px">
	<table style="width:650px">
		<tr>
			<td style="width:15px">No</td>
			<td style="width:100px">Tahun Akademik</td>
			<td style="width:200px">Status</td>
			<td style="width:100px">Pilihan</td>
		</tr>
		<?php $no=1; foreach($ta_akademik as $u){?>
		<tr>
			<td style="width:15px"><?=$no?></td>
			<td style="width:100px"><?=$u->periode_awal;?> / <?=$u->periode_akhir;?></td>
			<td style="width:200px"><?php if($u->is_periode==1){ echo "Aktif"; }else{ echo "Tidak Aktif"; }?></td>
			<td style="width:100px">
				
				<a data-tooltip="Edit" class="edit_account" val_id="<?=$u->id_periode_akademik?>" href="">
					<input type="button" class="button_edit btn-edit">
				</a>
				<?php if($u->is_periode==1){?>
				<a data-tooltip="Set Non Aktif" class="set_nonaktif" val_id="<?=$u->id_periode_akademik?>" href="">
					<input type="button" class="button_edit btn-unchecked">
				</a>
				<?php } else { ?>
				<a data-tooltip="Set Aktif" class="set_aktif" val_id="<?=$u->id_periode_akademik?>" href="">
					<input type="button" class="button_edit btn-checked">
				</a>
				<?php } ?>
			</td>
		</tr>		
		<?php $no++; } ?>
		
		<tbody id="add_account"></tbody>
	</table>
	</span>
</div>

		
<!--footer--> 
<div style="width:100%; position:absolute; bottom:0; background-color:#333; margin-top:20px;">
    <div style="width:750px; height:60px; margin: 0 auto;">
    <p style="text-align:center; color:#FFF; font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif; font-size:x-small">&copy; 2014 All rights reserved. </p>
    <p style="text-align:center; color:#CCC; font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif; font-size:x-small"> sistem pembayaran sekolah terpadu </p>
    </div>
</div>
</body>
</html>