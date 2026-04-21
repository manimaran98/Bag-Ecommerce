$delivery_id = (int) ($_GET['delivery_id'] ?? 0);
if ($delivery_id <= 0) {
    bag_redirect_to_entry('order.php');
    exit;
}

$stmt = mysqli_prepare($db, 'DELETE FROM `delivery` WHERE delivery_id=?');
mysqli_stmt_bind_param($stmt, 'i', $delivery_id);
mysqli_stmt_execute($stmt);
bag_redirect_to_entry('order.php');
