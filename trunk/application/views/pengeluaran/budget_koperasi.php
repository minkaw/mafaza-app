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
			//hide textbox budget & Lokasi
			$(".hideme-first").hide();
			
			//unchek Budget Pos
			 $('input:checkbox').removeAttr('checked');
			 $(".pos_alokasi").val("");

			 $("#now_date").click(function(){
				if($(this).is(":checked")){
					var d = new Date();
					var month_name=new Array(12);
						month_name[0]="Jan"
						month_name[1]="Feb"
						month_name[2]="Mar"
						month_name[3]="Apr"
						month_name[4]="May"
						month_name[5]="Jun"
						month_name[6]="Jul"
						month_name[7]="Aug"
						month_name[8]="Sep"
						month_name[9]="Oct"
						month_name[10]="Nov"     
						month_name[11]="Dec"
					var now_date = d.getDate()+' '+month_name[d.getMonth()]+' '+d.getFullYear();
					$("#start_date").attr("disabled","disabled");
					$("#start_date").css("background-color","#cdcdcd");
					$("#start_date").val(now_date);
				}else{
					var now_date = "";
					$("#start_date").removeAttr("disabled");
					$("#start_date").css("background-color","#ffffff")
				}
			 })
			 
			
			var i=0;
			
			/* parse */
			$(".get_valid").click(function(){
				vidp = $(this).attr("val_id");
				if($(this).is(":checked")){
					$.getJSON("<?=site_url()?>konfigurasi/get_forbudget/"+vidp, function(data){
						if(data>0){
							$(".bud"+vidp).val(numToCurr(data));
						}else{
							$(".bud"+vidp).val(0);
						}
					});
					
					$(".show"+vidp).show('slide', {direction: 'left'}, 777);
				}else{
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
		window.location.href="<?=site_url()?>pembayaran"
	});
	$("#transaksi-pengeluaran").click(function(){
		window.location.href="<?=site_url()?>pembayaran/transaksi_pengeluaran"
	});
	$("#pinjaman-karyawan").click(function(){
		window.location.href="<?=site_url()?>pembayaran/pinjaman_karyawan"
	});
	$("#budget-koperasi").click(function(){
		window.location.href="<?=site_url()?>pembayaran/budget_koperasi"
	});
});
</script>

