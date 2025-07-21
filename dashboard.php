<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user details
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
} catch(PDOException $e) {
    $_SESSION['error'] = "Error fetching user details.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Blood Bank Portal</title>
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
                <a href="dashboard.php" class="px-3 py-2 rounded hover:bg-white hover:text-red-600 transition font-medium">Dashboard</a>
                <a href="logout.php" class="px-3 py-2 rounded hover:bg-white hover:text-red-600 transition font-medium">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- User Profile Card -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4">Profile Information</h2>
                <div class="space-y-2">
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Blood Group:</strong> <?php echo htmlspecialchars($user['blood_group']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
                    <p><strong>City:</strong> <?php echo htmlspecialchars($user['city']); ?></p>
                    <p><strong>State:</strong> <?php echo htmlspecialchars($user['state']); ?></p>
                </div>
                <a href="edit_profile.php" class="mt-4 inline-block bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    Edit Profile
                </a>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
                <div class="space-y-4">
                    <?php if($user['is_donor']): ?>
                        <a href="donation_history.php" class="block bg-gray-100 p-4 rounded hover:bg-gray-200">
                            <i class="fas fa-history mr-2"></i> View Donation History
                        </a>
                    <?php endif; ?>
                    <a href="blood_requests.php" class="block bg-gray-100 p-4 rounded hover:bg-gray-200">
                        <i class="fas fa-hand-holding-medical mr-2"></i> View Blood Requests
                    </a>
                    <a href="find_donor.php" class="block bg-gray-100 p-4 rounded hover:bg-gray-200">
                        <i class="fas fa-search mr-2"></i> Find Donors
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4">Recent Activity</h2>
                <div class="space-y-4">
                    <?php
                    try {
                        // Get recent blood requests
                        $stmt = $pdo->prepare("SELECT * FROM blood_requests WHERE created_by = ? ORDER BY created_at DESC LIMIT 5");
                        $stmt->execute([$_SESSION['user_id']]);
                        $requests = $stmt->fetchAll();

                        if (count($requests) > 0) {
                            foreach ($requests as $request) {
                                echo '<div class="border-b pb-2">';
                                echo '<p class="font-semibold">' . htmlspecialchars($request['patient_name']) . '</p>';
                                echo '<p class="text-sm text-gray-600">' . htmlspecialchars($request['blood_group']) . ' - ' . date('M d, Y', strtotime($request['created_at'])) . '</p>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p class="text-gray-600">No recent activity</p>';
                        }
                    } catch(PDOException $e) {
                        echo '<p class="text-red-600">Error fetching recent activity</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 