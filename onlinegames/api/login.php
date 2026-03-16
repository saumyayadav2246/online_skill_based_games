<?php
session_start();
include('../connection.php');

if (isset($_POST['login_btn'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check user in database
    $query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);

        // Verify the hashed password
        if (password_verify($password, $row['password'])) {
            
            // 1. Create a simple random token for the project
            $token = uniqid() . "-" . rand(100, 999)."-".$row['id'];
            
            // 2. Set expiry for 24 hours from now
            $expiry = date("Y-m-d H:i:s", time() + 86400);
            $user_id = $row['id'];

            // 3. Save token to Database
            $update = "UPDATE users SET auth_token='$token', token_expiry='$expiry' WHERE id='$user_id'";
            mysqli_query($conn, $update);

            // 4. Set Sessions
            $_SESSION['auth'] = true;
            $_SESSION['auth_token'] = $token;
            
            // This helper session passes data to Local Storage on the next page load
            $_SESSION['js_storage'] = [
                'token' => $token,
                'user_name' => $row['first_name']." ".$row['last_name'],
                'is_admin' => $row['is_admin']
            ];

            // $_SESSION['message'] = "Login Successful!";
            if ($row['is_admin'] == 1) {
                header("Location: ../admin/dashboard.php");
            }else{
                if ($row['has_skills'] == 0) {
                    header("Location: ../user/setup-skills.php");
                }else{
                    header("Location: ../user/dashboard.php");
                }
            }
            exit();
        } else {
            $_SESSION['message'] = "Invalid Password";
            header("Location: ../login.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "Email not found";
        header("Location: ../login.php");
        exit();
    }
} else {
    header("Location: ../login.php");
    exit();
}