<?php //var_dump($detail);exit;?>
<script type="text/javascript">
	$(function(){
		$("#button_close").click(function(){
			$(".popup_close").trigger( "click" );
		});
	});
	$(document).keyup(function(e) {
		/* close by  ecs */
		if (e.keyCode == 27){
			$(".popup_close").trigger( "click" );		
		}
	});
</script>
<div style="margin-top:270px">
<table style="width:565px;">
	<thead>
		<tr>
			<th colspan="4"><b>Detail Alokasi Dana <?=$account?></b></th>
		</tr>
		<tr>
			<th  style="width:15px">No</th>
			<th  style="width:225px">Account Terpakai</th>
			<th  style="width:125px">Dana Alokasi</th>
			<th  style="width:200px">Tanggal Budget</th>
	</thead>
	<?php $no=1;foreach($detail as $d){?>
	<tr>
		<td><?=$no?></td>
		<td>
			<?php
				if($d->nama_acc_pembayaran){
					echo $d->nama_acc_pembayaran;
				}else{
					echo $d->id_acc_pembayaran;
				}
			?>
		</td>
		<td style="text-align:right"><?=number_format($d->budget)?></td>
		<td><?=indo_date($d->start_date)?></td>
	</tr>
	
	<?php
		$total[] = $d->budget;
		echo "<script type='text/javascript'>
				$(function(){ 
					$('#idpval".$no."').val('".$d->id_acc_pembayaran."');  
					//$('input:checkbox[val_idpop=".$d->id_acc_pembayaran."]').prop('checked','checked'); 
					$('#idaval".$no."').val('".$d->id_alokasi."');  
					}); 
			  </script>";
		$no++; 
		} 
		if(is_array($total)){
			$total = array_sum($total);
		}
	?>
	<tr>
		<th colspan="3" style="text-align:right">
			<b>Total Budget :</b> <?=number_format($total);?>
		</th>
		<th>&nbsp;</th>
	</tr>
	
	<!-- Periode akademik vs Periode Budget -->
	<?php if($budget_periode != $current_periode){ ?>
	<thead>
	<tr>
		<th colspan="4"><b>Relokasi Budget</b></th>
	</tr>
	</thead>
	<tr>
		<td colspan="3" style="text-align:left">Total Pengeluaran</td>
		<?php $tp = @$this->db->query("SELECT SUM(biaya) AS biaya FROM transaksi_tbl WHERE id_budget = '".$id_budget."'")->row()->biaya?>
		<td><?=number_format($tp)?></td>
	</tr>
	<tr>
		<td colspan="3" style="text-align:left">Sisa Budget</td>
		<?php $sb = $total - $tp;?>
		<td><?=number_format($sb)?></td>
	</tr>
	<tr>
		<td colspan="4" style="text-align:left">
			Pilihan Relokasi &nbsp;:&nbsp; 
			<select id="alokasi_sisa_budget" class="class_select1">
				<option value="reset_account">Reset Account</value>
				<option value="alokasi_ulang">Alokasi Ulang</value>
			</select>
			&nbsp;
			<button id="proses_alokasi"  class="btn-process" style="background-color:#3162dd">Proses</button>
		</td>
	</tr>
	<?php } ?>
	
	<tr>
		<th colspan="4"><button id="button_close"  class="btn-canc">Tutup</button></th>
	</tr>
</table>
	<!-- change for hardcode -->
	<input type="hidden" id="idpval1" class="get_idpval">
	<input type="hidden" id="idpval2" class="get_idpval">
	<input type="hidden" id="idpval3" class="get_idpval">
	<input type="hidden" id="idpval4" class="get_idpval">
	<input type="hidden" id="idpval5" class="get_idpval">
	<input type="hidden" id="idpval6" class="get_idpval">
	
	<input type="hidden" id="idaval1" class="get_idaval">
	<input type="hidden" id="idaval2" class="get_idaval">
	<input type="hidden" id="idaval3" class="get_idaval">
	<input type="hidden" id="idaval4" class="get_idaval">
	<input type="hidden" id="idaval5" class="get_idaval">
	<input type="hidden" id="idaval6" class="get_idaval">
