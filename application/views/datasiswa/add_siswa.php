<link rel="stylesheet" type="text/css" href="<?php echo site_url();?>assets/css/fotobox.css">
<!-- custome css -->
<style>
	table.custome_table tr td{
		text-align:left;
		font-family: "HelveticaNeue-Light","Helvetica Neue Light","Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;
		font-size:12px;
	}
	table.custome_table td,
	table.custome_table th{
		border:1px solid #efefef;
	}

	#ui-datepicker-div { font-size: 12px; } 
</style>
<!-- End -->
<script type="text/javascript" src="<?php echo site_url();?>assets/js/not-use/jquery-ui-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo site_url();?>assets/js/hello-tgl.js"></script>
<script type="text/javascript" src="<?php echo site_url();?>assets/ajaxupload/scripts/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo site_url();?>assets/js/datasiswa.js"></script>
<script type="text/javascript">
	$(document).keyup(function(e) {
		/* close by  ecs */
		if (e.keyCode == 27){
			if(confirm("Close ?")){
				$(".popup_close").trigger( "click" );
				window.location.href = "<?=site_url()?>datasiswa";
			}
		}
	});
	
	$(function(){
		$(".button-remove").hide();
				
		$( ".kalender" ).datepicker({ 
			yearRange: "1970:2020",
			changeYear: true,
			changeMonth: true,
			dateFormat: 'dd M yy'
		});
		
		/* save by enter */
			//in active textbox
		$("input[type=text]").keypress(function(e) {
			if(e.keyCode == 13){
				if(confirm("Simpan ?")){
					$("#tambah_siswa").trigger( "click" );
				}
			}
		});
		
		$('#post_provinsi').change(function(){
			$.post("<?php echo site_url(); ?>datasiswa/get_kotamadya/"+$('#post_provinsi').val(),{},function(obj){
				$('#post_kota').html(obj);
			});
		});
		
		$('#post_kota').change(function(){
			$.post("<?php echo site_url(); ?>datasiswa/get_kecamatan/"+$('#post_kota').val(),{},function(obj){
				$('#post_kecamatan').html(obj);
			});
		});
		
		$('#post_kecamatan').change(function(){
			$.post("<?php echo site_url(); ?>datasiswa/get_kelurahan/"+$('#post_kecamatan').val(),{},function(obj){
				$('#post_kelurahan').html(obj);
			});
		});
				
		$('#photoimg1').bind('change', function(){
			var file = $('input[type="file"]').val();
			var exts = ['jpg','gif','png','jpeg'];
			if ( file ) {
				var get_ext = file.split('.');
				get_ext = get_ext.reverse();
				if ( $.inArray ( get_ext[0].toLowerCase(), exts ) > -1 ){
					$("#preview1").html('');
					$("#preview1").html('<img src="<?php echo site_url(); ?>assets/ajaxupload/loading/loader.gif" height="10px" width="100px" alt="Uploading...."/>');
					$("#imageform1").ajaxForm({
						success: function(responseText){
							//tampilkan gambar
							var splitbycoma = responseText.split(',,');
							var images   = splitbycoma[0];
							
							$("#preview1").html('<img src="<?php echo site_url();?>assets/ajaxupload/slider/'+images+'" height="110px" width="100px" />');
							$("#images_siswa").val(images);
							$(".button-remove").show();
							$(".button-file").hide();
						}
					}).submit();
				}else{
					alert('File '+get_ext[0]+' tidak di ijinkan');
				}
			}
		});
		
		$(".button-remove").click(function(){
			if(confirm("Hapus ?")){
				var img = $("#images_siswa").val();
				$.getJSON('<?=site_url()?>datasiswa/unlink_images/'+img,function(images){
					
				});
				$(".button-remove").hide();
				$(".button-file").show();
				$("#preview1").html('');
				$("#images_siswa").val("");
			}
			return false;
		});
		
		$("#button_canc").click(function(){
			if(confirm("Close ?")){
				$(".popup_close").trigger( "click" );
				window.location.href = "datasiswa";
			}
			return false;
		});
		/*
			default show-hide
		*/

		$(".formortu").hide();
		$(".formwali").hide();
		$(".formbiaya").hide();
		
		/* Show form Siswa */
		$("#form_siswa").click(function(){
			$(".formsiswa").show();
			$(".formortu").hide();
			$(".formwali").hide();
			$(".formbiaya").hide();
		});
				
		/* Show form Siswa */
		$("#form_ortu").click(function(){
			$(".formsiswa").hide();
			$(".formortu").show();
			$(".formwali").hide();
			$(".formbiaya").hide();
		});
		
		/* Show form wali */
		$("#form_wali").click(function(){
			$(".formsiswa").hide();
			$(".formortu").hide();
			$(".formwali").show();
			$(".formbiaya").hide();
		});
		
		/* Show form Biaya */
		$("#form_biaya").click(function(){
			$(".formsiswa").hide();
			$(".formortu").hide();
			$(".formwali").hide();
			$(".formbiaya").show();
		});
	});
