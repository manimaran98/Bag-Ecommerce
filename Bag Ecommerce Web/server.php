<?php
session_start();
// initializing variables
        $username = "";
        $username2 = "";
        $name = "";
        $address    = "";
        $contact    = "";
        $tableType = "";
        $tableNumber="";
        $seatNumber="";
        $date1="";
        $time1="";
        $errors = array();

try { // Check Connection
        

// connect to the database
        $db = mysqli_connect('localhost', 'root', '', 'bag_biz');


        } catch (Exception $e) {
        array_push($errors, "There was an Error in The Connection");
}

// REGISTER USER
        if (isset($_POST['reg_user'])) {
        // receive all input values from the form
        $username = mysqli_real_escape_string($db, $_POST['username']);
        $name = mysqli_real_escape_string($db, $_POST['name']);
        $contact = mysqli_real_escape_string($db, $_POST['contact']);
        $address = mysqli_real_escape_string($db, $_POST['address']);
        $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
        $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

        // form validation: ensure that the form is correctly filled ...
        // by adding (array_push()) corresponding error unto $errors array
        if (empty($username)) { array_push($errors, "Username is required"); }
        if (empty($name)) { array_push($errors, "Name is required"); }
        if (empty($contact)) { array_push($errors, "Contact Number is required"); }
        if (empty($address)) { array_push($errors, "Address is required"); }
        if (empty($password_1)) { array_push($errors, "Password is required"); }
        if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
        }

        // first check the database to make sure 
        // a user does not already exist with the same username and/or email
        $user_check_query = "SELECT * FROM users WHERE username='$username' OR contact='$contact' LIMIT 1";
        $result = mysqli_query($db, $user_check_query);
        $user = mysqli_fetch_assoc($result);

        if ($user) { // if user exists
        if ($user['username'] === $username) {
        array_push($errors, "Username already exists");
        }

        if ($user['contact'] === $contact) {
        array_push($errors, "Contact number already exists");
        }
        }

        // Finally, register user if there are no errors in the form
        if (count($errors) == 0) {
        $password = md5($password_1);//encrypt the password before saving in the database

        $query = "INSERT INTO users (username, name, contact, address, password) 
        VALUES('$username', '$name', '$contact', '$address', '$password')";
        mysqli_query($db, $query);
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now logged in";
        header('location: index.php');
        }
        }


// LOGIN USER
        if (isset($_POST['login_user'])) {
        $username = mysqli_real_escape_string($db, $_POST['username']);
        $password = mysqli_real_escape_string($db, $_POST['password']);

        if (empty($username)) {
        array_push($errors, "Username is required");
        }
        if (empty($password)) {
        array_push($errors, "Password is required");
        }

        if (count($errors) == 0) {
        $password = md5($password);
        $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $results = mysqli_query($db, $query);
        if (mysqli_num_rows($results) == 1) {
        $_SESSION['username'] = $username;

        if ($username == "admin") {
        $_SESSION['success'] = "You are now logged in";
        header('location: admin.php');
        }
        else{$_SESSION['success'] = "You are now logged in";
        header('location: index.php');
        }
        }
        else {
        array_push($errors, "Wrong username/password combination");
        }
        }
        }


 //Admin Add User
        if (isset($_POST['newUser'])) {
        $username = mysqli_real_escape_string($db, $_POST['username']);
        $name = mysqli_real_escape_string($db, $_POST['name']);
        $contact = mysqli_real_escape_string($db, $_POST['contact']);
        $address = mysqli_real_escape_string($db, $_POST['address']);
        $password1 = mysqli_real_escape_string($db, $_POST['password1']);

        // form validation: ensure that the form is correctly filled ...
        // by adding (array_push()) corresponding error unto $errors array
        if (empty($username)) { array_push($errors, "Username is required"); }
        if (empty($name)) { array_push($errors, "Name is required"); }
        if (empty($contact)) { array_push($errors, "Contact Number is required"); }
        if (empty($address)) { array_push($errors, "Address is required"); }
        if (empty($password1)) { array_push($errors, "Password is required"); }

        // first check the database to make sure 
        // a user does not already exist with the same username and/or email
        $user_check_query = "SELECT * FROM users WHERE username='$username' OR contact='$contact' LIMIT 1";
        $result = mysqli_query($db, $user_check_query);
        $user = mysqli_fetch_assoc($result);

        if ($user) { // if user exists
        if ($user['username'] === $username) {
        array_push($errors, "Username already exists");
        }

        if ($user['contact'] === $contact) {
        array_push($errors, "Contact number already exists");
        }
        }

        // Finally, register user if there are no errors in the form
        if (count($errors) == 0) {
        $password = md5($password1);//encrypt the password before saving in the database

        $query = "INSERT INTO users (username, name, contact, address, password) 
        VALUES('$username', '$name', '$contact', '$address', '$password')";
        mysqli_query($db, $query);
        $_SESSION['username'] = $username;
        header('location: users.php');
      }
      }      

  //Admin Update User
  if (isset($_POST['updateUser'])) {
  $id=$_GET['id'];
  $username=$_POST['username'];
  $name=$_POST['name'];
  $contact=$_POST['contact'];
  $address=$_POST['address'];
  $password=$_POST['password'];

  $test =mysqli_query($db,"UPDATE users SET username='$username', name='$name', contact='$contact' , address='$address', password='$password' WHERE id='$id'");

   if ($test) {
     echo '<script>alert("Data Updated")</script>';
     header('location:users.php');
   }

   else{
    echo '<script>alert("Not Data Updated")</script>';
  }
}

