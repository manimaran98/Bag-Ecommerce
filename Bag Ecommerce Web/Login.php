<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" type="text/css" href="asset/css/style2.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
</head>
<body style="background-image: url('asset/img/bg1.jpg');" >

  <div class="Title"> <h1 style="color: honeydew;">My Bag Swag Enterprice</h1></div>
  <div class="header1">
  	<h2>Login</h2>
  </div>
	 
  <form style="background-color: lightblue;" class="form1" method="post" action="login.php">
  	<?php include('errors.php'); ?>
  	<div class="input-group">
  		<label>Username</label>
  		<input type="text" name="username" >
  	</div>
  	<div class="input-group">
  		<label>Password</label>
  		<input type="password" name="password">
  	</div>
  	<div class="input-group">
  		<button type="submit" class="btn" name="login_user">Login</button>
  	</div>
  	<p>
  		Not yet a member? <a href="register.php">Sign up</a>
  	</p>
  </form>
</body>
</html>