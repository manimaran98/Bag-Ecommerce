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
  $total=0;
  $total_quantity=0;
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
		<a class="active" href="admin.php" style="text-decoration: none; color: aliceblue;"><li>Home</li></a>
		<a href="users.php" style="text-decoration: none; color: aliceblue;"><li>Users</li></a>
		<a href="inventory.php" style="text-decoration: none; color: aliceblue;"><li>Stock Inventory</li></a>
		<a href="suppliers.php" style="text-decoration: none; color: aliceblue;"><li>Suppliers</li></a>
		<a href="order.php" style="text-decoration: none; color: aliceblue;"><li>Order</li></a>
		<a  href="purchase.php" style="text-decoration: none; color: aliceblue;"><li>Purchase</li></a>
		<a onclick="if (!confirm('Are you sure you want to logout?')) return false;" href="admin.php?logout='1'" style="text-decoration: none; color: aliceblue;"><li>Logout</li></a>
	</ul>
	</div>
</div>
<table style="margin-top: 130px;" id="user">
	<th style="background-color: white; border: none;" colspan="10"><h1 style="color:black; text-align: center;">Welcome Admin</h1></th>
	<tr>
		<th style="background-color: whitesmoke; color:black;" colspan="10">
	<h1>Sales Report</h1>
<form action="" method="POST">
	
<label>From: </label><input type="Date" name="purchase_date1">&nbsp;&nbsp;
<label>TO: </label><input type="Date" name="purchase_date2"><br><br>
<input type="submit" name="search" value="Search">
</form>

		</th>
	</tr>
<tr>
<th>Purchase Item ID</th>
<th>Purchase ID</th>
<th>User ID</th>
<th>Stock ID</th>
<th>Stock Image</th>
<th>Stock Name</th>
<th>Stock Quantity</th>
<th>Purchase Date</th>
<th>Stock Price</th>
<th>Action</th>
</tr>

<?php
try {
	
$sql = 'SELECT * from purchase_item';
if(isset($_POST['search'])){
  $purchase_date1 = $_POST['purchase_date1'];
  $purchase_date2 = $_POST['purchase_date2'];

if($purchase_date1 == null || $purchase_date2 == null){
	echo '<script>alert("Date Search Cannot be Null")</script>';
						 header("Refresh:0");
             exit();
}
else{
	$sql = "SELECT * FROM purchase_item WHERE purchase_date BETWEEN '$purchase_date1' AND '$purchase_date2'";
}
}

if (mysqli_query($db, $sql)) {
echo "";
} else {
echo "<tr>
<td style='padding: 50px; font-size: 30px;' colspan='10'>
Currently there is No Purchase  Yet.
</td>
</tr>";
echo '<script>alert("No Sales on the Selected date")</script>';
}
$count=1;
$result = mysqli_query($db, $sql);
if (mysqli_num_rows($result) > 0) {
// output data of each row
while($row = mysqli_fetch_assoc($result)) { ?>

<td>
<?php echo $row['purchase_item_id']; ?>
</td>
<td>
<?php echo $row['purchase_id']; ?>
</td>
<td>
<?php echo $row['id']; ?>
</td>
<td>
<?php echo $row['stock_id']; ?>
</td>
	<td>
<?php echo "<img width='100' height='60' src='asset/stockImg/".$row['stock_img']."'>"; ?>
</td>
<td>
<?php echo $row['stock_name']; ?>
</td>
<td>
<?php echo $row['stock_quantity']; ?>
</td>
<td>
<?php echo date("d/m/Y", strtotime($row['purchase_date'])); ?>
</td>
<td>
RM<?php echo $row['stock_price'];?>.00
</td>
<td>
	<a href="adminEdit.php?purchase_item_id=<?php echo $row['purchase_item_id']; ?>">Edit</a>
	<a onclick="if (!confirm('Are you sure you want to delete this Item?')) return false;" href="adminDelete.php?purchase_item_id=<?php echo $row['purchase_item_id']; ?>">Delete</a>
</td>
</tr>
<?php  
  $total = $total + ($row["stock_quantity"] * $row["stock_price"]);
  $total_quantity = $total_quantity + $row["stock_quantity"];
  ?>
<?php
$count++;
}
} else {
echo "<tr>
<td style='padding: 50px; font-size: 30px;' colspan='10'>
Currently there is No Purchase  Yet.
</td>
</tr>";
echo '<script>alert("No Sales Recorded in the Search Date")</script>';
}
} catch (Exception $e) {
	echo '<script>alert("Something Went Wrong")</script>';
}?>                        
    <tr>  
    <td colspan="6" align="right">Total Quantity</td> 
    <td colspan="1" align="center"><?php echo $total_quantity; ?></td>
    <td colspan="1" align="center">Total Amount</td>  
    <td colspan="1" align="center">RM <?php echo number_format($total, 2); ?></td>  
    <td><input style="background-color: lightgreen; padding: 20px;" name="payment" type="button" value="Print Report" onclick="window.print()"></td> 
    </tr>
                               
     <?php  
      ?>  
</table>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

<script type="text/javascript">

	var x = document.getElementById("hidden1");
	var y = document.getElementById("hidden2");
	function myFunction() {
  if (x.style.display === "none") {
    x.style.display = "block";
    y.style.display = "none";
  }

}

function myFunction2() {
  
  if (y.style.display === "none") {
    y.style.display = "block";
    x.style.display = "none";
  } 
}

</script>

	</body>
</html>
