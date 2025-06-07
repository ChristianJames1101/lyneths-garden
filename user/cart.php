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

if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $remove_id, $user_id);
    $stmt->execute();
    header("Location: home.php?page=cart&remove_success=1");
    exit();
}

if (isset($_POST['update_quantity'])) {
    $cart_id = intval($_POST['cart_id']);
    $new_quantity = max(1, intval($_POST['quantity']));
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("iii", $new_quantity, $cart_id, $user_id);
    $stmt->execute();
    header("Location: home.php?page=cart");
    exit();
}

if (isset($_POST['checkout_all'])) {
    $stmt = $conn->prepare("SELECT cart.id as cart_id, products.*, cart.quantity 
                            FROM cart JOIN products ON cart.product_id = products.id 
                            WHERE cart.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $cart_items = $stmt->get_result();

    while ($item = $cart_items->fetch_assoc()) {
        $total_price = $item['price'] * $item['quantity'];
        $order_stmt = $conn->prepare("INSERT INTO orders (name, contact_number, address, product_name, quantity, total_price, status, order_date, total)
            VALUES (?, ?, ?, ?, ?, ?, 'Pending', NOW(), ?)");
        $order_stmt->bind_param("ssssidd",
            $_SESSION['user']['first_name'],
            $_SESSION['user']['contact_number'],
            $_SESSION['user']['address'],
            $item['name'],
            $item['quantity'],
            $total_price,
            $total_price
        );
        $order_stmt->execute();
    }

    $conn->query("DELETE FROM cart WHERE user_id = $user_id");
    header("Location: home.php?page=cart&checkout_success=1");
    exit();
}

if (isset($_POST['checkout_item'])) {
    $cart_id = intval($_POST['cart_id']);
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    $total_price = $product['price'] * $quantity;

    $order_stmt = $conn->prepare("INSERT INTO orders (name, contact_number, address, product_name, quantity, total_price, status, order_date, total)
        VALUES (?, ?, ?, ?, ?, ?, 'Pending', NOW(), ?)");
    $order_stmt->bind_param("ssssidd",
        $_SESSION['user']['first_name'],
        $_SESSION['user']['contact_number'],
        $_SESSION['user']['address'],
        $product['name'],
        $quantity,
        $total_price,
        $total_price
    );
    $order_stmt->execute();

    $del_stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $del_stmt->bind_param("ii", $cart_id, $user_id);
    $del_stmt->execute();

    header("Location: home.php?page=cart");
    exit();
}

$stmt = $conn->prepare("SELECT cart.id as cart_id, products.*, cart.quantity 
                        FROM cart 
                        JOIN products ON cart.product_id = products.id 
                        WHERE cart.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_items = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #e8f5e9;
      margin: 0;
      padding: 20px;
    }

    h2 {
      text-align: center;
      color: #2f6f3f;
    }

    table {
      width: 90%;
      margin: 20px auto;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    th, td {
      padding: 15px;
      border-bottom: 1px solid #ddd;
      text-align: center;
    }

    th {
      background-color: #3fa64b;
      color: white;
    }



    .btn {
      padding: 8px 12px;
      background: #3fa64b;
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 5px;
      font-weight: bold;
      text-decoration: none;
    }

    .btn:hover {
      background: #2e8d3a;
    }

    .remove-btn {
      background: #e74c3c;
    }

    .remove-btn:hover {
      background: #c0392b;
    }

    .checkout-bar {
      width: 90%;
      margin: 20px auto;
      text-align: right;
    }

    .empty {
      text-align: center;
      color: #999;
      font-size: 18px;
    }

    form.inline {
      display: inline;
    }

    input[type="number"] {
      width: 60px;
      padding: 5px;
      text-align: center;
    }
    
  </style>
</head>
<body>

<h2>Your Cart</h2>

<?php if ($cart_items->num_rows > 0): ?>
  <form method="post" id="cartForm">
    <table>
      <thead>
        <tr>
          <th>Product</th>
          <th>Image</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Total</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php $grand_total = 0; ?>
        <?php while ($item = $cart_items->fetch_assoc()):
            $total = $item['price'] * $item['quantity'];
            $grand_total += $total;
        ?>
          <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><img src="../uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" width="80" height="auto"></td>
            <td>₱<?= number_format($item['price'], 2) ?></td>
            <td>
              <form method="post" class="inline">
                <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock'] ?>">
                <button type="submit" name="update_quantity" class="btn">Update</button>
              </form>
            </td>
            <td>₱<?= number_format($total, 2) ?></td>
            <td>
              <button type="button" class="btn remove-btn" onclick="confirmRemove(<?= $item['cart_id'] ?>)">Remove</button>
              <form method="post" class="inline" id="checkoutForm<?= $item['cart_id'] ?>">
                <input type="hidden" name="checkout_item" value="1">
                <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                <input type="hidden" name="quantity" value="<?= $item['quantity'] ?>">
                <button type="button" class="btn" onclick="confirmCheckoutItem(<?= $item['cart_id'] ?>)">Checkout</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
        <tr>
          <td colspan="4" style="text-align: right;"><strong>Grand Total:</strong></td>
          <td colspan="2"><strong>₱<?= number_format($grand_total, 2) ?></strong></td>
        </tr>
      </tbody>
    </table>
    <div class="checkout-bar">
      <button type="button" class="btn" onclick="confirmCheckoutAll()">Checkout All</button>
    </div>
  </form>
<?php else: ?>
  <p class="empty">Your cart is empty.</p>
<?php endif; ?>

<?php if (isset($_GET['remove_success'])): ?>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Removed!',
      text: 'Item removed from your cart.',
      timer: 1500,
      showConfirmButton: false
    });
  </script>
<?php endif; ?>

<?php if (isset($_GET['checkout_success'])): ?>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Checkout Complete!',
      text: 'Your order has been placed successfully.',
      timer: 2000,
      showConfirmButton: false
    });
  </script>
<?php endif; ?>

<script>
  function confirmRemove(cartId) {
    Swal.fire({
      title: 'Are you sure?',
      text: "Do you want to remove this item?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e74c3c',
      cancelButtonColor: '#aaa',
      confirmButtonText: 'Yes, remove it!'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = `home.php?page=cart&remove=${cartId}`;
      }
    });
  }

  function confirmCheckoutItem(cartId) {
    Swal.fire({
      title: 'Checkout this item?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3fa64b',
      cancelButtonColor: '#aaa',
      confirmButtonText: 'Yes, checkout'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('checkoutForm' + cartId).submit();
      }
    });
  }

  function confirmCheckoutAll() {
    Swal.fire({
      title: 'Checkout all items?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3fa64b',
      cancelButtonColor: '#aaa',
      confirmButtonText: 'Yes, checkout all'
    }).then((result) => {
      if (result.isConfirmed) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = '<input type="hidden" name="checkout_all" value="1">';
        document.body.appendChild(form);
        form.submit();
      }
    });
  }
</script>

</body>
</html>
