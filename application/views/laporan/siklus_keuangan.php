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
			containerID : "laporan",
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
		
		
	</script>
	<script type="text/javascript">
	$(function(){
		$("#account-budget").click(function(){
			window.location.href = "<?=site_url()?>laporan";
		});
		$("#koperasi-sekolah").click(function(){
			window.location.href = "<?=site_url()?>laporan/laporankoperasi";
		});
		$("#iuran-siswa").click(function(){
			window.location.href = "<?=site_url()?>laporan/laporan_iuran_siswa";
		});
		$("#siklus-keuangan").click(function(){
			window.location.href = "<?=site_url()?>laporan/laporan_siklus_keuangan";
		});
		
		$( ".kalender" ).datepicker({ 
			dateFormat: 'dd M yy'
		});
		
		$("#laporan-view").click(function(){					
			/*
			var sd = $("#start_date").val();
			var ed = $("#end_date").val();
			
			if(sd==""){
				$("#start_date").css("background-color","#ff6666");
				return false;
			}			
			if(ed==""){
				$("#start_date").css("background-color","#ff6666");
				return false;
			}
			*/
			//window.open("<?=site_url()?>laporan/load_sikluskeuangan/"+encodeURIComponent(sd)+"/"+encodeURIComponent(ed)+"", 'window name', 'window settings');
			window.open("<?=site_url()?>laporan/load_sikluskeuangan/", 'window name', 'window settings');
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

<div id="content" style="width:100%; height:530px;">
	<table>
	<tr style="height:40px;">
		<th colspan="7">
			<button id="account-budget" class="btn-add2" style="width:150px;height:27px;background-color:#4545ab;text-align:right">Laporan Budget</button>
			<button id="koperasi-sekolah" class="btn-add2" style="width:150px;height:27px;background-color:#4545ab;text-align:right">Laporan Koperasi</button>
			<button id="iuran-siswa" class="btn-add2" style="width:150px;height:27px;background-color:#4545ab;text-align:right">Laporan Iuran</button>
			<button id="siklus-keuangan" class="btn-add2" style="width:150px;height:27px;background-color:#4545ab;text-align:right">Siklus Keuangan</button>
		</th>
	</tr>
	</table>
	
	<span style="height:530px;">
	<table align="" style="width:20%;margin-right:25px">
			<!--tr><td>
				<span style="float:left;margin-top:5px">
				Tanggal Awal
				</span>
				<span style="float:right">
					:
					<input type="text" id="start_date" class="small_textbox kalender">
				</span>
			</td></tr>
			<tr><td>
				<span style="float:left;margin-top:5px">
				Tanggal akhir 
				</span>
				<span style="float:right">
					:
					<input type="text" id="end_date" class="small_textbox kalender">
				</span>
			</td></tr-->
			<tr>
				<td style="text-align:right">
					<button id="laporan-view" class="btn-view2">View</button>
				</td>
			</tr>
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