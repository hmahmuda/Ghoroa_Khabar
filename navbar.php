<?php
session_start();
?>
<div class="menu text-right">
  <ul>
    <li><a href="home.html">Home</a></li>
    <li><a href="categories.html">Categories</a></li>
    <li><a href="foods.html">Foods</a></li>
    <?php if (isset($_SESSION['user_id'])): ?>
      <li><a href="logout.php">Logout</a></li>
    <?php else: ?>
      <li><a href="login.html">Log In</a></li>
    <?php endif; ?>
  </ul>
</div>
