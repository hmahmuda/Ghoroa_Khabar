<?php
include 'db.php';
session_start();

// Redirect if already logged in
if (is_logged_in()) {
    redirect('home.html');
}

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $contact = sanitize_input($_POST['contact']);
    $address = sanitize_input($_POST['address']);
    
    // Validation
    $errors = [];
    
    if (empty($name) || strlen($name) < 2) {
        $errors[] = "Name must be at least 2 characters long.";
    }
    
    if (!validate_email($email)) {
        $errors[] = "Please enter a valid email address.";
    }
    
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    
    if (empty($contact) || strlen($contact) < 10) {
        $errors[] = "Please enter a valid contact number.";
    }
    
    if (empty($address)) {
        $errors[] = "Please enter your address.";
    }
    
    // Check if email already exists
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $errors[] = "Email already registered. Please use a different email or login.";
            }
            $stmt->close();
        }
    }
    
    // If no errors, proceed with registration
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = 'customer';
        
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, contact, address, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        if ($stmt) {
            $stmt->bind_param("ssssss", $name, $email, $hashed_password, $role, $contact, $address);
            
            if ($stmt->execute()) {
                $success_message = "Registration successful! You can now login.";
            } else {
                $error_message = "Registration failed. Please try again.";
            }
            $stmt->close();
        } else {
            $error_message = "Database error occurred. Please try again.";
        }
    } else {
        $error_message = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Ghoroa Khabar</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Navbar -->
<section class="navbar">
    <div class="container">
        <div class="logo">
            <a href="home.html"><img src="images/logo.png" alt="Ghoroa Khabar" class="img-responsive"></a>
        </div>
        <div class="menu text-right">
            <ul>
                <li><a href="home.html">Home</a></li>
                <li><a href="categories.html">Categories</a></li>
                <li><a href="foods.php">Foods</a></li>
                <li><a href="login.php">Log In</a></li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
</section>

<!-- Registration Form -->
<section class="food-search text-center">
    <div class="container">
        <h2>Create Your Account</h2>
        
        <?php if ($error_message): ?>
            <div class="error-message" style="color: red; margin: 10px 0; background: #ffe6e6; padding: 10px; border-radius: 4px;"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <?php if ($success_message): ?>
            <div class="success-message" style="color: green; margin: 10px 0; background: #e6ffe6; padding: 10px; border-radius: 4px;">
                <?php echo $success_message; ?>
                <br><a href="login.php" style="color: #ff6b6b;">Login Now</a>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="register.php" style="max-width: 500px; margin: 0 auto; padding: 20px;">
            <div style="margin-bottom: 15px;">
                <label for="name" style="display: block; margin-bottom: 5px;">Full Name:</label>
                <input type="text" id="name" name="name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="email" style="display: block; margin-bottom: 5px;">Email:</label>
                <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="password" style="display: block; margin-bottom: 5px;">Password:</label>
                <input type="password" id="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="confirm_password" style="display: block; margin-bottom: 5px;">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="contact" style="display: block; margin-bottom: 5px;">Contact Number:</label>
                <input type="tel" id="contact" name="contact" required value="<?php echo isset($_POST['contact']) ? htmlspecialchars($_POST['contact']) : ''; ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="address" style="display: block; margin-bottom: 5px;">Address:</label>
                <textarea id="address" name="address" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; height: 80px;"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
            </div>
            
            <button type="submit" style="background-color: #ff6b6b; color: white; padding: 12px 30px; border: none; border-radius: 4px; cursor: pointer; width: 100%;">Register</button>
        </form>
        
        <p style="margin-top: 20px;">
            Already have an account? <a href="login.php" style="color: #ff6b6b;">Login here</a>
        </p>
    </div>
</section>

<!-- Footer -->
<section class="footer">
    <div class="container text-center">
        <p>All rights reserved. Designed By <a href="#">You</a></p>
    </div>
</section>

</body>
</html>
