$stock_id = (int) ($_GET['stock_id'] ?? 0);
if ($stock_id <= 0) {
    bag_redirect_to_entry('inventory.php');
    exit;
}

$select = mysqli_query($db, 'SELECT stock_img FROM stock_inventory WHERE stock_id=' . $stock_id . ' LIMIT 1');
$image = mysqli_fetch_assoc($select);
if ($image && !empty($image['stock_img'])) {
    $p = bag_paths()['stockImg'] . '/' . basename((string) $image['stock_img']);
    if (is_file($p)) {
        @unlink($p);
    }
}

$stmt = mysqli_prepare($db, 'DELETE FROM `stock_inventory` WHERE stock_id=?');
mysqli_stmt_bind_param($stmt, 'i', $stock_id);
mysqli_stmt_execute($stmt);
bag_redirect_to_entry('inventory.php');
