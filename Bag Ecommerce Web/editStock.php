<?php include('server.php');
  $stock_id=$_GET['stock_id'];
  $query=mysqli_query($db,"SELECT * FROM `stock_inventory` WHERE stock_id='$stock_id'");
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

  <form class="form1" method="POST" enctype="multipart/form-data" action="editStock.php?stock_id=<?php echo $stock_id; ?>">
    <div class="imgcontainer">
      <h1>Edit Stock</h1>
    </div>
      <div class="input-group">
      <label>Stock Id</label>
      <input type="text" name="stock_id" value="<?php echo $row['stock_id']; ?> "readonly>
    </div>
      <div class="input-group">
      <label>Stock Name</label>
      <input type="text" name="stock_name" value="<?php echo $row['stock_name']; ?>">
    <div class="input-group">
      <label>Stock Brand</label>
      <input type="text" name="stock_brand" value="<?php echo $row['stock_brand']; ?>">
    </div>
    <div class="input-group">
      <label>Stock Category</label>
      <input type="text" name="stock_category" value="<?php echo $row['stock_category']; ?>">
    </div>
      <div class="input-group">
      <label>Stock Price</label>
      <input type="text" name="stock_price" value="<?php echo $row['stock_price']; ?>">
    </div>
    <div class="input-group">
      <label>Stock Quantity</label>
      <input type="text" name="stock_quantity" value="<?php echo $row['stock_quantity']; ?>">
    </div>
    <div class="input-group">
      <label>Stock Description</label>
      <textarea rows="5" cols="60" type="text" name="stock_description"><?php echo $row['stock_description'];?></textarea>
    </div>
       <div class="input-group">
      <label>Stock Image</label>
      <input style="border: 0;" type="file" name="stock_img" value="<?php echo $row['stock_img'];?>" >
    </div>
    <div class="input-group4">
      <button type="submit" name="updateStock" class="savebtn" style="background-color:green">Save</button>
       <button type="button" onclick="history.back();" style="background-color:grey" >Back</button>
    </div>
  </form>
</div>
</body>
</html>