<?php //var_dump($get_pembayaran);exit;?>
<script type="text/javascript">
	$(function(){
		$("#post_bayar").click(function(){
			var n = $("#dopay").val();
			var o = '<?=$idacp?>';
			var p = '<?=$idp?>';
			var r = '<?=$nis?>';
			var h = '<?=$hapem?>';
			
			var total = $("span#total").html().replace(/\,/g, '');  			// replace semua coma
			var total = parseFloat(total) + parseFloat(n.replace(/\,/g, ''));	
			
			var sisa = parseFloat(h) - parseFloat(total);
			
			$.ajax({
				url 	: '<?=site_url()?>pembayaran/add_cicilan',
				type 	: 'post',
				data 	: { 'cicilan':n,
							'idacp'  :o,
							'idp'    :p,
							'nis'    :r,
				},
				success : function(data){
					$("#hidden").hide();
					$("#dopay").hide();
					$("#post_bayar").hide();
					$("#total").hide();
					$("#get_bayar").html("<span style='text-align:right'>"+numToCurr(n)+"<span>");
					$("#get_total").html("<span style='text-align:right'>"+numToCurr(total)+"<span>");
					$("#sisabayar").hide();
					$("#get_sisabayar").html(numToCurr(sisa));
				}
			});

		});
		
		$("#close_pembayaran").click(function(){
			$(".popup_close").trigger( "click" );
		});
		
		$('#dopay').bind('keyup keypress',function(){			
			$(this).val(numToCurr($(this).val()));
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
	});
</script>
<table align="left" style="width:500px;">
	<thead>
		<tr>
			<th>No</th>
			<th>Periode Pembayaran</th>
			<th>Jumlah cicilan (Rp)</th>
		</tr>
	</thead>
	<?php 
		$reduksi_biaya = 0;
		if($get_pembayaran[0]->biaya_reduksi){
	?>
	<tr>
		<td>1</td>
		<td>Reduksi Biaya</td>
		<td style="text-align:right"><?=number_format($get_pembayaran[0]->biaya_reduksi);?></td>
	</tr>
	<?php 
			$reduksi_biaya = $get_pembayaran[0]->biaya_reduksi;
			$no=2;
		}else{
			$no=1;
		} 
		?>
	<?php  foreach($get_pembayaran as $d) { ?>
	<tr>
		<td><?=$no;?></td>
		<td>Pembayaran <?=$no;?></td>
		<td style="text-align:right"><?=number_format($d->jumlah_transaksi);?></td>
	</tr>
	<?php 
		$jml_pembayaran[]  = $d->jumlah_transaksi;
		$no++; 
	}
		if(is_array($jml_pembayaran)){
			$jml_pembayaran = array_sum($jml_pembayaran);
		}
		$jml_pembayaran = $jml_pembayaran + $reduksi_biaya;
		$sisa_pembayaran = $hapem - $jml_pembayaran;
	if($jml_pembayaran<$hapem){
	?>
	<tr>
		<td><?=$no;?></td>
		<td><span id="hidden">Lakukan</span> Pembayaran <?=$no;?></td>
		<td style="text-align:right">
			<input type="text" class="normal_textbox" id="dopay">
			<span id="get_bayar"></span>
			<input type="button" id="post_bayar" class="btn-bayar">
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td colspan='3' style="text-align:right">
			<span style="float:left">
			Sisa Pembayaran :
				<span id="sisabayar"><?=number_format($sisa_pembayaran)?></span>
				<span id="get_sisabayar"></span>
			</span>
			
			Total Pembayaran : 
				<span id="total"><?=number_format($jml_pembayaran)?></span>
				<span id="get_total"></span>
		</td>
	</tr>
	<tr>
		<td colspan='3' style="text-align:right">
			<button id="close_pembayaran" class="btn-canc">Tutup</button>
		</td>
	</tr>
</table>
