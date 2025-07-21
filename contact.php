<?php
session_start();
$name = $email = $subject = $message = '';
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Here you can send an email or store the message in the database
        $success = 'Thank you for contacting us! We will get back to you soon.';
        $name = $email = $subject = $message = '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Blood Bank Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-red-600 text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center py-3 px-4">
            <div class="flex items-center space-x-2">
                <i class="fas fa-tint text-2xl text-white"></i>
                <a href="index.php" class="text-2xl font-bold tracking-wide hover:text-gray-200 transition">Blood Bank Portal</a>
            </div>
            <div class="space-x-2 md:space-x-4 flex items-center">
                <a href="index.php" class="px-3 py-2 rounded hover:bg-white hover:text-red-600 transition font-medium">Home</a>
                <a href="find_donor.php" class="px-3 py-2 rounded hover:bg-white hover:text-red-600 transition font-medium">Find Donor</a>
                <a href="blood_requests.php" class="px-3 py-2 rounded hover:bg-white hover:text-red-600 transition font-medium">Blood Requests</a>
                <a href="contact.php" class="px-3 py-2 rounded hover:bg-white hover:text-red-600 transition font-medium">Contact Us</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php" class="px-3 py-2 rounded hover:bg-white hover:text-red-600 transition font-medium">Dashboard</a>
                    <a href="logout.php" class="px-3 py-2 rounded hover:bg-white hover:text-red-600 transition font-medium">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="px-3 py-2 rounded hover:bg-white hover:text-red-600 transition font-medium">Login</a>
                    <a href="register.php" class="px-3 py-2 rounded hover:bg-white hover:text-red-600 transition font-medium">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mx-auto py-12">
        <div class="max-w-lg mx-auto bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold mb-6 text-center">Contact Us</h2>
            <?php if ($success): ?>
                <div class="mb-4 p-3 rounded bg-green-100 text-green-700 text-center">
                    <?php echo $success; ?>
                </div>
            <?php elseif ($error): ?>
                <div class="mb-4 p-3 rounded bg-red-100 text-red-700 text-center">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <form method="POST" class="space-y-4">
                <div>
                    <label for="name" class="block text-gray-700">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div>
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div>
                    <label for="subject" class="block text-gray-700">Subject</label>
                    <input type="text" id="subject" name="subject" value="<?php echo htmlspecialchars($subject); ?>" required class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div>
                    <label for="message" class="block text-gray-700">Message</label>
                    <textarea id="message" name="message" rows="5" required class="w-full px-3 py-2 border rounded-lg"><?php echo htmlspecialchars($message); ?></textarea>
                </div>
                <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 font-semibold">Send Message</button>
            </form>
        </div>
    </div>
</body>
</html> 