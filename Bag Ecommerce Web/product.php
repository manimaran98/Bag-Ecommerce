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
  <link rel="stylesheet" type="text/css" href="asset/css/style3.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
  <meta charset="utf-8">
  <title>Home</title>
</head>
<header>
  <ul>
     <form id="myForm" action="product.php"> <li><select onchange="myFunction()" id="categorySearch" name="search" title="Type in a category">
  <option value="">Category</option>
  <option value="AllCategory">All Category</option>
  <option value="Mens">Mens</option>
  <option value="Women">Women</option>
  <option value="Sports">Sports</option>
      </select></li>
    </form>
    <li style="float: left;">
    <h1 class="headline">My Bag Swag Shop</h1>
    <img style="margin-left :20px; margin-top:5px ;" height="80" width="120" src="asset/img/logo.png"></li>
    <li ><a onclick="if (!confirm('Are you sure you want to logout?')) return false;" href="index.php?logout='1'" style="color: red;">logout</a></li>
    <li><a><?php  if (isset($_SESSION['username'])) : ?>
                    <strong><?php echo $_SESSION['username']; ?></strong>
                    <?php endif ?>
    <li ><a href="cart.php">Cart</a></li>
    <li ><a href="trackOrder.php">Track order</a></li>
    <li class="active"><strong><a href="">Product</a></strong></li>
    <li ><a href="index.php">Home</a></li>
  </ul>
</header>
<body style="background-color: grey;">
<table id="user">
<th style="padding: 40px; font-size: 25px;" colspan="3">My Bag Swag Product</th>
</tr>
<?php
$sql = 'SELECT * from stock_inventory';
if(isset($_GET['search'])){
  $stock_category = $_GET['search'];

if($stock_category =="AllCategory"){
  $sql = 'SELECT * from stock_inventory';
}
else{
  $sql = "SELECT * from stock_inventory WHERE stock_category ='$stock_category'";
}
}

if (mysqli_query($db, $sql)) {
echo "";
} else {
echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
$count=1;
$row1=0;
$result = mysqli_query($db, $sql);
if (mysqli_num_rows($result) > 0) {
// output data of each row
  $count1 = 1; 
while($row = mysqli_fetch_assoc($result)) { 
 
 if ($count == 1) {
     echo "<td>";
}
?>
<?php $stock = $row['stock_quantity'];  ?>
 <?php if ($stock):0 ?>

<form method="post" action="product.php?action=add&id=<?php echo $row["stock_id"]; ?>">
 
  <?php endif ?>
<h5 style="text-align: left; margin-left: 20px;"><?php echo $count1 ?></h5>
<br>
<?php echo "<img width='300' height='200' src='asset/stockImg/".$row['stock_img']."'>"; ?>
<div class="item-table">
<b>Product Name : <b> <?php echo $row['stock_name']; ?>
<br>
<br>
Product Price : RM<?php echo $row['stock_price']; ?>
<br>
<br>
Stock Avability : <?php if ($stock==0){
    echo "Out of Stock"; }
    else{
    echo $stock;
  }
 ?>
<br>
<br>
 Quantity     : <?php if ($stock==0) {
   
  echo "<input type='number' value='1'  placeholder='1' name='item_quantity' disabled >";} 
 else{
  echo "<input type='number' value='1'  placeholder='1' name='item_quantity'> ";
 }?>

</div>
  <a class="add_cart" href="viewStock.php?stock_id=<?php echo $row['stock_id']; ?>">Details</a>
  <input type="hidden" name="hidden_name" value="<?php echo $row["stock_name"]; ?>" />  
   <input type="hidden" name="hidden_price" value="<?php echo $row["stock_price"]; ?>" />
   <input type="hidden" name="hidden_img" value="<?php echo $row["stock_img"]; ?>" />  
  <input style="background-color: crimson;" class="add_cart"  type="submit" name="add_to_cart" style="margin-top:5px;" class="btn btn-success" value="Add to Cart" 
  />
  </form> 
</td>
<?php
$count1++;    
if ($count%2 == 1) {
     echo "</td>";
     $count=1;
     $row1++;
  }
else {
     $count++;
  }

  if ($row1 ==3) {
    echo "<tr>";
    $row1=0;
  }
}
if ($row1 > 0 ) {
    echo "<td><img src='asset/img/soon.png' width='150' height='250'><h1 style='color: #34568B;'>Coming Soon</h1></td>";
    echo "<td><img src='asset/img/soon.png' width='150' height='250'><h1 style='color: #34568B;'>Coming Soon</h1></td>";
  }

} else {
echo '0 results';
}
?>
</table>
<script>
function myFunction() {
  document.getElementById("myForm").submit();
}
</script>
</script>
</body>
</html>

