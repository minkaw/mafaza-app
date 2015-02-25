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
				var idp = [];
				var nik = '<?=$nik?>';
				var idh = '<?=$idh;?>';
				var p = $("#pembayaran").val().replace(/\,/g, '');
				
				$('.get_valid:checked').each(function(){
					idp.push($(this).attr("val_id"));
				});
				
				$.ajax({
					url 	: '<?=site_url()?>pembayaran/post_piutang',
					type 	: 'post',
					data 	: {'nik':nik,
							   'idh':idh,
							   'idp':idp,
							   'p':p},
					success : function(data){
						window.location.href="<?=site_url()?>pembayaran/pembayaran_piutang";						
					}
				});
			}
		})
	});
</script>
<div style="margin-top:300px">
<table border=0 style="width:300px;font-size:12px;font-family:Lucida Sans Unicode,Lucida Grande,sans-serif;">
	<thead>
		<tr>
			<th colspan="4"><b>Pembayaran Hutang karyawan</b></th>
		</tr>
	</thead>
	<tr style="width:300px">
		<td><span style="float:left">NIK</span></td>
		<td><span style="float:left"><?=$nik?></span></td>
	</tr>
	<tr>
		<td><span style="float:left">nama</span></td>
		<td><span style="float:left"><?=$nama?></span></td>
	</tr>
	<tr>
		<td><span style="float:left">Pembayaran</span></td>
		<td><input type="text" id="pembayaran" class="normal_textbox"></td>
	</tr>
	<tr>
		<td colspan=2><span style="float:left">Account</span></td>
	</tr>
	<tr>
		<td colspan=2>
			<?php 
				foreach($account as $i) { 
					if($i->id_acc_pembayaran=="donasi"){
						echo "<input type='checkbox' class='get_valid' val_id='donasi'>Donasi";
					}else if($i->id_acc_pembayaran=="koperasi"){
						echo "<input type='checkbox' class='get_valid' val_id='koperasi'>Koperasi";
					}else{
						$n = $this->db->query("select nama_acc_pembayaran from account_pembayaran_tbl where id_acc_pembayaran = '".$i->id_acc_pembayaran."'")->row()->nama_acc_pembayaran;
						echo "<input type='checkbox' class='get_valid' val_id='".$i->id_acc_pembayaran."'>".$n."";
					}
				}
			?>
		</td>
	</tr>
	<tr>
		<th colspan=2 style="text-align:right">
			<button id="button_bayar"  class="btn-save">Bayar</button>
			<button id="button_close"  class="btn-canc">Tutup</button>
		</th>
	</tr>
</table>
</div>