//Admin Add Stock
        if (isset($_POST['addItem'])) {
        $itemName = mysqli_real_escape_string($db, $_POST['itemName']);
        $itemBrand = mysqli_real_escape_string($db, $_POST['itemBrand']);
        $itemCategory = mysqli_real_escape_string($db, $_POST['itemCategory']);
        $itemPrice = mysqli_real_escape_string($db, $_POST['itemPrice']);
        $itemQuantity = mysqli_real_escape_string($db, $_POST['itemQuantity']);
        $itemDescription = mysqli_real_escape_string($db, $_POST['itemDescription']);
        
        $itemImg = $_FILES['itemImg']['name'];
        // Get text

        // image file directory
        $target = "asset/stockImg/".basename($itemImg);

        $result = mysqli_query($db, "SELECT * FROM stock_inventory");

        // form validation: ensure that the form is correctly filled ...
        // by adding (array_push()) corresponding error unto $errors array
        if (empty($itemName)) { array_push($errors, "Item Name is required"); }
        if (empty($itemBrand)) { array_push($errors, "Item Brand required"); }
        if (empty($itemCategory)) { array_push($errors, "Item Category is required"); }
        if (empty($itemPrice)) { array_push($errors, "Item Price is required"); }
        if (empty($itemQuantity)) { array_push($errors, "Item Quantity is required"); }
        if (empty($itemDescription)) { array_push($errors, "Item Description is required"); }


        $stock_check_query = "SELECT * FROM stock_inventory WHERE stock_name='$itemName' AND stock_brand='$itemBrand' LIMIT 1";
        $result = mysqli_query($db, $stock_check_query);
        $stock = mysqli_fetch_assoc($result);

        if ($stock) { // if user exists
        if ($stock['stock_name'] === $itemName) {
        array_push($errors, "Item Name already exists");
        }

        if ($stock['stock_brand'] === $itemBrand) {
        array_push($errors, "Item Brand number already exists");
        }
        }

        // Finally, register user if there are no errors in the form
        if (count($errors) == 0) {

        $query = "INSERT INTO stock_inventory (stock_name, stock_brand, stock_category, stock_quantity, stock_description,stock_img,stock_price) 
        VALUES('$itemName', '$itemBrand', '$itemCategory', '$itemQuantity', '$itemDescription', '$itemImg',$itemPrice)";
        mysqli_query($db, $query);

        if (move_uploaded_file($_FILES['itemImg']['tmp_name'], $target)) {
                echo '<script>alert("Image is Uploaded")</script>';
        }else{
                echo '<script>alert("Failed to upload image")</script>';
        }
        header('location: inventory.php');

        }
        }

  //Admin Update Stock
  if (isset($_POST['updateStock'])) {
  $stock_id=$_GET['stock_id'];
  $stock_name=$_POST['stock_name'];
  $stock_brand=$_POST['stock_brand'];
  $stock_category=$_POST['stock_category'];
  $stock_price=$_POST['stock_price'];
  $stock_quantity=$_POST['stock_quantity'];
  $stock_description=$_POST['stock_description'];
  $stock_img=$_FILES['stock_img']['name'];
  $target = "asset/stockImg/".basename($stock_img);

  $img_query = "SELECT * FROM stock_inventory WHERE stock_id='$stock_id'";
  $img_query_run = mysqli_query($db,$img_query);

  foreach ($img_query_run as $img_row) {
          //echo $img_row['stock_img'];

        if ($stock_img==Null) {
                $image_data = $img_row['stock_img'];
                $query =  "UPDATE stock_inventory SET stock_name='$stock_name', stock_brand='$stock_brand', stock_category='$stock_category' , stock_quantity='$stock_quantity', stock_description='$stock_description',stock_price='$stock_price' WHERE stock_id='$stock_id'";
                        $img_query_run2 = mysqli_query($db,$query);
        }

        else{

                if ($img_path = "asset/stockImg/".$img_row['stock_img']) {
                        
                        unlink($img_path);
                        $image_data = $stock_img;
                        $query =  "UPDATE stock_inventory SET stock_name='$stock_name', stock_brand='$stock_brand', stock_category='$stock_category' , stock_quantity='$stock_quantity', stock_description='$stock_description', stock_img='$stock_img',stock_price='$stock_price' WHERE stock_id='$stock_id'";
                        $img_query_run2 = mysqli_query($db,$query);
                }

        }
  }
        
        if ($img_query_run2) {

                move_uploaded_file($_FILES['stock_img']['tmp_name'], $target);
                if ($stock_img == Null) {

                echo '<script>alert("Image is Uploaded with existing Image")</script>';
                header('location: inventory.php');
                }

                else{
                echo '<script>alert("Image is Uploaded")</script>';
                header('location: inventory.php');
        
                }
        } 

        else{
                echo '<script>alert("Failed to upload image")</script>';
                header('location: inventory.php');
        }
        
        }

        //Admin Add Suppliers
        if (isset($_POST['addSuppliers'])) {
        $supplierName = mysqli_real_escape_string($db, $_POST['supplierName']);
        $supplierBrand = mysqli_real_escape_string($db, $_POST['supplierBrand']);
        
        $result = mysqli_query($db, "SELECT * FROM suppliers");

        // form validation: ensure that the form is correctly filled ...
        // by adding (array_push()) corresponding error unto $errors arrays
        if (empty($supplierName)) { array_push($errors, "Supplier Name is required"); }
        if (empty($supplierBrand)) { array_push($errors, "Supplier Brand required"); }


        $stock_check_query = "SELECT * FROM suppliers WHERE suppliers_name='$supplierName' AND stock_brand='$supplierBrand' LIMIT 1";
        $result = mysqli_query($db, $stock_check_query);
        $stock = mysqli_fetch_assoc($result);

        if ($stock) { // if user exists
        if ($stock['suppliers_name'] === $supplierName) {
        array_push($errors, "Supplier Name  already exists");
        }

        if ($stock['stock_brand'] === $supplierBrand) {
        array_push($errors, "Supplier Brand already exists");
        }
        }

        // Finally, register user if there are no errors in the form
        if (count($errors) == 0) {

        $query = "INSERT INTO suppliers (suppliers_name, stock_brand)
        VALUES('$supplierName', '$supplierBrand')";
        $test =mysqli_query($db, $query);

        if ($test) {
                echo '<script>alert("Suppliers Added")</script>';
                    header('location: suppliers.php');
        }else{
                echo '<script>alert("Failed to add Suppliers")</script>';
        }
    
        }
        }

  //Admin Update Suppliers
  if (isset($_POST['updateSuppliers'])) {
  $suppliers_id=$_GET['suppliers_id'];
  $suppliers_name=$_POST['suppliers_name'];
  $stock_brand=$_POST['stock_brand'];


  $query = mysqli_query($db,"UPDATE suppliers SET suppliers_name='$suppliers_name', stock_brand='$stock_brand' WHERE suppliers_id='$suppliers_id'");

        

        if ($query) {
                
                header('location: suppliers.php');
                echo '<script>alert("Suppliers is Updated")</script>';
        }

        else{
                echo '<script>alert("Failed to Add suppliers")</script>';
        }
}

