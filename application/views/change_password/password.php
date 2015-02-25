<script type="text/javascript">
	$(function(){
		$("#close_password").click(function(){
			$(".popup_close").trigger( "click" );
		});
		
		$("#update_password").click(function(){
			var L = $("#passlama").val();
			var B = $("#newpass").val();
			var U = $("#repass").val();
			
			if(L==""){
				$("#passlama").css("background-color","#de6666");
				$("#passlama").focus();
				return false;
			}
			if(B==""){
				$("#newpass").css("background-color","#de6666");
				$("#newpass").focus();
				return false;
			}
			if(U==""){
				$("#repass").css("background-color","#de6666");
				$("#repass").focus();
				return false;
			}
			
			if(B != U){
				$("#newpass").css("background-color","#de6666");
				$("#repass").css("background-color","#de6666");
				return false;
			}
			
			$.ajax({
				url 	: '<?=site_url()?>password/check_password',
				type 	: 'post',
				data 	: {'L':L, 'B':B, 'U':U},
				success : function(data){
					if(data==4){
						alert("Password lama anda salah !!");
						$("#passlama").val("");
						$("#passlama").focus();
					}else if(data==5){
						alert("Password baru tidak sama !!");
						$("#newpass").val("");
						$("#repass").val("");
						$("#newpass").focus();
					}else{
						alert(data);
						$("#passlama").val("");
						$("#newpass").val("");
						$("#repass").val("");
					}
				}
			});
		});
	});
</script>

<div id="pass_change" style="position:relative;top:150px;height:300px">
<table style="width:300px">
	<tr>
		<td style="text-align:left">
		Password Lama
		<span style="float:right">: <input type="password" id="passlama" class="class_textbox1"></span>
		</td>
	</tr>
	<tr>
		<td style="text-align:left">
		Password Baru
		<span style="float:right">: <input type="password" id="newpass" class="class_textbox1"></span>
		</td>
	</tr>
	<tr>
		<td style="text-align:left">
		Ulangi 
		<span style="float:right">: <input type="password" id="repass" class="class_textbox1"></span>
		</td>
	</tr>
	<tr id="for_update">
		<td style="text-align:right">
			<button id="update_password" class="btn-save">Simpan</button>
			<button id="close_password" class="btn-canc" >Close</button>
		</td>
	</tr>
</table>
</div>