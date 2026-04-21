$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    bag_redirect_to_entry('users.php');
    exit;
}

$idEsc = (int) $id;
$query = mysqli_query($db, "SELECT * FROM `users` WHERE id={$idEsc} LIMIT 1");
$row = mysqli_fetch_array($query);
if (!$row) {
    bag_redirect_to_entry('users.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
	<meta charset="utf-8">
	<title>Edit</title>
</head>
<body>

  <form class="form1" method="POST" action="<?php echo bag_url('editUser', ['id' => $idEsc]); ?>">
    <div class="imgcontainer">
      <h1>Edit User</h1>
    </div>
      <div class="input-group">
      <label>ID</label>
      <input type="text" name="id" value="<?php echo bag_h((string) $row['id']); ?>" readonly>
    </div>
      <div class="input-group">
      <label>Username</label>
      <input type="text" name="username" value="<?php echo bag_h((string) $row['username']); ?>">
    </div>
    <div class="input-group">
      <label>Name</label>
      <input type="text" name="name" value="<?php echo bag_h((string) $row['name']); ?>">
    </div>
    <div class="input-group">
      <label>Contact Number</label>
      <input type="text" name="contact" value="<?php echo bag_h((string) $row['contact']); ?>">
    </div>
    <div class="input-group">
      <label>Address</label>
      <input type="text" name="address" value="<?php echo bag_h((string) $row['address']); ?>">
    </div>
    <div class="input-group">
      <label>New password</label>
      <input type="password" name="new_password" value="" placeholder="Leave blank to keep current password" autocomplete="new-password">
    </div>
    <div class="input-group4">
      <button type="submit" name="updateUser" class="savebtn" style="background-color:green">Save</button>
       <button type="button" onclick="history.back();" style="background-color:grey" >Back</button>
    </div>
  </form>
</body>
</html>
