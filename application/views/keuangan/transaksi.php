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
			containerID : "item_koperasi",
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
	$("#table_transakasi_budget").hide();
	$("#table_transakasi_koperasi").hide();
	$("#post_transakasi_budget").hide();
	$("#post_transakasi_koperasi").hide();
	clear_text();
	
	$("#account-budget").click(function(){
		window.location.href="<?=site_url()?>keuangan"
	});
	$("#detail-pengeluaran").click(function(){
		window.location.href="<?=site_url()?>keuangan/transaksi"
	});
	$("#biaya_trans").bind("keypress keyup", function(){
		$(this).val(numToCurr($(this).val()));
	});
	$("#koperasi-sekolah").click(function(){
		window.location.href="<?=site_url()?>keuangan/koperasi"
	});
	
	$("#transaksi-view").click(function(){
		if($("#show_table").val()==1){
			$(".table_up").hide('slide', {direction:'up'});
			$(".table_left").hide('slide');
		}
		var valchoise = $("#transaksi-view").val();

		if(valchoise=="budget"){ 									/* Budget Choice */
			var id_budget = $("#budget_choice").val();
			$.getJSON('<?=site_url()?>keuangan/get_budget/'+id_budget,function(data){ 
				valbudget = data.budget;
				$("#budget_transaksi").html(numToCurr(data.budget)) 
			});
			$.getJSON('<?=site_url()?>keuangan/get_transakasi_budget/'+id_budget,function(data){
				if(data){
				var sum   = 0;
				var items = "";
				for(m=0; m<data.length; m++){
					items += "<tr>";
					items += "<td>"+(m+1)+"</td>";
					items += "<td colspan=2 style='text-align:left'>"+data[m].nama_trans+"</td>";
					items += "<td style=''>"+data[m].tgl_trans+"</td>";
					items += "<td style='text-align:right'>"+numToCurr(data[m].biaya_trans)+"</td>";
					items += "</tr>";				
					sum = sum + parseFloat(data[m].biaya_trans);
				}
					items += "<tr>";
					items += "<td colspan=4 style='text-align:right'>Total</td>";
					items += "<td style='text-align:right'>"+numToCurr(sum)+"</td>";
					items += "</tr>";
				var saldo = parseFloat(valbudget) - parseFloat(sum)
				$("#saldo_transaksi").html(numToCurr(saldo));
				$("#data_transaksi_budget").html(items);
				$("#get_alltransaction_budget").html("<a class='default_popup' href='<?=site_url()?>keuangan/get_alltransaction/budget/"+id_budget+"'>Tampilkan Semua Transaksi</a>");
				$("#table_transakasi_budget").show('slide', {direction:'up'});
				$("#post_transakasi_budget").show('slide');
				$("#show_table").val(1);
				}else{
					$("#table_transakasi_budget").show('slide', {direction:'up'});
					$("#post_transakasi_budget").show('slide');
					var saldo = parseFloat(valbudget);
					$("#saldo_transaksi").html(numToCurr(saldo));
				}
			});
		}else if(valchoise=="koperasi"){									/* Koperasi Choice */
			var id_item = $("#koperasi_choice").val();
			$.getJSON('<?=site_url()?>keuangan/get_koperasi/'+id_item,function(data){ 
				var jml_item  = data.jml_terjual;
				if(jml_item){
					var jml_harga = parseFloat(data.harga) * parseInt(jml_item); 
					$("#total_penjualan").html(numToCurr(jml_harga));
					$("#total_item_terjual").html(jml_item)					
				}
			});
			$.getJSON('<?=site_url()?>keuangan/get_transakasi_koperasi/'+id_item,function(data){ 
				$("#harga_item").val(numToCurr(data[0].harga));
				var sum   = 0;
				var items = "";
				for(m=0; m<data.length; m++){				
					items += "<tr>";
					items += "<td style='align:center'>"+(m+1)+"</td>";
					items += "<td>"+data[m].tgl_terjual+"</td>";
					items += "<td>"+data[m].jumlah+"</td>";
					items += "</tr>";
				}
				
				
				$("#data_transaksi_koperasi").html(items);
				$("#get_alltransaction_koperasi").html("<a class='default_popup' href='<?=site_url()?>keuangan/get_alltransaction/koperasi/"+id_item+"'>Tampilkan Semua Transaksi</a>");
				
				$("#table_transakasi_koperasi").show('slide', {direction:'up'});
				$("#post_transakasi_koperasi").show('slide');
				$(".page2").hide();
				$("#show_table").val(1);
			});
			
		}
		
	});
	
	$("#jumlah_item").bind("keypress keyup",function(){
		var hargaitem  = $("#harga_item").val().replace(/\,/g, '');
		var jumlahitem = $("#jumlah_item").val();
		var totalharga = parseFloat(hargaitem) * parseFloat(jumlahitem);
		if(totalharga == isNaN){
			totalharga = 0;
		}
		$("#harga_total").val(numToCurr(totalharga));
	});
	
	$("#button_save_budget").click(function(){
		var id_budget  = $("#budget_choice").val();
		var nama_tran  = $("#ket_trans").val();
		var biaya	   = $("#biaya_trans").val();
		var sisa_saldo = $("#saldo_transaksi").html();
		if(nama_tran==""){
			$("#ket_trans").css("background-color","#ff6666");
			$("#ket_trans").focus();
			return false;
		}
		
		if(biaya==""){
			$("#biaya_trans").css("background-color","#ff6666");
			$("#biaya_trans").focus();
			return false;
		}
		
		//check saldo, cukupkah ?		
		if(parseFloat(sisa_saldo.replace(/\,/g, '')) < parseFloat(biaya.replace(/\,/g, ''))){
			alert(" Saldo tidak mencukupi untuk transaksi !!");
			$("#ket_trans").val("");
			$("#biaya_trans").val("");
			return false;
		}
			
		$.ajax({
			url 	: "<?=site_url()?>keuangan/save_transakasi",
			type 	: "post",
			data 	: {'id_budget':id_budget,
					   'nama_tran':nama_tran,
					   'biaya':biaya,
			},
			success : function(data){
				clear_text();
				$("#transaksi-view").trigger("click");
			}
		});
 	});
	
	$("#button_save_koperasi").click(function(){
		var id = $("#koperasi_choice").val();
		var jml = $("#jumlah_item").val();
		$.getJSON("<?=site_url();?>keuangan/save_penjualan/"+id+"/"+jml,function(data){
			clear_text();
			$("#transaksi-view").trigger("click");
		});
	});
	
});

