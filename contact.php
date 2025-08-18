<?php
$title = "Contact Us | Foodie RMS";
include __DIR__."/includes/header.php";
include __DIR__."/includes/config.php";

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? "");
  $email = trim($_POST['email'] ?? "");
  $message = trim($_POST['message'] ?? "");

  if ($name === "" || $email === "" || $message === "") {
    $error = "Please fill in all fields.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Please enter a valid email.";
  } else {
    $stmt = mysqli_prepare($conn, "INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $message);
    if (mysqli_stmt_execute($stmt)) {
      $success = "Thanks! Your message has been received.";
    } else {
      $error = "Failed to send message. Please try again.";
    }
    mysqli_stmt_close($stmt);
  }
}
?>

<h2 class="mb-3">Contact Us</h2>

<?php if($success): ?>
  <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>
<?php if($error): ?>
  <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="post" class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($_POST['name'] ?? ""); ?>">
  </div>
  <div class="col-md-6">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($_POST['email'] ?? ""); ?>">
  </div>
  <div class="col-12">
    <label class="form-label">Message</label>
    <textarea name="message" rows="5" class="form-control" required><?php echo htmlspecialchars($_POST['message'] ?? ""); ?></textarea>
  </div>
  <div class="col-12">
    <button class="btn btn-primary">Send Message</button>
  </div>
</form>

<?php include __DIR__."/includes/footer.php"; ?>
