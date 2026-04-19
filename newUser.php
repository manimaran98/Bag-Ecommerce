<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="asset/css/style.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
	<meta charset="utf-8">
	<title>Edit</title>
</head>
<body>
<div class="header">
  <form class="form1" method="POST" action="newUser.php">
        <?php include('errors.php'); ?>
    <div class="imgcontainer">
      <h1>New User</h1>
    </div>
      <div class="input-group">
      <label>Username</label>
      <input type="text" name="username">
    <div class="input-group">
      <label>Name</label>
      <input type="text" name="name">
    </div>
    <div class="input-group">
      <label>Contact Number</label>
      <input type="text" name="contact">
    </div>
    <div class="input-group">
      <label>Address</label>
      <input type="text" name="address">
    </div>
    <div class="input-group">
      <label>Password</label>
      <input type="password" name="password1">
    </div>
    <div class="input-group3">
      <button type="submit" name="newUser" class="savebtn" style="background-color:green">Save</button>
      <button type="button" onclick="history.back();" style="background-color:grey" >Back</button>
    </div>
  </form>
</div>
</div>
</body>
</html>