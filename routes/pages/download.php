<?php

if (empty($_GET['payment_resit'])) {
    http_response_code(404);
    echo 'Not found.';

    exit;
}

$fileName = basename((string) $_GET['payment_resit']);
$full = bag_safe_receipt_path($fileName);
if ($full === null || !is_readable($full)) {
    http_response_code(404);
    echo 'Not found.';

    exit;
}

$allowed = false;
if (bag_is_admin()) {
    $allowed = true;
} else {
    $userEsc = mysqli_real_escape_string($db, $_SESSION['username']);
    $fileEsc = mysqli_real_escape_string($db, $fileName);
    $q = mysqli_query($db, "SELECT p.purchase_id FROM purchase p INNER JOIN users u ON p.id = u.id WHERE u.username='{$userEsc}' AND p.payment_resit='{$fileEsc}' LIMIT 1");
    $allowed = $q && mysqli_num_rows($q) >= 1;
}

if (!$allowed) {
    header('HTTP/1.1 403 Forbidden');
    echo 'Access denied.';

    exit;
}

$mime = @mime_content_type($full) ?: 'application/octet-stream';
header('Cache-Control: private');
header('Content-Type: ' . $mime);
$safeName = str_replace(["\r", "\n", '"'], '', $fileName);
header('Content-Disposition: attachment; filename="' . $safeName . '"');
readfile($full);

exit;
