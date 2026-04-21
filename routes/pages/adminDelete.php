$purchase_item_id = (int) ($_GET['purchase_item_id'] ?? 0);
if ($purchase_item_id <= 0) {
    bag_redirect_to_entry('admin.php');
    exit;
}

$stmt = mysqli_prepare($db, 'DELETE FROM purchase_item WHERE purchase_item_id=?');
mysqli_stmt_bind_param($stmt, 'i', $purchase_item_id);
$result = mysqli_stmt_execute($stmt);
if ($result) {
    echo '<script>alert("Customer Purchase Item Have been Deleted.")</script>';
    bag_redirect_to_entry('admin.php');
} else {
    echo '<script>alert("Unable to Delete Customer Purchase Item.")</script>';
}
