<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><link rel="shortcut icon" href="<?=site_url()?>assets/img-web/pavicon_sd.png"/><title>Sekolah Dasar</title>
<meta http-equiv="Expires" content="Mon, 01 Jan 1990 01:00:00 GMT" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="<?php echo site_url();?>assets/js/TweenLite.min.js"></script>
<script type="text/javascript" src="<?php echo site_url();?>assets/js/TweenMax.min.js"></script>
<script type="text/javascript" src="<?php echo site_url();?>assets/js/not-use/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo site_url();?>assets/js/jquery.popup.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo site_url();?>assets/css/css-table.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo site_url();?>assets/css/css-button.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo site_url();?>assets/css/popup.css">
<script type="text/javascript">
$(function(){
	$('.default_popup').popup();
});
</script>
<style>
body{
	margin:0;
	background:url("<?=site_url()?>assets/img-web/logo_sdi.png")no-repeat;
	background-position: center 90px;
	background-color:#369;
	overflow:auto;
}

#btn1{
	background:#06F;
	height:200px;
	width:200px;
	position:relative;
	margin-left:10px;
	left:900px;
	cursor:pointer;
	
	color:#FFF;
	font-size:18px;
	font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif;
	line-height:300px;
	padding-left:10px;
	text-align:left;
	
	
}
#btn2{
	background:#C30;
	height:200px;
	width:200px;
	position:relative;
	margin-left:10px;
	left:900px;
	cursor:pointer;
	
	color:#FFF;
	font-size:18px;
	font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif;
	line-height:300px;
	padding-left:5px;
	text-align:left;
}

#btn3{
	background:#F60;
	border-color:#F60;
	height:200px;
	width:200px;
	position:relative;
	margin-left:10px;
	left:900px;
	cursor:pointer;
	
	color:#FFF;
	font-size:18px;
	font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif;
	line-height:300px;
	padding-left:5px;
	text-align:left;
}

#btn4{
	background:#C3C;
	border-color:#F60;
	height:200px;
	width:200px;
	position:relative;
	margin-left:10px;
	margin-top:30px;
	left:900px;
	cursor:pointer;
	
	color:#FFF;
	font-size:25px;
	font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif;
	line-height:300px;
	padding-left:10px;
	text-align:left;
}

#btn5{
	background:#F36;
	border-color:#F60;
	height:200px;
	width:200px;
	position:relative;
	margin-left:10px;
	margin-top:30px;
	left:900px;
	cursor:pointer;
	
	color:#FFF;
	font-size:25px;
	font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif;
	line-height:300px;
	padding-left:10px;
	text-align:left;
}

#btn6{
	background:#6C0;
	border-color:#F60;
	height:200px;
	width:200px;
	position:relative;
	margin-left:10px;
	margin-top:30px;
	left:900px;
	cursor:pointer;
	
	color:#FFF;
	font-size:25px;
	font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif;
	line-height:300px;
	padding-left:10px;
	text-align:left;
}

#content{
	background:#369;
	height:480px;
	width:750px;
	position:relative;
	top:500px;
	z-index:0;
}

#backbutton{
	position:relative;
	left:760px;
	cursor:pointer;
}

#caption{
	position:relative;
	top:-60px;
	
	color:#FFF;
	font-size:25px;
	font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif;
}

#pagecaption{
	position:relative;
	top:-60px;
	
	color:#FFF;
	font-size:25px;
	font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif;
}
</style>
</head>

<body link="#FFFFFF">
<!--header-->
<div style="width:100%; height:50px; overflow:hidden; margin: 0 auto; background-color:#333">
  <div style="width:100%; height:50px; margin: 0 auto; overflow:hidden; position:relative">
        <div id="caption" style="height:44px; margin-top:10px;float:left; margin-left:10px;"> Sistem Informasi Mafaza </div>
		<div style="float:right;margin-right:20px">
		<a class="default_popup" href="<?=site_url()?>password"><img src="<?=site_url()?>assets/img-web/change_password.png"></a>
		<a href="<?=site_url()?>logout"><img src="<?=site_url()?>assets/img-web/logout.png"></a>
		</div>
  </div>
</div>


<!--buttons-->
<!-- Menu Admin  -->
<div style="width:750px; height:450px; overflow:hidden; margin: 0 auto; position:relative; margin-top:35px;">
	<a href="#" target="main"><div id="btn1" style="float:left"> <p> Data Siswa </p> </div> </a>
    <a href="#" target="main"><div id="btn2" style="float:left"> <p> Account Pengeluaran </p> </div> </a>
    <a href="#" target="main"><div id="btn3" style="float:left"> <p> Account Pemasukan</p> </div> </a>
	<a href="#" target="main"><div id="btn4" style="float:left"> <p> Koperasi </p> </div> </a>
	<a href="#" target="main"><div id="btn5" style="float:left"> <p> Konfigurasi </p> </div> </a>
	<a href="#" target="main"><div id="btn6" style="float:left"> <p> Laporan </p> </div> </a>
    <div id="content" style="position:absolute"> <iframe name="main" frameborder=0 height=450 width=750></iframe>  </div>
</div>


<!--footer-->
<div style="width:100%; position:absolute; bottom:0; background-color:#333; margin-top:20px;">
    <div style="width:750px; height:60px; margin: 0 auto;">
    <p style="text-align:center; color:#FFF; font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif; font-size:x-small">&copy; 2014 All rights reserved. </p>
    <p style="text-align:center; color:#CCC; font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif; font-size:x-small"> sistem pembayaran sekolah terpadu </p>
    </div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		TweenLite.to($("#caption"),2,{css:{top:0},delay:1, ease:Power2.easeOut});
		TweenLite.to($("#btn1"),1,{css:{left:0},delay:1, ease:Power2.easeOut});
		TweenLite.to($("#btn2"),1,{css:{left:20},delay:1.5, ease:Power2.easeOut});
		TweenLite.to($("#btn3"),1,{css:{left:40},delay:2, ease:Power2.easeOut});
		TweenLite.to($("#btn4"),1,{css:{left:0},delay:1.5, ease:Power2.easeOut});
		TweenLite.to($("#btn5"),1,{css:{left:20},delay:2.5, ease:Power2.easeOut});
		TweenLite.to($("#btn6"),1,{css:{left:40},delay:3.0, ease:Power2.easeOut});
	});
		
	$("#btn1").click(function(){
		window.location.href = "<?php echo site_url();?>datasiswa";
	});
	  
	$("#btn2").click(function(){
		window.location.href = "<?php echo site_url();?>pembayaran";
	});
	  
	$("#btn3").click(function(){
		window.location.href = "<?php echo site_url();?>pembayaran/pembayaran_siswa";
	});

	$("#btn4").click(function(){
		window.location.href = "<?php echo site_url();?>koperasi";
	});
	
	$("#btn5").click(function(){
		window.location.href = "<?php echo site_url();?>konfigurasi";
	});
	
	$("#btn6").click(function(){
		window.location.href = "<?php echo site_url();?>laporan";
	});
  
   $("#backbutton").click(function(){
	    TweenLite.to($("#content"),1,{css:{top:500}, ease:Power2.easeIn});
		TweenLite.to($("#backbutton"),1,{css:{left:760, rotation:0}, ease:Power2.easeIn});
		TweenLite.to($("#pagecaption"),1,{css:{top:-60}, ease:Power2.easeOut});
		TweenLite.to($("#caption"),1,{css:{top:0}, ease:Power2.easeOut});
	});   
</script>	

</body>
</html>
