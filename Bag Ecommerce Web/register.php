<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Registration</title>
   <link rel="stylesheet" type="text/css" href="asset/css/style2.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
</head>
<body style="background-image: url('asset/img/bg2.jpg');">
  <div class="header" style="margin-top: 40px; ">
      <h2>Mybag Swag Registration</h2>
  </div>
  <form style="background-color: lightblue;" class="form1" method="post" action="register.php">
  	<?php include('errors.php'); ?>
  	<div class="input-group">
  	  <label>Username</label>
  	  <input type="text" name="username" value="<?php echo $username; ?>">
  	</div>
    <div class="input-group">
      <label>Name</label>
      <input type="text" name="name" value="<?php echo $name; ?>">
    </div>
    <div class="input-group">
      <label>Mobile Number</label>
      <input type="text" name="contact" value="<?php echo $contact; ?>">
    </div>
  	<div class="input-group">
  	  <label>Address</label>
  	  <input type="text" name="address" value="<?php echo $address; ?>">
  	</div>
  	<div class="input-group">
  	  <label>Password</label>
  	  <input type="password" name="password_1">
  	</div>
  	<div class="input-group">
  	  <label>Confirm password</label>
  	  <input type="password" name="password_2">
  	</div>
  	<div class="input-group">
  	  <button type="submit" class="btn" name="reg_user">Register</button>
  	</div>
  	<p>
  		Already a member? <a href="login.php">Sign in</a>
  	</p>
  </form>
</body>
</html>