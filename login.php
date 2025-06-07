<link rel="icon" href="logo.jpg" type="image/x-icon">
<?php
session_start();
include 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;

            if ($user['role'] === 'admin') {
                header("Location: admin/index.php");
                exit();
            } elseif ($user['role'] === 'customer') {
                header("Location: user/home.php"); 
                exit();
            } else {
                $error = "Unauthorized role detected.";
            }
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Lyneth's Garden - Login</title>
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
    .login-box {
      display: flex;
      box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
      width: 800px;
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
    .right-panel h2 { text-align: center; margin-bottom: 20px; }
    form { display: flex; flex-direction: column; }
    input[type="text"], input[type="password"] {
      padding: 10px; margin: 10px 0;
      border: 1px solid #ccc; font-size: 14px;
    }
    .forgot {
      font-size: 13px; color: #5f7f30;
      text-align: right; margin-bottom: 10px;
    }
    .btn {
      background: linear-gradient(to right, #2b7e48, #39a760);
      color: white; font-weight: bold;
      padding: 15px; border: none; cursor: pointer;
    }
    .signup { text-align: center; font-size: 14px; margin-top: 10px; }
    .signup a {
      color: #5f7f30; text-decoration: none; font-weight: bold;
    }
    .error {
      color: red; font-size: 14px;
      margin-bottom: 10px; text-align: center;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="navbar">
    <a href="index.php" style="display: flex; align-items: center; text-decoration: none; color: white;">
      <span style="font-size: 1.5rem; font-weight: bold;">Lyneth’s Garden</span>
    </a>
  </div>
  <div class="container">
    <div class="login-box">
      <div class="left-panel">
        <img src="logo.jpg" alt="Logo">
        <h2>Lyneth’s Garden</h2>
        <p>"Your Garden, Our Passion"</p>
      </div>
      <div class="right-panel">
        <h2>Log in</h2>
        <?php if ($error): ?>
          <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <form method="post">
          <input type="text" name="email" placeholder="Email or number" required>
          <div style="position: relative;">
            <input type="password" id="password" name="password" placeholder="Password" required style="width: 100%; padding: 10px; padding-right: 50px; border: 1px solid #ccc; font-size: 14px;">
            <span onclick="const p=document.getElementById('password'); this.textContent=p.type==='password'?'Hide':'Show'; p.type=p.type==='password'?'text':'password';" 
                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; font-size: 12px; color: #5f7f30;">
                Show
            </span>
            </div>
          <div class="forgot">
            <label>
                <input type="checkbox" name="remember"> Remember me
            </label>
            </div>
          <button class="btn" type="submit">Log in</button>
          <div class="signup">Don't have an account? <a href="register.php">Sign-Up</a></div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
