<?php include('server.php');
  $delivery_id=$_GET['delivery_id'];
  $query=mysqli_query($db,"SELECT * FROM `delivery` WHERE delivery_id='$delivery_id'");
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

  <form class="form1" method="POST" enctype="multipart/form-data" action="editOrder.php?delivery_id=<?php echo $delivery_id; ?>">
    <div class="imgcontainer">
      <h1>Edit Order</h1>
    </div>
      <div class="input-group">
      <label>Delivery Id</label>
      <input type="text" name="delivery_id" value="<?php echo $row['delivery_id']; ?> "readonly>
    </div>
      <div class="input-group">
      <label>User ID</label>
      <input type="text" name="id" value="<?php echo $row['id']; ?>">
    <div class="input-group">
      <label>Purchase ID</label>
      <input type="text" name="purchase_id" value="<?php echo $row['purchase_id']; ?>">
    </div>
    <div class="input-group">
      <label>Delivery Agent</label>
      <select style="border: 0;" type="text" name="delivery_agent" value="<?php echo $row['delivery_agent'];?>" >
        <option value="Processing">Processing</option>
        <option value="Pos Laju">Pos Laju</option>
        <option value="GDex">GDex</option>
        <option value="J&T Express">J&T Express</option>
        <option value="FedEx">FedEx</option>
        <option value="DHL Express">DHL Express</option>
        <option value="Canceled">Canceled</option>
      </select>
      </div>
      <div class="input-group">
      <label>Delivery Status</label>
      <select style="border: 0;" type="text" name="delivery_status" value="<?php echo $row['delivery_status'];?>" >
        <option value="Processing">Processing</option>
        <option value="Shipped">Shipped</option>
        <option value="in Transit">in Transit</option>
        <option value="Delivered">Delivered</option>
        <option value="Canceled">Canceled</option>
      </select>
    </div>
    <div class="input-group">
      <label>Delivery Address</label>
      <input type="text" name="address" value="<?php echo $row['address']; ?>">
    </div>
    <div class="input-group">
      <label>Payment Status</label>
        <select style="border: 0;" type="text" name="payment_status" value="<?php echo $row['payment_status'];?>" >
        <option value="Processing">Processing</option>
        <option value="Approved">Approved</option>
        <option value="Declined">Declined</option>
      </select>
    </div>
    <div class="input-group4">
      <button type="submit" name="updateOrder" class="savebtn" style="background-color:green">Save</button>
       <button type="button" onclick="history.back();" style="background-color:grey" >Back</button>
    </div>
  </form>
</div>
</body>
</html>