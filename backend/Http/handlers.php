<?php

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
        $password = password_hash($password_1, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, name, contact, address, password) 
        VALUES('$username', '$name', '$contact', '$address', '$password')";
        mysqli_query($db, $query);
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now logged in";
        bag_redirect_to_entry('index.php');
        }
        }


// LOGIN USER
        if (isset($_POST['login_user'])) {
        $username = mysqli_real_escape_string($db, $_POST['username']);
        $plain = isset($_POST['password']) ? (string) $_POST['password'] : '';

        if (empty($username)) {
        array_push($errors, "Username is required");
        }
        if ($plain === '') {
        array_push($errors, "Password is required");
        }

        if (count($errors) == 0) {
        $stmt = mysqli_prepare($db, "SELECT id, username, password FROM users WHERE username=? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $results = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($results);
        $ok = false;
        if ($row) {
            $stored = (string) $row['password'];
            if (password_verify($plain, $stored)) {
                $ok = true;
            } elseif (strlen($stored) === 32 && ctype_xdigit($stored) && hash_equals($stored, md5($plain))) {
                $newHash = password_hash($plain, PASSWORD_DEFAULT);
                $uid = (int) $row['id'];
                $up = mysqli_prepare($db, "UPDATE users SET password=? WHERE id=?");
                mysqli_stmt_bind_param($up, 'si', $newHash, $uid);
                mysqli_stmt_execute($up);
                $ok = true;
            }
        }
        if ($ok) {
        $_SESSION['username'] = $username;

        if ($username === bag_admin_username()) {
        $_SESSION['success'] = "You are now logged in";
        bag_redirect_to_entry('admin.php');
        }
        else{$_SESSION['success'] = "You are now logged in";
        bag_redirect_to_entry('index.php');
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
        $password = password_hash($password1, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, name, contact, address, password) 
        VALUES('$username', '$name', '$contact', '$address', '$password')";
        mysqli_query($db, $query);
        $_SESSION['username'] = $username;
        bag_redirect_to_entry('users.php');
      }
      }      

  //Admin Update User
  if (isset($_POST['updateUser'])) {
  $id = (int) ($_GET['id'] ?? 0);
  $username = mysqli_real_escape_string($db, $_POST['username'] ?? '');
  $name = mysqli_real_escape_string($db, $_POST['name'] ?? '');
  $contact = mysqli_real_escape_string($db, $_POST['contact'] ?? '');
  $address = mysqli_real_escape_string($db, $_POST['address'] ?? '');
  $new_password = isset($_POST['new_password']) ? (string) $_POST['new_password'] : '';

  if ($id <= 0) {
      array_push($errors, "Invalid user id");
  } else {
      if ($new_password !== '') {
          $hash = password_hash($new_password, PASSWORD_DEFAULT);
          $stmt = mysqli_prepare($db, "UPDATE users SET username=?, name=?, contact=?, address=?, password=? WHERE id=?");
          mysqli_stmt_bind_param($stmt, 'sssssi', $username, $name, $contact, $address, $hash, $id);
          $test = mysqli_stmt_execute($stmt);
      } else {
          $stmt = mysqli_prepare($db, "UPDATE users SET username=?, name=?, contact=?, address=? WHERE id=?");
          mysqli_stmt_bind_param($stmt, 'ssssi', $username, $name, $contact, $address, $id);
          $test = mysqli_stmt_execute($stmt);
      }
   if ($test) {
     echo '<script>alert("Data Updated")</script>';
     bag_redirect_to_entry('users.php');
   }

   else{
    echo '<script>alert("Not Data Updated")</script>';
  }
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

        $result = mysqli_query($db, "SELECT * FROM stock_inventory");

        // form validation: ensure that the form is correctly filled ...
        // by adding (array_push()) corresponding error unto $errors array
        if (empty($itemName)) { array_push($errors, "Item Name is required"); }
        if (empty($itemBrand)) { array_push($errors, "Item Brand required"); }
        if (empty($itemCategory)) { array_push($errors, "Item Category is required"); }
        if (empty($itemPrice)) { array_push($errors, "Item Price is required"); }
        if (empty($itemQuantity)) { array_push($errors, "Item Quantity is required"); }
        if (empty($itemDescription)) { array_push($errors, "Item Description is required"); }

        $storedImg = bag_save_stock_image($_FILES['itemImg'] ?? []);
        if ($storedImg === null) {
            array_push($errors, "A valid product image (JPG, PNG, GIF, or WebP) is required");
        }

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
        VALUES('$itemName', '$itemBrand', '$itemCategory', '$itemQuantity', '$itemDescription', '$storedImg','$itemPrice')";
        mysqli_query($db, $query);

        echo '<script>alert("Item saved")</script>';
        bag_redirect_to_entry('inventory.php');

        }
        }

  //Admin Update Stock
  if (isset($_POST['updateStock'])) {
  $stock_id = (int) ($_GET['stock_id'] ?? 0);
  $stock_name = mysqli_real_escape_string($db, $_POST['stock_name'] ?? '');
  $stock_brand = mysqli_real_escape_string($db, $_POST['stock_brand'] ?? '');
  $stock_category = mysqli_real_escape_string($db, $_POST['stock_category'] ?? '');
  $stock_price = mysqli_real_escape_string($db, $_POST['stock_price'] ?? '');
  $stock_quantity = (int) ($_POST['stock_quantity'] ?? 0);
  $stock_description = mysqli_real_escape_string($db, $_POST['stock_description'] ?? '');

  $img_query_run2 = false;
  $rowQ = mysqli_query($db, "SELECT stock_img FROM stock_inventory WHERE stock_id=" . $stock_id . " LIMIT 1");
  $img_row = mysqli_fetch_assoc($rowQ);
  if (!$img_row) {
        echo '<script>alert("Stock not found")</script>';
        bag_redirect_to_entry('inventory.php');
        exit;
  }

  $newImgName = null;
  if (!empty($_FILES['stock_img']['tmp_name']) && is_uploaded_file($_FILES['stock_img']['tmp_name'])) {
        $newImgName = bag_save_stock_image($_FILES['stock_img']);
  }

  if ($newImgName === null) {
        $stmt = mysqli_prepare($db, "UPDATE stock_inventory SET stock_name=?, stock_brand=?, stock_category=?, stock_quantity=?, stock_description=?, stock_price=? WHERE stock_id=?");
        mysqli_stmt_bind_param($stmt, 'sssissi', $stock_name, $stock_brand, $stock_category, $stock_quantity, $stock_description, $stock_price, $stock_id);
        $img_query_run2 = mysqli_stmt_execute($stmt);
  } else {
        $oldFile = bag_paths()['stockImg'] . '/' . basename((string) $img_row['stock_img']);
        if (is_file($oldFile)) {
            @unlink($oldFile);
        }
        $stmt = mysqli_prepare($db, "UPDATE stock_inventory SET stock_name=?, stock_brand=?, stock_category=?, stock_quantity=?, stock_description=?, stock_img=?, stock_price=? WHERE stock_id=?");
        mysqli_stmt_bind_param($stmt, 'sssisssi', $stock_name, $stock_brand, $stock_category, $stock_quantity, $stock_description, $newImgName, $stock_price, $stock_id);
        $img_query_run2 = mysqli_stmt_execute($stmt);
  }

        if ($img_query_run2) {
                echo '<script>alert("Stock updated")</script>';
                bag_redirect_to_entry('inventory.php');
        } 

        else{
                echo '<script>alert("Failed to update stock")</script>';
                bag_redirect_to_entry('inventory.php');
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
                    bag_redirect_to_entry('suppliers.php');
        }else{
                echo '<script>alert("Failed to add Suppliers")</script>';
        }
    
        }
        }

  //Admin Update Suppliers
  if (isset($_POST['updateSuppliers'])) {
  $suppliers_id = (int) ($_GET['suppliers_id'] ?? 0);
  $suppliers_name = mysqli_real_escape_string($db, $_POST['suppliers_name'] ?? '');
  $stock_brand = mysqli_real_escape_string($db, $_POST['stock_brand'] ?? '');

  $stmt = mysqli_prepare($db, "UPDATE suppliers SET suppliers_name=?, stock_brand=? WHERE suppliers_id=?");
  mysqli_stmt_bind_param($stmt, 'ssi', $suppliers_name, $stock_brand, $suppliers_id);
  $query = mysqli_stmt_execute($stmt);

        

        if ($query) {
                
                bag_redirect_to_entry('suppliers.php');
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
                echo '<script>window.location=' . json_encode(bag_url('product')) . ';</script>';  
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
                     echo '<script>window.location=' . json_encode(bag_url('cart')) . ';</script>';  
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
        $payment_id = mysqli_real_escape_string($db, $_POST['payment_id'] ?? '');
        $item_date = mysqli_real_escape_string($db, $_POST['item_date'] ?? '');
        $item_overall_total_price = mysqli_real_escape_string($db, $_POST['item_overall_total_price'] ?? '');
        $purchase_validation = 'Processing';

        $storedReceipt = bag_save_receipt_upload($_FILES['payment_resit'] ?? []);

        if ($storedReceipt === null) {
            array_push($errors, "Receipt upload is required (JPG, PNG, GIF, WebP, or PDF)");
        }

        if (count($errors) == 0) {

        $result = mysqli_query($db, "SELECT * FROM users WHERE username = '$username'");
        $row = mysqli_fetch_assoc($result);
        $id  = $row['id'];

        $query = "INSERT INTO purchase (purchase_id, id, total_price, purchase_date, payment_resit,purchase_validation) 
        VALUES('$payment_id', '$id', '$item_overall_total_price', '$item_date', '$storedReceipt','$purchase_validation')";

         $test = mysqli_query($db, $query);

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
  $purchase_id = mysqli_real_escape_string($db, $_GET['purchase_id'] ?? '');
  $id = (int) ($_POST['id'] ?? 0);
  $total_price = mysqli_real_escape_string($db, $_POST['total_price'] ?? '');
  $purchase_date = mysqli_real_escape_string($db, $_POST['purchase_date'] ?? '');
  $purchase_validation = mysqli_real_escape_string($db, $_POST['purchase_validation'] ?? '');

  $img_query_run2 = false;
  $img_query = mysqli_query($db, "SELECT payment_resit FROM purchase WHERE purchase_id='" . $purchase_id . "' LIMIT 1");
  $img_row = mysqli_fetch_assoc($img_query);

  $newReceipt = null;
  if (!empty($_FILES['payment_resit']['tmp_name']) && is_uploaded_file($_FILES['payment_resit']['tmp_name'])) {
        $newReceipt = bag_save_receipt_upload($_FILES['payment_resit']);
  }

  if ($img_row) {
        if ($newReceipt === null) {
                $stmt = mysqli_prepare($db, "UPDATE purchase SET id=?, total_price=?, purchase_date=?, purchase_validation=? WHERE purchase_id=?");
                mysqli_stmt_bind_param($stmt, 'issss', $id, $total_price, $purchase_date, $purchase_validation, $purchase_id);
                $img_query_run2 = mysqli_stmt_execute($stmt);
        } else {
                $oldPath = bag_paths()['receipt'] . '/' . basename((string) $img_row['payment_resit']);
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
                $stmt = mysqli_prepare($db, "UPDATE purchase SET id=?, total_price=?, purchase_date=?, payment_resit=?, purchase_validation=? WHERE purchase_id=?");
                mysqli_stmt_bind_param($stmt, 'isssss', $id, $total_price, $purchase_date, $newReceipt, $purchase_validation, $purchase_id);
                $img_query_run2 = mysqli_stmt_execute($stmt);
        }
  }

        if ($img_query_run2) {
                echo '<script>alert("Purchase is Updated")</script>';
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

        bag_redirect_to_entry('purchase.php');
        }

        } 
        catch (Exception $e) {
         echo '<script>alert("There was an Error in the Validation Process")</script>';
        }


        //Admin Update Purchase Item

  if (isset($_POST['updateStockItem'])) {
  $purchase_item_id = (int) ($_GET['purchase_item_id'] ?? 0);
  $purchase_id = mysqli_real_escape_string($db, $_POST['purchase_id'] ?? '');
  $id = (int) ($_POST['id'] ?? 0);
  $stock_id = mysqli_real_escape_string($db, $_POST['stock_id'] ?? '');
  $stock_name = mysqli_real_escape_string($db, $_POST['stock_name'] ?? '');
  $stock_quantity = (int) ($_POST['stock_quantity'] ?? 0);
  $stock_price = mysqli_real_escape_string($db, $_POST['stock_price'] ?? '');
  $purchase_date = mysqli_real_escape_string($db, $_POST['purchase_date'] ?? '');

  $stmt = mysqli_prepare($db, "UPDATE purchase_item SET purchase_id=?, id=?, stock_id=?, stock_name=?, stock_quantity=?, stock_price=?, purchase_date=? WHERE purchase_item_id=?");
  mysqli_stmt_bind_param($stmt, 'sississi', $purchase_id, $id, $stock_id, $stock_name, $stock_quantity, $stock_price, $purchase_date, $purchase_item_id);
  $result = mysqli_stmt_execute($stmt);

                echo '<script>alert("Customer Purchase Item Updated")</script>';
                bag_redirect_to_entry('admin.php');

        }


        //Admin Update Customer Order Item

try {
        

        if (isset($_POST['updateOrder'])) {

        $delivery_id = (int) ($_GET['delivery_id'] ?? 0);
        $id = (int) ($_POST['id'] ?? 0);
        $purchase_id = mysqli_real_escape_string($db, $_POST['purchase_id'] ?? '');
        $delivery_agent = mysqli_real_escape_string($db, $_POST['delivery_agent'] ?? '');
        $delivery_status = mysqli_real_escape_string($db, $_POST['delivery_status'] ?? '');
        $address = mysqli_real_escape_string($db, $_POST['address'] ?? '');
        $payment_status = mysqli_real_escape_string($db, $_POST['payment_status'] ?? '');


          $stmt = mysqli_prepare($db, "UPDATE delivery SET id=?, purchase_id=?, delivery_agent=?, delivery_status=?, address=?, payment_status=? WHERE delivery_id=?");
          mysqli_stmt_bind_param($stmt, 'isssssi', $id, $purchase_id, $delivery_agent, $delivery_status, $address, $payment_status, $delivery_id);
          $result = mysqli_stmt_execute($stmt);

                echo '<script>alert("Customer Order is Updated")</script>';
                bag_redirect_to_entry('order.php');

        }

        }

         catch (Exception $e) {
        echo '<script>alert("Customer Order is Not Updated")</script>';
}





        