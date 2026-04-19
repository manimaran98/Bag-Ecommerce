<?php include('server.php');
  $purchase_item_id=$_GET['purchase_item_id'];
  $query=mysqli_query($db,"SELECT * FROM `purchase_item` WHERE purchase_item_id='$purchase_item_id'");
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

  <form class="form1" method="POST" enctype="multipart/form-data" action="editStock.php?purchase_item_id=<?php echo $purchase_item_id; ?>">
    <div class="imgcontainer">
      <h1>Purchase Item Edit</h1>
    </div>
      <div class="input-group">
      <label>Purchase Item ID</label>
      <input type="text" name="purchase_item_id" value="<?php echo $row['purchase_item_id']; ?> "readonly>
    </div>
      <div class="input-group">
      <label>Purchase ID</label>
      <input type="text" name="purchase_id" value="<?php echo $row['purchase_id']; ?>">
    <div class="input-group">
      <label>User ID</label>
      <input type="text" name="id" value="<?php echo $row['id']; ?>">
    </div>
    <div class="input-group">
      <label>Stock ID</label>
      <input type="text" name="stock_id" value="<?php echo $row['stock_id']; ?>">
    </div>
      <div class="input-group">
      <label>Stock Name</label>
      <input type="text" name="stock_name" value="<?php echo $row['stock_name']; ?>">
    </div>
    <div class="input-group">
      <label>Stock Quantity</label>
      <input type="text" name="stock_quantity" value="<?php echo $row['stock_quantity']; ?>">
    </div>
    <div class="input-group">
      <label>Stock Price</label>
      <input type="text" name="stock_price" value="<?php echo $row['stock_price']; ?>">
    </div>
      <div class="input-group">
      <label>Purchase Date</label>
      <input type="Date" name="purchase_date" value="<?php echo $row['purchase_date']; ?>">
    </div>
    <div class="input-group4">
      <button type="submit" name="updateStockItem" class="savebtn" style="background-color:green">Save</button>
       <button type="button" onclick="history.back();" style="background-color:grey" >Back</button>
    </div>
  </form>
</div>
</body>
</html>