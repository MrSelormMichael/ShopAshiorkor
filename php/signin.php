<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $hashed_password, $role);

    if ($stmt->fetch() && password_verify($password, $hashed_password)) {
        session_start();
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        if ($role == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: index.html");
        }
    } else {
        echo "Invalid email or password!";
    }

    $stmt->close();
}
?>