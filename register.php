<link rel="icon" href="logo.jpg" type="image/x-icon">
<?php
include 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (last_name, first_name, middle_name, contact_number, address, email, password, role)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'customer')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $last_name, $first_name, $middle_name, $contact_number, $address, $email, $password);

    if ($stmt->execute()) {
        $success = "Account created successfully!";
    } else {
        $error = "Registration failed. Email may already exist.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Lyneth's Garden - Register</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Georgia', serif; }
    body {
      background: url('bg.jpg') no-repeat center center fixed;
      background-size: cover;
    }
    .navbar {
      background: linear-gradient(to right, #2d7735, #3fa64b);
      color: white;
      padding: 30px 40px;
      font-size: 24px;
      font-weight: bold;
    }
    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: calc(100vh - 70px);
      animation: fadeIn 1s ease;
    }
    .register-box {
      display: flex;
      box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
      width: 900px;
      background: white;
    }
    .left-panel {
      background: #5f7f30;
      color: white;
      padding: 40px;
      width: 40%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }
    .left-panel img {
      width: 100px;
      height: auto;
    }
    .left-panel h2 { margin-top: 20px; }
    .left-panel p { font-size: 11px; margin-top: 5px; }
    .right-panel {
      background: #f1f1f1;
      width: 60%;
      padding: 40px;
    }
    .right-panel h2 { text-align: center; margin-bottom: 10px; }
    input[type="text"], input[type="email"], input[type="password"] {
      padding: 10px; margin: 8px 0;
      width: 100%; border: 1px solid #ccc; font-size: 14px;
    }
    .btn {
      background: linear-gradient(to right, #2b7e48, #39a760);
      color: white; font-weight: bold;
      padding: 15px; border: none; cursor: pointer;
    }
    .login-link { text-align: center; font-size: 14px; margin-top: 10px; }
    .login-link a {
      color: #5f7f30; text-decoration: none; font-weight: bold;
    }
    .error {
      color: red; font-size: 14px; text-align: center; margin-bottom: 10px;
    }
    .success {
      color: green; font-size: 14px; text-align: center; margin-bottom: 10px;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="navbar">Lyneth’s Garden</div>
  <div class="container">
    <div class="register-box">
      <div class="left-panel">
        <img src="logo.jpg" alt="Logo">
        <h2>Lyneth’s Garden</h2>
        <p>"Your Garden, Our Passion"</p>
      </div>
      <div class="right-panel">
        <h2>Register</h2>
        <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?= $success ?></div><?php endif; ?>
        <form method="post">
          <input type="text" name="last_name" placeholder="Last Name" required>
          <input type="text" name="first_name" placeholder="First Name" required>
          <input type="text" name="middle_name" placeholder="Middle Name">
          <input type="text" name="contact_number" placeholder="Contact Number" required>
          <input type="text" name="address" placeholder="Delivery Address" required>
          <input type="email" name="email" placeholder="Email" required>
          <div style="position: relative;">
            <input type="password" id="password" name="password" placeholder="Password" required style="width: 100%; padding: 10px; padding-right: 50px; border: 1px solid #ccc; font-size: 14px;">
            <span onclick="const p=document.getElementById('password'); this.textContent=p.type==='password'?'Hide':'Show'; p.type=p.type==='password'?'text':'password';" 
                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; font-size: 12px; color: #5f7f30;">
                Show
            </span>
            </div>
          <button class="btn" type="submit">Register</button>
          <div class="login-link">Already have an account? <a href="login.php">Login</a></div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
