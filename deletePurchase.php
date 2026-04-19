<?php
 include('server.php');
  $purchase_id = $_GET['purchase_id'];
$select=mysqli_query($db,"SELECT payment_resit FROM purchase WHERE purchase_id ='$purchase_id'");
$image=mysqli_fetch_assoc($select);
 unlink("asset/receipt/".$image['payment_resit']);
 
 $query= "DELETE FROM purchase WHERE purchase_id='$purchase_id'";
 $result = mysqli_query($db,$query);
 if($result){
    echo '<script>alert("Customer Purchase Have been Deleted.")</script>';

     $query2 = "DELETE FROM delivery WHERE purchase_id='$purchase_id'";
     $result2 = mysqli_query($db,$query2);
    header('location:purchase.php');

 }
 else{
    echo '<script>alert("Unable to Delete Customer Purchase. ")</script>';
 }
  
