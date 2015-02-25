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
			containerID : "hutang_id",
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
		$("#account-budget").click(function(){
			window.location.href = "<?=site_url()?>pembayaran/pembayaran_siswa";
		});
		$("#koperasi-sekolah").click(function(){
			window.location.href = "<?=site_url()?>pembayaran/pembayaran_koperasi";
		});
		$("#donasi-sekolah").click(function(){
			window.location.href = "<?=site_url()?>pembayaran/pembayaran_donasi";
		});
		$("#piutang-pegawai").click(function(){
			window.location.href = "<?=site_url()?>pembayaran/pembayaran_piutang";
		});
		$("#piutang-koperasi").click(function(){
			window.location.href = "<?=site_url()?>pembayaran/pembayaran_piutang_koperasi";
		});
	});
</script>

<div id="content" style="width:100%; height:530px;">
	<table>
	<tr style="height:40px;">
		<th colspan="7">
			<button id="account-budget" 	 class="btn-add2" style="width:170px;height:27px;background-color:#4545ab;text-align:right">pembayaran siswa</button>
			<button id="koperasi-sekolah" 	 class="btn-add2" style="width:170px;height:27px;background-color:#4545ab;text-align:right">Koperasi</button>
			<button id="donasi-sekolah" 	 class="btn-add2" style="width:170px;height:27px;background-color:#4545ab;text-align:right">Donasi</button>
			<button id="piutang-pegawai" 	 class="btn-add2" style="width:170px;height:27px;background-color:#4545ab;text-align:right">Piutang Pegawai</button>
			<button id="piutang-koperasi" 	 class="btn-add2" style="width:170px;height:27px;background-color:#4545ab;text-align:right">Piutang Koperasi</button>
		</th>
	</tr>
	</table>
	
	<table style="width:55%">
		<thead>
			<tr>
				<th colspan=7>Data Hutang-Piutang Pegawai</th>
			</tr>
			<tr>
				<th>No</th>
				<th>Tanggal Pinjaman</th>
				<th>Keterangan</th>
				<th>Pinjaman</th>
				<th>Pengembalian</th>
				<th>Sisa Pinjaman</th>
				<th>Detail</th>
			</tr>
		</thead>
		
		<!-- Result  -->
		<tbody id="hutang_id">
			<?php $no=1; foreach($piutang as $i){?>
			<tr val_id="<?=$i->id_hutang_koperasi;?>" class="oncursor">
				<td><?=$no;?></td>
				<td><?=$i->tgl_hutang;?></td>
				<td><?=$i->keterangan;?></td>
				<td><?=number_format($i->hutang);?></td>
				<td><?=number_format($i->piutang);?></td>
				<td><?=number_format($i->sisa);?></td>
				<td>
					<?php if($i->hutang==$i->piutang){ ?>
						<input type="button" class="btn-alodin">
					<?php }else{ ?>
					<a style="text-decoration:none" href="<?=site_url()?>pembayaran/detail_piutang_koperasi/<?=htmlspecialchars($i->id_hutang_koperasi)."/".htmlspecialchars($i->keterangan);?>" data-tooltip="Detail Piutang"  class="default_popup">
						<input type="button" class="btn-alodan">
					</a>
					<?php } ?>
				</td>
			</tr>
			<?php $no++;} ?>
		</tbody>
	</table>
	<div class="holder" style="position:absolute; bottom:1;"></div>
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