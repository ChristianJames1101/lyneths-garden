<?php
$imageFolder = '../featured/pics/';
$videoFolder = '../featured/videos/';
$error = "";

if (!is_dir($imageFolder)) {
    mkdir($imageFolder, 0777, true);
}
if (!is_dir($videoFolder)) {
    mkdir($videoFolder, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['featured_image']) && $_POST['type'] === 'image') {
        $file = $_FILES['featured_image'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (in_array($file['type'], $allowed_types) && $file['error'] === 0) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newName = uniqid('img_', true) . '.' . $ext;
            move_uploaded_file($file['tmp_name'], $imageFolder . $newName);
            header("Location: index.php?page=featured");
            exit;
        } else {
            $error = "Invalid image file.";
        }
    }

    if (isset($_FILES['featured_video']) && $_POST['type'] === 'video') {
        $file = $_FILES['featured_video'];
        $allowed_types = ['video/mp4', 'video/webm', 'video/ogg'];

        if (in_array($file['type'], $allowed_types) && $file['error'] === 0) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newName = uniqid('vid_', true) . '.' . $ext;
            move_uploaded_file($file['tmp_name'], $videoFolder . $newName);
            header("Location: index.php?page=featured");
            exit;
        } else {
            $error = "Invalid video file.";
        }
    }
}

if (isset($_GET['delete']) && isset($_GET['type'])) {
    $filename = basename($_GET['delete']);
    $targetFolder = $_GET['type'] === 'video' ? $videoFolder : $imageFolder;
    $filePath = rtrim($targetFolder, '/') . '/' . $filename;

    if (file_exists($filePath)) {
        unlink($filePath);
    }

    header("Location: index.php?page=featured");
    exit;
}


$images = array_values(array_filter(scandir($imageFolder), function($file) use ($imageFolder) {
    return is_file($imageFolder . $file) && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file);
}));

$videos = array_values(array_filter(scandir($videoFolder), function($file) use ($videoFolder) {
    return is_file($videoFolder . $file) && preg_match('/\.(mp4|webm|ogg)$/i', $file);
}));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Featured Media</title>
    <style>
        body {
            background:rgba(45, 119, 52, 0);
            margin: 0;
            padding: 40px;
            color: #333;
        }

        h1, h2 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .upload-section {
            margin-bottom: 30px;
            padding: 20px;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .upload-section form {
            margin-bottom: 15px;
        }

        .upload-section input[type="file"] {
            padding: 10px;
        }

        button {
            padding: 10px 15px;
            background-color: #2d7735;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2d7735;
        }

        .media {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .card img, .card video {
            max-width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 6px;
        }

        .delete-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 6px 12px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        .error-message {
            background: #ffe6e6;
            color: #d8000c;
            padding: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #d8000c;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Announcements Manager</h1>

    <?php if (!empty($error)): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="upload-section">
        <h2>Upload Image</h2>
        <form action="featured.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="type" value="image">
            <input type="file" name="featured_image" accept="image/*" required>
            <button type="submit">Upload Image</button>
        </form>

        <h2>Upload Video</h2>
        <form action="featured.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="type" value="video">
            <input type="file" name="featured_video" accept="video/*" required>
            <button type="submit">Upload Video</button>
        </form>
    </div>

    <h2>Featured</h2>
    <div class="media">
        <?php foreach ($images as $img): ?>
            <div class="card">
                <img src="<?= $imageFolder . $img ?>" alt="Image">
                <br>
                <a class="delete-btn"
                    href="index.php?page=featured&delete=<?= urlencode($img) ?>&type=image"
                    onclick="return confirm('Delete this image?')">Delete</a>
            </div>
        <?php endforeach; ?>
    </div>

    <h2>Showcase Videos</h2>
    <div class="media">
        <?php foreach ($videos as $vid): ?>
            <div class="card">
                <video controls>
                    <source src="<?= $videoFolder . $vid ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <br>
                <a class="delete-btn"
                    href="index.php?page=featured&delete=<?= urlencode($vid) ?>&type=video"
                    onclick="return confirm('Delete this video?')">Delete</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
