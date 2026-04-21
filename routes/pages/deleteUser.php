$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    bag_redirect_to_entry('users.php');
    exit;
}

$stmt = mysqli_prepare($db, 'DELETE FROM `users` WHERE id=?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
bag_redirect_to_entry('users.php');
