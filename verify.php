<?php
require_once 'config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE verification_token = ?");
        $stmt->execute([$token]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['success'] = "Email verified successfully! You can now login.";
        } else {
            $_SESSION['error'] = "Invalid or expired verification token.";
        }
    } catch(PDOException $e) {
        $_SESSION['error'] = "Verification failed. Please try again.";
    }
    
    header("Location: login.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?> 