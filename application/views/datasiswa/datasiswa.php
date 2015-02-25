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
			containerID : "datasiswa",
			previous : " ← previous",
			next : "next → ",
			links: "numeric",
			perPage : 14,
			delay : 20
			});
			
			/* popup */
			$('.default_popup').popup();
		});
		
		/* custome script */
		$(function(){
			$("#resultSearching").hide();
			$(".button_dele").click(function(){
				if(confirm("Hapus Data ?")){
					var v = $(this).attr("val_id");
					$.getJSON('<?=site_url()?>datasiswa/dele_siswa/'+v,function(data){
						if(data==1){
							alert("data tidak bisa di hapus!! hubungi administrator!!");
						}else{
							window.location.href="datasiswa";
						}
						return false;
					});
				}
			});
			
			$( ".auto-nis" ).autocomplete({
				source: function(request, response) { 
						var nis = $(".auto-nis").val();
						$.ajax({ 
							url		: "<?=site_url();?>datasiswa/auto_nis",
							data	: {term: nis},
							dataType: "json",
							type	: "POST",
							success	: function(data){
							response(data);
						}
					});
				},
				select: function (event, ui) {
					var id = ui.item.id;
			
					event.preventDefault();
					var value = ui.item.value;
					var nis = value.split(' | ');
					$(".auto-nis").val(value);
					//$(".clt").html('');
					$.getJSON('<?=site_url()?>datasiswa/post_searching/'+encodeURIComponent(nis[0]),function(data){
						$("#datasiswa").hide();
						$("#resultSearching").show();
						$(".Rnis").html(data.nis_siswa);
						$(".Rnama").html(data.nama_siswa);
						$(".Rgender").html(data.jenis_kelamin);
						$(".Ralamat").html(data.alamat);
						$(".Rtelp").html(Jsort_telp(data.telp_orangtua, data.telp_wali));
						$(".Rview").html("<a style='text-decoration:none'" 
											+"href='<?=site_url()?>datasiswa/view_siswa/"+encodeURIComponent(data.nis_siswa)+"' class='default_popup'>"
											+"<input type='button' class='btn-view button_view'>"
										    +"</a>");
						$(".Redit").html("<a style='text-decoration:none'" 
											+"href='<?=site_url()?>datasiswa/edit_siswa/"+encodeURIComponent(data.nis_siswa)+"' class='default_popup'>"
											+"<input type='button' class='btn-edit button_edit'>"
										    +"</a>");
						$(".Rdele").html("<input type='button' val_id='"+encodeURIComponent(data.nis_siswa)+"' class='btn-dele button_dele'>");
									
					});
				},
				minLength: 1
			});
			
			$("#filter_class").change(function(){
				var c = $("#filter_class").val();
				var d = $('input[name=kelas]:checked').val()
				window.location.href="<?=site_url()?>datasiswa/filter_class/"+c+"/"+d;
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
	
	 .holder {
    margin:15px 0;
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

<div id="content" style="width:100%;">
	<table>
	<tr style="height:40px;">
		<th colspan="7">
			<span class="class_label1">Pencarian : </span>
			<input type="text" class="class_textbox1 auto-nis" id="">
			<span class="class_label1"></span>
			<span class="class_label1">Kelas : </span>
			<select id="filter_class" style="height:23px;width:130px">
				<option <?php if($Myselect=="all"){ echo "selected";}?> value="all">Semua Kelas</option>
				<option <?php if($Myselect==1){ echo "selected";}?> value="1">1</option>
				<option <?php if($Myselect==2){ echo "selected";}?> value="2">2</option>
				<option <?php if($Myselect==3){ echo "selected";}?> value="3">3</option>
				<option <?php if($Myselect==4){ echo "selected";}?> value="4">4</option>
				<option <?php if($Myselect==5){ echo "selected";}?> value="5">5</option>
				<option <?php if($Myselect==5){ echo "selected";}?> value="6">6</option>
			</select>
			<input type="radio" <?php if($Mychoice=="A"){ echo "checked";}?> name="kelas" value="A">A
			<input type="radio" <?php if($Mychoice=="B"){ echo "checked";}?> name="kelas" value="B">B
			
			<a style="margin-left:280px;" href="<?=site_url()?>datasiswa/import_datasiswa" class="default_popup">
				<button class="btn-add2" style="width:130px;height:27px;background-color:#4545ab;text-align:right">Import Data</button>
			</a>
			
			<a style="text-decoration:none;float:right" href="<?=site_url()?>datasiswa/tambah_siswa" class="default_popup">
				<button class="btn-add2" style="width:130px;height:27px;background-color:#4545ab;text-align:right">Tambah Data</button>
			</a>
		</th>
	</tr>
	</table>
	<table>
		<thead>
			<tr>
				<th style="width:30px">No</th>
				<th style="width:150px">NIS Siswa</th>
				<th style="width:200px">Nama</th>
				<th style="width:100px">Jenis Kelamin</th>
				<th>Alamat</th>
				<th style="width:150px">No Telp</th>
				<th style="width:90px">Pilihan</th>
			</tr>
		</thead>
		<tbody id="datasiswa">
			<?php 
				$no=1;foreach($data_siswa as $d){
			?>
			<tr style="height:20px;">
				<td><?=$no;?></td>
				<td><?=$d->nis_siswa;?></td>
				<td style="text-align:left"><?=$d->nama_siswa;?></td>
				<td><?=$d->jenis_kelamin;?></td>
				<td style="text-align:left"><?=$d->alamat;?></td>
				<td style="text-align:left"><?=sort_telp($d->telp_orangtua,$d->telp_wali);?></td>
				<td>
					<!-- VIEW -->
					<a style="text-decoration:none" href="<?=site_url()?>datasiswa/view_siswa/<?=htmlspecialchars($d->nis_siswa);?>" class="default_popup">
						<input type="button" class="btn-view button_view">
					</a>
					<!-- EDIT -->
					<a style="text-decoration:none" href="<?=site_url()?>datasiswa/edit_siswa/<?=htmlspecialchars($d->nis_siswa);?>" class="default_popup">
						<input type="button" class="btn-edit button_edit">
					</a>
					<!-- DELETE-->
						<input type="button" val_id="<?=$d->nis_siswa;?>" class="btn-dele button_dele">
				</td>
			</tr>
			<?php $no++;
				} 
			?>
		</tbody>
		
		<!-- Result of searching -->
		<tbody id="resultSearching">
			<tr>
				<td>1</td>
				<td><span class="Rnis"></span></td>
				<td style="text-align:left"><span class="Rnama"></span></td>
				<td><span class="Rgender"></span></td>
				<td style="text-align:left"><span class="Ralamat"></span></td>
				<td style="text-align:left"><span class="Rtelp"></span></td>
				<td>
					<span class="Rview"></span>
					<span class="Redit"></span>
					<span class="Rdele"></span>
				</td>
			</tr>
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

<?php 
	# split jika ada "-spr-" atau "/"
	function sort_telp($number1,$number2){
		if($number1 == "-spr-"){			
			$n = preg_split( '/(\-spr-|\/|,)/', 'This and&this and,this' );
		}else{
			$n = preg_split( '/(\-spr-|\/|,)/', $number1 );
		}
		return $n[0];
	}
?>

<script>
	function Jsort_telp(number1, number2){
		if(number1 == "-spr-"){
			n = number2.split('-spr-');
		}else{
			n = number1.split('-spr-');
		}
		return n[0];
		
	}
</script>