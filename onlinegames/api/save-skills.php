<?php
session_start();
include('../connection.php');

if (isset($_POST['save_skills_btn'])) {
    // 1. Double-check authentication
    if (!isset($_SESSION['auth_token'])) {
        header("Location: ../login.php");
        exit();
    }

    $token = $_SESSION['auth_token'];
    
    // 2. Identify the User ID from the token
    $user_query = $conn->prepare("SELECT id FROM users WHERE auth_token = ? LIMIT 1");
    $user_query->bind_param("s", $token);
    $user_query->execute();
    $user_result = $user_query->get_result();

    if ($user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
        $user_id = $user['id'];

        // 3. Clean and validate the input
        // Expecting a string like "1,4,7"
        $skill_ids_raw = $_POST['skill_ids'] ?? '';
        $skill_ids = array_filter(explode(',', $skill_ids_raw));

        // Validation: Must be 1-3 skills
        $count = count($skill_ids);
        if ($count < 1 || $count > 3) {
            $_SESSION['message'] = "Please select between 1 and 3 skills.";
            header("Location: ../user/setup-skills.php");
            exit();
        }

        try {
            // Remove any existing skills for this user (prevents duplicates if they refresh)
            $delete_stmt = $conn->prepare("DELETE FROM user_skills WHERE user_id = ?");
            $delete_stmt->bind_param("i", $user_id);
            $delete_stmt->execute();

            // Insert new skills
            $insert_stmt = $conn->prepare("INSERT INTO user_skills (user_id, skill_id) VALUES (?, ?)");
            foreach ($skill_ids as $s_id) {
                $s_id = intval($s_id); // Security: force integer
                $insert_stmt->bind_param("ii", $user_id, $s_id);
                $insert_stmt->execute();
            }

            // 5. Mark user as finished with setup
            $update_user = $conn->prepare("UPDATE users SET has_skills = 1 WHERE id = ?");
            $update_user->bind_param("i", $user_id);
            $update_user->execute();

            $_SESSION['message'] = "Profile completed! Welcome to Brain League.";
            header("Location: ../user/dashboard.php");
            exit();

        } catch (Exception $e) {
            $_SESSION['message'] = "Error saving skills. Please try again.";
            header("Location: ../user/setup-skills.php");
            exit();
        }

    } else {
        session_destroy();
        header("Location: ../login.php");
        exit();
    }
} else {
    header("Location: ../user/setup-skills.php");
    exit();
}