<script type="text/javascript">
	$(document).keyup(function(e) {
		/* close by  ecs */
		if (e.keyCode == 27){
			$(".popup_close").trigger( "click" );		
		}
	});
	
	$(function(){
		$("#button_close").click(function(){
			$(".popup_close").trigger( "click" );
		});
		
		$("input[type=text]").keypress(function(e) {
			if(e.keyCode == 13){
				if(confirm("Simpan ?")){
					$("#tambah_item").trigger( "click" );
				}
			}
		});
		
		$("#tambah_item").click(function(){
			var n = $("#nama_item").val();
			var s = $("#satuan_item").val();
			var h = $("#harga_item").val();
			if(n==""){
				$("#nama_item").css("background-color","#ff6666");
				$("#nama_item").focus();
				return false;
			}
			if(s==""){
				$("#satuan_item").css("background-color","#ff6666");
				$("#satuan_item").focus();
				return false;
			}
			if(h==""){
				$("#harga_item").css("background-color","#ff6666");
				$("#harga_item").focus();
				return false;
			}
			
			$.ajax({
				url 	: '<?=site_url()?>koperasi/save_itemkoperasi',
				type 	: 'post',
				data 	: {'n':n,'s':s,'h':h},
				success	: function(data){
					window.location.href = "<?=site_url()?>koperasi";
				}
			});
		});
		
		$("#harga_item").bind("keypress keyup", function(){
			$("#harga_item").val(numToCurr($(this).val()));
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
		<td style="text-align:left">Nama Item</td>
		<td><input type="text" id="nama_item" class="normal_textbox"></td>
	</tr>
	<tr>
		<td style="text-align:left">Satuan</td>
		<td>
			<select class="class_select1" id="satuan_item">
				<option value="pcs">PCS</option>
				<option value="paket">Paket</option>
			</select>
		</td>
	</tr>
	<tr>
		<td style="text-align:left">harga</td>
		<td><input type="text" class="normal_textbox" id="harga_item"></td>
	</tr>
	<tr>
		<td colspan="2">
			<span style="float:right">
			<button id="tambah_item" class="btn-save" >Simpan</button>
			<button id="button_close"  class="btn-canc">Tutup</button>
			</span>
		</td>
	</tr>
</table>