try { // Check Error in Add Item in the Cart 
        


//Customer Add to Cart
  

 if(isset($_POST["add_to_cart"]))  
 {  

        $username = mysqli_real_escape_string($db, $_SESSION['username']);
        $item_id = mysqli_real_escape_string($db, $_GET['id']);
        $item_name = mysqli_real_escape_string($db, $_POST['hidden_name']);
        $item_price = mysqli_real_escape_string($db, $_POST['hidden_price']);
        $item_quantity = mysqli_real_escape_string($db, $_POST['item_quantity']);
        $item_img = mysqli_real_escape_string($db, $_POST['hidden_img']);
        
        $result = mysqli_query($db, "SELECT * FROM users WHERE username = '$username'");
        $row = mysqli_fetch_assoc($result);
        $id  = $row['id'];

        $result2 = mysqli_query($db, "SELECT * FROM stock_inventory WHERE stock_id = '$item_id'");
        $row2 = mysqli_fetch_assoc($result2);
        $stock_quantity  = $row2['stock_quantity'];


        if($stock_quantity==0){
            echo '<script>alert("Cannot Add out of Stock Item in the Cart")</script>';
            header("Refresh:0");
             exit();
        }

        if($item_quantity>$stock_quantity){
            echo '<script>alert("Cannot Enter Value More Than Available Stock")</script>';
            header("Refresh:0");
             exit();
        }

        
      if(isset($_SESSION["shopping_cart"]))  
      {  
           $item_array_id = array_column($_SESSION["shopping_cart"], "item_id");

           if(!in_array($_GET["id"], $item_array_id))  
           {  
                 $query = "INSERT INTO cart_item (id, item_id, item_img, item_name, item_price,item_quantity ) 
                                VALUES('$id', '$item_id', '$item_img', '$item_name', '$item_price','$item_quantity')";

                $test = mysqli_query($db, $query); 

                $count = count($_SESSION["shopping_cart"]);  
                $item_array = array(  
                     'item_id'                 =>     $_GET["id"],  
                     'item_name'               =>     $_POST["hidden_name"],  
                     'item_price'              =>     $_POST["hidden_price"],  
                     'item_quantity'           =>     $_POST["item_quantity"],  
                     'item_img'                =>     $_POST["hidden_img"]   
                );  
                $_SESSION["shopping_cart"][$count] = $item_array;

                echo '<script>alert("Item Added to Cart")</script>';  
           }  
           else  
           {  
                echo '<script>alert("Item Already Added")</script>';  
                echo '<script>window.location="product.php"</script>';  
           }  
      }  
      else  
      {  
           $item_array = array(  
                     'item_id'                 =>     $_GET["id"],  
                     'item_name'               =>     $_POST["hidden_name"],  
                     'item_price'              =>     $_POST["hidden_price"],  
                     'item_quantity'           =>     $_POST["item_quantity"],  
                     'item_img'                =>     $_POST["hidden_img"] 
           ); 

           $_SESSION["shopping_cart"][0] = $item_array;
           $query = "INSERT INTO cart_item (id, item_id, item_img, item_name, item_price,item_quantity ) 
           VALUES('$id', '$item_id', '$item_img', '$item_name', '$item_price','$item_quantity')";

                $test = mysqli_query($db, $query);   
                 echo '<script>alert("Item Added to Cart")</script>';
      } 
 }  
 if(isset($_GET["action"]))  
 {  
      if($_GET["action"] == "delete")  
      {    


        $item_id = mysqli_real_escape_string($db, $_GET['id']);
        $username = mysqli_real_escape_string($db, $_SESSION['username']);
        $result = mysqli_query($db, "SELECT * FROM users WHERE username = '$username'");
        $row = mysqli_fetch_assoc($result);
        $id  = $row['id'];
        
           mysqli_query($db,"DELETE FROM `cart_item` WHERE id='$id' AND item_id= '$item_id' ");
           
           foreach($_SESSION["shopping_cart"] as $keys => $values)  
           {  
                if($values["item_id"] == $_GET["id"])  
                {  
                     unset($_SESSION["shopping_cart"][$keys]);  
                     echo '<script>alert("Item Removed")</script>';  
                     echo '<script>window.location="cart.php"</script>';  
                }  
           }  
      }  
 }  

 } catch (Exception $e) {
         echo '<script>alert("There was an Error When Adding Item in the Cart")</script>';
}

   
        //Remove Customer Cart Item After Log Out in Database

   if (isset($_GET['logout'])) {

        $username = mysqli_real_escape_string($db, $_SESSION['username']);
        $result = mysqli_query($db, "SELECT * FROM users WHERE username = '$username'");
        $row = mysqli_fetch_assoc($result);
        $id  = $row['id'];

        $result = mysqli_query($db, "SELECT * FROM cart_item WHERE id = '$id'");
        while($row  = mysqli_fetch_assoc($result)){
        $cart_id  = $row['cart_id'];
        mysqli_query($db,"DELETE FROM `cart_item` WHERE id='$id' AND cart_id= '$cart_id' "); }
  }
 



 // Customer Payment Form
