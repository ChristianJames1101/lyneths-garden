<link rel="icon" href="logo.jpg" type="image/x-icon">
<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: ../login.php");
    exit();
}
include '../db.php';

$sql = "SELECT * FROM notifications ORDER BY created_at DESC";
$result = $conn->query($sql);
$notifications = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

$unread_count = 0;
foreach ($notifications as $note) {
    if (!$note['is_read']) $unread_count++;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Customer Home - Lyneth’s Garden</title>
  <style>
    body {
      margin: 0;
      font-family: 'Georgia', serif;
      background: #f0f0f0;
    }

    .navbar {
      background: #3fa64b;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px;
      font-size: 20px;
      font-weight: bold;
      position: fixed;
      width: 100%;
      top: 0;
      left: 0;
      z-index: 1000;
      box-sizing: border-box;
    }

    .navbar .logo {
      flex: 1;
      display: flex;
      align-items: center;
    }

    .navbar .logo img {
      width: 40px;
      height: auto;
      margin-right: 10px;
    }

    .navbar .logo span {
      font-size: 22px;
      font-weight: bold;
    }

    .nav-links {
      display: flex;
      overflow-x: auto;
      white-space: nowrap;
      gap: 20px;
      justify-content: flex-end;
      flex: 1;
      align-items: center;
    }

    .nav-links a, .nav-links button {
      color: white;
      text-decoration: none;
      font-size: 16px;
      padding: 8px 12px;
      flex-shrink: 0;
      background: none;
      border: none;
      cursor: pointer;
      position: relative;
      font-family: inherit;
      transition: background 0.3s ease;
      border-radius: 5px;
    }

    .nav-links a:hover, .nav-links button:hover {
      background: rgba(255,255,255,0.2);
    }

    #notificationBtn {
      display: flex;
      align-items: center;
      gap: 6px;
      font-weight: 600;
      position: relative;
    }

    #notificationBtn img {
      height: 18px;
      width: 18px;
      filter: brightness(0) invert(1);
    }

    #notificationBtn .badge {
      background: #ff4d4d;
      color: white;
      border-radius: 50%;
      font-size: 12px;
      padding: 2px 6px;
      position: absolute;
      top: 4px;
      right: 4px;
      font-weight: bold;
      user-select: none;
      pointer-events: none;
    }

    .main {
      margin-top: 100px; 
      padding: 30px;
    }

    .notifications-container {
      position: fixed;
      top: 70px;
      right: 30px;
      width: 380px;
      max-height: 400px;
      overflow-y: auto;
      background: white;
      box-shadow: 0 10px 25px rgba(0,0,0,0.12);
      border-radius: 12px;
      font-family: 'Arial', sans-serif;
      z-index: 1001;
      border-left: 6px solid #3fa64b;
      animation: slideDown 0.3s ease forwards;
      display: none;
      padding: 10px 0;
    }

    @keyframes slideDown {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .notifications-container.show {
      display: block;
    }

    .notifications-container h2 {
      margin: 0 20px 15px 20px;
      padding-bottom: 5px;
      border-bottom: 1px solid #ddd;
      color: #2f6627;
      font-weight: 700;
      font-size: 20px;
    }

    .notification-item {
      padding: 15px 20px;
      margin: 0 15px 10px 15px;
      border-radius: 10px;
      background: #fafafa;
      box-shadow: 0 2px 6px rgba(0,0,0,0.06);
      cursor: pointer;
      transition: box-shadow 0.3s ease, background 0.3s ease;
      border-left: 5px solid transparent;
      display: flex;
      flex-direction: column;
      gap: 6px;
      position: relative;
      will-change: transform, opacity;
    }

    .notification-item:hover {
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      background: #e8f5e9;
    }

    .notification-item.unread {
      background: #e1f0d9;
      border-left-color: #3fa64b;
      font-weight: 600;
    }

    .notification-message {
      font-size: 14px;
      color: #2e2e2e;
      line-height: 1.3;
    }

    .notification-meta {
      font-size: 12px;
      color: #666;
      display: flex;
      justify-content: space-between;
      font-style: italic;
      user-select: none;
    }

    .notification-meta span {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 45%;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      text-align: center;
    }

    .modal-content button {
      margin: 10px;
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
    }

    .modal-content button.confirm {
      background: #3fa64b;
      color: white;
      border: none;
    }

    .modal-content button.cancel {
      background: #ff4d4d;
      color: white;
      border: none;
    }

    .modal.show {
      display: flex;
    }

    @media(max-width: 400px) {
      .notifications-container {
        width: 90vw;
        right: 5vw;
        top: 80px;
        max-height: 300px;
      }
    }

    @keyframes swipeRight {
      0% {
        transform: translateX(0);
        opacity: 1;
        max-height: 100px;
        margin-bottom: 10px;
      }
      100% {
        transform: translateX(100%);
        opacity: 0;
        max-height: 0;
        margin-bottom: 0;
        padding-top: 0;
        padding-bottom: 0;
      }
    }

    .swipe-right {
      animation: swipeRight 0.5s forwards ease;
      overflow: hidden;
    }
  </style>
</head>
<body>

<div class="navbar">
  <div class="logo">
    <img src="logo.jpg" alt="Lyneth's Garden Logo" />
    <span>Welcome to Lyneth’s Garden</span>
  </div>
  <div class="nav-links">
    <button id="notificationBtn" aria-label="Toggle notifications" style="position: relative;">
      🔔
      <?php if ($unread_count > 0): ?>
        <span class="badge" id="notificationBadge"><?= $unread_count ?></span>
      <?php endif; ?>
    </button>
    <a href="home.php?page=products"><img src="plants.png" alt="Plants" style="vertical-align: middle; height: 16px; margin-right: 1px;">Products</a>
    <a href="home.php?page=cart"><img src="cart.png" alt="Cart" style="vertical-align: middle; height: 16px; margin-right: 1px;">Cart</a>
    <a href="home.php?page=orders"><img src="orders.png" alt="Orders" style="vertical-align: middle; height: 16px; margin-right: 1px;">Orders</a>
    <a href="home.php?page=account"><img src="acc.png" alt="Account" style="vertical-align: middle; height: 16px; margin-right: 1px;">Account</a>
    <a href="#" id="logoutLink">Logout</a>
  </div>
</div>

<div class="notifications-container" role="region" aria-label="Notifications" id="notificationsPanel">
  <h2>Notifications</h2>
  <?php if (empty($notifications)): ?>
    <p style="padding: 20px; text-align: center; color: #666;">No notifications yet.</p>
  <?php else: ?>
    <?php foreach ($notifications as $note): ?>
      <div
        class="notification-item <?= $note['is_read'] ? '' : 'unread' ?>"
        data-id="<?= $note['id'] ?>"
        tabindex="0"
        role="button"
        aria-pressed="false"
        title="Click to remove notification"
      >
        <div class="notification-message"><?= htmlspecialchars($note['message']) ?></div>
        <div class="notification-meta">
          <span><?= htmlspecialchars($note['product']) ?></span>
          <span><?= date('M d, Y - h:i A', strtotime($note['created_at'])) ?></span>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<div class="main">
  <?php
    $page = $_GET['page'] ?? 'products';
    $allowed = ['products', 'cart', 'orders', 'account'];
    if (in_array($page, $allowed)) {
        include $page . '.php';
    } else {
        echo "<h2>Page not found.</h2>";
    }
  ?>
</div>

<div class="modal" id="logoutModal">
  <div class="modal-content">
    <h3>Are you sure you want to log out?</h3>
    <button class="confirm" id="confirmLogout">Yes</button>
    <button class="cancel" id="cancelLogout">No</button>
  </div>
</div>

<script>
  const logoutLink = document.getElementById('logoutLink');
  const logoutModal = document.getElementById('logoutModal');
  const cancelLogout = document.getElementById('cancelLogout');
  const confirmLogout = document.getElementById('confirmLogout');

  logoutLink.addEventListener('click', function(event) {
    event.preventDefault();
    logoutModal.classList.add('show');
  });

  cancelLogout.addEventListener('click', function() {
    logoutModal.classList.remove('show');
  });

  confirmLogout.addEventListener('click', function() {
    window.location.href = '../logout.php';
  });

  const notificationBtn = document.getElementById('notificationBtn');
  const notificationsPanel = document.getElementById('notificationsPanel');
  const badge = document.getElementById('notificationBadge');

  notificationBtn.addEventListener('click', () => {
    notificationsPanel.classList.toggle('show');
  });

  const notificationItems = document.querySelectorAll('.notification-item');

  notificationItems.forEach(item => {
    item.addEventListener('click', () => {
      const id = item.dataset.id;
      item.classList.add('swipe-right');

      item.addEventListener('animationend', () => {
        fetch('delete_notification.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'id=' + encodeURIComponent(id),
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            item.remove();

            if (item.classList.contains('unread') && badge) {
              let count = parseInt(badge.textContent);
              count--;
              if (count <= 0) {
                badge.remove();
              } else {
                badge.textContent = count;
              }
            }
          } else {
            alert('Error deleting notification.');
            item.classList.remove('swipe-right');
          }
        })
        .catch(() => {
          alert('Network error.');
          item.classList.remove('swipe-right');
        });
      }, {once: true});
    });

    item.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        item.click();
      }
    });
  });
</script>

</body>
</html>
