<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="asset/css/style.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
	<meta charset="utf-8">
	<title>Edit</title>
</head>
<body>
<div class="header">
  <form class="form1" method="POST" action="addItem.php" enctype="multipart/form-data">
        <?php include('errors.php'); ?>
    <div class="imgcontainer">
      <h1>New Item</h1>
    </div>
      <div class="input-group">
      <label>Item Name</label>
      <input type="text" name="itemName">
    <div class="input-group">
      <label>Stock Brand</label>
      <?php  

      $sql = "SELECT stock_brand FROM suppliers";
      $result = mysqli_query($db,$sql);

      echo "<select stock_brand name = 'itemBrand'>";
      while ($row1 = mysqli_fetch_assoc($result)) {
      echo "<option value='" . $row1['stock_brand'] ."'>" . $row1['stock_brand'] ."</option>";
        }
      echo "</select>";

      ?>

    </div>
    <div class="input-group">
      <label>Item Category</label>
      <input type="text" name="itemCategory">
    </div>
    <div class="input-group">
      <label>Item Price</label>
      <input type="text" name="itemPrice">
    </div>
    <div class="input-group">
      <label>Item Quantity</label>
      <input type="text" name="itemQuantity">
    </div>
    <div class="input-group">
      <label>Item Description</label>
      <textarea type="text" name="itemDescription" rows="5" cols="60"></textarea>
    </div>
      <div class="input-group">
      <label>Item Image</label>
      <input style="border: 0;" type="file" name="itemImg">
      <input type="hidden" name="size" value="1000000">
    </div>
    <div class="input-group3">
      <button type="submit" name="addItem" class="savebtn" style="background-color:green">Save</button>
      <button type="button" onclick="history.back();" style="background-color:grey" >Back</button>
    </div>
  </form>
</div>
</div>
</body>
</html>