<div id="content" style="width:100%; height:550px;">
	<table>
	<tr style="height:40px;">
		<th colspan="7">
			<button id="account-budget" 	 class="btn-add2" style="width:170px;height:27px;background-color:#4545ab;text-align:right">Budget</button>
			<button id="transaksi-pengeluaran" 	 class="btn-add2" style="width:170px;height:27px;background-color:#4545ab;text-align:right">Transaksi Pengeluaran</button>
			<button id="pinjaman-karyawan" 	 class="btn-add2" style="width:170px;height:27px;background-color:#4545ab;text-align:right">Pinjaman Karyawan</button>
			<button id="budget-koperasi" 	 class="btn-add2" style="width:170px;height:27px;background-color:#4545ab;text-align:right">Budget Koperasi</button>
		</th>
	</tr>
	</table>
	<!-- 
		left side 
		-->
	<span>
	<table align="left" style="width:25%;margin-right:25px">
			<tr><td style="text-align:left"><span>Keterangan</span> <span style="float:right">:<input type="text" name="keterangan" id="keterangan" class="normal_textbox"></div></td></tr>
			<tr><td style="text-align:left">
				Budget Pinjaman :
				<div style="overflow:auto;height:270px">
					<!-- DARI PEMBAYARAN SISWA -->
					<?php 
						$post_bgt = $this->db->query("select id_acc_pembayaran, nama_acc_pembayaran from account_pembayaran_tbl order by id_acc_pembayaran asc")->result();
						foreach($post_bgt as $d){
					?>
					<div style="margin-top:5px;color:#ffff00;font-weight:bold">
						<input type="checkbox" style="z-index:1" class="get_valid" val_id="<?=$d->id_acc_pembayaran;?>">
						<span class="bgt_pos"><?=$d->nama_acc_pembayaran;?></span>
					</div>
						
					<div class="hideme-first show<?=$d->id_acc_pembayaran;?>" style="margin-top:3px;">Budget&nbsp;&nbsp;:&nbsp; <input type="text" class="bud<?=$d->id_acc_pembayaran;?> pos_budget" style="width:90px"></div>
					<div class="hideme-first show<?=$d->id_acc_pembayaran;?>" style="margin-top:3px;">Alokasi&nbsp; : &nbsp;<input type="text" 	 class="alo<?=$d->id_acc_pembayaran;?> pos_alokasi" style="width:90px"></div>
					<?php } ?>
					
					<!-- DARI DONASI -->
					<div style="margin-top:5px;color:#ffff00;font-weight:bold">
						<input type="checkbox" style="z-index:1" class="get_valid" val_id="donasi">
						<span class="bgt_pos">Donasi</span>
					</div>
					<div class="hideme-first showdonasi" style="margin-top:3px;">Budget&nbsp;&nbsp;:&nbsp; <input type="text" class="buddonasi pos_budget" style="width:90px"></div>
					<div class="hideme-first showdonasi" style="margin-top:3px;">Alokasi&nbsp; : &nbsp;<input type="text" 	 class="alodonasi  pos_alokasi" style="width:90px"></div>
					
					<!-- DARI KOPERASI -->
					<div style="margin-top:5px;color:#ffff00;font-weight:bold">
						<input type="checkbox" style="z-index:1" class="get_valid" val_id="koperasi">
						<span class="bgt_pos">Koperasi</span>
					</div>
					<div class="hideme-first showkoperasi" style="margin-top:3px;">Budget&nbsp;&nbsp;:&nbsp; <input type="text" class="budkoperasi pos_budget" style="width:90px"></div>
					<div class="hideme-first showkoperasi" style="margin-top:3px;">Alokasi&nbsp; : &nbsp;<input type="text" 	 class="alokoperasi  pos_alokasi" style="width:90px"></div>
					
				</div>
			</td></tr>
			<tr>
				<td style="text-align:left">Tanggal Pinjaman &nbsp;:&nbsp;
					<div>
						<input type="text" id="start_date" class="kalender" style="width:150px">
						<input type="checkbox" id="now_date"> Hari ini
					</div>
				</td>
			</tr>
			<tr>
				<td style="text-align:left">
					<input type="hidden" name="uid" id="uid">
					<button id="button_save" class="btn-save">Simpan</button>
					<button id="button_canc" class="btn-canc">Cancel</button>
				</td>
			</tr>
	</table>
	
	<script type="text/javascript">
	$(function(){
		$( ".kalender" ).datepicker({ 
			dateFormat: 'dd M yy'
		});
		
		$("#button_save").click(function(){
			var ket   = $("#keterangan").val();
			var idp  = [];
			var bgt  = [];
			
			/* Get all value */
			$('.get_valid:checked').each(function(){
				idp.push($(this).attr("val_id"));
			});
			$('.pos_alokasi').each(function(){
				if($(this).val()!=""){
					bgt.push($(this).val().replace(/\,/g, ''));
				}
			});
			var sd = $("#start_date").val();
						
			if(ket==""){
				$("#keterangan").css("background-color","#ff6666");
				$("#keterangan").focus();
				return false;
			}
			if(idp==""){
				$(".bgt_pos").css("color","#ff0000");
				return false;
			}
			if(sd==""){
				$("#start_date").css("background-color","#ff6666");
				return false;
			}
			
			
			$.ajax({
				url 	: '<?=site_url()?>pembayaran/post_pinjaman_koperasi',
				type 	: 'post',
				data 	: {'ket':ket, 'sd':sd, 'idp':idp, 'bgt':bgt,},
				success : function(data){
					window.location.href="<?=site_url()?>pembayaran/budget_koperasi";
				}
			})
		});
		
		
		
	});
	</script>
	<!-- 
		Right side 
		-->
	<table style="width:675px">
		<thead>
			<tr>
				<th colspan=5>
					<span style="float:left;padding:5px"><b>Pinjaman Koperasi</b></span>
				</th>
			</tr>
		</thead>
		<thead>
			<tr>
				<th style="width:15px">No</th>
				<th style="width:200px">Tanggal Pinjaman</th>
				<th style="width:250px">Keterangan</th>
				<th style="width:150px">Status</th>				
				<th style="width:50px">Detail</th>
			</tr>
		</thead>
		<tbody id="account_budget">
			<?php $pinjaman = $this->db->query("select * from hutang_koperasi_tbl")->result();?>
			<?php $no=1; foreach($pinjaman as $p){?>
			<tr>
				<td><?=$no;?></td>
				<td><?=indo_date($p->tgl_hutang);?></td>
				<td style="text-align:left"><?=$p->keterangan;?></td>
				<td style="text-align:left"><?=$p->nik_pegawai;?></td>
				<td>
					<a style="text-decoration:none" href="<?=site_url()?>pembayaran/detail_pinjaman_koperasi/<?=htmlspecialchars($p->id_hutang_koperasi);?>" data-tooltip="Alokasi Dana"  class="default_popup">
						<input type="button" class="btn-alodan">
					</a>
				</td>
			</tr>
			<?php $no++; } ?>
		</tbody>
	</table>
	<div class="holder" style="position:absolute; right:600px; top:550px"></div>
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