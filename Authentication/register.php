<?php

include '../db.connect.php';

// Getting the inputs
$username = $_POST['username'];
$pass = $_POST['pass']; 
$email = $_POST['email'];
$role = $_POST['role']; 

$hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

// Checking if the username or email is already used
$check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email=?");
$check->bind_param("ss", $username, $gmail);
$check->execute();
$check->store_result();

$stmt = $conn->prepare("INSERT INTO users (username, pass, email, role) VALUES (?, ?, ?, ?)");

if ($check->num_rows > 0) {
    echo "Username or Email is already taken. <a href='register.html'>Try another</a>";
} else {
    // Proceed with insert
    $stmt = $conn->prepare("INSERT INTO users (username, pass, email, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $hashed_pass, $email, $role);

    if ($stmt->execute()) {
        echo "Registration successful! <a href='login.html'>Login Here</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();

?>


