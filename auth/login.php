<?php
session_start();
require_once "../includes/config.php";

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $pass  = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        if (password_verify($pass, $user["password"])) {
            $_SESSION["user"] = $user;

            if ($user["role"] === "admin") {
                header("Location: ../admin/dashboard_admin.php");
            } elseif ($user["role"] === "chef") {
                header("Location: ../chef/dashboard_chef.php");
            } else {
                header("Location: ../customer/dashboard_customer.php");
            }
            exit;
        } else {
            $msg = "Invalid password.";
        }
    } else {
        $msg = "No account found with that email.";
    }
}
?>
<?php include "../includes/header.php"; ?>
<div class="row justify-content-center mt-5">
  <div class="col-md-4">
    <h3 class="mb-3">Login</h3>
    <?php if($msg): ?><div class="alert alert-danger"><?php echo $msg; ?></div><?php endif; ?>
    <form method="POST">
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-success w-100">Login</button>
    </form>
    <p class="mt-3">Don’t have an account? <a href="register.php">Register here</a></p>
  </div>
</div>
<?php include "../includes/footer.php"; ?>
