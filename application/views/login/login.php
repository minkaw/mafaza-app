<!DOCTYPE html>
<html lang='en'>
<head><link rel="shortcut icon" href="<?=site_url()?>assets/img-web/pavicon_sd.png"/><title>Sekolah Dasar</title>
    <meta charset="UTF-8" /> 
    <title>
        HTML Document Structure
    </title>
    <link rel="stylesheet" type="text/css" href="<?php echo site_url()?>assets/css/style.css" />
</head>
<body>
<style>
	input[type=text],
	input[type=password]{
		font-family: "HelveticaNeue-Light","Helvetica Neue Light","Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;
		font-size:12px;
		color:#fff;
		font-weight:bold;
		
	}
</style>
<div style="margin-top:180px">
<form action="<?php echo site_url()?>login/check_login" method="post">
  <h1>Employer Log in</h1>
  <div class="inset">
  <p>
    <label for="email">NIK</label>
    <input type="text" name="nik" id="nik" style="color:#ffffff;background:#4f4f4f">
  </p>
  <p>
    <label for="password">PASSWORD</label>
    <input type="password" name="password" id="password" style="color:#ffffff;background:#4f4f4f">
  </p>
  <p>
    <?php
	if(@$auth_false){
		echo "<center>".$auth_false."</center>";
	}
	?>
  </p>
  </div>
  <p class="p-container">
    <!--span>Forgot password ?</span-->
    <input type="submit" name="go" id="go" value="Log in">
  </p>
</form>
</div>

</body>
</html>
