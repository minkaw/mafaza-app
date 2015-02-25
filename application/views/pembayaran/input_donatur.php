<script type="text/javascript">
	$(function(){
		$("#button_close").click(function(){
			$(".popup_close").trigger( "click" );
		});
		
		$("#tambah_donatur").click(function(){
			var n = $("#nama_donatur").val();
			var h = $("#jumlah_donasi").val();
			if(n==""){
				$("#nama_donatur").css("background-color","#ff6666");
				$("#nama_donatur").focus();
				return false;
			}
			if(h==""){
				$("#jumlah_donasi").css("background-color","#ff6666");
				$("#jumlah_donasi").focus();
				return false;
			}
			
			if(!confirm("  Data tidak bisa di hapus !!! \n pastikan Data Sudah benar ?")){
				return false;
			}
			$.ajax({
				url 	: '<?=site_url()?>pembayaran/save_donasi',
				type 	: 'post',
				data 	: {'n':n,'h':h},
				success	: function(data){
					window.location.href = "<?=site_url()?>pembayaran/pembayaran_donasi";
				}
			});
		});
		
		$("#jumlah_donasi").bind("keypress keyup", function(){
			$("#jumlah_donasi").val(numToCurr($(this).val()));
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
<table style="width:350px">
	<tr>
		<th colspan=2>Tambah Items</th>
	</tr>
	<tr>
		<td style="text-align:left">Nama Donatur</td>
		<td><input type="text" id="nama_donatur" class="normal_textbox"></td>
	</tr>
	<tr>
		<td style="text-align:left">Jumlah Donasi</td>
		<td><input type="text" class="normal_textbox" id="jumlah_donasi"></td>
	</tr>
	<tr>
		<td colspan="2">
			<span style="float:right">
			<button id="tambah_donatur" class="btn-save" >Simpan</button>
			<button id="button_close"  class="btn-canc">Tutup</button>
			</span>
		</td>
	</tr>
</table>