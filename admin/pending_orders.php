<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'];
    $contact = $_POST['contact_number'];
    $address = $_POST['address'];
    $product = $_POST['product_name'];
    $quantity = (int)$_POST['quantity'];
    $price = (float)$_POST['total_price'];
    $total = $quantity * $price;
    $status = 'pending';
    $estimated_date = $_POST['estimated_date'] ?? null;

    if ($id) {
        $stmtOld = $conn->prepare("SELECT estimated_date FROM orders WHERE id=?");
        $stmtOld->bind_param("i", $id);
        $stmtOld->execute();
        $resultOld = $stmtOld->get_result();
        $oldData = $resultOld->fetch_assoc();
        $oldEstimated = $oldData['estimated_date'];

        $stmt = $conn->prepare("UPDATE orders SET name=?, contact_number=?, address=?, product_name=?, quantity=?, total_price=?, total=?, estimated_date=? WHERE id=?");
        $stmt->bind_param("ssssiddsi", $name, $contact, $address, $product, $quantity, $price, $total, $estimated_date, $id);
        $stmt->execute();

        if ($estimated_date !== $oldEstimated) {
            $msg = "Changes in estimated delivery: \"$name\", your order #$id ($product) is being processed. Estimated delivery: $estimated_date.";
            $stmtNotif = $conn->prepare("INSERT INTO notifications (order_id, name, product, message) VALUES (?, ?, ?, ?)");
            $stmtNotif->bind_param("isss", $id, $name, $product, $msg);
            $stmtNotif->execute();
        }

    } else {
        $stmt = $conn->prepare("INSERT INTO orders (name, contact_number, address, product_name, quantity, total_price, status, order_date, total, estimated_date) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)");
        $stmt->bind_param("ssssidiss", $name, $contact, $address, $product, $quantity, $price, $status, $total, $estimated_date);
        $stmt->execute();
    }

    header("Location: index.php?page=pending_orders");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $result = $conn->query("SELECT name, product_name FROM orders WHERE id=$id");
    $order = $result->fetch_assoc();

    $msg = "Your order #$id ({$order['product_name']}) has been deleted.";
    $stmtNotif = $conn->prepare("INSERT INTO notifications (order_id, name, product, message) VALUES (?, ?, ?, ?)");
    $stmtNotif->bind_param("isss", $id, $order['name'], $order['product_name'], $msg);
    $stmtNotif->execute();

    $conn->query("DELETE FROM orders WHERE id=$id");

    header("Location: index.php?page=pending_orders");
    exit();
}

if (isset($_GET['complete'])) {
    $id = $_GET['complete'];
    $result = $conn->query("SELECT * FROM orders WHERE id=$id");
    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();

        $stmt = $conn->prepare("INSERT INTO completed_orders (id, name, contact_number, address, product_name, quantity, total_price, status, total, completed_date) VALUES (?, ?, ?, ?, ?, ?, ?, 'completed', ?, NOW())");
        $stmt->bind_param(
            "issssidd",
            $order['id'],
            $order['name'],
            $order['contact_number'],
            $order['address'],
            $order['product_name'],
            $order['quantity'],
            $order['total_price'],
            $order['total']
        );
        $stmt->execute();

        $msg = "Your order #$id ({$order['product_name']}) has been completed.";
        $stmtNotif = $conn->prepare("INSERT INTO notifications (order_id, name, product, message) VALUES (?, ?, ?, ?)");
        $stmtNotif->bind_param("isss", $id, $order['name'], $order['product_name'], $msg);
        $stmtNotif->execute();

        $conn->query("DELETE FROM orders WHERE id=$id");
    }

    header("Location: index.php?page=pending_orders");
    exit();
}

$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM orders WHERE id=$id");
    $editData = $result->fetch_assoc();
}

if (isset($_GET['delete_cancel'])) {
    $cancelId = $_GET['delete_cancel'];

    $conn->query("DELETE FROM cancellation_requests WHERE id=$cancelId");

    header("Location: index.php?page=pending_orders");
    exit();
}

$orders = $conn->query("SELECT * FROM orders WHERE status = 'pending' ORDER BY order_date DESC");
$cancellationRequests = $conn->query("SELECT * FROM cancellation_requests ORDER BY request_date DESC");

?>

