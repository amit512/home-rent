<?php
// Database connection\
require '../config/config.php';

// eSewa parameters
$pid = $_GET['pid'];
$refId = $_GET['refId'];

// Verify with eSewa
$url = "https://uat.esewa.com.np/epay/transrec";
$data = [
    'amt' => 7500,  // Amount you expect
    'rid' => $refId,
    'pid' => $pid,
    'scd' => 'EPAYTEST'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Update database based on eSewa's response
if (strpos($response, 'Success') !== false) {
    $stmt = $conn->prepare("UPDATE payments SET status='success', transaction_id=? WHERE pid=?");
    $stmt->bind_param("ss", $refId, $pid);
    $stmt->execute();
    echo "Payment successful!";
} else {
    $stmt = $conn->prepare("UPDATE payments SET status='failure' WHERE pid=?");
    $stmt->bind_param("s", $pid);
    $stmt->execute();
    echo "Payment verification failed.";
}
?>
