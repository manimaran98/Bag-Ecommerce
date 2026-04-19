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
  <link rel="stylesheet" type="text/css" href="asset/css/style3.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
  <meta charset="utf-8">
  <title>Home</title>
</head>
<header>
  <ul>
    <li style="float: left;">
    <h2 class="headline">My Bag Swag Shop</h2>
    <img style="margin-left :20px; margin-top:5px ;" height="80" width="120" src="asset/img/logo.png"></li>
    <li ><a onclick="if (!confirm('Are you sure you want to logout?')) return false;" href="index.php?logout='1'" style="color: red;">logout</a></li>
    <li><a><?php  if (isset($_SESSION['username'])) : ?>
                    <strong><?php echo $_SESSION['username']; ?></strong>
                    <?php endif ?>
    <li ><a href="cart.php">Cart</a></li>
    <li><a href="trackOrder.php">Track order</a></li>
    <li><a href="product.php">Product</a></li>
    <li class="active"><strong><a href="#">Home</a></strong></li>
  </ul>
</header>
<body>
    <div class="Top-Content">
  <marquee><h1>Fear of Pendemic! Afaird to go out for Shopping, Why not Shop with us.</h1></marquee>
  <div class="row">
  <div class="column">
    <!-- Slideshow container -->
<div class="slideshow-container">

  <!-- Full-width images with number and caption text -->
  <div class="mySlides fade">
    <div class="numbertext">1 / 3</div>
    <img src="asset/img/bag1.png" alt="1" style="width:100%;">
  </div>

  <div class="mySlides fade">
    <div class="numbertext">2 / 3</div>
    <img src="asset/img/online2.png" alt="2" style="width:100%;">
  </div>

  <div class="mySlides fade">
    <div class="numbertext">3 / 3</div>
    <img src="asset/img/online3.png" alt="3" style="width:100%;">
  </div>

  <!-- Next and previous buttons -->
  <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
  <a class="next" onclick="plusSlides(1)">&#10095;</a>
</div>
<br>

<!-- The dots/circles -->
<div style="text-align:center">
  <span class="dot" onclick="currentSlide(1)"></span>
  <span class="dot" onclick="currentSlide(2)"></span>
  <span class="dot" onclick="currentSlide(3)"></span>
</div>
  </div>
</div>
  <div class="heading"><h1>Why You Should Shop with us</h1></div>
  <div class="grid-container">
  <div> <h4>You are supporting a small business</h4><img height="200" width="250" src="asset/img/icon1.png" alt="icon1"><p>The accessories we sell come from small businesses, so when you purchase them from us, you are helping those businesses grow as well.</p></div>
  <div><h4>Our products are unique</h4><img height="200" width="250" src="asset/img/icon2.png" alt="icon2"><p>You likely won't find our brands in stores or on many other retail shops. We only order a small amount of each style to keep our product line fresh. </p></div>
  <div><h4>Money Back Guarantee</h4><img height="200" width="250" src="asset/img/icon3.png" alt="icon3"><p>For your peace of mind, we offer a 30-Day Money Back Guarantee on all the non-custom playground parts & accessories we sell. If you are dissatisfied for any reason with the products you receive, items are fully refundable (less shipping) if returned in new, unused, re-sellable condition. </p></div>  
  <div><h4>Secure Shopping</h4><img height="200" width="250" src="asset/img/icon4.jpg" alt="icon4"><p>You can shop at our website with confidence. We have high security for system in our system that can secure credit card and electronic check transactions for our customers.</p></div> 
</div>

  <script>
var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}
</script>

</div>
</body>
</html>

