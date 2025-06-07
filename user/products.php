<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = max(1, intval($_POST['quantity'] ?? 1));
    $action = $_POST['action'];

    if ($action === 'cart') {
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $stmt->execute();
        $_SESSION['success'] = "Item added to cart!";
    } elseif ($action === 'order') {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();
        $total_price = $product['price'] * $quantity;

        $stmt = $conn->prepare("INSERT INTO orders (name, contact_number, address, product_name, quantity, total_price, status, order_date, total)
                                VALUES (?, ?, ?, ?, ?, ?, 'Pending', NOW(), ?)");
        $stmt->bind_param("ssssidd",
            $_SESSION['user']['first_name'],
            $_SESSION['user']['contact_number'],
            $_SESSION['user']['address'],
            $product['name'],
            $quantity,
            $total_price,
            $total_price
        );
        $stmt->execute();
        $_SESSION['success'] = "Order placed successfully!";
    }

echo "<script>window.location.href = 'home.php?page=products';</script>";    exit();
}

$search = $_GET['search'] ?? '';
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $products = $stmt->get_result();
} else {
    $products = $conn->query("SELECT * FROM products");
}

$images = array_filter(scandir('../uploads'), function($file) {
    return in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']);
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Products - Lyneth's Garden</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background: #e8f5e9;
    }

    .slideshow {
      width: 100%;
      height: 300px;
      position: relative;
      overflow: hidden;
      margin-bottom: 30px;
    }

    .slide-images {
      display: flex;
      height: 100%;
      animation: slide 20s infinite;
    }

    .slide-images img {
      width: 100%;
      object-fit: cover;
    }

    @keyframes slide {
      0%, 20%   { transform: translateX(0); }
      25%, 45%  { transform: translateX(-100%); }
      50%, 70%  { transform: translateX(-200%); }
      75%, 95%  { transform: translateX(-300%); }
      100%      { transform: translateX(0); }
    }

    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 30px;
    }

    .top-bar h2 {
      color: #2f6f3f;
    }

    .top-bar form {
      display: flex;
      gap: 10px;
    }

    .top-bar input[type="text"] {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 200px;
    }

    .top-bar button, .top-bar a {
      background: #3fa64b;
      color: white;
      padding: 8px 12px;
      border-radius: 5px;
      text-decoration: none;
      border: none;
      cursor: pointer;
      font-weight: bold;
    }

    .top-bar a {
      background: #ccc;
      color: #333;
    }

    .product-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      justify-content: center;
      padding: 20px;
    }

    .product-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      width: 280px;
      overflow: hidden;
      transition: transform 0.3s ease;
    }

    .product-card:hover {
      transform: translateY(-6px);
    }

    .product-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .product-content {
      padding: 15px;
    }

    .product-content h3 {
      margin: 0 0 10px;
      font-size: 20px;
      color: #3fa64b;
    }

    .product-content p {
      margin: 5px 0;
      font-size: 14px;
      color: #444;
    }

    .price {
      font-size: 16px;
      font-weight: bold;
      color: #2f6f3f;
    }

    .actions {
      margin-top: 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .actions input[type="number"] {
      width: 60px;
      padding: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .actions button {
      background: #3fa64b;
      color: white;
      border: none;
      padding: 8px 10px;
      cursor: pointer;
      font-weight: bold;
      border-radius: 5px;
    }

    .actions button:hover {
      background: #2e8d3a;
    }
  </style>
</head>
<body>

<?php if (isset($_SESSION['success'])): ?>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: <?= json_encode($_SESSION['success']) ?>,
      confirmButtonColor: '#3fa64b'
    });
  });
</script>
<?php unset($_SESSION['success']); endif; ?>

<div class="slideshow">
  <div class="slide-images">
    <?php foreach ($images as $img): ?>
      <img src="../uploads/<?= htmlspecialchars($img) ?>" alt="Slideshow Image">
    <?php endforeach; ?>
  </div>
</div>

<div class="top-bar">
  <h2>Available Products</h2>
  <form method="get">
    <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
    <a href="home.php">Clear</a>
  </form>
</div>

<div class="product-grid">
  <?php while ($row = $products->fetch_assoc()): ?>
    <div class="product-card">
      <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
      <div class="product-content">
        <h3><?= htmlspecialchars($row['name']) ?></h3>
        <p><?= htmlspecialchars($row['description']) ?></p>
        <p class="price">₱<?= number_format($row['price'], 2) ?></p>
        <p>Stock: <?= $row['stock'] ?></p>
        <form method="post" class="product-form">
          <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
          <input type="hidden" name="action" value="">
          <div class="actions">
            <input type="number" name="quantity" value="1" min="1" max="<?= $row['stock'] ?>">
            <button type="button" onclick="confirmCart(this)">Add to Cart</button>
            <button type="button" onclick="confirmOrder(this)">Order</button>
          </div>
        </form>
      </div>
    </div>
  <?php endwhile; ?>
</div>

<script>
function confirmCart(button) {
  Swal.fire({
    title: 'Add to Cart?',
    text: 'Do you want to add this item to your cart?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Yes',
    cancelButtonText: 'No',
    confirmButtonColor: '#3fa64b'
  }).then((result) => {
    if (result.isConfirmed) {
      const form = button.closest('form');
      form.querySelector('input[name="action"]').value = 'cart';
      form.submit();
    }
  });
}

function confirmOrder(button) {
  Swal.fire({
    title: 'Place Order?',
    text: 'Are you sure you want to place this order?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes',
    cancelButtonText: 'No',
    confirmButtonColor: '#3fa64b'
  }).then((result) => {
    if (result.isConfirmed) {
      const form = button.closest('form');
      form.querySelector('input[name="action"]').value = 'order';
      form.submit();
    }
  });
}
</script>

</body>
</html>
