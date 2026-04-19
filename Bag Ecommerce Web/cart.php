<?php include('server.php') ?>
<?php 

  if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
  }
  if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
  }
?>
<!DOCTYPE html>
<html>
<head>
  <link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>
  <link rel="stylesheet" type="text/css" href="asset/css/style5.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
  <meta charset="utf-8">
  <title>Home</title>
</head>
<header>
  <ul>
    </form>
    <li style="float: left;">
    <h1 class="headline">My Bag Swag Shop</h1>
    <img style="margin-left :20px; margin-top:5px ;" height="80" width="120" src="asset/img/logo.png"></li>
    <li ><a onclick="if (!confirm('Are you sure you want to logout?')) return false;" href="index.php?logout='1'" style="color: red;">logout</a></li>
    <li><a><?php  if (isset($_SESSION['username'])) : ?>
                    <strong><?php echo $_SESSION['username']; ?></strong>
                    <?php endif ?>
    <li class="active" ><a href="cart.php">Cart</a></li>
    <li><a href="trackOrder.php">Track order</a></li>
    <li ><strong><a href="product.php">Product</a></strong></li>
    <li ><a href="index.php">Home</a></li>
  </ul>
</header>
<body style="background-color: grey;">

<table class="table table-bordered">  
                          <tr>  
                               <th>No</th>
                               <th>Bag Name</th>
                               <th>Back Image</th>   
                               <th>Quantity</th>  
                               <th>Price</th>  
                               <th>Total</th>  
                               <th>Action</th>  

                          </tr>
                          <?php 
                            if(empty($_SESSION["shopping_cart"]))  
                          {  
                              echo "<tr><td style='padding : 50px; font-size: 35px; font-style:cursive  ' colspan =7>Cart is Currently Empty. Please add Item for Purchase...</td></tr>"; }
                          ?>

                             
                          <?php   
                          if(!empty($_SESSION["shopping_cart"]))  
                          {  
                               $total = 0;
                               $count1 = 0;   
                               foreach($_SESSION["shopping_cart"] as $keys => $values)  
                               {  
                          ?>

                          <form  action="trackOrder.php" method="post" enctype="multipart/form-data">   
                          <tr>  <td><b><?php $count1++; echo $count1;?></b></td>
                               <td><b><?php echo $values["item_name"]; ?></td><b>
                               <td><?php echo "<img width='300' height='200' src='asset/stockImg/".$values['item_img']."'>"; ?></td>   
                               <td><?php echo $values["item_quantity"]; ?></td>  
                               <td>RM <?php echo $values["item_price"]; ?>.00</td>  
                               <td>RM <?php echo number_format($values["item_quantity"] * $values["item_price"], 2); ?></td>  
                               <td><a href="index.php?action=delete&id=<?php echo $values["item_id"]; ?>"><span class="text-danger">Remove</span></a></td>

                          </tr>
                          <?php  
                                    $total = $total + ($values["item_quantity"] * $values["item_price"]);

                               }  
                          ?>
                            
                          <tr>  
                               <td colspan="4" align="right">Total Amount</td>  
                               <td colspan="2" align="right">RM <?php echo number_format($total, 2); ?></td>  
                               <td><input style="background-color: lightgreen; padding: 20px;" name="payment" type="button" value="Pay Now" onclick="document.getElementById('id01').style.display='block'"></td> 
                          </tr>
                              
                             
                          <?php
                          
                          }  
                          ?>  
                     </table> 
                     
                      <div id="id01" class="modal">s
                       
                     <table style="background-color: white;" class="modal-content animate" >
                      
                      <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
                       <th colspan="2">Payment Section</th>
                        <tr><td colspan="2">Payment ID : <?php $payment_id = 'RST-'.(rand()); ?> <?php echo $payment_id ;?></td></tr>
                      <tr><td>Username <td> <?php echo $_SESSION['username']; ?></td></td></tr>
                       <tr><td>Purchase Date <td> <?php $item_date = date("Y-m-d"); ?><?php echo date("d-m-Y");;?></td></td></tr>
                       <tr>
                        <td  colspan="2">
                          <div>
                          <div  class="payment">
                            Bank Name : Maybank <br>  
                        Bank Transfer : 505116171123 <br>
                        Account Name : My Bag Swag Enterprice <br>
                        Payment will be Approved in 24 hours after Completing the Order. <br>
                        Upload your Payment Receipt here <br>
                        Save the PDF file with the Payment ID.<br>
                            <input type="hidden" name="payment_id" value="<?php echo $payment_id; ?>" />
                            <input type="hidden" name="item_date" value="<?php echo $item_date ; ?>" />
                            <input type="hidden" name="item_overall_total_price" value="<?php echo $total; ?>" />
                            <input type="file" name="payment_resit">
                            <input type="hidden" name="size" value="1000000"><br>

                        <button type="submit" class="subbtn" name="submitReceipt">Submit</button>
                        <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button> 
                        </form>
                        </div>
                        </div>   
                        </td> 

                       </tr>
                      <tr>
                      <td style="padding: 30px;">
                        Total Amount
                      </td>
                      <td style="padding: 30px;">
                       RM <?php echo $total; ?>.00
                      </td>
                      </tr>
                     </table> 
                     </div>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.tab a').on('click', function (e) {
    e.preventDefault();
    
    $(this).parent().addClass('active');
    $(this).parent().siblings().removeClass('active');
    
    var href = $(this).attr('href');
    $('.forms > form').hide();
    $(href).fadeIn(500);
  });
});

// Get the modal
var modal = document.getElementById('id01');
// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }

  }
</script>
	</body>
</html>