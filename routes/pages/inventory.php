<?php 

  if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    bag_redirect_to_entry('login.php');
  }
  if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    bag_redirect_to_entry('login.php');
  }
?>
<!DOCTYPE html>
<html>
<head>
	 <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
	<title>Stock</title>
	</head>
	
<body>
	
<div id="header">

	<center><img src="assets/img/admin.png" alt = "admin" id="adminlogo" width="80px" height="70px"><br> <p style="font-family:verdana;  font-weight: bolder;color: white;">Admin Dashboard</p></center>
	
	</div>
<div class="sidebar">
	<ul style="color: whitesmoke;">
		<a href="<?php echo bag_url('admin'); ?>" style="text-decoration: none; color: aliceblue;"><li>Home</li></a>
		<a href="<?php echo bag_url('users'); ?>" style="text-decoration: none; color: aliceblue;"><li>Users</li></a>
		<a class="active" href="<?php echo bag_url('inventory'); ?>" style="text-decoration: none; color: aliceblue;"><li>Stock Inventory</li></a>
		<a href="<?php echo bag_url('suppliers'); ?>" style="text-decoration: none; color: aliceblue;"><li>Suppliers</li></a>
		<a href="<?php echo bag_url('order'); ?>" style="text-decoration: none; color: aliceblue;"><li>Order</li></a>
		<a href="<?php echo bag_url('purchase'); ?>" style="text-decoration: none; color: aliceblue;"><li>Purchase</li></a>
		<a onclick="if (!confirm('Are you sure you want to logout?')) return false;" href="<?php echo bag_url('admin', ['logout' => 1]); ?>" style="text-decoration: none; color: aliceblue;"><li>Logout</li></a>
	</ul>
	</div>

</div>
<table style="margin-top: 130px;" id="user">
	<tr>
		<th style="background-color: whitesmoke; color:black;" colspan="9">
	<h1>Search By ID</h1>
<form action="" method="POST">
<input type="text" name="stock_id">
<input type="submit" name="search" value="Search">
</form>
<form style="text-align: right;" action="<?php echo bag_url('addItem'); ?>">
<input type="submit" value="Add Item">
		</th>
	</tr>
<tr>
<th>Stock ID</th>
<th>Stock Image</th>
<th>Stock Name</th>
<th>Stock Brand</th>
<th>Stock Price</th>
<th>Stock Category</th>
<th>Stock Quantity</th>
<th>Stock Description</th>
<th>Action</th>
</tr>


<?php
$sql = 'SELECT * from stock_inventory';
if(isset($_POST['search'])){
  $stock_id = $_POST['stock_id'];

if($stock_id =="all" ||$stock_id == ""){
	$sql = 'SELECT * from stock_inventory';
}
else{
	$sql = "SELECT * from stock_inventory WHERE stock_id ='$stock_id'";
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
<?php echo $row['stock_id']; ?>
</td>
	<td>
<?php echo "<img width='100' height='60' src='assets/stockImg/".$row['stock_img']."'>"; ?>
</td>
<td>
<?php echo $row['stock_name']; ?>
</td>
<td>
<?php echo $row['stock_brand']; ?>
</td>
<td>
RM<?php echo $row['stock_price']; ?>
</td>
<td>
<?php echo $row['stock_category']; ?>
</td>
<td>
<?php echo $row['stock_quantity']; ?>
</td>
<td>
<?php echo $row['stock_description']; ?>
</td>
<td>
	<a href="<?php echo bag_url('editStock', ['stock_id' => $row['stock_id']]); ?>">Edit</a>
	<a onclick="if (!confirm('Are you sure you want to delete this Item?')) return false;" href="<?php echo bag_url('deleteStock', ['stock_id' => $row['stock_id']]); ?>">Delete</a>
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
