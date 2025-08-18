<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo isset($title) ? $title : "Foodie RMS"; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Absolute path ব্যবহার করেছি: /restaurant-ms/... (ফোল্ডার নাম অবশ্যই restaurant-ms হবে) -->
  <link rel="stylesheet" href="/restaurant-ms/assets/css/styles.css">
</head>
<body>
<?php include __DIR__ . "/navbar.php"; ?>
<div class="container py-4">
