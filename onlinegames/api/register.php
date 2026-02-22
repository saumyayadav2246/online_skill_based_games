<?php
session_start();
include('../connection.php');

if(isset($_POST['register_btn'])) {
    // 1. Get form data and escape it to prevent basic SQL injection
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 2. Check if passwords match
    if($password !== $confirm_password) {
        $_SESSION['message'] = "Passwords do not match!";
        header("Location: ../register.php");
        exit();
    }

    // 3. Check if email already exists
    $check_email = "SELECT email FROM users WHERE email='$email' LIMIT 1";
    $check_result = mysqli_query($conn, $check_email);

    if(mysqli_num_rows($check_result) > 0) {
        $_SESSION['message'] = "Email already registered!";
        header("Location: ../register.php");
        exit();
    }

    // 4. Encrypt password (Student-level security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 5. Insert into Database
    $query = "INSERT INTO users (first_name, last_name, email, password) 
              VALUES ('$first_name', '$last_name', '$email', '$hashed_password')";
    
    $query_run = mysqli_query($conn, $query);

    if($query_run) {
        $_SESSION['message'] = "Registration Successful!";
        header("Location: ../register.php");
        exit();
    } else {
        $_SESSION['message'] = "Something went wrong. Please try again.";
        header("Location: ../register.php");
        exit();
    }
}
?>