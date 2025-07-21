<?php
require_once 'config.php';

$blood_group = isset($_GET['blood_group']) ? $_GET['blood_group'] : '';
$city = isset($_GET['city']) ? $_GET['city'] : '';
$state = isset($_GET['state']) ? $_GET['state'] : '';
$donors = [];

try {
    $query = "SELECT * FROM users WHERE is_donor = 1";
    $params = [];
    if (!empty($blood_group)) {
        $query .= " AND blood_group = ?";
        $params[] = $blood_group;
    }
    if (!empty($city)) {
        $query .= " AND city LIKE ?";
        $params[] = "%$city%";
    }
    if (!empty($state)) {
        $query .= " AND state LIKE ?";
        $params[] = "%$state%";
    }
    $query .= " ORDER BY RAND() LIMIT 20";
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $donors = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error searching for donors.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Donor - Blood Bank Portal</title>
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

    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-8">Find Blood Donors</h1>

        <!-- Search Form -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <form method="GET" action="" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-700 mb-2">Blood Group</label>
                    <select name="blood_group" class="w-full px-3 py-2 border rounded-lg">
                        <option value="">All Blood Groups</option>
                        <option value="A+" <?php echo $blood_group == 'A+' ? 'selected' : ''; ?>>A+</option>
                        <option value="A-" <?php echo $blood_group == 'A-' ? 'selected' : ''; ?>>A-</option>
                        <option value="B+" <?php echo $blood_group == 'B+' ? 'selected' : ''; ?>>B+</option>
                        <option value="B-" <?php echo $blood_group == 'B-' ? 'selected' : ''; ?>>B-</option>
                        <option value="AB+" <?php echo $blood_group == 'AB+' ? 'selected' : ''; ?>>AB+</option>
                        <option value="AB-" <?php echo $blood_group == 'AB-' ? 'selected' : ''; ?>>AB-</option>
                        <option value="O+" <?php echo $blood_group == 'O+' ? 'selected' : ''; ?>>O+</option>
                        <option value="O-" <?php echo $blood_group == 'O-' ? 'selected' : ''; ?>>O-</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">City</label>
                    <input type="text" name="city" value="<?php echo htmlspecialchars($city); ?>" class="w-full px-3 py-2 border rounded-lg" placeholder="Enter city">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">State</label>
                    <input type="text" name="state" value="<?php echo htmlspecialchars($state); ?>" class="w-full px-3 py-2 border rounded-lg" placeholder="Enter state">
                </div>
                <div class="md:col-span-3">
                    <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">
                        Search Donors
                    </button>
                </div>
            </form>
        </div>

        <!-- Donors List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (isset($donors) && count($donors) > 0): ?>
                <?php foreach ($donors as $donor): ?>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($donor['name']); ?></h3>
                        <div class="space-y-2">
                            <p><strong>Blood Group:</strong> <?php echo htmlspecialchars($donor['blood_group']); ?></p>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($donor['city'] . ', ' . $donor['state']); ?></p>
                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($donor['phone']); ?></p>
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <button onclick="showContactInfo(<?php echo $donor['id']; ?>)" class="mt-4 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                    Contact Donor
                                </button>
                            <?php else: ?>
                                <p class="text-red-600 mt-4">Please <a href="login.php" class="underline">login</a> to contact donors</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-600">No donors found matching your criteria.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function showContactInfo(donorId) {
        // In a real application, you would fetch the contact information from the server
        alert('Contact information will be displayed here. In a real application, this would be fetched from the server.');
    }
    </script>
</body>
</html> 