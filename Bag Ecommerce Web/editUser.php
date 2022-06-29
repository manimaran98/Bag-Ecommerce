<?php include('server.php');
  $id=$_GET['id'];
  $query=mysqli_query($db,"SELECT * FROM `users` WHERE id='$id'");
  $row=mysqli_fetch_array($query);
 ?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="asset/css/style.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
	<meta charset="utf-8">
	<title>Edit</title>
</head>
<body>

  <form class="form1" method="POST" action="editUser.php?id=<?php echo $id; ?>">
    <div class="imgcontainer">
      <h1>Edit User</h1>
    </div>
      <div class="input-group">
      <label>ID</label>
      <input type="text" name="id" value="<?php echo $row['id']; ?> "readonly>
    </div>
      <div class="input-group">
      <label>Username</label>
      <input type="text" name="username" value="<?php echo $row['username']; ?>">
    <div class="input-group">
      <label>Name</label>
      <input type="text" name="name" value="<?php echo $row['name']; ?>">
    </div>
    <div class="input-group">
      <label>Contact Number</label>
      <input type="text" name="contact" value="<?php echo $row['contact']; ?>">
    </div>
    <div class="input-group">
      <label>Address</label>
      <input type="text" name="address" value="<?php echo $row['address']; ?>">
    </div>
    <div class="input-group">
      <label>Password</label>
      <input type="password" name="password" value="<?php echo $row['password'];?>"readonly >
    </div>
    <div class="input-group4">
      <button type="submit" name="updateUser" class="savebtn" style="background-color:green">Save</button>
       <button type="button" onclick="history.back();" style="background-color:grey" >Back</button>
    </div>
  </form>
</div>
</body>
</html>