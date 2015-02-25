<?php 
	#biaya reduksi
	if($bired){ 
		$reduksi = $bired[0]->biaya_reduksi;
	}else{ 
		$reduksi = 0;
	}
?>
<script>
$("#post_pembayaran").click(function(){
	
	var f = $("#cara_pembayaran").val();
	var g = $("#jml_bayar").val();
	if(f==""){
		$("#cara_pembayaran").css("background-color","#de6666");
		$("#cara_pembayaran").focus();
		if(g==""){
			$("#jml_bayar").css("background-color","#de6666");
			$("#jml_bayar").focus();
		}
		return false;
	}
	
	if(g=="" || g==0){
		$("#jml_bayar").css("background-color","#de6666");
		$("#jml_bayar").focus();
		return false;
	}
	
	var jb   = $("#jml_bayar").val();
	var nis  = '<?=$nis;?>';
	var idpe = '<?=$idpe;?>'; 	//id_pembayaran [pembayaran_siswa]
	var idap = '<?=$idap;?>';	//id_detail_acc_pemb [detail_acc_pemb]

	$.ajax({
		url 	:'<?=site_url()?>pembayaran/save_postpembayaran',
		type 	:'post',
		data 	:{'jb':jb,
				  'nis':nis,
				  'idpe':idpe,
				  'idap':idap,
		},
		success	:function(data){
			$(".popup_close").trigger( "click" );
		}
	});
});


/* validasi perubahan pilihan pembayaran */
$("#cara_pembayaran").change(function(){
	var f = $("#cara_pembayaran").val();
	if(f=="lunas"){
		$("#jml_bayar").val('<?=number_format($hapem - $reduksi);?>');
		$("#jml_bayar").attr("readonly", "readonly");
		$("#cara_pembayaran").css("background-color","#ffffff");
		$("#jml_bayar").css("background-color","#ffffff");
	}else{
		$("#jml_bayar").val('');
		$("#jml_bayar").prop("readonly", "");
	}
});
/* END */


$("#close_pembayaran").click(function(){
	$(".popup_close").trigger( "click" );
});

$('#jml_bayar').bind('keyup keypress',function(){			
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
</script>
<table style="width:500px;">
	<thead>
		<tr>
			<th colspan=2>Transaksi Pembayaran <?=urldecode($nmp);?> Periode <?=urldecode($per)?></th>
		</tr>
	</thead>
</table>
	<div class="partisi" style="margin-top:-13px;">
	<div style="margin-left:10px;">
		<div>
			<span>NIS</span><span>: <?=$nis?></span>
		</div>
		<div>
			<span>Nama</span><span>: <?=urldecode($nama)?></span>
		</div>
		<div>
			<span>Kelas</span><span>: <?=urldecode($kelas)?></span>
		</div>
		<div>
			<span>Biaya Pembayaran</span><span>: <?=number_format($hapem);?></span>
		</div>
		<div>
			<span>Reduksi Pembayaran</span><span>: <?=number_format($reduksi)?> </span>
		</div>
	</div>
	</div>
<table style="width:500px;margin-top:2px;">
	<tr>
		<td>Cara Pembayaran</td>
		<td style="text-align:left">
			<select id="cara_pembayaran" class="class_select1">
				<option value="">Pilih Pembayaran</option>
				<option value="lunas">Lunas</option>
				<option value="cicil">Cicil</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Jumlah Pembayaran</td>
		<td style="text-align:left">
			<input type="text" class="normal_textbox" id="jml_bayar">
		</td>
	</tr>
	<tr>
		<td colspan=2>
			<button id="post_pembayaran">Bayar</button>
			<button id="close_pembayaran">Cancel</button>
		</td>
	</tr>
</table>

<!--/form-->

<style>
	.partisi{
		background:#fefefe;
		font-family: "HelveticaNeue-Light","Helvetica Neue Light","Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;
		font-size:12px;
		font-weight:bold;
	}
</style>