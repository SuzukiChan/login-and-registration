<?php

$token = $_GET["token"];

$token_hash = hash("sha256", $token);

// Check if the token exists in the database
$sql = "SELECT * FROM users WHERE activation_token = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $token_hash);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if ($user) {
    // Update the database to activate the account
    $sql = "UPDATE users SET activation_token = NULL WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user['id']);
    mysqli_stmt_execute($stmt);

    // Redirect to a page informing the user that the account has been activated
    header("Location: account_activated.php");
    exit;
} else {
    die("Invalid activation token");
}
?>
