<?php
 include('server.php');
  $stock_id=$_GET['stock_id'];
$select=mysqli_query($db,"SELECT stock_img FROM stock_inventory WHERE stock_id='$stock_id'");
$image=mysqli_fetch_assoc($select);
 unlink("asset/stockImg/".$image['stock_img']); 
 $img_query_run = mysqli_query($db,"DELETE FROM `stock_inventory` WHERE stock_id='$stock_id'");
  header('location:inventory.php');

