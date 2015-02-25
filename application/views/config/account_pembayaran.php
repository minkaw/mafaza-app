<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><link rel="shortcut icon" href="<?=site_url()?>assets/img-web/pavicon_sd.png"/><title>Sekolah Dasar</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Untitled Document</title>
	<script type="text/javascript" src="<?php echo site_url();?>assets/js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="<?php echo site_url();?>assets/js/jquery.popup.js"></script>
	<script src="<?php echo site_url();?>assets/js/jPages.js"></script>
	<script>
		$(function(){
			/* Paging */
			$("div.holder").jPages({
			containerID : "datasiswa",
			previous : " ← previous",
			next : "next → ",
			links: "numeric",
			perPage : 10,
			delay : 20
			});
			
			/* popup */
			$('.default_popup').popup();
			
			
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function(){			
			
			if($("#uid").val()==""){
				$("#button_canc").hide();		
			}else{
				$("#button_canc").show();
			}
			
			
			$("#bip").bind("keyup keypress",function(){
				$(this).val(numToCurr($(this).val()));
			})
			
						
			$(".button_edit").click(function(){
				var v = $(this).attr("val_id");
				$.getJSON('<?=site_url()?>konfigurasi/edit_pembayaran/'+v,function(data){
					$("#nap").val(data.nama_acc_pembayaran);
					$("#bip").val(numToCurr(data.harga_pembayaran));
					$("#ket").val(data.keterangan);
					$("#uid").val(data.id_acc_pembayaran);
					if(data.cost_reduction=="on"){
						$("#biayareduksi").prop('checked','checked');
					}else{
						$("#biayareduksi").prop('checked','');
					}
					$("#button_canc").show();
				});
			});

			$(".button_dete").click(function(){
				if(confirm("Hapus Data ?")){
					var v = $(this).attr("val_id");
					$.getJSON('<?=site_url()?>konfigurasi/delete_pembayaran/'+v,function(data){
						if(data){
							window.location.href = "<?=site_url()?>konfigurasi/account_penbayaran" ;
						}else{
							alert("data tidak bisa di hapus!! hubungi administrator!!");
						}
					});
				}
				return false;
			});
			
			$("#button_canc").click(function(){
				if(confirm("Cancel Update ?")){
					$("#uid").val("");
					$("#nap").val("");
					$("#jep").val("");
					$("#bip").val("");
					$("#ket").val("")
					$("#button_canc").hide();
				}
				return false;
			});
			
			$("#button_save").click(function(){
				var u = $("#uid").val();
				var n = $("#nap").val();
				var j = $("#jep").val();
				var b = $("#bip").val();
				var k = $("#ket").val();
				var c = $("#biayareduksi").is(":checked");
				
				if(n==""){
					$("#nap").css("background-color","#de6666");
					$("#nap").focus();
					return false;
				}
				if(j==""){
					$("#jep").css("background-color","#de6666");
					$("#jep").focus();
					return false;
				}
				if(b==""){
					$("#bip").css("background-color","#de6666");
					$("#bip").focus();
					return false;
				}
				
				if(u){
					var content = "Perbarui Data ?";
				}else{
					var content = "Simpan Data ?";
				}
				
				if(c){
					var c = "on";
				}else{
					var c = "off";
				}
				
				if(confirm(content)){
					$.ajax({
						url 	: '<?=site_url()?>konfigurasi/tambah_pembayaran',
						type 	: 'post',
						data 	: {'uid':u, 'nap':n, 'jep':j, 'bip':b, 'ket':k, 'red':c },
						success : function(data){
							if(data){
								window.location.href = "<?=site_url()?>konfigurasi/account_pembayaran" ;
							}else{
								alert("data tidak bisa di update!! hubungi administrator!!");
							}
							
						}
					});
				}
				return false;
			});
			
			$("#nap").bind("keyup keypress",function(){
				$("#nap").css("background-color","#ffffff");
			});
			
			$("#bip").bind("keyup keypress",function(){
				$("#bip").css("background-color","#ffffff");
			});
			
			$("#jep").bind("change",function(){
				$("#jep").css("background-color","#ffffff");
			});
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
	</script>
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo site_url();?>assets/css/css-table.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo site_url();?>assets/css/css-button.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo site_url();?>assets/css/popup.css">
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
	

	</style>
</head>

<body>
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
	<!-- left side -->
	<span>
	<!-- form action="<?php echo site_url()?>pembayaran/tambah_pembayaran" method="post" -->
	<table align="left" style="width:25%;margin-right:25px">
			<tr><td style="text-align:left">Nama : <div><input type="text" name="nap" id="nap" class="normal_textbox"></div></td></tr>
			<tr><td style="text-align:left">
				Periode :
				<div>
				<select name="jep" id="jep" class="class_select1">
					<option value="">Periode Pembayaran</option>
					<option value="pt">Pertahun</option>
					<option value="ps">Semester</option>
					<option value="pb">Perbulan</option>
					<option value="hs">Tidak Berperiode</option>
				</select>
				</div>
			</td></tr>
			<tr><td style="text-align:left">Biaya : <div><input type="text" name="bip" id="bip" class="normal_textbox"></div></td></tr>
			<tr><td style="text-align:left">Keterangan : <div><textarea class="normal_texarea" name="ket" id="ket"></textarea></div></td></tr>
			<tr><td style="text-align:left">Biaya Reduksi : <span style="margin-top:20px"><input type="checkbox" id="biayareduksi"></span></td></tr>
			<tr>
				<td style="text-align:left">
					<input type="hidden" name="uid" id="uid">
					<button id="button_save" class="btn-save">Simpan</button>
					<button id="button_canc" class="btn-canc">Cancel</button>
				</td>
			</tr>
	</table>
	<!-- /form -->
	
	<!-- right side -->
	<span>
	<table align="left" style="width:60%;">
		<thead>
			<tr>
				<th>No</th>
				<th>Nama Pembayaran</th>
				<th>Jenis Pembayaran</th>
				<th>Biaya (Rp)</th>
				<th>Pilihan</th>
			</tr>
		</thead>
		<tbody id="datasiswa">
			<?php $no=1;foreach($acpem as $acp){?>
			<tr>
				<td><?=$no;?></td>											<!-- No -->
				<td><?=$acp->nama_acc_pembayaran;?></td>					<!-- Nama Pembayaran  -->
				<td>														<!-- Jenis Pembayaran -->
					<?php
					if($acp->jenis_pembayaran == "pb"){
						echo "Perbulan";
					}else if($acp->jenis_pembayaran == "ps"){
						echo "Persemester";
					}else if($acp->jenis_pembayaran == "pt"){
						echo "Pertahun";
					}else{
						echo "Hanya Sekali";
					}
					?>
				</td>
				<td style="text-align:right"><?=number_format($acp->harga_pembayaran);?></td>		<!-- Biaya pembayaran -->
				<td>																				<!-- Pilihan -->
					<a href="<?=site_url();?>pembayaran/detail_account/<?=$acp->id_acc_pembayaran.'/'.htmlspecialchars($acp->nama_acc_pembayaran).'/'.htmlspecialchars($acp->harga_pembayaran);?>" class="default_popup">
						<input type="button" class="btn-view">
					</a>
					<input type="button"  val_id="<?=$acp->id_acc_pembayaran;?>" class="button_edit btn-edit">
					<input type="button"  val_id="<?=$acp->id_acc_pembayaran;?>" class="button_dete btn-dele">
				</td>
			</tr>
			<?php $no++; } ?>
		</tbody>
	</table>
	</span>
	<div class="holder" style="margin-top:400px;margin-left:650px;"></div>
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
