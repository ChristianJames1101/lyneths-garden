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
$user_id = $user['id'];
$message = $error = $pass_message = $pass_error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_account'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $middle_name = trim($_POST['middle_name']);
    $contact_number = trim($_POST['contact_number']);
    $address = trim($_POST['address']);
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, middle_name = ?, contact_number = ?, address = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $first_name, $last_name, $middle_name, $contact_number, $address, $email, $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['user']['first_name'] = $first_name;
        $_SESSION['user']['last_name'] = $last_name;
        $_SESSION['user']['middle_name'] = $middle_name;
        $_SESSION['user']['contact_number'] = $contact_number;
        $_SESSION['user']['address'] = $address;
        $_SESSION['user']['email'] = $email;
        $message = "Account details updated successfully.";
    } else {
        $error = "Failed to update account details.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($current_password, $hashed_password)) {
        if ($new_password === $confirm_password) {
            $new_hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $new_hashed, $user_id);
            if ($stmt->execute()) {
                $pass_message = "Password changed successfully.";
            } else {
                $pass_error = "Error updating password.";
            }
        } else {
            $pass_error = "New passwords do not match.";
        }
    } else {
        $pass_error = "Current password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Account - Lyneth's Garden</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #e8f5e9;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding: 50px 20px;
    }

    .container {
      background: white;
      max-width: 1000px;
      width: 1000px;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      position: relative;
    }

    .user-image {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
      margin: -90px auto 10px;
      display: block;
      border: 5px solid white;
      background: white;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    h2 {
      text-align: center;
      color: #2f6f3f;
      margin-bottom: 20px;
    }

    form {
      margin-top: 30px;
    }

    .form-group {
      margin: 15px 0;
    }

    label {
      font-weight: 600;
      display: block;
      margin-bottom: 5px;
      color: #444;
    }

    input[type="text"], input[type="email"], input[type="password"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
      background-color: #f9f9f9;
    }

    input:focus {
      border-color: #3fa64b;
      outline: none;
    }

    button {
      background: #3fa64b;
      color: white;
      padding: 10px 20px;
      border: none;
      font-weight: bold;
      cursor: pointer;
      border-radius: 8px;
      transition: background 0.3s;
    }

    button:hover {
      background: #2e8d3a;
    }

    .section {
      margin-bottom: 50px;
    }

    .form-title {
      margin-top: 40px;
      font-size: 18px;
      font-weight: bold;
      color: #2f6f3f;
      border-bottom: 1px solid #ccc;
      padding-bottom: 5px;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>

<div class="container">
  <img src="user.png" class="user-image" alt="User Image">
  <h2>My Account</h2>

  <form method="post" class="section">
    <div class="form-title">Edit Profile Information</div>
    <input type="hidden" name="update_account" value="1">

    <div class="form-group">
      <label>First Name</label>
      <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
    </div>
    <div class="form-group">
      <label>Middle Name</label>
      <input type="text" name="middle_name" value="<?= htmlspecialchars($user['middle_name']) ?>">
    </div>
    <div class="form-group">
      <label>Last Name</label>
      <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
    </div>
    <div class="form-group">
      <label>Contact Number</label>
      <input type="text" name="contact_number" value="<?= htmlspecialchars($user['contact_number']) ?>" required>
    </div>
    <div class="form-group">
      <label>Delivery Address</label>
      <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>" required>
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>

    <button type="submit">Save Changes</button>
  </form>

  <form method="post" class="section">
    <div class="form-title">Change Password</div>
    <input type="hidden" name="change_password" value="1">

    <div class="form-group">
      <label>Current Password</label>
      <input type="password" name="current_password" required>
    </div>
    <div class="form-group">
      <label>New Password</label>
      <input type="password" name="new_password" required>
    </div>
    <div class="form-group">
      <label>Confirm New Password</label>
      <input type="password" name="confirm_password" required>
    </div>

    <button type="submit">Update Password</button>
  </form>
</div>

<script>
<?php if (!empty($message)): ?>
  alert("<?= addslashes($message) ?>");
<?php elseif (!empty($error)): ?>
  alert("<?= addslashes($error) ?>");
<?php endif; ?>

<?php if (!empty($pass_message)): ?>
  alert("<?= addslashes($pass_message) ?>");
<?php elseif (!empty($pass_error)): ?>
  alert("<?= addslashes($pass_error) ?>");
<?php endif; ?>
</script>

</body>
</html>
