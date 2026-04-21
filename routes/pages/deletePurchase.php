$purchase_id = mysqli_real_escape_string($db, $_GET['purchase_id'] ?? '');
if ($purchase_id === '') {
    bag_redirect_to_entry('purchase.php');
    exit;
}

$select = mysqli_query($db, "SELECT payment_resit FROM purchase WHERE purchase_id ='$purchase_id' LIMIT 1");
$image = mysqli_fetch_assoc($select);
if ($image && !empty($image['payment_resit'])) {
    $p = bag_paths()['receipt'] . '/' . basename((string) $image['payment_resit']);
    if (is_file($p)) {
        @unlink($p);
    }
}

$stmt = mysqli_prepare($db, 'DELETE FROM purchase WHERE purchase_id=?');
mysqli_stmt_bind_param($stmt, 's', $purchase_id);
$result = mysqli_stmt_execute($stmt);
if ($result) {
    $stmt2 = mysqli_prepare($db, 'DELETE FROM delivery WHERE purchase_id=?');
    mysqli_stmt_bind_param($stmt2, 's', $purchase_id);
    mysqli_stmt_execute($stmt2);
    bag_redirect_to_entry('purchase.php');
} else {
    echo '<script>alert("Unable to Delete Customer Purchase.")</script>';
}
