<?php
require_once __DIR__ . "/../includes/auth_guard.php";
require_role("admin");
require_once __DIR__ . "/../includes/config.php";
include __DIR__ . "/../includes/header.php";

$msg = "";
// Create/Update
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name = trim($_POST['name'] ?? '');
  $price = (float)($_POST['price'] ?? 0);
  $desc = trim($_POST['description'] ?? '');
  $image = trim($_POST['image'] ?? '');
  $status = ($_POST['status'] ?? 'active') === 'inactive' ? 'inactive' : 'active';

  if(isset($_POST['create'])){
    $stmt = $conn->prepare("INSERT INTO menu (name, description, price, image, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $name, $desc, $price, $image, $status);
    $msg = $stmt->execute() ? "Item created." : "Create failed.";
    $stmt->close();
  }

  if(isset($_POST['update']) && isset($_POST['id'])){
    $id = (int)$_POST['id'];
    $stmt = $conn->prepare("UPDATE menu SET name=?, description=?, price=?, image=?, status=? WHERE id=?");
    $stmt->bind_param("ssdssi", $name, $desc, $price, $image, $status, $id);
    $msg = $stmt->execute() ? "Item updated." : "Update failed.";
    $stmt->close();
  }
}

// Delete
if(isset($_GET['delete'])){
  $id=(int)$_GET['delete'];
  $conn->query("DELETE FROM menu WHERE id=$id");
  header("Location: /restaurant-ms/admin/menu_manage.php");
  exit;
}

$items = $conn->query("SELECT * FROM menu ORDER BY id DESC");
function h($s){ return htmlspecialchars($s ?? "", ENT_QUOTES, 'UTF-8'); }
?>
<div class="container mt-5">
  <h2>Manage Menu</h2>
  <?php if($msg): ?><div class="alert alert-info mt-2"><?php echo h($msg); ?></div><?php endif; ?>

  <div class="card mt-3">
    <div class="card-body">
      <form method="post" class="row g-2">
        <div class="col-md-3">
          <input class="form-control" name="name" placeholder="Item name" required>
        </div>
        <div class="col-md-2">
          <input class="form-control" name="price" type="number" step="0.01" placeholder="Price" required>
        </div>
        <div class="col-md-3">
          <input class="form-control" name="image" placeholder="/restaurant-ms/assets/images/burger.jpg" required>
        </div>
        <div class="col-md-2">
          <select class="form-select" name="status">
            <option value="active">active</option>
            <option value="inactive">inactive</option>
          </select>
        </div>
        <div class="col-md-12">
          <textarea class="form-control" name="description" rows="2" placeholder="Description"></textarea>
        </div>
        <div class="col-md-12">
          <button class="btn btn-primary" name="create">Add Item</button>
        </div>
      </form>
    </div>
  </div>

  <h5 class="mt-4">All Items</h5>
  <table class="table table-striped align-middle">
    <thead><tr>
      <th>#</th><th>Image</th><th>Name</th><th>Price</th><th>Status</th><th>Actions</th>
    </tr></thead>
    <tbody>
      <?php while($m = $items->fetch_assoc()): ?>
        <tr>
          <td><?php echo (int)$m['id']; ?></td>
          <td><img src="<?php echo h($m['image']); ?>" style="width:60px;height:40px;object-fit:cover"></td>
          <td><?php echo h($m['name']); ?></td>
          <td>৳ <?php echo h($m['price']); ?></td>
          <td><span class="badge <?php echo $m['status']==='active'?'bg-success':'bg-secondary'; ?>"><?php echo h($m['status']); ?></span></td>
          <td>
            <!-- Quick inline edit form -->
            <form method="post" class="d-flex flex-wrap gap-2">
              <input type="hidden" name="id" value="<?php echo (int)$m['id']; ?>">
              <input class="form-control form-control-sm" name="name" value="<?php echo h($m['name']); ?>">
              <input class="form-control form-control-sm" name="price" type="number" step="0.01" value="<?php echo h($m['price']); ?>">
              <input class="form-control form-control-sm" name="image" value="<?php echo h($m['image']); ?>">
              <select class="form-select form-select-sm" name="status">
                <option value="active" <?php echo $m['status']==='active'?'selected':''; ?>>active</option>
                <option value="inactive" <?php echo $m['status']==='inactive'?'selected':''; ?>>inactive</option>
              </select>
              <input class="form-control form-control-sm" name="description" value="<?php echo h($m['description']); ?>">
              <button class="btn btn-sm btn-primary" name="update">Save</button>
              <a class="btn btn-sm btn-danger" href="?delete=<?php echo (int)$m['id']; ?>" onclick="return confirm('Delete this item?')">Delete</a>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . "/../includes/footer.php"; ?>
