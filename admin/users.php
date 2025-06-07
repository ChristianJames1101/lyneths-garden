<?php
include '../db.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? null;

$search = $_GET['search'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    if ($action === 'edit' && $id) {
        if ($password) {
            $stmt = $conn->prepare("UPDATE users SET last_name=?, first_name=?, middle_name=?, contact_number=?, address=?, email=?, role=?, password=? WHERE id=?");
            $stmt->bind_param("ssssssssi", $last_name, $first_name, $middle_name, $contact_number, $address, $email, $role, $password, $id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET last_name=?, first_name=?, middle_name=?, contact_number=?, address=?, email=?, role=? WHERE id=?");
            $stmt->bind_param("sssssssi", $last_name, $first_name, $middle_name, $contact_number, $address, $email, $role, $id);
        }
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO users (last_name, first_name, middle_name, contact_number, address, email, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $last_name, $first_name, $middle_name, $contact_number, $address, $email, $password, $role);
        $stmt->execute();
    }

    header("Location: index.php?page=users");
    exit();
}

if ($action === 'delete' && $id) {
    $conn->query("DELETE FROM cart WHERE user_id = $id");
    $conn->query("DELETE FROM users WHERE id = $id");
    header("Location: index.php?page=users");
    exit();
}

$user = null;
if ($action === 'edit' && $id) {
    $result = $conn->query("SELECT * FROM users WHERE id = $id");
    $user = $result->fetch_assoc();
}

$searchSql = "";
$params = [];
if ($search !== '') {
    $search = "%$search%";
    $searchSql = " WHERE last_name LIKE ? OR first_name LIKE ? OR middle_name LIKE ? OR contact_number LIKE ? OR email LIKE ? OR role LIKE ?";
    $params = [$search, $search, $search, $search, $search, $search];
}

if ($searchSql) {
    $stmt = $conn->prepare("SELECT * FROM users $searchSql ORDER BY id DESC");
    $stmt->bind_param("ssssss", ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM users ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            margin: 20px;
        }

        h2 {
            color: #3fa64b;
        }

        .user-form {
            margin-bottom: 20px;
        }

        .user-form input, .user-form button, select {
            width: 100%;
            max-width: 400px;
            margin-bottom: 10px;
            padding: 10px;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .user-form button {
            background-color: #3fa64b;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        .user-form button:hover {
            background-color: #338a3b;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 16px;
        }

        .user-table th, .user-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        .user-table th {
            background-color: #3fa64b;
            color: white;
        }

        .user-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .user-table a {
            color: #3fa64b;
            text-decoration: none;
            font-weight: bold;
        }

        .user-table a:hover {
            text-decoration: underline;
        }

        .search-box {
            margin-bottom: 20px;
        }

        .search-box input[type="text"] {
            width: 300px;
            padding: 8px 12px;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .search-box button {
            padding: 8px 16px;
            font-size: 16px;
            border-radius: 6px;
            border: none;
            background-color: #3fa64b;
            color: white;
            cursor: pointer;
            font-weight: bold;
            margin-left: 5px;
        }

        .search-box button:hover {
            background-color: #338a3b;
        }
    </style>
</head>
<body>

<h2><?php echo $action === 'edit' ? 'Edit User' : 'Add New User'; ?></h2>
<form method="post" class="user-form">
    <input type="text" name="last_name" placeholder="Last Name" required value="<?= htmlspecialchars($user['last_name'] ?? '') ?>">
    <input type="text" name="first_name" placeholder="First Name" required value="<?= htmlspecialchars($user['first_name'] ?? '') ?>">
    <input type="text" name="middle_name" placeholder="Middle Name" required value="<?= htmlspecialchars($user['middle_name'] ?? '') ?>">
    <input type="text" name="contact_number" placeholder="Contact Number" required value="<?= htmlspecialchars($user['contact_number'] ?? '') ?>">
    <input type="text" name="address" placeholder="Address" required value="<?= htmlspecialchars($user['address'] ?? '') ?>">
    <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($user['email'] ?? '') ?>">
    <select name="role" required>
        <option value="" disabled <?= empty($user['role']) ? 'selected' : '' ?>>Select Role</option>
        <option value="admin" <?= (isset($user['role']) && $user['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
        <option value="customer" <?= (isset($user['role']) && $user['role'] === 'customer') ? 'selected' : '' ?>>Customer</option>
    </select>

    <input type="password" name="password" placeholder="<?= $action === 'edit' ? 'New Password (optional)' : 'Password' ?>" <?= $action !== 'edit' ? 'required' : '' ?>>
    <button type="submit"><?= $action === 'edit' ? 'Update' : 'Create' ?></button>
</form>

<h2>User List</h2>

<form method="get" action="index.php" class="search-box">
    <input type="hidden" name="page" value="users">
    <input type="text" name="search" placeholder="Search users..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" style="flex-grow:1;">
    <button type="submit">Search</button>
    <button type="button" onclick="window.location.href='index.php?page=users'">Clear</button>
</form>

<table class="user-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Last</th>
            <th>First</th>
            <th>Middle</th>
            <th>Contact</th>
            <th>Address</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['last_name']) ?></td>
                <td><?= htmlspecialchars($row['first_name']) ?></td>
                <td><?= htmlspecialchars($row['middle_name']) ?></td>
                <td><?= htmlspecialchars($row['contact_number']) ?></td>
                <td><?= htmlspecialchars($row['address']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td>
                    <a href="index.php?page=users&action=edit&id=<?= $row['id'] ?>">Edit</a> |
                    <a href="index.php?page=users&action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Delete this user?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="9" style="text-align:center;">No users found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>
