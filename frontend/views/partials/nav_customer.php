<?php
/** @var string $navActive home|product|cart|track */
$navActive = $navActive ?? '';
?>
<header>
  <ul>
    <li style="float: left;">
    <h2 class="headline">My Bag Swag Shop</h2>
    <img style="margin-left :20px; margin-top:5px ;" height="80" width="120" src="assets/img/logo.png"></li>
    <li ><a onclick="if (!confirm('Are you sure you want to logout?')) return false;" href="<?php echo bag_url('index', ['logout' => 1]); ?>" style="color: red;">logout</a></li>
    <li><a><?php if (isset($_SESSION['username'])) : ?>
                    <strong><?php echo bag_h((string) $_SESSION['username']); ?></strong>
                    <?php endif ?></a></li>
    <li ><a href="<?php echo bag_url('cart'); ?>">Cart</a></li>
    <li><a href="<?php echo bag_url('trackOrder'); ?>">Track order</a></li>
    <li><a href="<?php echo bag_url('product'); ?>">Product</a></li>
    <li class="<?php echo $navActive === 'home' ? 'active' : ''; ?>"><strong><a href="<?php echo bag_url('index'); ?>">Home</a></strong></li>
  </ul>
</header>
