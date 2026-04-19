<?php include('server.php');
  $stock_id=$_GET['stock_id'];
  $query=mysqli_query($db,"SELECT * FROM `stock_inventory` WHERE stock_id='$stock_id'");
  $row=mysqli_fetch_array($query);
 ?>
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
  <link rel="stylesheet" type="text/css" href="asset/css/style4.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
  <meta charset="utf-8">
  <title>Home</title>
</head>
<header>
  <ul>
    <li style="float: left;">
    <h1 class="headline">My Bag Swag Shop</h1>
    <img style="margin-left :20px; margin-top:5px ;" height="80" width="120" src="asset/img/logo.png"></li>
    <li ><a onclick="if (!confirm('Are you sure you want to logout?')) return false;" href="index.php?logout='1'" style="color: red;">logout</a></li>
    <li><a><?php  if (isset($_SESSION['username'])) : ?>
                    <strong><?php echo $_SESSION['username']; ?></strong>
                    <?php endif ?>
    <li ><a href="">Cart</a></li>
    <li><a href="">Track order</a></li>
    <li class="active"><strong><a href="">Product</a></strong></li>
    <li ><a href="index.php">Home</a></li>
  </ul>
</header>

<body style="background-color: grey;">
  <form method="post" action="product.php?action=add&id=<?php echo $row["stock_id"]; ?>">  
<table>
<th style="padding: 40px; font-size: 25px;" ><?php echo $row['stock_name']; ?></th>
</tr>
  <td>
<?php echo "<img width='500' height='250' src='asset/stockImg/".$row['stock_img']."'>"; ?>
<div class="item-table">
Brand : <?php echo $row['stock_brand']; ?>
<br>
Bag Price : RM<?php echo $row['stock_price']; ?>
<br>
Category : <?php echo $row['stock_category']; ?>
<br>
Stock Avability : <?php $stock = $row['stock_quantity'];  ?> <?php if ($stock==0){
    echo "Out of Stock"; }
    else{
    echo $stock;
  }
 ?>
<br>
Description : <?php echo $row['stock_description']; ?>
<br>
Quantity     : <?php if ($stock==0) {
   
  echo "<input style='font-size: 20px'  type='number' value='1'  placeholder='1' name='item_quantity' disabled >";} 
 else{
  echo "<input style='font-size: 20px'  type='number' value='1'  placeholder='1' name='item_quantity'> ";
 }?>
</div>
  <input type="hidden" name="hidden_name" value="<?php echo $row["stock_name"]; ?>" />  
   <input type="hidden" name="hidden_price" value="<?php echo $row["stock_price"]; ?>" />
   <input type="hidden" name="hidden_img" value="<?php echo $row["stock_img"]; ?>" />  
  <input style="background-color: crimson;" class="add_cart"  type="submit" name="add_to_cart" style="margin-top:5px;" class="btn btn-success" value="Add to Cart" />
  </form> 
  <a class="add_cart" type="button" onclick="history.back();" style="background-color:grey" >Back</a>
</td>
</tr>
  </form>
  </table>
</div>
</body>
</html>