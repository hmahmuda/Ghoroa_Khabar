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
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password']; // Don't sanitize password before verification
    
    // Validate email
    if (!validate_email($email)) {
        $error_message = "Please enter a valid email address.";
    } else {
        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['email'] = $user['email'];
                    
                    $success_message = "Login successful! Redirecting...";
                    // Redirect after 2 seconds
                    header("refresh:2;url=home.html");
                } else {
                    $error_message = "Incorrect password!";
                }
            } else {
                $error_message = "User not found!";
            }
            $stmt->close();
        } else {
            $error_message = "Database error occurred. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Ghoroa Khabar</title>
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
                <li><a href="login.html">Log In</a></li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
</section>

<!-- Login Form -->
<section class="food-search text-center">
    <div class="container">
        <h2>Login to Your Account</h2>
        
        <?php if ($error_message): ?>
            <div class="error-message" style="color: red; margin: 10px 0;"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <?php if ($success_message): ?>
            <div class="success-message" style="color: green; margin: 10px 0;"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="login.php" style="max-width: 400px; margin: 0 auto; padding: 20px;">
            <div style="margin-bottom: 15px;">
                <label for="email" style="display: block; margin-bottom: 5px;">Email:</label>
                <input type="email" id="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="password" style="display: block; margin-bottom: 5px;">Password:</label>
                <input type="password" id="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            
            <button type="submit" style="background-color: #ff6b6b; color: white; padding: 12px 30px; border: none; border-radius: 4px; cursor: pointer; width: 100%;">Login</button>
        </form>
        
        <p style="margin-top: 20px;">
            Don't have an account? <a href="register.php" style="color: #ff6b6b;">Register here</a>
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
