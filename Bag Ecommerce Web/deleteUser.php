<?php
 include('server.php');
  $id=$_GET['id']; 
  mysqli_query($db,"DELETE FROM `users` WHERE id='$id'");
  header('location:users.php');