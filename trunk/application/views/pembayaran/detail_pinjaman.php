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
<div style="margin-top:300px">
<table style="width:565px;font-size:12px;font-family:Lucida Sans Unicode,Lucida Grande,sans-serif;">
	<thead>
		<tr>
			<th colspan="4"><b>Detail Alokasi Dana <?=$account?></b></th>
		</tr>
		<tr>
			<th colspan="4" style="text-align:left"><b>NIK &nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp; <?=$detail_pinjaman[0]->nik_pegawai;?></b></th>
		</tr>
		<tr>
			<th colspan="4" style="text-align:left"><b>Nama&nbsp;&nbsp; : &nbsp;&nbsp; <?=$detail_pinjaman[0]->nama;?></b></th>
		</tr>
		<tr>
			<th  style="width:15px">No</th>
			<th  style="width:200px">Tanggal Transaksi</th>
			<th  style="width:200px">Hutang</th>
			<th  style="width:200px">Piutang</th>
	</thead>
	<?php $no=1;foreach($detail_pinjaman as $d){?>
	<tr>
		<td><?=$no?></td>
		<td><?=indo_date($d->tgl_transaksi);?></td>
		<td><?=number_format($d->jumlah_hutang);?></td>
		<td><?=number_format($d->jumlah_piutang);?></td>
	</tr>
	
	<?php
		$total_hutang[] = $d->jumlah_hutang;
		$total_piutang[] = $d->jumlah_piutang;
		$no++; 
		} 
	?>
	<tr>
		<th colspan="2" style="text-align:right"><b>Total :</b></th>
		<th><?=number_format(array_sum($total_hutang));?></th>
		<th><?=number_format(array_sum($total_piutang));?></th>
	</tr>
	<tr>
		<th colspan="6"><button id="button_close"  class="btn-canc">Tutup</button></th>
	</tr>
</table>
</div>