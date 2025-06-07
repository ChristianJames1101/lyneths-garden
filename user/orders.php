<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: ../login.php");
    exit();
}

$user = $_SESSION['user'];
$customerName = $user['first_name'];

$pendingStmt = $conn->prepare("SELECT * FROM orders WHERE name = ? ORDER BY order_date DESC");
$pendingStmt->bind_param("s", $customerName);
$pendingStmt->execute();
$pendingOrders = $pendingStmt->get_result();

$completedStmt = $conn->prepare("SELECT * FROM completed_orders WHERE name = ? ORDER BY completed_date DESC");
$completedStmt->bind_param("s", $customerName);
$completedStmt->execute();
$completedOrders = $completedStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Your Orders - Lyneth's Garden</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background: #e8f5e9;
      padding: 20px;
    }
    h2 {
      color: #2f6f3f;
      text-align: center;
      margin-bottom: 30px;
    }
    .section-container {
      max-width: 1400px;
      margin: auto;
      margin-bottom: 40px;
    }
    .order-section {
      background: white;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      margin-bottom: 30px;
    }
    .order-section h3 {
      margin-bottom: 20px;
      color: #3fa64b;
      border-bottom: 2px solid #3fa64b;
      padding-bottom: 10px;
      font-size: 20px;
    }
    .orders-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    .orders-table th {
      background-color: #f1f8e9;
      color: #2f6f3f;
      padding: 12px;
      text-align: left;
      border: 1px solid #ddd;
      font-weight: 600;
    }
    .orders-table td {
      padding: 12px;
      border: 1px solid #ddd;
      vertical-align: top;
    }
    .orders-table tr:nth-child(even) {
      background-color: #fafafa;
    }
    .orders-table tr:hover {
      background-color: #f5f5f5;
    }
    .status {
      font-weight: bold;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 12px;
    }
    .pending {
      color: #c98500;
      background-color: #fff3cd;
    }
    .completed {
      color: #007700;
      background-color: #d4edda;
    }
    .no-orders {
      font-style: italic;
      text-align: center;
      color: #666;
      padding: 30px;
      background-color: #f9f9f9;
      border-radius: 5px;
    }
    .action-buttons {
      display: flex;
      gap: 5px;
      flex-wrap: wrap;
    }
    .print-receipt-btn, .cancel-request-btn {
      background-color: #3fa64b;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 12px;
      transition: background-color 0.3s;
    }
    .print-receipt-btn:hover, .cancel-request-btn:hover {
      background-color: #2d572c;
    }
    .cancel-request-btn {
      background-color: #dc3545;
    }
    .cancel-request-btn:hover {
      background-color: #c82333;
    }
    .price {
      font-weight: bold;
      color: #3fa64b;
    }
    #cancelModal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: #00000088;
      z-index: 9999;
      align-items: center;
      justify-content: center;
    }
    #cancelModal form {
      background: white;
      padding: 20px;
      border-radius: 10px;
      width: 300px;
    }
    #cancelModal h3 {
      margin-top: 0;
      color: #3fa64b;
    }
    #cancelModal textarea {
      width: 100%;
      height: 80px;
      border: 1px solid #ddd;
      border-radius: 4px;
      padding: 8px;
      resize: vertical;
    }
    #cancelModal button {
      margin-right: 10px;
      padding: 8px 16px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    #cancelModal button[type="submit"] {
      background-color: #dc3545;
      color: white;
    }
    #cancelModal button[type="button"] {
      background-color: #6c757d;
      color: white;
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
      .orders-table {
        font-size: 14px;
      }
      .orders-table th,
      .orders-table td {
        padding: 8px;
      }
      .action-buttons {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

<h2>Your Order History</h2>

<div class="section-container">
  <!-- Pending Orders Section -->
  <div class="order-section">
    <h3>Pending Orders</h3>
    <?php if ($pendingOrders->num_rows > 0): ?>
      <table class="orders-table">
        <thead>
          <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Delivery Address</th>
            <th>Estimated Delivery</th>
            <th>Status</th>
            <th>Order Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($order = $pendingOrders->fetch_assoc()): ?>
            <tr data-product="<?= htmlspecialchars($order['product_name']) ?>"
                data-quantity="<?= $order['quantity'] ?>"
                data-total="<?= $order['total_price'] ?>"
                data-status="<?= $order['status'] ?>"
                data-date="<?= $order['order_date'] ?>"
                data-customer="<?= htmlspecialchars($customerName) ?>"
                data-address="<?= htmlspecialchars($order['address']) ?>"
                data-order-type="pending">
              <td><?= htmlspecialchars($order['product_name']) ?></td>
              <td><?= $order['quantity'] ?></td>
              <td class="price">₱<?= number_format($order['total_price'], 2) ?></td>
              <td><?= htmlspecialchars($order['address']) ?></td>
              <td><?= htmlspecialchars($order['estimated_date']) ?></td>
              <td><span class="status pending"><?= $order['status'] ?></span></td>
              <td><?= date("M j, Y", strtotime($order['order_date'])) ?></td>
              <td>
                <div class="action-buttons">
                  <button class="print-receipt-btn" onclick="printReceipt(this)">Receipt</button>
                  <button class="cancel-request-btn" data-order-id="<?= $order['id'] ?>" data-customer-name="<?= htmlspecialchars($customerName) ?>">Cancel</button>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="no-orders">You have no pending orders.</div>
    <?php endif; ?>
  </div>

  <!-- Completed Orders Section -->
  <div class="order-section">
    <h3>Completed Orders</h3>
    <?php if ($completedOrders->num_rows > 0): ?>
      <table class="orders-table">
        <thead>
          <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Delivery Address</th>
            <th>Status</th>
            <th>Completed Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($order = $completedOrders->fetch_assoc()): ?>
            <tr data-product="<?= htmlspecialchars($order['product_name']) ?>"
                data-quantity="<?= $order['quantity'] ?>"
                data-total="<?= $order['total_price'] ?>"
                data-status="<?= $order['status'] ?>"
                data-date="<?= $order['completed_date'] ?>"
                data-customer="<?= htmlspecialchars($customerName) ?>"
                data-address="<?= htmlspecialchars($order['address']) ?>"
                data-order-type="completed">
              <td><?= htmlspecialchars($order['product_name']) ?></td>
              <td><?= $order['quantity'] ?></td>
              <td class="price">₱<?= number_format($order['total_price'], 2) ?></td>
              <td><?= htmlspecialchars($order['address']) ?></td>
              <td><span class="status completed"><?= $order['status'] ?></span></td>
              <td><?= date("M j, Y", strtotime($order['completed_date'])) ?></td>
              <td>
                <div class="action-buttons">
                  <button class="print-receipt-btn" onclick="printReceipt(this)">Receipt</button>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="no-orders">You have no completed orders.</div>
    <?php endif; ?>
  </div>
</div>

<div id="cancelModal">
  <form method="POST" action="submit_cancellation.php">
    <h3>Cancel Order</h3>
    <input type="hidden" name="order_id" id="modalOrderId">
    <input type="hidden" name="customer_name" id="modalCustomerName">
    <label for="cancelReason">Reason for cancellation:</label>
    <textarea name="reason" id="cancelReason" required placeholder="Please provide a reason for cancelling this order..."></textarea><br><br>
    <button type="submit">Submit Cancellation</button>
    <button type="button" onclick="document.getElementById('cancelModal').style.display='none'">Close</button>
  </form>
</div>

<script>
function printReceipt(button) {
  const orderRow = button.closest('tr');
  const product = orderRow.dataset.product;
  const quantity = orderRow.dataset.quantity;
  const total = orderRow.dataset.total;
  const status = orderRow.dataset.status;
  const date = orderRow.dataset.date;
  const customer = orderRow.dataset.customer;
  const address = orderRow.dataset.address;
  const type = orderRow.dataset.orderType;

  const title = type === 'pending' ? 'Pending Order Receipt' : 'Completed Order Receipt';

  const html = `
    <html>
    <head>
      <title>${title}</title>
      <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h2 { color: #3fa64b; text-align: center; }
        .header { border-bottom: 2px solid #3fa64b; padding-bottom: 10px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #e9f5e9; }
        p { font-size: 16px; margin: 8px 0; }
        .info-section { margin-bottom: 20px; }
      </style>
    </head>
    <body>
      <div class="header">
        <h2>Lyneth's Garden</h2>
        <h3>${title}</h3>
      </div>
      <div class="info-section">
        <p><strong>Customer:</strong> ${customer}</p>
        <p><strong>Order Date:</strong> ${new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
        <p><strong>Delivery Address:</strong> ${address}</p>
      </div>
      <table>
        <thead>
          <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>${product}</td>
            <td>${quantity}</td>
            <td>₱${parseFloat(total).toFixed(2)}</td>
            <td>${status}</td>
          </tr>
        </tbody>
      </table>
    </body>
    </html>
  `;

  const w = window.open('', '_blank');
  w.document.write(html);
  w.document.close();
  w.focus();
  w.print();
  w.close();
}

document.querySelectorAll('.cancel-request-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.getElementById('modalOrderId').value = btn.dataset.orderId;
    document.getElementById('modalCustomerName').value = btn.dataset.customerName;
    document.getElementById('cancelModal').style.display = 'flex';
  });
});
</script>

</body>
</html>