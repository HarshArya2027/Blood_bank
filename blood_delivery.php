<?php
session_start();
require_once 'config.php';

$success = $error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $patient_name = trim($_POST['patient_name']);
    $blood_group = trim($_POST['blood_group']);
    $hospital_name = trim($_POST['hospital_name']);
    $hospital_address = trim($_POST['hospital_address']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $contact_person = trim($_POST['contact_person']);
    $contact_number = trim($_POST['contact_number']);
    $priority = trim($_POST['priority']);
    $comments = trim($_POST['comments']);

    if (!$patient_name || !$blood_group || !$hospital_name || !$hospital_address || !$city || !$state || !$contact_person || !$contact_number || !$priority) {
        $error = 'All fields except comments are required.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO blood_deliveries (patient_name, blood_group, hospital_name, hospital_address, city, state, contact_person, contact_number, priority, comments, requested_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$patient_name, $blood_group, $hospital_name, $hospital_address, $city, $state, $contact_person, $contact_number, $priority, $comments, $_SESSION['user_id']]);
            $success = 'Blood delivery scheduled successfully!';
        } catch(PDOException $e) {
            $error = 'Error scheduling blood delivery.';
        }
    }
}

// Fetch recent deliveries
try {
    $stmt = $pdo->query("SELECT * FROM blood_deliveries ORDER BY created_at DESC LIMIT 10");
    $deliveries = $stmt->fetchAll();
} catch(PDOException $e) {
    $deliveries = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Delivery - Blood Bank Portal</title>
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
                <a href="blood_delivery.php" class="px-3 py-2 rounded hover:bg-white hover:text-red-600 transition font-medium">Blood Delivery</a>
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
        <h1 class="text-3xl font-bold mb-8 text-center">Blood Delivery</h1>
        <?php if(isset($_SESSION['user_id'])): ?>
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-8 mb-10">
            <h2 class="text-xl font-bold mb-4">Schedule Blood Delivery</h2>
            <?php if ($success): ?>
                <div class="mb-4 p-3 rounded bg-green-100 text-green-700 text-center"><?php echo $success; ?></div>
            <?php elseif ($error): ?>
                <div class="mb-4 p-3 rounded bg-red-100 text-red-700 text-center"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700">Patient Name</label>
                        <input type="text" name="patient_name" required class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-gray-700">Blood Group</label>
                        <select name="blood_group" required class="w-full px-3 py-2 border rounded-lg">
                            <option value="">Select Blood Group</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700">Hospital Name</label>
                        <input type="text" name="hospital_name" required class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-gray-700">Hospital Address</label>
                        <input type="text" name="hospital_address" required class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-gray-700">City</label>
                        <input type="text" name="city" required class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-gray-700">State</label>
                        <input type="text" name="state" required class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-gray-700">Contact Person</label>
                        <input type="text" name="contact_person" required class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-gray-700">Contact Number</label>
                        <input type="tel" name="contact_number" required class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-gray-700">Priority</label>
                        <select name="priority" required class="w-full px-3 py-2 border rounded-lg">
                            <option value="Normal">Normal</option>
                            <option value="Urgent">Urgent</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700">Comments</label>
                    <textarea name="comments" rows="3" class="w-full px-3 py-2 border rounded-lg"></textarea>
                </div>
                <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 font-semibold">Schedule Delivery</button>
            </form>
        </div>
        <?php else: ?>
        <div class="max-w-2xl mx-auto bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-10">
            <p>Please <a href="login.php" class="text-red-600 hover:underline">login</a> to schedule a blood delivery.</p>
        </div>
        <?php endif; ?>

        <!-- Recent Deliveries -->
        <div class="max-w-4xl mx-auto">
            <h2 class="text-xl font-bold mb-4">Recent Blood Deliveries</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php if (count($deliveries) > 0): ?>
                    <?php foreach ($deliveries as $delivery): ?>
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-bold mb-2"><?php echo htmlspecialchars($delivery['patient_name']); ?> (<?php echo htmlspecialchars($delivery['blood_group']); ?>)</h3>
                            <p><strong>Hospital:</strong> <?php echo htmlspecialchars($delivery['hospital_name']); ?></p>
                            <p><strong>Address:</strong> <?php echo htmlspecialchars($delivery['hospital_address'] . ', ' . $delivery['city'] . ', ' . $delivery['state']); ?></p>
                            <p><strong>Contact:</strong> <?php echo htmlspecialchars($delivery['contact_person'] . ' (' . $delivery['contact_number'] . ')'); ?></p>
                            <p><strong>Priority:</strong> <span class="<?php echo $delivery['priority'] == 'Urgent' ? 'text-red-600 font-bold' : 'text-gray-700'; ?>"><?php echo htmlspecialchars($delivery['priority']); ?></span></p>
                            <p><strong>Comments:</strong> <?php echo htmlspecialchars($delivery['comments']); ?></p>
                            <p class="text-sm text-gray-500 mt-2">Scheduled on <?php echo date('M d, Y H:i', strtotime($delivery['created_at'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full text-center py-8">
                        <p class="text-gray-600">No blood deliveries found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html> 