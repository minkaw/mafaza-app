<script type="text/javascript">
$(document).keyup(function(e) {
	/* close by  ecs */
	if (e.keyCode == 27){
		$(".popup_close").trigger( "click" );		
	}
});
</script>
<table align="left" style="width:500px;">
	<thead>
		<tr>
			<th>No</th>
			<th>Nama Pembayaran</th>
			<th>Bulan</th>
			<th>Biaya (Rp)</th>
			<th>Pilihan</th>
		</tr>
	</thead>
	<?php $no=1; foreach($get_detail as $d) { ?>
	<tr>
		<td><?=$no;?></td>
		<td><?=$pembayaran;?></td>
		<td><?=date_create($d->buying_date)->format('Y-M');?></td>
		<td><?=$biaya;?></td>
	</tr>
	<?php $no++; } ?>
</table>
