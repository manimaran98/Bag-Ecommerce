<?php
 include('server.php');
  $suppliers_id=$_GET['suppliers_id'];
 $img_query_run = mysqli_query($db,"DELETE FROM `suppliers` WHERE suppliers_id='$suppliers_id'");
  header('location:suppliers.php');

