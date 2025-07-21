<?php
require_once 'config.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $patient_name = $_POST['patient_name'];
    $blood_group = $_POST['blood_group'];
    $hospital_name = $_POST['hospital_name'];
    $hospital_address = $_POST['hospital_address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $contact_person = $_POST['contact_person'];
    $contact_number = $_POST['contact_number'];
    $required_date = $_POST['required_date'];

    try {
        $stmt = $pdo->prepare("INSERT INTO blood_requests (patient_name, blood_group, hospital_name, hospital_address, city, state, contact_person, contact_number, required_date, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$patient_name, $blood_group, $hospital_name, $hospital_address, $city, $state, $contact_person, $contact_number, $required_date, $_SESSION['user_id']]);
        
        $_SESSION['success'] = "Blood request submitted successfully!";
        header("Location: blood_requests.php");
        exit();
    } catch(PDOException $e) {
        $_SESSION['error'] = "Error submitting blood request.";
    }
}

// Get all blood requests
try {
    $stmt = $pdo->query("SELECT br.*, u.name as requester_name FROM blood_requests br JOIN users u ON br.created_by = u.id ORDER BY br.created_at DESC");
    $requests = $stmt->fetchAll();
} catch(PDOException $e) {
    $_SESSION['error'] = "Error fetching blood requests.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Requests - Blood Bank Portal</title>
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
        <h1 class="text-3xl font-bold mb-8">Blood Requests</h1>

        <?php if(isset($_SESSION['user_id'])): ?>
            <!-- Request Form -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                <h2 class="text-xl font-bold mb-4">Submit New Blood Request</h2>
                <form method="POST" action="" class="space-y-4">
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
                            <label class="block text-gray-700">Required Date</label>
                            <input type="date" name="required_date" required class="w-full px-3 py-2 border rounded-lg">
                        </div>
                    </div>
                    
                    <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">
                        Submit Request
                    </button>
                </form>
            </div>
        <?php else: ?>
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-8">
                <p>Please <a href="login.php" class="text-red-600 hover:underline">login</a> to submit a blood request.</p>
            </div>
        <?php endif; ?>

        <!-- Blood Requests List -->
        <div class="space-y-4">
            <?php if (isset($requests) && count($requests) > 0): ?>
                <?php foreach ($requests as $request): ?>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-xl font-bold"><?php echo htmlspecialchars($request['patient_name']); ?></h3>
                                <p class="text-gray-600">Requested by <?php echo htmlspecialchars($request['requester_name']); ?></p>
                            </div>
                            <span class="px-3 py-1 text-sm rounded <?php 
                                echo $request['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    ($request['status'] == 'fulfilled' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800');
                            ?>">
                                <?php echo ucfirst($request['status']); ?>
                            </span>
                        </div>
                        
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p><strong>Blood Group:</strong> <?php echo htmlspecialchars($request['blood_group']); ?></p>
                                <p><strong>Hospital:</strong> <?php echo htmlspecialchars($request['hospital_name']); ?></p>
                                <p><strong>Location:</strong> <?php echo htmlspecialchars($request['city'] . ', ' . $request['state']); ?></p>
                            </div>
                            <div>
                                <p><strong>Contact Person:</strong> <?php echo htmlspecialchars($request['contact_person']); ?></p>
                                <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($request['contact_number']); ?></p>
                                <p><strong>Required Date:</strong> <?php echo date('M d, Y', strtotime($request['required_date'])); ?></p>
                            </div>
                        </div>
                        
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <div class="mt-4">
                                <a href="find_donor.php?blood_group=<?php echo urlencode($request['blood_group']); ?>&city=<?php echo urlencode($request['city']); ?>&state=<?php echo urlencode($request['state']); ?>" 
                                   class="text-red-600 hover:underline">
                                    Find Donors
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-8">
                    <p class="text-gray-600">No blood requests found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 