try {
        // Check Error For Customer Payment

if(isset($_POST['submitReceipt']))
{
        $username = mysqli_real_escape_string($db, $_SESSION['username']);
        $payment_id = mysqli_real_escape_string($db, $_POST['payment_id']);
        $item_date = mysqli_real_escape_string($db, $_POST['item_date']);
        $item_overall_total_price = mysqli_real_escape_string($db, $_POST['item_overall_total_price']);
        $purchase_validation = 'Processing';
        $payment_id = mysqli_real_escape_string($db, $_POST['payment_id']); 
        
        $payment_resit = $_FILES['payment_resit']['name'];

        $target = "asset/receipt/".basename($payment_resit);

        // Get text

        // image file directory
    

        if (empty($payment_resit)) { array_push($errors, "Item Name is required"); }

        if (count($errors) == 0) {

        $result = mysqli_query($db, "SELECT * FROM users WHERE username = '$username'");
        $row = mysqli_fetch_assoc($result);
        $id  = $row['id'];

        $query = "INSERT INTO purchase (purchase_id, id, total_price, purchase_date, payment_resit,purchase_validation) 
        VALUES('$payment_id', '$id', '$item_overall_total_price', '$item_date', '$payment_resit','$purchase_validation')";

         $test = mysqli_query($db, $query);

         if (move_uploaded_file($_FILES['payment_resit']['tmp_name'], $target)) {
               
        }else{
                echo '<script>alert("Failed to upload image")</script>';
        } 

        $username = mysqli_real_escape_string($db, $_SESSION['username']);
        $result = mysqli_query($db, "SELECT * FROM users WHERE username = '$username'");
        $row = mysqli_fetch_assoc($result);
        $id  = $row['id'];

        $result2 = mysqli_query($db, "SELECT * FROM cart_item WHERE id = '$id'");

        while($row2 = mysqli_fetch_assoc($result2)) {

            $id  = $row2['id']; 
            $stock_id = $row2['item_id']; 
            $stock_img = $row2['item_img']; 
            $stock_name = $row2['item_name']; 
            $item_quantity = $row2['item_quantity']; 
            $stock_price = $row2['item_price']; 
            $purchase_date = date("Y-m-d");

             $query = "INSERT INTO purchase_item (purchase_id, id, stock_id, stock_img, stock_name, stock_quantity, stock_price,purchase_date ) 
                                           VALUES('$payment_id', '$id', '$stock_id', '$stock_img', '$stock_name', '$item_quantity', '$stock_price', '$purchase_date')";

             $test = mysqli_query($db, $query);

             $result3 = mysqli_query($db, "SELECT * FROM stock_inventory WHERE stock_id = '$stock_id'");

             while($row3  = mysqli_fetch_assoc($result3)){

                $stock_quantity = $row3['stock_quantity'];

                $output = $stock_quantity-$item_quantity;

                $query2 = "UPDATE stock_inventory SET stock_quantity ='$output' WHERE stock_id = '$stock_id'";

                 $test = mysqli_query($db,$query2);

             }

        }

        // Customer Delivery Status

        $query1="SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($db,$query1);
        $row = mysqli_fetch_assoc($result);
        $address  = $row['address'];
        $delivery_agent="Processing";
        $delivery_status="Processing";
        $payment_status="Processing";

        $query = "INSERT INTO delivery (id, purchase_id, delivery_agent, delivery_status, address,payment_status) 
                                VALUES('$id', '$payment_id', '$delivery_agent', '$delivery_status', '$address','$payment_status')";
        $test = mysqli_query($db, $query);


        mysqli_query($db,"DELETE FROM `cart_item` WHERE id='$id' ");
        unset($_SESSION["shopping_cart"]);
        echo '<script>alert("Payment Success")</script>';
        header("Refresh:0");

        }

         else{
        echo '<script>alert("Please upload the Bank transfer Receipt for Payment Process")</script>';
    }

    }
}

