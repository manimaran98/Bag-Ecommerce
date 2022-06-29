<?php
 include('server.php');
  $purchase_item_id = $_GET['purchase_item_id'];

 $query= "DELETE FROM purchase_item WHERE purchase_item_id='$purchase_item_id'";
 $result = mysqli_query($db,$query);
 if($result){
    echo '<script>alert("Customer Purchase Item Have been Deleted.")</script>';
    header('location:admin.php');

 }
 else{
    echo '<script>alert("Unable to Delete Customer Purchase Item. ")</script>';
 }
  
