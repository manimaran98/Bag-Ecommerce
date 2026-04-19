<?php
 include('server.php');
  $delivery_id=$_GET['delivery_id']; 
 $img_query_run = mysqli_query($db,"DELETE FROM `delivery` WHERE delivery_id='$delivery_id'");
  header('location:order.php');

