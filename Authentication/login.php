<?php
session_start();

include '../db.connect.php';

$username = $_POST['username'];
$pass = $_POST['pass'];

$stmt = $conn->prepare("SELECT id, pass, role, deleted FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if($stmt->num_rows == 1){
    $stmt->bind_result($user_id, $hashed_pass, $role, $deleted);
    $stmt->fetch();

    if($deleted == 1){
        echo "User account is currently deactivated. Please contact customer support. <a href='login.html'>Back to login</a>";
        exit();
    }

    if(password_verify($pass, $hashed_pass)){
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        // Setting a cookie. Logged in for 7 days
        setcookie("user_id", $user_id, time() + (86400 * 7), "/");
        setcookie("username", $username, time() + (86400 * 7), "/");
        setcookie("role", $role, time() + (86400 * 7), "/");

        // The path once the username and password is verified. Their roles will also be verified to the exact path
        if($role == 'author') {
            header("Location: /TechTala/Author/homepage.php");
        } elseif($role == 'reader') {
            header("Location: /TechTala/Reader/homepage.php");
        } elseif($role == 'admin') {
            header("Location: /TechTala/Admin/dashboard.php");
        }
        exit;
    }else{
        echo "Incorrect password. <a href='login.html'>Try again</a>";
    }
}else{
    echo "User not found. <a href=register.html>Register here</a>";
}

$stmt->close();
$conn->close();

?>