<style>

  .dashboard {
    font-family: 'Segoe UI', sans-serif;
  }

  .dashboard h2 {
    color: #2d572c;
    margin-bottom: 15px;
    font-size: 28px;
  }

  .dashboard form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 15px;
    margin-bottom: 30px;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
  }

  .dashboard form label {
    display: flex;
    flex-direction: column;
    font-size: 14px;
    color: #444;
  }

  .dashboard form input {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
  }

  .dashboard form button {
    grid-column: span 2;
    padding: 10px;
    background: #3fa64b;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 10px;
  }

  .dashboard table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 40px;
  }

  .dashboard table th, .dashboard table td {
    padding: 14px 16px;
    text-align: left;
    border-bottom: 1px solid #f0f0f0;
    font-size: 14px;
  }

  .dashboard table th {
    background-color: #e9f5e9;
    color: #2d572c;
  }

  .dashboard table tr:hover {
    background-color: #f7f7f7;
  }

  .actions a {
    margin-right: 10px;
    text-decoration: none;
    font-weight: bold;
  }

  .actions a.edit {
    color: #2196f3;
  }

  .actions a.delete {
    color: #f44336;
  }

  .actions a.complete {
    color: #4caf50;
  }

  .search-box {
    margin-bottom: 15px;
  }
  .search-box input {
    width: 300px;
    max-width: 100%;
    padding: 8px 10px;
    font-size: 14px;
    border-radius: 8px;
    border: 1px solid #ccc;
  }

  @media (max-width: 768px) {
    .dashboard form {
      grid-template-columns: 1fr;
    }

    .dashboard form button {
      grid-column: span 1;
    }
  }
</style>

<section class="dashboard">
  <h2>Pending Orders</h2>

  <form method="post">
    <input type="hidden" name="id" value="<?= htmlspecialchars($editData['id'] ?? '') ?>">
    <label>Name <input type="text" name="name" required value="<?= htmlspecialchars($editData['name'] ?? '') ?>"></label>
    <label>Contact <input type="text" name="contact_number" required value="<?= htmlspecialchars($editData['contact_number'] ?? '') ?>"></label>
    <label>Address <input type="text" name="address" required value="<?= htmlspecialchars($editData['address'] ?? '') ?>"></label>
    <label>Product <input type="text" name="product_name" required value="<?= htmlspecialchars($editData['product_name'] ?? '') ?>"></label>
    <label>Quantity <input type="number" name="quantity" required value="<?= htmlspecialchars($editData['quantity'] ?? '') ?>"></label>
    <label>Unit Price <input type="number" step="0.01" name="total_price" required value="<?= htmlspecialchars($editData['total_price'] ?? '') ?>"></label>
    <label>Estimated Delivery Date
      <input type="date" name="estimated_date" value="<?= htmlspecialchars($editData['estimated_date'] ?? '') ?>">
    </label>
    <button type="submit"><?= $editData ? 'Update' : 'Add' ?> Order</button>
  </form>

  <div class="search-box">
    <input type="text" id="searchOrders" placeholder="Search Pending Orders...">
  </div>

  <table id="ordersTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Contact</th>
        <th>Address</th>
        <th>Product</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Total</th>
        <th>Order Date</th>
        <th>Estimated Delivery Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while($order = $orders->fetch_assoc()): ?>
        <tr>
          <td><?= $order['id'] ?></td>
          <td><?= htmlspecialchars($order['name']) ?></td>
          <td><?= htmlspecialchars($order['contact_number']) ?></td>
          <td><?= htmlspecialchars($order['address']) ?></td>
          <td><?= htmlspecialchars($order['product_name']) ?></td>
          <td><?= $order['quantity'] ?></td>
          <td>₱<?= number_format($order['total_price'], 2) ?></td>
          <td>₱<?= number_format($order['total'], 2) ?></td>
          <td><?= $order['order_date'] ?></td>
          <td><?= $order['estimated_date'] ?? '' ?></td>
          <td class="actions">
            <a class="edit" href="index.php?page=pending_orders&edit=<?= $order['id'] ?>">Edit</a>
            <a class="delete" href="index.php?page=pending_orders&delete=<?= $order['id'] ?>" onclick="return confirm('Delete this order?')">Cancel</a>
            <a class="complete" href="index.php?page=pending_orders&complete=<?= $order['id'] ?>" onclick="return confirm('Mark as completed?')">Complete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <h2>Cancellation Requests</h2>

  <div class="search-box">
    <input type="text" id="searchCancellation" placeholder="Search Cancellation Requests...">
  </div>

  <table id="cancellationTable">
    <thead>
      <tr>
        <th>Request ID</th>
        <th>Order ID</th>
        <th>User Name</th>
        <th>Reason</th>
        <th>Request Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while($c = $cancellationRequests->fetch_assoc()): ?>
      <tr>
        <td><?= $c['id'] ?></td>
        <td><?= $c['order_id'] ?></td>
        <td><?= htmlspecialchars($c['customer_name']) ?></td>
        <td><?= htmlspecialchars($c['reason']) ?></td>
        <td><?= date("F j, Y g:i A", strtotime($c['request_date'])) ?></td>
        <td class="actions">
          <a class="delete" href="index.php?page=pending_orders&delete_cancel=<?= $c['id'] ?>" onclick="return confirm('Delete this cancellation request?')">Done</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</section>

<script>
function setupSearch(inputId, tableId) {
  const input = document.getElementById(inputId);
  const table = document.getElementById(tableId);
  input.addEventListener('keyup', () => {
    const filter = input.value.toLowerCase();
    const rows = table.tBodies[0].rows;
    for(let row of rows) {
      let text = row.textContent.toLowerCase();
      row.style.display = text.includes(filter) ? '' : 'none';
    }
  });
}

setupSearch('searchOrders', 'ordersTable');
setupSearch('searchCancellation', 'cancellationTable');
</script>
