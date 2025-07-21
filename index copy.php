<?php
require_once 'config.php';

// Fetch statistics
try {
    $total_donors = $pdo->query("SELECT COUNT(*) FROM users WHERE is_donor = 1")->fetchColumn();
    $total_requests = $pdo->query("SELECT COUNT(*) FROM blood_requests")->fetchColumn();
    $recent_donations = $pdo->query("SELECT COUNT(*) FROM donations WHERE donation_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();
    // Set number of hospitals manually or fetch from DB if you have a hospitals table
    $total_hospitals = 15; // Example: 15 partner hospitals
} catch (PDOException $e) {
    $total_donors = $total_requests = $recent_donations = $total_hospitals = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank Portal</title>
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

    <!-- Hero Section -->
    <div class="bg-red-600 text-white py-16 px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Donate Blood, Save Lives</h1>
        <p class="text-lg md:text-xl mb-6">Join our community of lifesavers. Every drop counts!</p>
        <a href="register.php" class="inline-block bg-white text-red-600 font-bold px-6 py-3 rounded shadow hover:bg-red-100 transition duration-300 hover:scale-110 group">Register as Donor</a>
        <a href="find_donor.php" class="inline-block ml-4 bg-white text-red-600 font-bold px-6 py-3 rounded shadow hover:bg-red-100 transition duration-300 hover:scale-110 group">Find Donor</a>
    </div>

    <!-- Impact Statistics -->
    <div class="container mx-auto py-12 grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
        <div class="bg-white rounded-lg shadow p-6 transition-transform hover:scale-110 group">
            <div class="text-3xl font-bold text-red-600 mb-2"><?php echo $total_donors; ?></div>
            <div class="text-gray-700">Registered Donors</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 transition-transform hover:scale-110 group">
            <div class="text-3xl font-bold text-red-600 mb-2"><?php echo $total_requests; ?></div>
            <div class="text-gray-700">Blood Requests</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 transition-transform hover:scale-110 group">
            <div class="text-3xl font-bold text-red-600 mb-2"><?php echo $recent_donations; ?></div>
            <div class="text-gray-700">Donations (Last 30 Days)</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 transition-transform hover:scale-110 group">
            <div class="text-3xl font-bold text-red-600 mb-2"><?php echo $total_hospitals; ?></div>
            <div class="text-gray-700">Partner Hospitals</div>
        </div>
    </div>

    <!-- Awards & Recognition Section -->
    <div class="container mx-auto py-12">
        <h2 class="text-2xl font-bold text-center mb-8 text-red-700">Awards & Recognition</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-md text-center transition-transform hover:scale-110 group">
                <i class="fas fa-trophy text-yellow-500 text-4xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Best Social Impact 2023</h3>
                <p>Recognized for outstanding contribution to community health and blood donation awareness.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md text-center transition-transform hover:scale-110 group">
                <i class="fas fa-medal text-red-500 text-4xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Innovation in Healthcare</h3>
                <p>Awarded for innovative use of technology in connecting donors and recipients efficiently.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md text-center transition-transform hover:scale-110 group">
                <i class="fas fa-star text-green-500 text-4xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Community Choice Award</h3>
                <p>Voted as the most trusted blood bank portal by users and partner organizations.</p>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="container mx-auto py-12">
        <h2 class="text-2xl font-bold text-center mb-8 text-red-700 ">How It Works</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-lg shadow p-6 text-center transition-transform hover:scale-110 group">
                <div class="text-red-600 text-4xl mb-2"><i class="fas fa-user-plus"></i></div>
                <h3 class="font-bold mb-2">1. Register</h3>
                <p>Create an account and join our donor community.</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 text-center transition-transform hover:scale-110 group  ">
                <div class="text-red-600 text-4xl mb-2"><i class="fas fa-hand-holding-medical"></i></div>
                <h3 class="font-bold mb-2">2. Donate or Request</h3>
                <p>Donate blood or request blood when needed, easily and securely.</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 text-center transition-transform hover:scale-110 group  ">
                <div class="text-red-600 text-4xl mb-2"><i class="fas fa-heartbeat"></i></div>
                <h3 class="font-bold mb-2">3. Save Lives</h3>
                <p>Your contribution makes a real difference in someone's life.</p>
            </div>
        </div>
    </div>

    <!-- Learn About Donation Section -->
    <div class="container mx-auto py-12">
        <h2 class="text-3xl font-bold text-center mb-8 text-red-700">Learn About Donation</h2>
        <div class="flex flex-col md:flex-row items-center justify-center gap-8 bg-white rounded-lg shadow-lg p-8">
            <div class="flex-1 w-full max-w-lg">
                <div class="mb-6">
                    <p class="text-lg font-semibold text-center mb-4">Select your Blood Type</p>
                    <div id="blood-type-buttons" class="flex flex-wrap justify-center gap-3">
                        <button type="button" data-type="A+" class="blood-btn border-2 border-red-400 rounded-lg px-6 py-2 text-lg font-semibold">A+</button>
                        <button type="button" data-type="O+" class="blood-btn border-2 border-red-400 rounded-lg px-6 py-2 text-lg font-semibold">O+</button>
                        <button type="button" data-type="B+" class="blood-btn border-2 border-red-400 rounded-lg px-6 py-2 text-lg font-semibold">B+</button>
                        <button type="button" data-type="AB+" class="blood-btn border-2 border-red-400 rounded-lg px-6 py-2 text-lg font-semibold">AB+</button>
                        <button type="button" data-type="A-" class="blood-btn border-2 border-red-400 rounded-lg px-6 py-2 text-lg font-semibold">A-</button>
                        <button type="button" data-type="O-" class="blood-btn border-2 border-red-400 rounded-lg px-6 py-2 text-lg font-semibold">O-</button>
                        <button type="button" data-type="B-" class="blood-btn border-2 border-red-400 rounded-lg px-6 py-2 text-lg font-semibold">B-</button>
                        <button type="button" data-type="AB-" class="blood-btn border-2 border-red-400 rounded-lg px-6 py-2 text-lg font-semibold">AB-</button>
                    </div>
                </div>
                <div class="bg-orange-200 rounded-lg p-4 mb-4 flex items-center">
                    <div class="w-16 h-16 bg-orange-300 rounded-full mr-4"></div>
                    <div>
                        <p class="text-lg font-bold">You can take from</p>
                        <p id="can-take-from" class="text-lg mt-1">O- &nbsp; A- &nbsp; B- &nbsp; AB-</p>
                    </div>
                </div>
                <div class="bg-blue-200 rounded-lg p-4 flex items-center">
                    <div class="w-16 h-16 bg-blue-300 rounded-full mr-4"></div>
                    <div>
                        <p class="text-lg font-bold">You can give to</p>
                        <p id="can-give-to" class="text-lg mt-1">AB+ &nbsp; AB-</p>
                    </div>
                </div>
            </div>
            <div class="flex-1 flex flex-col items-center">
                <img src="image.png" alt="Blood Donation Illustration" class="w-72 h-auto mb-4">
                <p class="text-center text-lg">One Blood Donation can save upto <span class="text-red-600 font-bold">Three Lives</span></p>
            </div>
        </div>
    </div>
    <script>
    const compatibility = {
        'A+':   { take: ['A+', 'A-', 'O+', 'O-'], give: ['A+', 'AB+'] },
        'O+':   { take: ['O+', 'O-'], give: ['O+', 'A+', 'B+', 'AB+'] },
        'B+':   { take: ['B+', 'B-', 'O+', 'O-'], give: ['B+', 'AB+'] },
        'AB+':  { take: ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'], give: ['AB+'] },
        'A-':   { take: ['A-', 'O-'], give: ['A+', 'A-', 'AB+', 'AB-'] },
        'O-':   { take: ['O-'], give: ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] },
        'B-':   { take: ['B-', 'O-'], give: ['B+', 'B-', 'AB+', 'AB-'] },
        'AB-':  { take: ['A-', 'B-', 'AB-', 'O-'], give: ['AB+', 'AB-'] }
    };

    const btns = document.querySelectorAll('.blood-btn');
    const canTakeFrom = document.getElementById('can-take-from');
    const canGiveTo = document.getElementById('can-give-to');

    function updateCompatibility(type) {
        canTakeFrom.innerHTML = compatibility[type].take.join(' &nbsp; ');
        canGiveTo.innerHTML = compatibility[type].give.join(' &nbsp; ');
        btns.forEach(btn => {
            if (btn.getAttribute('data-type') === type) {
                btn.classList.add('bg-red-600', 'text-white', 'border-red-600');
            } else {
                btn.classList.remove('bg-red-600', 'text-white', 'border-red-600');
            }
        });
    }

    btns.forEach(btn => {
        btn.addEventListener('click', () => {
            updateCompatibility(btn.getAttribute('data-type'));
        });
    });
    // Set default selection to AB-
    updateCompatibility('AB-');
    </script>

    <!-- Features Section -->
    
    <h2 class="text-4xl font-bold text-center text-red-700 mb-12 transition-transform hover:scale-110 group">Our Main Services</h2>
    <div class="container mx-auto py-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <a href="find_donor.php" class="inline-block transition-transform hover:scale-110 group">
                    <i class="fas fa-search text-red-600 text-4xl mb-4 group-hover:text-red-800 transition-colors"></i>
                </a>
                <h3 class="text-xl font-semibold mb-2">Find Donors</h3>
                <p>Quickly find blood donors in your area based on blood group and location.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <a href="blood_requests.php" class="inline-block transition-transform hover:scale-110 group">
                    <i class="fas fa-hand-holding-medical text-red-600 text-4xl mb-4 group-hover:text-red-800 transition-colors"></i>
                </a>
                <h3 class="text-xl font-semibold mb-2">Request Blood</h3>
                <p>Post blood requests and get connected with potential donors.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <a href="register.php" class="inline-block transition-transform hover:scale-110 group">
                    <i class="fas fa-user-plus text-red-600 text-4xl mb-4 group-hover:text-red-800 transition-colors"></i>
                </a>
                <h3 class="text-xl font-semibold mb-2">Become a Donor</h3>
                <p>Register as a blood donor and help save lives in your community.</p>
            </div>
        </div>
    </div>        
</div>
    <!-- Other Services Section -->
    <div class="container mx-auto py-8 md:py-12 px-4">
        <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-center mb-6 md:mb-8 lg:mb-12 text-red-700">Other Services</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6 justify-items-center">
            <div class="bg-white rounded-lg md:rounded-xl shadow-lg p-4 md:p-6 flex flex-col items-center w-full max-w-xs group hover:shadow-xl transition">
                <i class="fas fa-search text-2xl md:text-3xl text-red-500 mb-3 md:mb-4 transition-transform duration-200 group-hover:scale-125 group-hover:text-red-700"></i>
                <span class="text-sm md:text-base font-medium text-gray-800 text-center">Blood Availability Search</span>
            </div>
            <div class="bg-white rounded-lg md:rounded-xl shadow-lg p-4 md:p-6 flex flex-col items-center w-full max-w-xs group hover:shadow-xl transition">
                <i class="fas fa-hospital text-2xl md:text-3xl text-red-500 mb-3 md:mb-4 transition-transform duration-200 group-hover:scale-125 group-hover:text-red-700"></i>
                <span class="text-sm md:text-base font-medium text-gray-800 text-center">Blood Bank Directory</span>
            </div>
            <div class="bg-white rounded-lg md:rounded-xl shadow-lg p-4 md:p-6 flex flex-col items-center w-full max-w-xs group hover:shadow-xl transition">
                <i class="fas fa-users text-2xl md:text-3xl text-red-500 mb-3 md:mb-4 transition-transform duration-200 group-hover:scale-125 group-hover:text-red-700"></i>
                <span class="text-sm md:text-base font-medium text-gray-800 text-center">Blood Donation Camps</span>
            </div>
            <div class="bg-white rounded-lg md:rounded-xl shadow-lg p-4 md:p-6 flex flex-col items-center w-full max-w-xs group hover:shadow-xl transition">
                <i class="fas fa-id-card text-2xl md:text-3xl text-red-500 mb-3 md:mb-4 transition-transform duration-200 group-hover:scale-125 group-hover:text-red-700"></i>
                <span class="text-sm md:text-base font-medium text-gray-800 text-center">Donor Login</span>
            </div>
            <div class="bg-white rounded-lg md:rounded-xl shadow-lg p-4 md:p-6 flex flex-col items-center w-full max-w-xs group hover:shadow-xl transition">
                <i class="fas fa-clipboard-list text-2xl md:text-3xl text-red-500 mb-3 md:mb-4 transition-transform duration-200 group-hover:scale-125 group-hover:text-red-700"></i>
                <span class="text-sm md:text-base font-medium text-gray-800 text-center">Register Voluntary Blood Camp</span>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 Blood Bank Portal. All rights reserved.</p>
        </div>
    </footer>
</body>
</html> 