$suppliers_id = (int) ($_GET['suppliers_id'] ?? 0);
if ($suppliers_id <= 0) {
    bag_redirect_to_entry('suppliers.php');
    exit;
}

$stmt = mysqli_prepare($db, 'DELETE FROM `suppliers` WHERE suppliers_id=?');
mysqli_stmt_bind_param($stmt, 'i', $suppliers_id);
mysqli_stmt_execute($stmt);
bag_redirect_to_entry('suppliers.php');
