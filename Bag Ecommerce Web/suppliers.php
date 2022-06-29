<?php include('server.php') ?>
<?php 

  if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
  }
  if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
  }
?>
<!DOCTYPE html>
<html>
<head>
	 <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="asset/css/style.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
	<title>Stock</title>
	</head>
	
<body>
	
<div id="header">

	<center><img src="asset/img/admin.png" alt = "admin" id="adminlogo" width="80px" height="70px"><br> <p style="font-family:verdana;  font-weight: bolder;color: white;">Admin Dashboard</p></center>
	
	</div>
<div class="sidebar">
	<ul style="color: whitesmoke;">
		<a href="admin.php" style="text-decoration: none; color: aliceblue;"><li>Home</li></a>
		<a href="users.php" style="text-decoration: none; color: aliceblue;"><li>Users</li></a>
		<a href="inventory.php" style="text-decoration: none; color: aliceblue;"><li>Stock Inventory</li></a>
		<a class="active" href="suppliers.php" style="text-decoration: none; color: aliceblue;"><li>Suppliers</li></a>
		<a href="order.php" style="text-decoration: none; color: aliceblue;"><li>Order</li></a>
		<a href="purchase.php" style="text-decoration: none; color: aliceblue;"><li>Purchase</li></a>
		<a onclick="if (!confirm('Are you sure you want to logout?')) return false;" href="admin.php?logout='1'" style="text-decoration: none; color: aliceblue;"><li>Logout</li></a>
	</ul>
	</div>

	<table style="margin-top: 130px;" id="user">
	<tr>
		<th style="background-color: whitesmoke; color:black;" colspan="7">
	<h1>Search By ID</h1>
<form action="" method="POST">
<input type="text" name="suppliers_id">
<input type="submit" name="search" value="Search">
</form>
<form style="text-align: right;" action="addSuppliers.php">
<input type="submit" value="Add Supplier">
</form>
</th>
</tr>
<tr>
<th>Supplier ID</th>
<th>Supplier Name</th>
<th>Stock Brand</th>
<th>Action</th>
</tr>


<?php
$sql = 'SELECT * from suppliers';
if(isset($_POST['search'])){
  $suppliers_id = $_POST['suppliers_id'];

if($suppliers_id =="all" ||$suppliers_id == ""){
	$sql = 'SELECT * from suppliers';
}
else{
	$sql = "SELECT * from suppliers WHERE suppliers_id ='$suppliers_id'";
}
}

if (mysqli_query($db, $sql)) {
echo "";
} else {
echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
$count=1;
$result = mysqli_query($db, $sql);
if (mysqli_num_rows($result) > 0) {
// output data of each row
while($row = mysqli_fetch_assoc($result)) { ?>

<td>
<?php echo $row['suppliers_id']; ?>
</td>
<td>
<?php echo $row['suppliers_name']; ?>
</td>
<td>
<?php echo $row['stock_brand']; ?>
</td>
<td>
	<a href="editSuppliers.php?suppliers_id=<?php echo $row['suppliers_id']; ?>">Edit</a>
	<a onclick="if (!confirm('Are you sure you want to delete this Item?')) return false;" href="deleteSuppliers.php?suppliers_id=<?php echo $row['suppliers_id']; ?>">Delete</a>
</td>
</tr>
<?php
$count++;
}
} else {
echo '0 results';
}
?>
</table>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

	</body>
</html>
