<?php
session_start();
require 'koneksi.php';

$action = $_GET['action'];
if($action == 'signup'){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            header("Location: index.php");
        }else{
            header("Location: signup.php");
        }
    }
}else if($action == 'login'){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];
    
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
    
            if (password_verify($password, $user['password'])) {
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['email'] = $user['email'];
                header("Location: index.php");
                exit;
            } else {
                header("Location: login.php");
                exit;
            }
        } else {
            header("Location: login.php");
            exit;
        }
    }
}

?>