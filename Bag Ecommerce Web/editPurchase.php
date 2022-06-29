<?php include('server.php');
  $purchase_id=$_GET['purchase_id'];
  $query=mysqli_query($db,"SELECT * FROM `purchase` WHERE purchase_id='$purchase_id'");
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

  <form class="form1" method="POST" enctype="multipart/form-data" action="editPurchase.php?purchase_id=<?php echo $purchase_id; ?>">
    <div class="imgcontainer">
      <h1>Edit Purchase</h1>
    </div>
      <div class="input-group">
      <label>Purchase Id</label>
      <input type="text" name="purchase_id" value="<?php echo $row['purchase_id']; ?> "readonly>
    </div>
      <div class="input-group">
      <label>User ID</label>
      <input type="text" name="id" value="<?php echo $row['id']; ?>">
    <div class="input-group">
      <label>Total Price</label>
      <input type="text" name="total_price" value="<?php echo $row['total_price']; ?>">
    </div>
      <div class="input-group">
      <label>Purchase Date</label>
      <input type="Date" name="purchase_date" value="<?php echo $row['purchase_date']; ?>">
    </div>
       <div class="input-group">
      <label>Purchase Validation</label>
      <select style="border: 0;" type="text" name="purchase_validation" value="<?php echo $row['purchase_validation'];?>" >
        <option value="Processing">Processing</option>
        <option value="Approved">Approved</option>
        <option value="Declined">Declined</option>
      </select>
    </div>
     <div class="input-group">
      <label>Bank Receipt</label>
      <input style="border: 0;" type="file" name="payment_resit" value="<?php echo $row['payment_resit'];?>" >
    </div>
    <div class="input-group4">
      <button type="submit" name="updatePurchase" class="savebtn" style="background-color:green">Save</button>
       <button type="button" onclick="history.back();" style="background-color:grey" >Back</button>
    </div>
  </form>
</div>
</body>
</html>