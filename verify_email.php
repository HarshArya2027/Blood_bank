<?php
require_once 'config.php';

$message = '';
$error = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    try {
        // Find user with this token
        $stmt = $pdo->prepare("SELECT id, email FROM users WHERE verification_token = ? AND is_verified = 0");
        $stmt->execute([$token]);
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch();
            
            // Update user as verified
            $stmt = $pdo->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?");
            $stmt->execute([$user['id']]);
            
            $message = 'Email verified successfully! You can now login.';
        } else {
            $error = 'Invalid or expired verification link.';
        }
    } catch (PDOException $e) {
        $error = 'Verification failed. Please try again.';
        error_log($e->getMessage());
    }
} else {
    $error = 'Invalid verification link.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - Blood Bank Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full space-y-8 p-8 bg-white rounded-lg shadow-lg">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Email Verification
                </h2>
            </div>
            
            <?php if ($message): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?php echo htmlspecialchars($message); ?></span>
                </div>
                <div class="text-center">
                    <a href="login.php" class="font-medium text-red-600 hover:text-red-500">
                        Proceed to Login
                    </a>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
                </div>
                <div class="text-center">
                    <a href="register.php" class="font-medium text-red-600 hover:text-red-500">
                        Register Again
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 