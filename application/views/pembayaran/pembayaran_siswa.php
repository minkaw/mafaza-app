<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><link rel="shortcut icon" href="<?=site_url()?>assets/img-web/pavicon_sd.png"/><title>Sekolah Dasar</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Untitled Document</title>
	<script type="text/javascript" src="<?php echo site_url();?>assets/js/not-use/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="<?php echo site_url();?>assets/js/not-use/jquery-ui-1.11.0.min.js"></script>
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
	
	<!-- custome script -->
	<script>
		$(function(){
			clear_refresh();		
			get_listpembayaran();
			$.getJSON('<?=site_url()?>pembayaran/account_pembayaran/',function(data){
				items = "";
				var c;
				for(c=0; c<data.length; c++){
					items += "<option value='"+data[c].id_pembayaran+"--"+data[c].harga+"--"+data[c].nama_pembayaran+"'>"+data[c].nama_pembayaran+"</option>";
				}
				$("#acc_pembayaran").html(items);
				$("#biaya_pembayaran").val(format_curr(parseFloat(data[0].harga)));
				$("#nama_pembayaran").val(data[0].nama_pembayaran);
			});
						
			$("#acc_pembayaran").change(function(){
				var c = $("#acc_pembayaran").val();
				var d = c.split('--');
				var e = format_curr(parseFloat(d[1]));
				$("#nama_pembayaran").val(d[2]);
				$("#biaya_pembayaran").val(e);
				$("#keywork").val(0);
				$(".clt").html('');
			});
			
			$("#get_siswabayar").click(function(){
				var nis = $("#nis_siswa").val();					/* get NIS */
				var d   = $("#acc_pembayaran").val().split('--'); 	/* get id_acc_pembayaran */
				//$('siswa')
				
				/* validasi */
				if(nis==""){
					$("#nis_siswa").css("background-color","#ff6666");
					$("#nis_siswa").focus();
					return false;
				}else{
					$("#nis_siswa").css("background-color","#ffffff");
				}
				
				if($("#nama-siswa").val()==""){
					$("#nama-siswa").css("background-color","#ff6666");
					$("#nama-siswa").focus();
					return false;
				}else{
					$("#nama-siswa").css("background-color","#ffffff");
				}
				/* end of validasi */
				
				$.ajax({
					url 	: '<?=site_url();?>pembayaran/get_siswabayar',
					type	: 'post',
					data 	: {	'nis':nis,
								'idp':d[0]
					
					},
					
					success	: function(data){
						var array = JSON.parse(data);
						var c=1;
						array.forEach(function(object){
							
							var hp    				= object.harga_pembayaran;
							var buying_date 		= object.buying_date;
							var id_acc_pembayaran 	= object.id_acc_pembayaran;
							var id_detail_acc_pemb	= object.id_detail_acc_pemb;
							var jumlah_transaksi 	= object.jml_trans;
							var tgl_transaksi 		= object.tgl_trans;

							if(object.reduksi){
								var reduksi_pembayaran	= parseFloat(object.reduksi);
							}else{
								var reduksi_pembayaran = 0;
							}

							if(tgl_transaksi){	
								$(".No"+c).html(c);
								$(".BuyDate"+c).html(cutdate(buying_date));
								if(parseFloat(hp) == parseFloat(jumlah_transaksi)+reduksi_pembayaran){
									var ket = "L";
									$(".Sta"+c).html("<b>Lunas</b>");
								}else{
									var ket = "BL";
									$(".Sta"+c).html("<i>Belum Lunas</i>");
								}
								$(".Tgl"+c).html(tgl_transaksi);
								$(".Byr"+c).html("<a data-tooltip='Detail'" 
													+"href='detail_pembayaran/"+nis+"/"+d[0]+"/"+id_detail_acc_pemb+"/"+hp+"/"+ket+"'" 
													+"class='default_popup'><input type=button class=btn-detail></a>");
							}else{
								$(".No"+c).html(c);
								$(".BuyDate"+c).html(cutdate(object.buying_date));
								$(".Sta"+c).html("Belum Ada Pembayaran");
								$(".Tgl"+c).html("-");
								$(".Byr"+c).html("<a data-tooltip='Bayar'" 
													 +"href='post_pembayaran/"
													 +encodeURIComponent($("#nama_pembayaran").val())+"/"
													 +encodeURIComponent(cutdate(object.buying_date))+"/"
													 +nis+"/"
													 +encodeURIComponent($("#nama-siswa").val())+"/"
													 +encodeURIComponent($("#kelas-siswa").val())+"/"
													 +d[0]+"/"
													 +id_detail_acc_pemb+"/"
													 +encodeURIComponent(hp)+"'" 
													 +"class='default_popup'><input type=button class=btn-bayar></a>");
							}
							c++;
						});
						/* working auto reload */
						$("#keywork").val(1);
					}
				});
				
			});
			
			$( ".auto-nis" ).autocomplete({
				source: function(request, response) { 
						var nis = $(".auto-nis").val();
						$.ajax({ 
							url		: "<?=site_url();?>pembayaran/auto_nis",
							data	: {term: nis},
							dataType: "json",
							type	: "POST",
							success	: function(data){
							response(data);
						}
					});
				},
				select: function (event, ui) {
					var id  = ui.item.id;
					var kls = ui.item.kelas;
					$("#nama-siswa").val(id);
					$("#kelas-siswa").val(kls);
					
					event.preventDefault();
					var value = ui.item.value;
					var nis = value.split(' | ');
					$("#nis_siswa").val(nis[0]);		
					$(".clt").html('');
					
					/* Change Pembayaran in list box*/
					$.getJSON('<?=site_url()?>pembayaran/change_pembayaran/'+kls,function(data){
						items = "";
						var c;
						for(c=0; c<data.length; c++){
							items += "<option value='"+data[c].id_pembayaran+"--"+data[c].harga+"--"+data[c].nama_pembayaran+"'>"+data[c].nama_pembayaran+"</option>";
						}
						$("#acc_pembayaran").html(items);
						$("#biaya_pembayaran").val(format_curr(parseFloat(data[0].harga)));
					});
				},
				minLength: 1
			});	
			
			$('#nis_siswa').bind('keyup keypress',function(){			
				$("#keywork").val(0);
			});
		});
		
		
		
				
		function format_curr(Amount){
			var DecimalSeparator = Number("1.2").toLocaleString().substr(1,1);
			var AmountWithCommas = Amount.toLocaleString();
			var arParts = String(AmountWithCommas).split(DecimalSeparator);
			var intPart = arParts[0];
			var decPart = (arParts.length > 1 ? arParts[1] : '');
			decPart = (decPart + '00').substr(0,2);

			var nom = intPart + DecimalSeparator;
			return nom;
		
		}
		
		function cutdate(xdate){
			//alert(xdate);
			var m_names = new Array("","Jan", "Feb", "Mar","Apr", "May", "Jun", "Jul", "Agu", "Sep","Okt", "Nop", "Des");
			var ydate = xdate.split('-');
			//alert(ydate);
			var thn = ydate[0];
			var bln = 1*ydate[1];
			//alert(bln);
			return m_names[bln]+" - "+thn;
		}
		
		function clear_refresh(){
			$("#keywork").val(0);
			$("#acc_pembayaran").val("");
			$("#nis_siswa").val("");
			$("#nama-siswa").val("");
			$("#kelas-siswa").val("");
		}
	</script>
	<!-- end custome script -->
	
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
	.content{
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
<div id="content" style="width:100%;">
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
	
	<span>
	<table align="left" style="width:22%;margin-right:10px">
		<tr>
			<td colspan=2>Pilih Siswa</td>
		</tr>
		<tr>
			<td>NIS</td>
			<td style="text-align:left;">
				<input type="text" id="nis_siswa" class="auto-nis" style="width:160px">
					<input type="button" class="btn-pointer" id="get_siswabayar">
			</td>
		</tr>
		<tr>
			<td>Nama</td>
			<td style="text-align:left;">
				<input type="text" id="nama-siswa" style="width:160px" readonly>
				<input type="hidden" id="kelas-siswa">
			</td>
		</tr>
	</table>
	</span>
	<span>
	<table align="left" style="width:25%;">
		<tr>
			<td colspan=2>Detail Pembayaran</td>
		</tr>
		<tr>
			<td>Pembayaran</td>
			<td style="text-align:left;">
				<select name="" id="acc_pembayaran" style="width:180px;height:22px">
				<select>
				
			</td>
		</tr>
		<tr>
			<td>Biaya</td>
			<td style="text-align:left;"><input type="text" id="biaya_pembayaran" style="width:175px"></td>
		</tr>
	</table>
	</span>
</div>

<!-- Type Table Pembayaran Tetap -->
<div id="periode_spp" class="content" style="width:70%;">
	<table>
		<thead>
			<tr>
				<th>No</th>
				<th>Peiode</th>
				<th>Status</th>
				<th>Tanggal Bayar</th>
				<th>Pilihan</th>

			</tr>
		</thead>
			<?php for($r=1; $r<=12; $r++){?>
			<tr style="height:27px;padding:1px;">
				<td style="width:30px"><span class="clt No<?=$r?>"></span></td>
				<td style="width:130px"><span class="clt BuyDate<?=$r?>"></span></td>				
				<td><span class="clt Sta<?=$r?>"></span></td>
				<td style="width:130px"><span class="clt Tgl<?=$r?>"></span></td>
				<td style="width:120px"><span class="clt Byr<?=$r?>"></span></td>
			</tr>		
			<?php } ?>
	</table>
</div>

<!-- object helper -->
<input type="hidden" id="keywork" value=0>
<input type="hidden" id="nama_pembayaran">
<!-- end Data SPP Content -->
<!--footer-->
<div style="width:100%; position:absolute; bottom:0; background-color:#333; margin-top:20px;">
    <div style="width:750px; height:60px; margin: 0 auto;">
    <p style="text-align:center; color:#FFF; font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif; font-size:x-small">&copy; 2014 All rights reserved. </p>
    <p style="text-align:center; color:#CCC; font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif; font-size:x-small"> sistem pembayaran sekolah terpadu </p>
    </div>
</div>
</body>
</html>

<script type="text/javascript">
function get_listpembayaran(){
	setTimeout(get_listpembayaran,3000)
	var keywork = $("#keywork").val();
	if(keywork==0){
		return false;
	}
	var nis = $("#nis_siswa").val();					/* get NIS */
	var d   = $("#acc_pembayaran").val().split('--'); 	/* get id_acc_pembayaran */
				
	/* validasi */
	if(nis==""){
		$("#nis_siswa").css("background-color","#ff6666");
		$("#nis_siswa").focus();
		return false;
	}else{
		$("#nis_siswa").css("background-color","#ffffff");
	}
	
	if($("#nama-siswa").val()==""){
		$("#nama-siswa").css("background-color","#ff6666");
		$("#nama-siswa").focus();
		return false;
	}else{
		$("#nama-siswa").css("background-color","#ffffff");
	}
	/* end of validasi */
	
	$.ajax({
		url 	: '<?=site_url();?>pembayaran/get_siswabayar',
		type	: 'post',
		data 	: {	'nis':nis,
					'idp':d[0]
		
		},
		
		success	: function(data){
			var array = JSON.parse(data);
			var c=1;
			array.forEach(function(object){
				
				var hp    				= object.harga_pembayaran;
				var buying_date 		= object.buying_date;
				var id_acc_pembayaran 	= object.id_acc_pembayaran;
				var id_detail_acc_pemb	= object.id_detail_acc_pemb;
				var jumlah_transaksi 	= object.jml_trans;
				var tgl_transaksi 		= object.tgl_trans;
				
				//alert(object.reduksi);
				if(object.reduksi){
					var reduksi_pembayaran	= parseFloat(object.reduksi);
				}else{
					var reduksi_pembayaran = 0;
				}
				
				if(tgl_transaksi){	
					$(".No"+c).html(c);
					$(".BuyDate"+c).html(cutdate(buying_date));
					if(parseFloat(hp) == parseFloat(jumlah_transaksi)+reduksi_pembayaran){
						var ket = "L";
						$(".Sta"+c).html("<b>Lunas</b>");
					}else{
						var ket = "BL";
						$(".Sta"+c).html("<i>Belum Lunas</i>");
					}
					$(".Tgl"+c).html(tgl_transaksi);
					$(".Byr"+c).html("<a data-tooltip='Detail'" 
										+"href='detail_pembayaran/"+nis+"/"+d[0]+"/"+id_detail_acc_pemb+"/"+hp+"/"+ket+"'" 
										+"class='default_popup'><input type=button class=btn-detail></a>");
				}else{
					$(".No"+c).html(c);
					$(".BuyDate"+c).html(cutdate(object.buying_date));
					$(".Sta"+c).html("Belum Ada Pembayaran");
					$(".Tgl"+c).html("-");
					$(".Byr"+c).html("<a data-tooltip='Bayar'" 
													 +"href='post_pembayaran/"
													 +encodeURIComponent($("#nama_pembayaran").val())+"/"        /*nama account*/
													 +encodeURIComponent(cutdate(object.buying_date))+"/"		 /*periode*/
													 +nis+"/"													 /*nis*/ 
													 +encodeURIComponent($("#nama-siswa").val())+"/"			 /*nama siswa*/
													 +encodeURIComponent($("#kelas-siswa").val())+"/"			 /*kelas*/
													 +d[0]+"/"													 /*idp*/
													 +id_detail_acc_pemb+"/"									 /*idap*/
													 +encodeURIComponent(hp)+"'" 								 /*harga bayar*/
													 +"class='default_popup'><input type=button class=btn-bayar></a>");
				}
				c++;
			});
		}
	});
}
</script>