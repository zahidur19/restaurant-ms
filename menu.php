<?php
$title = "Our Menu | Foodie RMS";
include __DIR__."/includes/header.php";
include __DIR__."/includes/config.php";

$sql = "SELECT id, name, description, price, image FROM menu WHERE status='active' ORDER BY id DESC LIMIT 9";
$res = mysqli_query($conn, $sql);
$items = [];
if ($res) {
  while($row = mysqli_fetch_assoc($res)) { $items[] = $row; }
}
$shown = count($items);
?>

<h2 class="mb-3 text-center">Our Menu</h2>
<p class="text-center text-muted mb-4">3 rows × 3 cards (up to 9 items)</p>

<div class="row g-3">
  <?php foreach($items as $m): ?>
    <div class="col-md-4">
      <div class="card h-100 shadow-sm">
        <img src="<?php echo htmlspecialchars($m['image']); ?>" class="card-img-top" alt="Food">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title"><?php echo htmlspecialchars($m['name']); ?></h5>
          <p class="card-text text-muted"><?php echo htmlspecialchars($m['description']); ?></p>
          <div class="mt-auto d-flex justify-content-between align-items-center">
            <span class="fw-bold">৳ <?php echo htmlspecialchars($m['price']); ?></span>
            <a href="/restaurant-ms/auth/login.php" class="btn btn-sm btn-primary">Add to Cart</a>
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