</script>
<link rel="stylesheet" type="text/css" href="<?php echo site_url();?>assets/css/not-use/jquery-ui-1.11.0.css" />

<table style="width:550px;" class="custome_table">
	<thead>
		<th colspan="5"><b>INPUT DATA SISWA BARU</b></th>
	</thead>
	<tr style="height:40px">
		<td colspan="5" style="text-align:center">
			<input style="background-color:#afcaff" type="button" id="form_siswa"class="btn-datasiswa">
			<input style="background-color:#afcaff" type="button" id="form_ortu" class="btn-dataortu">
			<input style="background-color:#afcaff" type="button" id="form_wali" class="btn-datawali">
			<input style="background-color:#afcaff" type="button" id="form_biaya"class="btn-reduksi">
		</td>
	</tr>
	
	<!-- -----------
	-- form siswa --
	------------ -->
	<tr class="formsiswa">
		<td style="width:130px;">NIS</td>
		<td style="width:240px"><input type="text" class="normal_textbox" id="nis" readonly ></td>
		<td rowspan=4 style="width:130px">
			<form name="f1" action='<?php echo site_url(); ?>datasiswa/upload_images' id="imageform1" method="post" enctype="multipart/form-data">
			<div class="inner-box-foto">
				<div id='preview1'></div>
				<input type="hidden" name="images_siswa" id="images_siswa"/>
				<input type="button" class="button-remove">
				<div style="margin-top:90px;margin-left:1px" class="file-wrapper bawah tengah">
					<input type="file" name="userfile" id="photoimg1" />
					<span class="button-file">upload foto</span>
				</div>
			</div>
			</form>
		</td>
	</tr>
	<tr class="formsiswa">
		<td>Kelas</td>
		<td>
			<select class="class_select1" id="kls">
				<?php 
					$kelas = $this->db->query("select * from kelas_tbl")->result();
					foreach($kelas as $k){
				?>
				<option value="<?=$k->id_kelas;?>"><?=$k->nama_kelas;?></option>
				<?php } ?>
			</select>
		</td>
	</tr>
	<tr class="formsiswa">
		<td>Nama Lengkap</td>
		<td><input type="text" class="normal_textbox" id="nama_siswa"></td>
	</tr>
	<tr class="formsiswa">
		<td>Nama Panggilan</td>
		<td><input type="text" class="normal_textbox" id="nama_kecil"></td>
	</tr>
	<tr class="formsiswa">
		<td>Agama</td>
		<td colspan=2>
			<select class="class_select1" id="agama_siswa">
				<option value="Islam">Islam</option>
				<option value="Protestan">Protestan</option>
				<option value="Katolik">Katolik</option>
				<option value="Hindu">Hindu</option>
				<option value="Budha">Budha</option>
			</select>
		</td>
	</tr>
	<tr class="formsiswa">
		<td>Tempat Lahir</td>
		<td colspan=2><input type="text" class="normal_textbox" id="lahir_siswa"></td>
	</tr>
	<tr class="formsiswa">
		<td>Tanggal Lahir</td>
		<td colspan=2><input type="text" class="normal_textbox kalender" id="tanggal_lahir_siswa"></td>
	</tr>
	<tr class="formsiswa">
		<td>Jenis Kelamin</td>
		<td colspan=2>
			<select id="gender_siswa" class="class_select1">
				<option value="L">Laki-Laki</option>
				<option value="P">Perempuan</option>
			</select>
		</td>
		
	</tr>
	<tr class="formsiswa">
		<td>Anak Ke / dari</td>
		<td colspan=2>
			<input type="text" class="" id="anakke" style="height:25px;width:70px">
			&nbsp;&nbsp; dari &nbsp;&nbsp;
			<input type="text" class="" id="jml_sdr" style="height:25px;width:70px">
			Bersaudara
		</td>
	</tr>
	<tr class="formsiswa">
		<td>Alamat</td>
		<td colspan="2"><input type="text" class="" id="alamat" style="height:25px;width:350px"></td>
	</tr>
	<tr class="formsiswa">
		<td></td>
		<td colspan="2">
			<select id="post_provinsi" class="small_select1">
				<option value="">-- Pilih Propinsi --</option>
				<?php 
					$prop = $this->db->query("select * from propinsi_tbl")->result();
					foreach($prop as $p){
				?>
				<option value='<?=$p->id_propinsi;?>'><?=$p->nama_propinsi;?></option>
				<?php } ?>
			</select>
			<select id="post_kota" class="small_select1">
				<option value="">-- Pilih Kota --</option>
			</select>
			<br><br>
			<select id="post_kecamatan" class="small_select1">
				<option value="">-- Pilih Kecamatan --</option>
			</select>
			<select id="post_kelurahan" class="small_select1">
				<option value="">-- Pilih Kelurahan --</option>
			</select>
		</td>
	</tr>
	
	<!-- ------------- 
	-- form ORTU -----
	-------------- -->
	<tr class="formortu">
		<td colspan="3" style="background-color:#dedede"><b>Orang Tua Laki-Laki</b></td>
	</tr>
	
	<!-- AYAH -->
	<tr class="formortu">
		<td style="width:120px">Nama Ayah</td>
		<td colspan="2" style="width:240px"><input type="text" class="normal_textbox" id="nama_ayah"></td>
	</tr>

	<tr class="formortu">
		<td>Tempat Lahir</td>
		<td><input type="text" class="normal_textbox" id="lahir_ayah"></td>
	</tr>
	<tr class="formortu">
		<td>Tanggal Lahir</td>
		<td><input type="text" class="normal_textbox kalender" id="tanggal_lahir_ayah"></td>
	</tr>
	<tr class="formortu">
		<td style="width:120px">Pekerjaan</td>
		<td colspan="2" style="width:240px"><input type="text" class="normal_textbox" id="kerja_ayah"></td>
	</tr>
	<tr class="formortu">
		<td style="width:120px">Pendidikan</td>
		<td colspan="2" style="width:240px">
			<select id="pendidikan_ayah" class="class_select1">
				<option value=""> -- Pilih --</option>
				<option value="SD">SD</option>
				<option value="SLTP">SLTP</option>
				<option value="SMA">SMA / SMK / Sederajat</option>
				<option value="D3">D3</option>
				<option value="S1">S1</option>
				<option value="S2">S2</option>
				<option value="S3">S3</option>
			</select>
		</td>
	</tr>
	<tr class="formortu">
		<td colspan="3" style="background-color:#dedede"><b>Orang Tua Perempuan</b></td>
	</tr>
	
	<!-- IBU -->
	<tr class="formortu">
		<td>Nama Ibu</td>
		<td><input type="text" class="normal_textbox" id="nama_ibu"></td>
	</tr>
	<tr class="formortu">
		<td>Tempat Lahir</td>
		<td><input type="text" class="normal_textbox" id="lahir_ibu"></td>
	</tr>
	<tr class="formortu">
		<td>Tanggal Lahir</td>
		<td><input type="text" class="normal_textbox kalender" id="tanggal_lahir_ibu"></td>
	</tr>
	<tr class="formortu">
		<td style="width:120px">Pekerjaan</td>
		<td colspan="2" style="width:240px"><input type="text" class="normal_textbox" id="kerja_ibu"></td>
	</tr>
	<tr class="formortu">
		<td style="width:120px">Pendidikan</td>
		<td colspan="2" style="width:240px">
			<select id="pendidikan_ibu" class="class_select1">
				<option value=""> -- Pilih --</option>
				<option value="SD">SD</option>
				<option value="SLTP">SLTP</option>
				<option value="SMA">SMA / SMK / Sederajat</option>
				<option value="D3">D3</option>
				<option value="S1">S1</option>
				<option value="S2">S2</option>
				<option value="S3">S3</option>
			</select>
		</td>
	</tr>
	<tr class="formortu">
		<td>No. Telp / HP </td>
		<td>
			<input type="text" class="" id="telp_orangtua1" style="height:25px;width:170px">
			&nbsp; <b>/</b> &nbsp;
			<input type="text" class="" id="telp_orangtua2" style="height:25px;width:170px">
		</td>
	</tr>
	
	<!-- ---------------- 
	-- form WALI SISWA --
	----------------- -->
	<tr class="formwali">
		<td colspan="3" style="background-color:#dedede"><b>Wali Siswa</b> ( <i> di isi jika siswa tidak tinggal dengan orang tua</i> ) </td>
	</tr>

	<!-- WALI -->
	<tr class="formwali">
		<td style="width:120px">Nama Wali</td>
		<td colspan="2" style="width:240px">
			<select id="tnny" style="width:50px;height:28px;border-radius: 2px;">
				<option value="Tn">Tn</option>
				<option value="Ny">Ny</option>
			</select>.
			<input type="text" class="normal_textbox" id="nama_wali">
		</td>
	</tr>

	<tr class="formwali">
		<td>Tempat Lahir</td>
		<td><input type="text" class="normal_textbox" id="lahir_wali"></td>
	</tr>
	<tr class="formwali">
		<td>Tanggal Lahir</td>
		<td><input type="text" class="normal_textbox kalender" id="tanggal_lahir_wali"></td>
	</tr>
	<tr class="formwali">
		<td style="width:120px">Pekerjaan</td>
		<td colspan="2" style="width:240px"><input type="text" class="normal_textbox" id="kerja_wali"></td>
	</tr>
	<tr class="formwali">
		<td style="width:120px">Pendidikan</td>
		<td colspan="2" style="width:240px">
			<select id="pendidikan_wali" class="class_select1">
				<option value=""> -- Pilih --</option>
				<option value="SD">SD</option>
				<option value="SLTP">SLTP</option>
				<option value="SMA">SMA / SMK / Sederajat</option>
				<option value="D3">D3</option>
				<option value="S1">S1</option>
				<option value="S2">S2</option>
				<option value="S3">S3</option>
			</select>
		</td>
	</tr>
	<tr class="formwali">
		<td>No. Telp / HP </td>
		<td>
			<input type="text" class="" id="telp_wali1" style="height:25px;width:170px">
			&nbsp; <b>/</b> &nbsp;
			<input type="text" class="" id="telp_wali2" style="height:25px;width:170px">
		</td>
	</tr>
	
	<!-- ----------------------
	-- form Biaya Keringanan --
	----------------------- -->
	<script type="text/javascript">
		$(function(){
			$("#jenis_pembayaran").change(function(){
				var parse = $("#jenis_pembayaran").val();
				$.getJSON('<?=site_url()?>datasiswa/get_biayapembayaran/'+encodeURIComponent(parse),function(data){
					$("#bebanpembayaran").val(numToCurr(data));
				});
			});
		
			$("#biayareduksi").bind("keyup keypress",function(){
				$(this).val(numToCurr($(this).val()));
				var b = $("#bebanpembayaran").val().replace(/\,/g, '');
				var c = $("#biayareduksi").val().replace(/\,/g, '');
				$("#biayapembayaran").val(numToCurr(parseFloat(b) - parseFloat(c)));
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
	<tr class="formbiaya">
		<td colspan="5" style="background-color:#dedede"><b>Keterangan Beban Biaya Siswa</b></td>
	</tr>
	
	<tr class="formbiaya">
		<td style="width:120px">Jenis Pembayaran</td>
		<td colspan="5" style="width:240px">
			<input type="hidden" id="id_biaya_reduksi">
			<select id="jenis_pembayaran" class="class_select1">
				<option value=""> -- Pilih --</option>
				<?php
					$jenis_pembayaran = $this->db->query("SELECT id_acc_pembayaran,
																 nama_acc_pembayaran
																	FROM account_pembayaran_tbl WHERE cost_reduction = 'on'")->result();
					foreach($jenis_pembayaran as $j){
				?>
				<option value="<?=$j->id_acc_pembayaran?>-spr-<?=$j->nama_acc_pembayaran;?>"><?=$j->nama_acc_pembayaran;?></option>
				<?php } ?>
			</select>
		</td>
	</tr>
		<tr class="formbiaya">
		<td style="width:120px">Beban Pembayaran</td>
		<td colspan="5"><input type="text" id="bebanpembayaran" readonly style="height:25px;width:200px"></td>
	</tr>
	</tr>
	<tr class="formbiaya">
		<td style="width:120px">Biaya Keringanan</td>
		<td colspan="5">
			<input type="text" id="biayareduksi" style="height:25px;width:200px" readonly>
			<input type="button" class="btn-alodan" id="show_budget">
		</td>
	</tr>
	</tr>
		<tr class="formbiaya">
		<td style="width:120px">Biaya Pembayaran</td>
		<td colspan="5"><input  type="text" id="biayapembayaran" readonly style="height:25px;width:200px"></td>
	</tr>
	<script type="text/javascript">
	$(function(){
		$("#tambahReduksi").click(function(){
			var nis = $("#nis").val();
			var idred 			= $("#id_biaya_reduksi").val();
			if(!idred){	     // for new row
				idred = 0; 	
			}else{		     // for update row
				//alert(idp_for_edit);
				//alert(nis_for_edit);
				//return false;
			}
			var jenispembayaran = $("#jenis_pembayaran").val().split('-spr-');
			var bebanpembayaran = $("#bebanpembayaran").val();
			var biayareduksi = $("#biayareduksi").val().replace(/\,/g,'');
			var biayapembayaran = $("#biayapembayaran").val();
			
			var chk = [];
			var bgt = [];
			
			if(biayareduksi==0){
				alert("Tidak ada Keringanan Biaya");
				return false;
			}
			
			/* Get all value */
				/* yg checked yg ke isi textboxnya */
			$('.get_valid:checked').each(function(){
				chk.push($(this).attr("val_id"));
			});
			$('.pos_alokasi').each(function(){
				if($(this).val()!=""){
					bgt.push($(this).val().replace(/\,/g, ''));
				}
			});
			
			$.getJSON('<?=site_url()?>datasiswa/post_biayareduksi/'+idred+'/'+jenispembayaran[0]+'/'+nis+'/'+biayareduksi+'/'+chk,function(data){
				if(data){
					//alert("Berhasil");
					var items = "";
						items += "<tr class='formbiaya' id='"+jenispembayaran[0]+nis+"' valtr='"+jenispembayaran[0]+"-spr-"+nis+"'>";
						items += "<td>"+jenispembayaran[1]+"</td>";
						items += "<td>"+bebanpembayaran+"</td>";
						items += "<td>"+biayareduksi+"</td>";
						items += "<td>"+biayapembayaran+"</td>";
						items += "<td>"+
										"<input type='button' valbp="+biayapembayaran+" valbtn='"+jenispembayaran[0]+"-spr-"+nis+"' class='btn-edit button_edit'>"+
										"<input type='button' valbp="+biayapembayaran+" valbtn='"+jenispembayaran[0]+"-spr-"+nis+"' class='btn-dele button_dele'></td>";
						items += "</tr>";
					if(idred == 0){	 // for new row
						$("#addreduksi").append(items); // after insert - new row
						var bp = $("#biayapembayaran").val().replace(/\,/g,'');
						var pt = $("#parse_totalbeban").val();
						if(pt==""){ 												/* for condition no value in textbox  */
							var total_beban = parseFloat(bp);
						}else{     													/* for condition has value in textbox */
							var bp = $("#biayapembayaran").val().replace(/\,/g,'');
							var total_beban = parseFloat(bp) + parseFloat(pt);
						}
					}else{			// for update row
						$("#"+idp_for_edit+nis_for_edit).remove();
						
						var pt = $("#parse_totalbeban").val(); 					/* total biaya - biaya yang lama  pt - valbp */
						var bp = $("#parse_biayapembayaran").val();		/* total biaya + biaya yang baru  pt + bp */
						var temp_biaya = parseFloat(pt) - parseFloat(bp);
						var bp = $("#biayapembayaran").val().replace(/\,/g,'');
						var total_beban = parseFloat(temp_biaya) + parseFloat(bp);
						$("#addreduksi").append(items);
					}
					
					$("#total_beban_biaya").html(numToCurr(total_beban));
					$("#parse_totalbeban").val(total_beban);
					$("#id_biaya_reduksi").val("");
					$("#jenis_pembayaran").val("");
					$("#bebanpembayaran").val("");
					$("#biayareduksi").val("");
					$("#biayapembayaran").val("");
					
					show_budget_status = 0;
					$(".bgt_pembayaran").hide();
				}
			});
		});
		
		/* fire edit */
		$("tbody#addreduksi").on("click",".formbiaya .button_edit",function(){
			var value = $(this).attr("valbtn").split('-spr-');
			var idp = value[0];
			var nis = value[1];
			idp_for_edit = value[0]; // public variable
			nis_for_edit = value[1]; // public variable
			$.getJSON('<?=site_url()?>datasiswa/edit_biayareduksi/'+idp+'/'+nis,function(data){
				$("#id_biaya_reduksi").val(data.id_biaya_reduksi);
				$('#jenis_pembayaran').val(data.id_acc_pembayaran+"-spr-"+data.nama_acc_pembayaran).change();
				$("#biayareduksi").val(numToCurr(data.biaya_reduksi));
				$("#biayapembayaran").val(numToCurr(data.beban_biaya));
				$("#parse_biayapembayaran").val(data.beban_biaya);
			});
		});
		
		/* fire delete */
		$("tbody#addreduksi").on("click",".formbiaya .button_dele",function(){
			var value = $(this).attr("valbtn").split('-spr-');
			var bp = $(this).attr("valbp").replace(/\,/g,'');
			var idp = value[0];
			var nis = value[1];
			$.getJSON('<?=site_url()?>datasiswa/remove_biayareduksi/'+idp+'/'+nis,function(data){
				if(data==1){
					$("#"+idp+nis).remove();
					var pt = $("#parse_totalbeban").val();
					var total_beban = parseFloat(pt) - parseFloat(bp);
					$("#total_beban_biaya").html(numToCurr(total_beban));
					$("#parse_totalbeban").val(total_beban);
				}else{
					alert("data tidak bisa di hapus!! hubungi administrator!!");
				}
			});
		});
		
		$(".button_edit").click(function(){
			var value = $(this).attr("valbtn").split('-spr-');
			var idp = value[0];
			var nis = value[1];
			idp_for_edit = value[0]; // public variable
			nis_for_edit = value[1]; // public variable
			$.getJSON('<?=site_url()?>datasiswa/edit_biayareduksi/'+idp+'/'+nis,function(data){
				$("#id_biaya_reduksi").val(data.id_biaya_reduksi);
				$('#jenis_pembayaran').val(data.id_acc_pembayaran+"-spr-"+data.nama_acc_pembayaran).change();
				$("#biayareduksi").val(numToCurr(data.biaya_reduksi));
				$("#biayapembayaran").val(numToCurr(data.beban_biaya));
				$("#parse_biayapembayaran").val(data.beban_biaya);
			});
		});
		
		$(".button_dele").click(function(){
			var value = $(this).attr("valbtn").split('-spr-');
			var idp = value[0];
			var nis = value[1];
			$.getJSON('<?=site_url()?>datasiswa/remove_biayareduksi/'+idp+'/'+nis,function(data){
				if(data==1){
					$("#"+idp+nis).remove();
					var pt = $("#parse_totalbeban").val();
					var total_beban = parseFloat(pt) - parseFloat(bp);
					$("#total_beban_biaya").html(numToCurr(total_beban));
					$("#parse_totalbeban").val(total_beban);
				}else{
					alert("data tidak bisa di hapus!! hubungi administrator!!");
				}
			});
		});
	});
	</script>
	<tr class="formbiaya">
		<td colspan="5" style="">
			<button id="tambahReduksi" class="btn-add" style="width:110px">&nbsp;&nbsp;&nbsp;Simpan Data</button>
		</td>
	</tr>
	
	</tr>
	
	<tr class="formbiaya">
		<td>Jenis Pembayaran</td>
		<td>Beban Pembayaran </td>
		<td>Biaya Keringanan</td>
		<td>Biaya Pembayaran</td>
		<td style="text-align:right">Pilihan</td>
	</tr>
	<tbody id="addreduksi"></tbody>
	<tr class="formbiaya">
		<td><span class="acc_pembayaran"></span></td>
		<td><span class="harga_pembayaran"></span></td>
		<td><span class="biaya_pembayaran"></span></td>
		<td><span class="dibayar"></span></td>
		<td><span class="btnpilihan"></span></td>
	</tr>
	<tr class="formbiaya">
		<td colspan=3 style="text-align:right"><b><i>Total Beban Biaya</b></i></td>
		<!-- start total -->
		<td style="text-align:right">
			<input type="hidden" id="parse_biayapembayaran">
			<input type="hidden" id="parse_totalbeban">
			<span id="total_beban_biaya"></span>
		</td>
		<td></td>
		<!-- end total -->
	</tr>
	<tr>
		<td colspan="5">
			<button id="tambah_siswa" class="btn-save" >Simpan</button>
			<button id="button_canc"  class="btn-canc">Tutup</button>
		</td>
	</tr>
</table>

<!-- Budget Biaya Subsidi Siswa -->
<script type="text/javascript">
$(function(){
	show_budget_status = 0;
	$(".bgt_pembayaran").hide();
	$(".hideme-first").hide();
	$("#show_budget").click(function(){
		if(show_budget_status==0){
			$(".bgt_pembayaran").show('slide', {direction: 'up'}, 777);
			show_budget_status=1;
		}else{
			$(".bgt_pembayaran").hide('slide', {direction: 'up'}, 777);
			show_budget_status=0;
		}
		
	})
	
	/* parse */
	$(".get_valid").click(function(){
		vidp = $(this).attr("val_id");
		$(".pos_alokasi").val(0);
		$("#biayareduksi").val(0);
		if($(this).is(":checked")){
			$.getJSON("<?=site_url()?>konfigurasi/get_forbudget/"+vidp, function(data){
				if(data>0){
					$(".bud"+vidp).val(numToCurr(data));
				}else{
					$(".bud"+vidp).val(0);
				}
			});
			
			$(".show"+vidp).show('slide', {direction: 'left'}, 777);
			$(".alo"+vidp).focus();
		}else{
			$(".show"+vidp).hide('slide', {direction: 'left'}, 777);
			$("#biayapembayaran").val($("#bebanpembayaran").val());
			
		}
	});
	
	$(".pos_alokasi").bind("keypress keyup", function(){
		$(this).val(numToCurr($(this).val()));
		$("#biayareduksi").val(numToCurr($(this).val()));
		$("#biayapembayaran").val(parseFloat($("#bebanpembayaran").val().replace(/\,/g, '')) - parseFloat($("#biayareduksi").val().replace(/\,/g, '')));
	});
	
	function calculate_budget(){
		$('.get_valid:checked').each(function(){
			idp.push($(this).attr("val_id"));
		});
		$('.pos_alokasi').each(function(){
			if($(this).val()!=""){
				bgt.push($(this).val().replace(/\,/g, ''));
			}
		});
	}
});
</script>

<div style="position:absolute;top:25px;right:-280px;" class="bgt_pembayaran">
<table align="left" style="width:300px">
		<thead>
			<th colspan="5"><b>BUDGET PEMBAYARAN</b></th>
		</thead>
		<tr><td style="text-align:left">
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
</table>
</div>