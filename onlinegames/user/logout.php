<?php
session_start();
include('../connection.php');

if(isset($_SESSION['auth_token'])) {
    $token = $_SESSION['auth_token'];
    // Optional: Clear token from DB on logout for extra security
    mysqli_query($conn, "UPDATE users SET auth_token=NULL, token_expiry=NULL WHERE auth_token='$token'");
}

session_destroy();
?>
<script>
    // Clear the browser's local storage
    localStorage.clear();
    // Redirect back to login
    window.location.href = '../login.php';
</script>