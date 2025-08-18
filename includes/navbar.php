<?php $user = $_SESSION['user'] ?? null; ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/restaurant-ms/index.php">Foodie RMS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="/restaurant-ms/index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="/restaurant-ms/menu.php">Our Menu</a></li>
        <li class="nav-item"><a class="nav-link" href="/restaurant-ms/about.php">About Us</a></li>
        <li class="nav-item"><a class="nav-link" href="/restaurant-ms/contact.php">Contact Us</a></li>
        <?php if($user): ?>
          <li class="nav-item"><a class="nav-link" href="/restaurant-ms/auth/logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="/restaurant-ms/auth/login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
