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
	<link rel="stylesheet" type="text/css" href="asset/css/style.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
	<title>Admin Dashboard</title>
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
		<a href="suppliers.php" style="text-decoration: none; color: aliceblue;"><li>Suppliers</li></a>
		<a class="active" href="order.php" style="text-decoration: none; color: aliceblue;"><li>Order</li></a>
		<a href="purchase.php" style="text-decoration: none; color: aliceblue;"><li>Purchase</li></a>
		<a onclick="if (!confirm('Are you sure you want to logout?')) return false;" href="admin.php?logout='1'" style="text-decoration: none; color: aliceblue;"><li>Logout</li></a>
	</ul>
	</div>
	<div>
<table style="margin-top: 130px;" id="user">
	<tr>
		<th style="background-color: whitesmoke; color:black;" colspan="9">
	<h1>Search By ID</h1>
<form action="" method="POST">
<input type="text" name="delivery_id">
<input type="submit" name="search" value="Search">
</form>
<form style="text-align: right;" action="addItem.php">
		</th>
	</tr>
<tr>
<th>Delivery ID</th>
<th>User ID</th>
<th>Purchase ID</th>
<th>Delivery Agent</th>
<th>Delivery Status</th>
<th>Delivery Address</th>
<th>Payment Status</th>
<th>Action</th>
</tr>


<?php
$sql = 'SELECT * from delivery';
if(isset($_POST['search'])){
  $delivery_id = $_POST['delivery_id'];

if($delivery_id =="all" ||$delivery_id == ""){
	$sql = 'SELECT * from delivery';
}
else{
	$sql = "SELECT * from delivery WHERE delivery_id ='$delivery_id'";
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
<?php echo $row['delivery_id']; ?>
</td>
<td>
<?php echo $row['id']; ?>
</td>
<td>
<?php echo $row['purchase_id']; ?>
</td>
<td>
<?php echo $row['delivery_agent']; ?>
</td>
<td>
<?php echo $row['delivery_status']; ?>
</td>
<td>
<?php echo $row['address']; ?>
</td>
<td>
<?php echo $row['payment_status']; ?>
</td>
<td>
	<a href="editOrder.php?delivery_id=<?php echo $row['delivery_id']; ?>">Edit</a>
	<a onclick="if (!confirm('Are you sure you want to delete this Order?')) return false;" href="deleteOrder.php?delivery_id=<?php echo $row['delivery_id']; ?>">Delete</a>
</td>
</tr>
<?php
$count++;
}
} else {
echo "<tr>
<td style='padding: 50px; font-size: 30px;' colspan='8'>
Currently there is No Order Available Yet.
</td>
</tr>";
}
?>
</table>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

	</body>
</html>