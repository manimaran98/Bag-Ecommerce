<?php
/** @var string $pageTitle */
$pageTitle = $pageTitle ?? 'Login';
?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo bag_h($pageTitle); ?></title>
  <link rel="stylesheet" type="text/css" href="assets/css/style2.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
</head>
<body style="background-image: url('assets/img/bg1.jpg');" >

  <div class="Title"> <h1 style="color: honeydew;">My Bag Swag Enterprice</h1></div>
  <div class="header1">
  	<h2>Login</h2>
  </div>

  <form style="background-color: lightblue;" class="form1" method="post" action="<?php echo bag_url('login'); ?>">
  	<?php require bag_project_root() . '/errors.php'; ?>
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
  		Not yet a member? <a href="<?php echo bag_url('register'); ?>">Sign up</a>
  	</p>
  </form>
</body>
</html>
