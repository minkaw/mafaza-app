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
			containerID : "jmlitems",
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
			//hide textbox budget & Lokasi
			$(".hideme-first").hide();
			
			//unchek Budget Pos
			 $('input:checkbox').removeAttr('checked');
			 $(".pos_alokasi").val("");
			var i=0;
			
			/* parse */
			$(".get_valid").click(function(){
				vidp = $(this).attr("val_id");
				if($(this).is(":checked")){
					var dana_alokasi = $("#alokasi_"+vidp).val();
					var dana_awal	 = $(this).attr("val_curr");
					var dana_akhir	 = parseFloat(dana_awal) - parseFloat(dana_alokasi);
					$(".bud"+vidp).val(numToCurr(dana_akhir));
					$(".show"+vidp).show('slide', {direction: 'left'}, 777);
				}else{
					$(".bud"+vidp).val("");
					$(".alo"+vidp).val("");
					$(".kal"+vidp).val("");
					$(".show"+vidp).hide('slide', {direction: 'left'}, 777);
				}
			});
			
			$(".pos_alokasi").bind("keypress keyup", function(){
				$(this).val(numToCurr($(this).val()));
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
		<a href="<?=site_url()?>home"><img src="<?=site_url()?>assets/img-web/index.png"></a>
		<a class="default_popup" href="<?=site_url()?>password"><img src="<?=site_url()?>assets/img-web/change_password.png"></a>
		<a href="<?=site_url()?>logout"><img src="<?=site_url()?>assets/img-web/logout.png"></a>
		</div>
  </div>
</div>

<script type="text/javascript">
$(function(){
		$("#account-budget").click(function(){
			window.location.href="<?=site_url()?>pembayaran"
		});
		$("#koperasi-sekolah").click(function(){
			window.location.href="<?=site_url()?>pembayaran/koperasi"
		});
		$("#hutang-pegawai").click(function(){
			window.location.href="<?=site_url()?>pembayaran/hutang_pegawai"
		});
		$("#subsidi-pengeluaran").click(function(){
			window.location.href="<?=site_url()?>pembayaran/subsidi"
		});
		$("#lain-pengeluaran").click(function(){
			window.location.href="<?=site_url()?>pembayaran/lainnya"
		});		
});
</script>

<div id="content" style="width:100%; height:530px;">
	<table>
	<tr style="height:40px;">
		<th colspan="7">
			<button id="account-budget" 	 class="btn-add2" style="width:170px;height:27px;background-color:#4545ab;text-align:right">Budget</button>
			<button id="koperasi-sekolah" 	 class="btn-add2" style="width:170px;height:27px;background-color:#4545ab;text-align:right">Koperasi</button>
			<button id="hutang-pegawai" 	 class="btn-add2" style="width:170px;height:27px;background-color:#4545ab;text-align:right">Hutang Pegawai</button>
			<button id="subsidi-pengeluaran" class="btn-add2" style="width:170px;height:27px;background-color:#4545ab;text-align:right">Subsidi</button>
			<button id="lain-pengeluaran" 	 class="btn-add2" style="width:170px;height:27px;background-color:#4545ab;text-align:right">Lain - Lain</button>
		</th>
	</tr>
	</table>
	
	<table>
	</table>
	
	<table style="width:55%">
		<thead>
			<tr>
				<th colspan=4>
				<a style="text-decoration:none;float:right" href="<?=site_url()?>keuangan/tambah_items_koperasi" class="default_popup">
				<button class="btn-add" style="width:120px;background-color:#63a37f;text-align:right">Tambah item</button>
				</a>
				</th>
			</tr>
			<tr>
				<th>No</th>
				<th>Nama Item</th>
				<th>Satuan</th>
				<th>Harga</th>
			</tr>
		</thead>
		
		<!-- Result  -->
		<tbody id="jmlitems">
			<?php $no=1; foreach($items as $i){?>
			<tr>
				<td><?=$no;?></td>
				<td><?=$i->nama_item;?></td>
				<td><?=$i->satuan;?></td>
				<td style="text-align:right"><?=number_format($i->harga);?></td>
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
<input type="hidden" id="show_table">
</body>
</html>