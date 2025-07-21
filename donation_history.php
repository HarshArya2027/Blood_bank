<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user's donation history
try {
    $stmt = $pdo->prepare("SELECT * FROM donations WHERE donor_id = ? ORDER BY donation_date DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $donations = $stmt->fetchAll();
} catch(PDOException $e) {
    $_SESSION['error'] = "Error fetching donation history.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation History - Blood Bank Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-red-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold">Blood Bank Portal</a>
            <div class="space-x-4">
                <a href="index.php" class="hover:text-gray-200">Home</a>
                <a href="find_donor.php" class="hover:text-gray-200">Find Donor</a>
                <a href="blood_requests.php" class="hover:text-gray-200">Blood Requests</a>
                <a href="dashboard.php" class="hover:text-gray-200">Dashboard</a>
                <a href="logout.php" class="hover:text-gray-200">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-8">My Donation History</h1>

        <?php if (isset($donations) && count($donations) > 0): ?>
            <div class="space-y-4">
                <?php foreach ($donations as $donation): ?>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-xl font-bold">Donation #<?php echo $donation['id']; ?></h3>
                                <p class="text-gray-600"><?php echo date('F j, Y', strtotime($donation['donation_date'])); ?></p>
                            </div>
                            <span class="px-3 py-1 text-sm rounded <?php 
                                echo $donation['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    ($donation['status'] == 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800');
                            ?>">
                                <?php echo ucfirst($donation['status']); ?>
                            </span>
                        </div>
                        
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p><strong>Blood Group:</strong> <?php echo htmlspecialchars($donation['blood_group']); ?></p>
                                <p><strong>Donation Date:</strong> <?php echo date('M d, Y', strtotime($donation['donation_date'])); ?></p>
                            </div>
                            <div>
                                <p><strong>Next Donation Date:</strong> <?php echo date('M d, Y', strtotime($donation['next_donation_date'])); ?></p>
                                <p><strong>Days Until Next Donation:</strong> 
                                    <?php
                                    $today = new DateTime();
                                    $next_date = new DateTime($donation['next_donation_date']);
                                    $interval = $today->diff($next_date);
                                    echo $interval->days;
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <p class="text-gray-600">You haven't made any blood donations yet.</p>
                <p class="mt-4">
                    <a href="blood_requests.php" class="text-red-600 hover:underline">View blood requests</a> to help those in need.
                </p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> 