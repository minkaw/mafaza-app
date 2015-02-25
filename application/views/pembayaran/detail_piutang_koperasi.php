<script type="text/javascript">
	$(function(){
		$("#button_close").click(function(){
			$(".popup_close").trigger( "click" );
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
		
		$("#pembayaran").bind("keyup keypress",function(){
			$(this).val(numToCurr($(this).val()));
		});
		$("#button_bayar").click(function(){
			if(!confirm("Lakukan Pembayaran !!")){
				return false;
			}else{
				var idp  = [];
				var idhk = '<?=$id_hut_koperasi?>';
				var p = $("#pembayaran").val().replace(/\,/g, '');
				
				$('.get_valid:checked').each(function(){
					idp.push($(this).attr("val_id"));
				});
				
				$.ajax({
					url 	: '<?=site_url()?>pembayaran/post_piutang_koperasi',
					type 	: 'post',
					data 	: {'idhk':idhk,
							   'idp':idp,
							   'p':p},
					success : function(data){
						window.location.href="<?=site_url()?>pembayaran/pembayaran_piutang_koperasi";						
					}
				});
			}
		})
	});
</script>
<div style="margin-top:300px">
<table border=0 style="width:350px;font-size:12px;font-family:Lucida Sans Unicode,Lucida Grande,sans-serif;">
	<thead>
		<tr>
			<th colspan="4"><b>Pembayaran Hutang karyawan</b></th>
		</tr>
	</thead>
	<tr>
		<td><span style="float:left">nama</span></td>
		<td><span style="float:left"><?=urldecode($keterangan);?></span></td>
	</tr>
	<tr>
		<td><span style="float:left">Saldo Koperasi</span></td>
		<td><span style="float:left"><?=number_format(@$saldo_koperasi->total_penjualan)?></span></td>
	</tr>
	<tr>
		<td><span style="float:left">Pembayaran</span></td>
		<td style="text-align:left"><input type="text" id="pembayaran" class="normal_textbox"></td>
	</tr>
	<tr>
		<td><span>Account</span></td>
		<td><span>Sisa Pembayaran</span></td>
	</tr>
	
	<?php
		foreach($account as $i){
			$P = @$this->db->query("SELECT 
									SUM(piutang_koperasi_tbl.`jumlah_piutang`) AS jumlah_piutang 
									FROM piutang_koperasi_tbl 
									WHERE id_hutang_pembayaran = '".$id_hut_koperasi."' 
									AND id_acc_pembayaran = '".$i->id_acc_pembayaran."'")->row();
			$Q = @$this->db->query("SELECT 
									jumlah_hutang 
									FROM piutang_koperasi_tbl 
									WHERE id_hutang_koperasi = '".$id_hut_koperasi."' 
									AND id_acc_pembayaran = '".$i->id_acc_pembayaran."'")->row();
			$sisa = number_format($Q->jumlah_hutang - $P->jumlah_piutang);
			
			echo "<tr>";
			if($i->id_acc_pembayaran=="donasi"){
				echo "<td style='text-align:left'><input type='checkbox' class='get_valid' val_id='donasi'>Donasi</td>";
				echo "<td style='text-align:right'>".$sisa."</td>";
			}else if($i->id_acc_pembayaran=="koperasi"){
				echo "<td style='text-align:left'><input type='checkbox' class='get_valid' val_id='koperasi'>Koperasi</td>";
				echo "<td style='text-align:right'>".$sisa."</td>";
			}else{
				$n = $this->db->query("select nama_acc_pembayaran from account_pembayaran_tbl where id_acc_pembayaran = '".$i->id_acc_pembayaran."'")->row()->nama_acc_pembayaran;
				echo "<td style='text-align:left'><input type='checkbox' class='get_valid' val_id='".$i->id_acc_pembayaran."'>".$n."</td>";
				echo "<td style='text-align:right'>".$sisa."</td>";
			}
			echo "</tr>";
		}
	?>
	<tr>
		<th colspan=2 style="text-align:right">
			<button id="button_bayar"  class="btn-save">Bayar</button>
			<button id="button_close"  class="btn-canc">Tutup</button>
		</th>
	</tr>
</table>
</div>