catch (Exception $e) {
        echo '<script>alert("There was an Error in The Purchase")</script>';
}

try { // Check Error in Admin Validation
        
    // Admin Validate Purchase

if (isset($_POST['updatePurchase'])) {
  $purchase_id=$_GET['purchase_id'];
  $id=$_POST['id'];
  $total_price=$_POST['total_price'];
  $purchase_date=$_POST['purchase_date'];
  $purchase_validation=$_POST['purchase_validation'];
  $payment_resit=$_FILES['payment_resit']['name'];
  $target = "asset/receipt/".basename($payment_resit);

  $img_query = "SELECT * FROM purchase WHERE purchase_id='$purchase_id'";
  $img_query_run = mysqli_query($db,$img_query);

  foreach ($img_query_run as $img_row) {
      
        if ($payment_resit==Null) {
                $image_data = $img_row['payment_resit'];
                $query = "UPDATE purchase SET id='$id',total_price='$total_price', purchase_date='$purchase_date', purchase_validation='$purchase_validation' WHERE purchase_id='$purchase_id'";
                        $img_query_run2 = mysqli_query($db,$query);
                        echo '<script>alert("Purchase is Updated")</script>';
        }

        else{

                if ($img_path = "asset/receipt/".$img_row['payment_resit']) {
                        
                        unlink($img_path);
                        $image_data = $payment_resit;
                        $query = "UPDATE purchase SET id='$id',total_price='$total_price', purchase_date='$purchase_date', payment_resit='$payment_resit', purchase_validation='$purchase_validation' WHERE purchase_id='$purchase_id'";
                        $img_query_run2 = mysqli_query($db,$query);
                        echo '<script>alert("Purchase is Updated")</script>';
                }

        }
  }
        
        if ($img_query_run2) {

                move_uploaded_file($_FILES['payment_resit']['tmp_name'], $target);
                if ($payment_resit == Null) {

                echo '<script>alert("Payment Receipt is Uploaded with existing Image")</script>';
                
                }

                else{
                echo '<script>alert("Image is Uploaded")</script>';
                
        
                }


        } 

        else{
                echo '<script>alert("Failed to Update Purchase")</script>';
                
        }

        if($purchase_validation=="Approved"){

        $payment_status="Approved";
        $query =  "UPDATE delivery SET payment_status='$payment_status' WHERE purchase_id='$purchase_id'";
        $result = mysqli_query($db,$query);

        }

        if($purchase_validation=="Declined"){

        $payment_status="Declined";
        $delivery_agent="Canceled";
        $delivery_status="Canceled";
        $query =  "UPDATE delivery SET payment_status='$payment_status',delivery_agent='$delivery_agent', delivery_status='$delivery_status' WHERE purchase_id='$purchase_id'";
        $result = mysqli_query($db,$query);


         $result2 = mysqli_query($db, "SELECT * FROM purchase_item WHERE purchase_id = '$purchase_id'");

             while($row2  = mysqli_fetch_assoc($result2)){
                $stock_id = $row2['stock_id'];
                $stock_quantity = $row2['stock_quantity'];

                $query4="SELECT * FROM stock_inventory WHERE stock_id = '$stock_id'";
                $result4 = mysqli_query($db,$query4);

                while($row4  = mysqli_fetch_assoc($result4)){
                $stock_quantity1 = $row4['stock_quantity'];

                $output = $stock_quantity+$stock_quantity1;
                $query2 = "UPDATE stock_inventory SET stock_quantity ='$output' WHERE stock_id = '$stock_id'";
                 $test = mysqli_query($db,$query2);

                $query3= "DELETE FROM purchase_item WHERE purchase_id='$purchase_id' AND stock_id = '$stock_id'";
                $result3 = mysqli_query($db,$query3); 

        }  
             }

        }

        header('location: purchase.php');
        }

        } 
        catch (Exception $e) {
         echo '<script>alert("There was an Error in the Validation Process")</script>';
        }


        //Admin Update Purchase Item

  if (isset($_POST['updateStockItem'])) {
  $purchase_item_id=$_GET['purchase_item_id'];
  $purchase_id=$_POST['purchase_id'];
  $id=$_POST['id'];
  $stock_id=$_POST['stock_id'];
  $stock_name=$_POST['stock_name'];
  $stock_quantity=$_POST['stock_quantity'];
  $stock_price=$_POST['stock_price'];
  $purchase_date=$_POST['purchase_date'];

  $img_query = "SELECT * FROM purchase_item WHERE purchase_item_id='$purchase_item_id'";
  $img_query_run = mysqli_query($db,$img_query);

  $query =  "UPDATE purchase_item SET purchase_item_id='$purchase_item_id', purchase_id='$purchase_id', id='$id' , stock_id='$stock_id', stock_name='$stock_name', stock_quantity='$stock_quantity',stock_price='$stock_price' ,purchase_date='$purchase_date'  WHERE purchase_item_id='$purchase_item_id'";


  $result = mysqli_query($db,$query);

                echo '<script>alert("Customer Purchase Item Updated")</script>';
                header('location: admin.php');

        }


        //Admin Update Customer Order Item

try {
        

        if (isset($_POST['updateOrder'])) {

        $delivery_id=$_GET['delivery_id'];
        $id=$_POST['id'];
        $purchase_id=$_POST['purchase_id'];
        $delivery_agent=$_POST['delivery_agent'];
        $delivery_status=$_POST['delivery_status'];
        $address=$_POST['address'];
        $payment_status=$_POST['payment_status'];


          $query =  "UPDATE delivery SET delivery_id='$delivery_id', id='$id', purchase_id='$purchase_id' , delivery_agent='$delivery_agent', delivery_status='$delivery_status', address='$address',payment_status='$payment_status' WHERE delivery_id='$delivery_id'";


          $result = mysqli_query($db,$query);

                echo '<script>alert("Customer Order is Updated")</script>';
                header('location: order.php');

        }

        }

         catch (Exception $e) {
        echo '<script>alert("Customer Order is Not Updated")</script>';
}





        