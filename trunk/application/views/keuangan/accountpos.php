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
		window.location.href="<?=site_url()?>keuangan"
	});
	$("#detail-pengeluaran").click(function(){
		window.location.href="<?=site_url()?>keuangan/transaksi"
	});
	$("#koperasi-sekolah").click(function(){
		window.location.href="<?=site_url()?>keuangan/koperasi"
	});
})
</script>

<div id="content" style="width:100%; height:530px;">
	<table>
	<tr style="height:40px;">
		<th colspan="7">
			<button id="account-budget" class="btn-add2" style="width:130px;height:27px;background-color:#4545ab;text-align:right">Budget</button>
			<button id="koperasi-sekolah" class="btn-add2" style="width:130px;height:27px;background-color:#4545ab;text-align:right">Koperasi</button>
			<button id="detail-pengeluaran" class="btn-add2" style="width:130px;height:27px;background-color:#4545ab;text-align:right">Transaksi</button>
		</th>
	</tr>
	</table>
	<!-- 
		left side 
		-->
	<span>
	<table align="left" style="width:25%;margin-right:25px">
			<tr><td style="text-align:left">Nama Budget: <div><input type="text" name="nama_budget" id="nama_budget" class="normal_textbox"></div></td></tr>
			<tr><td style="text-align:left">
				Budget Pos :
				<div style="overflow:auto;height:270px">
					<?php foreach($account_pemasukan as $d){?>
					<div style="margin-top:5px;color:#ffff00;font-weight:bold">
						<input type="checkbox" style="z-index:1" class="get_valid" val_id="<?=$d->idp;?>" val_curr="<?=$d->trans_pemb;?>">
						<span class="bgt_pos"><?=$d->nama_acc_pembayaran;?></span>
					</div>
						
						<?php
							$budget = $this->db->query("SELECT budget FROM alokasi_budget_tbl WHERE id_acc_pembayaran = '".$d->idp."'")->row();
							if($budget){
								echo "<input type='hidden' id='alokasi_".$d->idp."' value='".$budget->budget."'>";
							}else{
								echo "<input type='hidden' id='alokasi_".$d->idp."' value='0'>";
							}
							
						?>
					<div class="hideme-first show<?=$d->idp;?>" style="margin-top:3px;">Budget&nbsp;&nbsp;:&nbsp; <input type="text" val_id="<?=$d->idp;?>" class="bud<?=$d->idp;?> pos_budget" style="width:90px"></div>
					<div class="hideme-first show<?=$d->idp;?>" style="margin-top:3px;">Alokasi&nbsp; : &nbsp;<input type="text" 	 val_id="<?=$d->idp;?>" class="alo<?=$d->idp;?> pos_alokasi" style="width:90px"></div>
					<?php } ?>	
				</div>
			</td></tr>
			<tr>
				<td style="text-align:left">Tanggal Pengeluaran &nbsp;:&nbsp;
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
			var nm  = $("#nama_budget").val();
			var idp = [];
			var bgt = [];
			
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
						
			if(nm==""){
				$("#nama_budget").css("background-color","#ff6666");
				$("#nama_budget").focus();
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
				url 	: '<?=site_url()?>keuangan/post_budgeting',
				type 	: 'post',
				data 	: {'nm':nm, 'sd':sd, 'idp':idp, 'bgt':bgt,},
				success : function(data){
					window.location.href="keuangan";
				}
			})
		});
		
		
		
	});
	</script>
	<!-- 
		Right side 
		-->
	<table style="width:500px">
		<thead>
			<tr>
				<th colspan=4>
					<span style="float:left;padding:5px"><b>Pos Budget</b></span>
				</th>
			</tr>
		</thead>
		<thead>
			<tr>
				<th style="width:15px">No</th>
				<th style="width:340px">Nama Budget</th>
				<th style="width:340px">Tanggal Budget</th>
				<th style="width:140px">Detail</th>
			</tr>
		</thead>
		<tbody id="account_budget">
			<?php $no=1; foreach($account_budget as $b){?>
			<tr>
				<td><?=$no;?></td>
				<td style="text-align:left"><?=$b->nama_budget;?></td>
				<td><?=indo_date($b->start_date);?></td>
				<td>
					<a style="text-decoration:none" href="<?=site_url()?>keuangan/alokasi_budget/<?=htmlspecialchars($b->id_budget)?>/<?=htmlspecialchars($b->nama_budget)?>" data-tooltip="Alokasi Dana"  class="default_popup">
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