function clear_text(){
	$("#ket_trans").val("");
	$("#biaya_trans").val("");
	$("#show_table").val(0);
	$("#harga_item").val("");
	$("#jumlah_item").val("");
	$("#harga_total").val("");
}
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
	
	<script type="text/javascript">
		$(function(){
			/* Load default */
			$(".choise").hide();
			$("#budget_choice").show();
			$.post("<?=site_url()?>keuangan/data_budget",{},function(obj){
				$('#budget_choice').html(obj);
			});
			
			$("#transaksi_choice").change(function(){
				var choise = $("#transaksi_choice").val();
				$.post("<?=site_url()?>keuangan/data_"+choise+"",{},function(obj){
					$(".choise").hide();
					$("#"+choise+"_choice").show();
					$("#"+choise+"_choice").html(obj);
					$("#transaksi-view").val(choise);
				});
			});
		});
	</script>
	<span style="height:530px;">
	<table align="" style="width:25%;margin-right:25px">
			<tr><td>
				<span style="float:left;margin-top:5px">
				Pilih Transaksi
				</span>
				<span style="float:right">
					:
					<select id="transaksi_choice" class="class_select1">
						<option value="budget">Budget</option>
						<option value="koperasi">Koperasi</option>
					</select>
				</span>
			</td></tr>
			<tr><td>
				<span style="float:left;margin-top:5px">
				Pilihan <!-- Items -->
				</span>
				<span style="float:right">
					:
					<select id="budget_choice"   class="choise class_select1"></select>
					<select id="koperasi_choice" class="choise class_select1"></select>
				</span>
			</td></tr>
			<tr>
				<td style="text-align:right">
					<button id="transaksi-view" value="budget" class="btn-view2">View</button>
				</td>
			</tr>
	</table>
	
	<table id="post_transakasi_budget" class="table_left" style="width:25%;margin-right:25px">
			<tr><td style="text-align:left">
				Keterangan  
				<span style="float:right">
				: <input type="text" id="ket_trans" class="normal_textbox">
				</span>
			</td></tr>
			<tr><td style="text-align:left">
				Biaya 
				<span style="float:right">
				: <input type="text" id="biaya_trans" class="normal_textbox">
				</span>
			</td></tr>
			<tr>
				<td style="text-align:right">
					<button id="button_save_budget" class="btn-save">Save</button>
				</td>
			</tr>
	</table>
	
	<table id="post_transakasi_koperasi" class="table_left" style="width:25%;margin-right:25px">
			<tr><td style="text-align:left">
				Harga
				<span style="float:right">
				: <input type="text" id="harga_item" class="normal_textbox">
				</span>
			</td></tr>
			<tr><td style="text-align:left">
				Jumlah
				<span style="float:right">
				: <input type="text" id="jumlah_item" class="normal_textbox">
				</span>
			</td></tr>
			<tr><td style="text-align:left">
				Total
				<span style="float:right">
				: <input type="text" id="harga_total" class="normal_textbox">
				</span>
			</td></tr>
			<tr>
				<td style="text-align:right">
					<button id="button_save_koperasi" class="btn-save">Save</button>
				</td>
			</tr>
	</table>
	</span>
	
	<!-- 
		Right side
	-->
	
	<!-- BUDGET TABLE -->
	<span id="table_transakasi_budget" class="table_up" style="position:absolute;top:115px;left:350px">
	<table align="left" style="width:600px">
		<tr>
			<td colspan=2 style="text-align:left">Budget</td>
			<td colspan=3 style="text-align:left"><span id="budget_transaksi"></span></td>
		</tr>
		<tr>
			<td colspan=2 style="text-align:left">Saldo</td>
			<td colspan=3 style="text-align:left"><span id="saldo_transaksi"></span></td>
		</tr>
		<tr>
			<td style="width:15px">No</td>
			<td style="width:25px;border-right:none"></td>
			<td style="width:300px">Keterangan</td>
			<td style="width:150px">Tanggal Transaksi</td>
			<td style="width:150px">Kredit</td>
		</tr>
		
		<tbody id="data_transaksi_budget"><tbody>	
	</table>
	<table align="left" style="width:185px;margin-left:10px">
		<tr>
			<th style="text-align:center"><span id="get_alltransaction_budget"></span></th>
		</tr>
	</table>
	</span>
	
	<!-- KOPERASI TABLE -->
	<span id="table_transakasi_koperasi" class="table_up" style="position:absolute;top:115px;left:350px">
	<table style="width:300px">
		<tr>
			<th style="width:15px;text-align:center">No</th>
			<th style="width:150px;text-align:center">Tanggal</th>
			<th style="width:100px;text-align:center">Jumlah</th>
		</tr>
		<tbody id="data_transaksi_koperasi"></tbody>
	</table>

	<table style="width:300px;position:absolute;top:0px;left:320px">
		<tr>
			<td style="text-align:left">Total Penjualan
			<span style="text-align:right;margin-right:5px">
				: <span id="total_penjualan"></span>
			</span>
			</td>
		</tr>
		<tr>
			<td style="text-align:left">Total Item Terjual
			<span style="text-align:right;margin-right:5px">
				: <span id="total_item_terjual"></span>
				Items
			</span>
			</td>
		</tr>
		<br>
		<tr>
			<th colspan=3><span id="get_alltransaction_koperasi"></span></th>
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
<input type="hidden" id="show_table">
</body>
</html>