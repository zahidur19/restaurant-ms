<?php
session_start();
require_once "../includes/config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name  = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $pass  = $_POST["password"];
    $role  = $_POST["role"]; // customer, admin, chef

    if ($name && $email && $pass) {
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $name, $email, $hash, $role);

        if ($stmt->execute()) {
            $message = "Registration successful. Please login.";
        } else {
            $message = "Error: " . $conn->error;
        }
    } else {
        $message = "All fields are required!";
    }
}
?>
<?php include "../includes/header.php"; ?>
<div class="row justify-content-center mt-5">
  <div class="col-md-5">
    <h3 class="mb-3">Register</h3>
    <?php if($message): ?><div class="alert alert-info"><?php echo $message; ?></div><?php endif; ?>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Role</label>
        <select name="role" class="form-select">
          <option value="customer">Customer</option>
          <option value="chef">Chef</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>
    <p class="mt-3">Already registered? <a href="login.php">Login here</a></p>
  </div>
</div>
<?php include "../includes/footer.php"; ?>
