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
  <form class="form1" method="POST" action="addSuppliers.php">
        <?php include('errors.php'); ?>
    <div class="imgcontainer">
      <h1>New Item</h1>
    </div>
      <div class="input-group">
      <label>Supplier Name</label>
      <input type="text" name="supplierName">
    <div class="input-group">
      <label>Item Brand</label>
      <input type="text" name="supplierBrand">
    </div>
    <div class="input-group">
      <button type="submit" name="addSuppliers" class="savebtn" style="background-color:green">Save</button>
      <button type="button" onclick="history.back();" style="background-color:grey" >Back</button>
    </div>
  </form>
</div>
</div>
</body>
</html>