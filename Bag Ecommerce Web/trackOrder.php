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
  <link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>
  <link rel="stylesheet" type="text/css" href="asset/css/style5.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
  <meta charset="utf-8">
  <title>Track Order</title>
</head>
<header>
  <ul>
    </form>
    <li style="float: left;">
    <h1 class="headline">My Bag Swag Shop</h1>
    <img style="margin-left :20px; margin-top:5px ;" height="80" width="120" src="asset/img/logo.png"></li>
    <li ><a onclick="if (!confirm('Are you sure you want to logout?')) return false;" href="index.php?logout='1'" style="color: red;">logout</a></li>
    <li><a><?php  if (isset($_SESSION['username'])) : ?>
                    <strong><?php echo $_SESSION['username']; ?></strong>
                    <?php endif ?>
    <li><a href="cart.php">Cart</a></li>
    <li class="active"><a href="">Track order</a></li>
    <li ><strong><a href="product.php">Product</a></strong></li>
    <li ><a href="index.php">Home</a></li>
  </ul>
</header>
<body style="background-color:whitesmoke ;">
  <table>
    <th style="background-color: whitesmoke; border: none;" colspan="7"> <h1 style="color: black;">Search By ID</h1>
  <form action="" method="POST">
<form action="" method="POST">
<input type="text" name="delivery_id">
<input type="submit" name="search" value="Search">
</form>
</th>
    <tr>
      <th style="background-color:grey" colspan="7">Track Order</th>
    </tr>
    <tr>
    <th>Delivery ID</th>
    <th>Purchase ID</th>
    <th>Delivery Company</th>
    <th>Delivery Status</th>
    <th>Delivery Address</th>
    <th>Payment Status</th>
    <th>Action</th>
    </tr>
    <?php
    
  $username = mysqli_real_escape_string($db, $_SESSION['username']);
  $result = mysqli_query($db, "SELECT * FROM users WHERE username = '$username'");
  $row = mysqli_fetch_assoc($result);
  $id  = $row['id'];

$sql = "SELECT * from delivery WHERE id='$id'";
if(isset($_POST['search'])){
  $delivery_id = $_POST['delivery_id'];

if($delivery_id =="all" ||$delivery_id == ""){
  $sql = "SELECT * from delivery WHERE id='$id'";
}
else{
  $sql = "SELECT * from delivery WHERE delivery_id ='$delivery_id' AND id='$id' ";
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
<a href="resitPage.php?purchase_id=<?php echo $row['purchase_id']; ?>">Invoice</a>
</td>
</tr>
<?php
$count++;
}
} else {
echo "<tr>
<td style='padding: 50px; font-size: 30px;' colspan='7'>
Currently there is No Order Available Yet. Stay Tune For Futher Update. Thank you.
</td>
</tr>";
}
?>

  </table>
	</body>
</html>