</div>

<script type="text/javascript">
	$(function(){
		
		show_budget_status = 0;
		$(".bgt_pembayaran").hide();
		$(".hideme-first").hide();
		$("#alokasi_sisa_budget").change(function(){				
			if($("#alokasi_sisa_budget").val()=="alokasi_ulang"){							//------ When change alokasi ulang
				if(show_budget_status==0){
					$(".bgt_pembayaran").show('slide', {direction: 'up'}, 777);
					show_budget_status=1;
					var idp = [];
					$('.get_idpval').each(function(){
						if($(this).val()!=""){
							idp.push($(this).val());
						}
					});
					Lop=0;
					for(Lop=0; Lop<=idp.length-1; Lop++){
						$.getJSON("<?=site_url()?>konfigurasi/get_forbudget/"+idp[Lop], function(data){
							$(".bud"+idp[Lop]).val(data);
							if(data>0){
								$(".bud"+idp[Lop]).val(data);
							}else{
								$(".bud"+idp[Lop]).val(0);
							}
							$('.showpop'+idp[Lop]).show('slide', {direction: 'left'}, 777);
						});
					}	
				}
				vidp = $(this).attr("val_idpop");
				$(".pospop_alokasi").val(0);
				$("#biayareduksi").val(0);
			}else{
				$(".bgt_pembayaran").hide('slide', {direction: 'up'}, 777);
				show_budget_status=0;
			}
		});
		
		$("#proses_alokasi").click(function(){
			var asb = $("#alokasi_sisa_budget").val(); 		//alokasi budget
			var idb = '<?=$id_budget?>';					//id_budget
			var idp = [];
			var sb  = '<?=$sb?>';							//sisa budget
			var per = '<?=$detail[0]->periode;?>'			//periode akademik budget
			
			$('.get_idpval').each(function(){
				if($(this).val()!=""){
					idp.push($(this).val());
				}
			});
			
			if(asb=="reset_account"){											//Reset Account
				$.ajax({
					url 	: '<?=site_url()?>keuangan/reset_account_buget',
					type 	: 'post',
					data 	: {'idb':idb, 'idp':idp, 'per':per, 'sb':sb },
					success : function(data){
						alert(data);
						window.location.href = '<?=site_url()?>pembayaran';
					}
				});
			}else{																//Alokasi Ulang Account
				var ida = [];													/* get id alokasi */
				var chk = [];
				var bgt = [];
				
				/* id alokasi */
				$('.get_idaval').each(function(){
					if($(this).val()!=""){
						ida.push($(this).val());
					}
				});
				
				/* Get all value */
				/* yg checked yg ke isi textboxnya */
				$('.get_validpop:checked').each(function(){
					chk.push($(this).attr("val_idpop"));
				});
				
				$('.pospop_alokasi').each(function(){
					if($(this).val()!="" && $(this).val()!=0){
						bgt.push($(this).val().replace(/\,/g, ''));
					}
				});
				
				$.ajax({
					url 	: '<?=site_url()?>keuangan/relokasi_account_buget',
					type 	: 'post',
					data 	: {'idb':idb, 'idp':idp, 'ida':ida, 'bgt':bgt, 'per':per, 'sb':sb },
					success : function(data){
						alert(data);
						return false;
						window.location.href = '<?=site_url()?>pembayaran';
					}
				})
			}
		})
	})
</script>


