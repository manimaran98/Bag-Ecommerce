<?php include('server.php') ?>
<?php 

  if (!isset($_SESSION['username'])) {
	$_SESSION['msg'] = "You must log in first";
	header('location: login.php');
  }
$purchase_id=$_GET['purchase_id'];
?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="asset/css/style6.css">
<head>
	<meta charset="utf-8">
	<title>Customer Invoice</title>
</head>
<?php

     	$username = mysqli_real_escape_string($db, $_SESSION['username']);
        $result = mysqli_query($db, "SELECT * FROM users WHERE username = '$username'");
        $row = mysqli_fetch_assoc($result);
        $id  = $row['id'];
        $name  = $row['name'];

        $result2 = mysqli_query($db, "SELECT * FROM purchase_item WHERE purchase_id = '$purchase_id' ");
        $row = mysqli_fetch_assoc($result2);
        $data = $row['purchase_date'];

       $purchase_date = date("d/m/Y", strtotime($data));

 ?>
<body style="background-color: white;">
<table>
	<tr>
		<th style="background-color: white; border:none ; color: black; font-size: 30px; text-align: right;" colspan="2" >My Bag Swag </th>
		<th style="background-color: white; border:none ;" colspan="3"><img style=" margin-left :20px; margin-top:5px; float: left;" height="80" width="120" src="asset/img/logo.png"></th>
	</tr>
	<tr>
	<th colspan="5">Purchase Invoice</th>
	</tr>
	<tr>
		<td style="text-align: left;">Customer Name:</td>
		<td colspan="5" style="text-align: left;"><?php echo $name; ?></td>
	</tr>
	<tr>
		<td style="text-align: left;">Purchase ID:</td>
		<td colspan="5"  style="text-align: left;"><?php echo $purchase_id; ?></td>
	</tr>
	<tr>
		<td  style="text-align: left;" >Purchase Date:</td>
		<td colspan="5" style="text-align: left;"><?php echo $purchase_date; ?></td>

	</tr>
	<tr>
	<td>
	Bag Image
	</td>
	<td>
	Description	
	</td>
	<td>
	Quantity	
	</td>
	<td>
	Unit Price	
	</td>
	<td>
	Total	
	</td>
	</tr>
<?php 	
$total=0;
$sql = "SELECT * FROM purchase_item WHERE id = '$id' AND purchase_id = '$purchase_id' ";
$result = mysqli_query($db, $sql);
while($row = mysqli_fetch_assoc($result)) { ?>

 <td>
 <?php echo "<img width='100' height='60' src='asset/stockImg/".$row['stock_img']."'>"; ?>
 </td>
<td>
<?php echo $row['stock_name'];?>
</td>
<td>
<?php $stock_quantity = $row['stock_quantity']; echo $stock_quantity; ?>
</td>
<td>
RM <?php $stock_price = $row['stock_price']; echo number_format ($stock_price,2);  ?>
</td>
<td>
RM <?php $total_price = ($stock_quantity*$stock_price); echo number_format($total_price, 2); ?>
</td>
</tr>                    
     <?php
     $total = $total + $total_price;
     $total_quantity = $stock_quantity+$stock_quantity;
 }
      ?>
          <tr>  
    <td colspan="4" align="right">Total Amount</td>  
    <td colspan="1" align="center">RM <?php echo number_format($total, 2); ?></td>  
    </tr>
    <td colspan="5"><input style="background-color: lightgreen; padding: 20px;" name="payment" type="button" value="Print Receipt" onclick="window.print()">
    	<input type="button" onclick="window.close();window.open('trackOrder.php');" style="background-color:grey; padding: 20px;" value="Back" ></td>
             

</table>
</body>
</html>