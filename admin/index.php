<link rel="icon" href="logo.jpg" type="image/x-icon">
<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel - Lyneth’s Garden</title>
  <style>
  body {
    margin: 0;
    font-family: 'Georgia', serif;
    background:rgb(241, 241, 241);
  }

  .navbar {
    background:rgb(56, 109, 62);
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
  }

  .nav-links a {
    color: white;
    text-decoration: none;
    font-size: 16px;
    padding: 8px 12px;
    flex-shrink: 0;
  }

  .nav-links::-webkit-scrollbar {
    display: none;
  }

  .main {
    margin-top: 50px;
    padding: 30px;
  }

  @media (max-width: 600px) {
    .navbar {
      flex-direction: column;
      align-items: flex-start;
    }

    .nav-links {
      width: 100%;
      justify-content: flex-start;
      margin-top: 10px;
      overflow-x: auto;
    }
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
  
  </style>
</head>
<body>

<div class="navbar">
  <div class="logo">
    <img src="logo.jpg" alt="Lyneth's Garden Logo">  
    <span>Welcome to Lyneth’s Garden</span>
  </div>
  <div class="nav-links">
    <a href="index.php?page=featured">Upload</a>
    <a href="index.php?page=products">Products</a>
    <a href="index.php?page=users">Users</a>
    <a href="index.php?page=pending_orders">Orders</a>
    <a href="index.php?page=reports">Reports and Analytics</a>
    <a href="#" id="logoutLink">Logout</a>
  </div>
</div>

<div class="main">
  <?php
    $page = $_GET['page'] ?? 'products';
    $allowed = ['products', 'users', 'reports', 'pending_orders', 'featured'];
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
</script>

</body>
</html>
