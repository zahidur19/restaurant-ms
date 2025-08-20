<?php
session_start();
$title = "Our Menu | Foodie RMS";
include __DIR__."/includes/header.php";
include __DIR__."/includes/config.php";

$sql = "SELECT id, name, description, price, image 
        FROM menu 
        WHERE status='active' 
        ORDER BY id DESC 
        LIMIT 9";
$res = mysqli_query($conn, $sql);
$items = [];
if ($res) { while($row = mysqli_fetch_assoc($res)) { $items[] = $row; } }
$shown = count($items);

function h($s){ return htmlspecialchars($s ?? "", ENT_QUOTES, 'UTF-8'); }
?>

<h2 class="mb-3 text-center">Our Menu</h2>
<p class="text-center text-muted mb-4">Here is Our Signature Menu Items</p>

<div class="row g-3">
  <?php foreach($items as $m): ?>
    <div class="col-md-4">
      <div class="card h-100 shadow-sm">
        <img src="<?php echo h($m['image']); ?>" class="card-img-top" alt="Food">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title"><?php echo h($m['name']); ?></h5>
          <p class="card-text text-muted"><?php echo h($m['description']); ?></p>
          <div class="mt-auto d-flex flex-column gap-2">
            <span class="fw-bold">৳ <?php echo h($m['price']); ?></span>

            <?php if(isset($_SESSION["user"]) && $_SESSION["user"]["role"]==="customer"): ?>
              <form method="POST" action="/restaurant-ms/customer/place_order.php">
                <input type="hidden" name="menu_id" value="<?php echo (int)$m['id']; ?>">
                <input type="number" name="qty" value="1" min="1" class="form-control">
                <button type="submit" class="btn btn-success w-100 mt-2">Order Now</button>
              </form>
            <?php else: ?>
              <a href="/restaurant-ms/auth/login.php" class="btn btn-primary w-100">Login to Order</a>
            <?php endif; ?>

          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>

  <?php for($i=$shown; $i<9; $i++): ?>
    <div class="col-md-4">
      <div class="card h-100 shadow-sm">
        <img src="https://picsum.photos/seed/food<?php echo $i+1; ?>/600/400" class="card-img-top" alt="Food">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Coming Soon</h5>
          <p class="card-text text-muted">New item will be added soon.</p>
          <span class="badge bg-secondary mt-auto align-self-start">Not available</span>
        </div>
      </div>
    </div>
  <?php endfor; ?>
</div>

<?php include __DIR__."/includes/footer.php"; ?>
