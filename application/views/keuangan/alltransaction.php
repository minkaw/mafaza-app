<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><link rel="shortcut icon" href="<?=site_url()?>assets/img-web/pavicon_sd.png"/><title>Sekolah Dasar</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Untitled Document</title>
	<script>
		$(function(){
			/* Paging */
			$("div.holder").jPages({
			containerID : "item_transaksi",
			previous : " ← previous",
			next : "next → ",
			links: "numeric",
			perPage : 15,
			delay : 20
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
		
		/* custome script */
		$(function(){
			$(".button_close").click(function(){
				$(".popup_close").trigger( "click" );
			});
		});
		
	</script>
	<style>
	body {
		font-size:12px;
		font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif;
	}
	</style>
</head>
<body>
	<?php if($content == "koperasi"){ ?>
	<table style="width:400px">
		<thead>
			<tr>
				<th colspan="4" style="text-align:right">Nama Item
					<span style="float:right"> : <?=$transaksi[0]->nama_item;?></span>
				</th>
			</tr>
			<tr>
				<th colspan="4" style="text-align:right">Harga Satuan
					<span style="float:right"> : <?=number_format($transaksi[0]->harga);?></span>
				</th>
			</tr>
			<tr>
				<th>No</th>
				<th>Tanggal Terjual</th>
				<th>Kuantiti</th>
				<th>Total Jual</th>
			</tr>
		</thead>
		<tbody id="item_transaksi">
			<?php 
				$no=1;foreach($transaksi as $d){
			?>
			<tr>
				<td><?=$no;?></td>
				<td><?=indo_date($d->tgl_terjual);?></td>
				<td><?=$d->jumlah;?></td>
				<td><?=$d->jumlah*$d->harga;?></td>
			</tr>
			<?php $no++;
				} 
			?>
		</tbody>
		<tr>
			<th colspan=4><button  class="btn-canc button_close">Tutup</button></th>
		</tr>
	</table>
	<table style="height:30px;width:400px">
	<tr>
		<th colspan=4 style="background-color:#effeef;text-align:center">
			<div class="holder"></div>
		</th>
	</tr>
	</table>
	<?php } else { ?>
		<table style="width:550px">
		<thead>
			<tr>
				<th style="width:15px">No</th>
				<th style="width:300px">Keterangan</th>
				<th style="width:130px">Tanggal Transaksi</th>
				<th style="width:130px">Kredit</th>
			</tr>
		</thead>
		<tbody id="item_transaksi">
		<?php $no=1;foreach($transaksi as $d){ ?>
		<tr>
			<td><?=$no;?></td>
			<td style="text-align:left"><?=$d->nama_transaksi;?></td>
			<td><?=indo_date($d->tgl_transaksi);?></td>
			<td style="text-align:right"><?=number_format($d->biaya);?></td>
		</tr>
		<?php $no++; } ?>
		</tbody>
		<tr>
			<th colspan=4><button class="btn-canc button_close">Tutup</button></th>
		</tr>
		</table>
		<table style="height:30px;width:550px">
		<tr>
			<th colspan=4 style="background-color:#effeef;text-align:center">
				<div class="holder"></div>
			</th>
		</tr>
		</table>
	<?php } ?>
</body>
</html>