<!-- Budget Biaya Subsidi Siswa -->
<script type="text/javascript">
$(function(){
	/* parse */
	$(".get_validpop").click(function(){
		vidp = $(this).attr("val_idpop");
		$(".pospop_alokasi").val(0);	
		if($(this).is(":checked")){
			$.getJSON("<?=site_url()?>konfigurasi/get_forbudget/"+vidp, function(data){
				if(data>0){
					$(".budpop"+vidp).val(numToCurr(data));
				}else{
					$(".budpop"+vidp).val(0);
				}
			});
			$(".showpop"+vidp).show('slide', {direction: 'left'}, 777);
			$(".alopop"+vidp).focus();
		}else{
			$(".showpop"+vidp).hide('slide', {direction: 'left'}, 777);			
		}
	});
	
	$(".pospop_alokasi").bind("keypress keyup", function(){
		$(this).val(numToCurr($(this).val()));
	});
	
	function calculate_budget(){
		$('.get_validpop:checked').each(function(){
			idp.push($(this).attr("val_idpop"));
		});
		$('.pospop_alokasi').each(function(){
			if($(this).val()!=""){
				bgt.push($(this).val().replace(/\,/g, ''));
			}
		});
	}
});
</script>
<div style="position:absolute;top:295px;right:-290px;" class="bgt_pembayaran">
<table align="left" style="width:300px">
		<thead>
			<th colspan="5"><b>BUDGET PEMBAYARAN</b></th>
		</thead>
		<tr><td style="text-align:left">
			<div style="overflow:auto;height:300px">
				<!-- DARI PEMBAYARAN SISWA -->
				<?php 
					$post_bgt = $this->db->query("select id_acc_pembayaran, nama_acc_pembayaran from account_pembayaran_tbl order by id_acc_pembayaran asc")->result();
					foreach($post_bgt as $d){
				?>
				<div style="margin-top:5px;color:#ffff00;font-weight:bold">
					<input type="checkbox" style="z-index:1" class="get_validpop" val_idpop="<?=$d->id_acc_pembayaran;?>">
					<span class="bgt_pos"><?=$d->nama_acc_pembayaran;?></span>
				</div>
					
				<div class="hideme-first showpop<?=$d->id_acc_pembayaran;?>" style="margin-top:3px;">Budget&nbsp;&nbsp;:&nbsp; <input type="text" class="budpop<?=$d->id_acc_pembayaran;?> pospop_budget" style="width:90px"></div>
				<div class="hideme-first showpop<?=$d->id_acc_pembayaran;?>" style="margin-top:3px;">Alokasi&nbsp; : &nbsp;<input type="text" 	 class="alopop<?=$d->id_acc_pembayaran;?> pospop_alokasi" style="width:90px"></div>
				<?php } ?>
				
				<!-- DARI DONASI -->
				<div style="margin-top:5px;color:#ffff00;font-weight:bold">
					<input type="checkbox" style="z-index:1" class="get_validpop" val_idpop="donasi">
					<span class="bgt_pos">Donasi</span>
				</div>
				<div class="hideme-first showpopdonasi" style="margin-top:3px;">Budget&nbsp;&nbsp;:&nbsp; <input type="text" class="budpopdonasi pospop_budget" style="width:90px"></div>
				<div class="hideme-first showpopdonasi" style="margin-top:3px;">Alokasi&nbsp; : &nbsp;<input type="text" 	 class="alopopdonasi  pospop_alokasi" style="width:90px"></div>
				
				<!-- DARI KOPERASI -->
				<div style="margin-top:5px;color:#ffff00;font-weight:bold">
					<input type="checkbox" style="z-index:1" class="get_validpop" val_idpop="koperasi">
					<span class="bgt_pos">Koperasi</span>
				</div>
				<div class="hideme-first showpopkoperasi" style="margin-top:3px;">Budget&nbsp;&nbsp;:&nbsp; <input type="text" class="budpopkoperasi pospop_budget" style="width:90px"></div>
				<div class="hideme-first showpopkoperasi" style="margin-top:3px;">Alokasi&nbsp; : &nbsp;<input type="text" 	 class="pospopkoperasi  pospop_alokasi" style="width:90px"></div>
				
			</div>
		</td></tr>
</table>
</div>