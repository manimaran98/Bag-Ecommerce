<?php include('server.php');
  $suppliers_id=$_GET['suppliers_id'];
  $query=mysqli_query($db,"SELECT * FROM `suppliers` WHERE suppliers_id='$suppliers_id'");
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

  <form class="form1" method="POST" action="editSuppliers.php?suppliers_id=<?php echo $suppliers_id; ?>">
    <div class="imgcontainer">
      <h1>Edit Suppliers</h1>
    </div>
      <div class="input-group">
      <label>Supplier Id</label>
      <input type="text" name="suppliers_id" value="<?php echo $row['suppliers_id']; ?> "readonly>
    </div>
      <div class="input-group">
      <label>Stock Name</label>
      <input type="text" name="suppliers_name" value="<?php echo $row['suppliers_name']; ?>">
    <div class="input-group">
      <label>Stock Brand</label>
      <input type="text" name="stock_brand" value="<?php echo $row['stock_brand']; ?>">
    </div>
    <div class="input-group4">
      <button type="submit" name="updateSuppliers" class="savebtn" style="background-color:green">Save</button>
       <button type="button" onclick="history.back();" style="background-color:grey" >Back</button>
    </div>
  </form>
</div>
</body>
</html>