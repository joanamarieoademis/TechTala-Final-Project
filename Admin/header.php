<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once ('../db.connect.php');
require_once ('../db.php');

$profile_pic_path = 'image/profile.png';
$display_name = '';
$user = null;

// Uploading profile picture
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile-picture'], $_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $file = $_FILES['profile-picture'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $file_extension = pathinfo($_FILES['profile-picture']['name'], PATHINFO_EXTENSION);
        $current_date = date('Y-m-d');
        $profile_picture = $user_id . '_' . $current_date . '.' . $file_extension;
        $upload_dir = '../profiles/';
        $upload_path = $upload_dir . $profile_picture;

        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            $user = selectOne('users', ['id' => $user_id]);

            // Delete previous file if there is a profile changing it to new
            if (!empty($user['profile_picture']) && $user['profile_picture'] !== $profile_pic_path) {
                $old_file = '../profiles/' . $user['profile_picture'];
                if (file_exists($old_file)) {
                    unlink($old_file);
                }
            }

            update('users', $user_id, ['profile_picture' => $profile_picture]);
            header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
            exit;
        } else {
            $error = "Upload failed.";
        }
    } else {
        $error = "Upload error: " . $file['error'];
    }
}

// Display if the profile picture is uploaded successfully
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $success = "Profile picture updated successfully!";
}

// Get user information to display
if (isset($_SESSION['user_id'])) {
 
        $user = selectOne('users', ['id' => $_SESSION['user_id']]);
        if ($user && !empty($user['profile_picture'])) {
            $profile_pic_path = '../profiles/' . $user['profile_picture'];
        }
        $display_name = $user['display_name'] ?? $user['username'] ?? '';
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../image/logo.png">
    <link rel="stylesheet" href="header.css">
    <title>TechTala</title>
</head>
<body>
    <div class="hamburger">
        <span></span>
    </div>

    <nav>
        <img class="image-display" 
             src="<?= htmlspecialchars($profile_pic_path) ?>?v=<?= time() ?>" 
             alt="Profile Picture" 
             title="<?= htmlspecialchars($display_name) ?>"
             onerror="this.src='image/profile.png'">
        
        <h2 class="name"><?= htmlspecialchars($display_name ?: 'Guest') ?></h2>

        <ul class="links">
            <li><img src="image/dashboard.png" alt=""><a href="dashboard.php">Dashboard</a></li>
            <li><img src="image/paper-plane.png" alt=""><a href="posts.php">Manage Posts</a></li>
            <li><img src="image/group.png" alt=""><a href="users.php">Manage Users</a></li>
            <li><img src="image/chat.png" alt=""><a href="comments.php">Manage Comments</a></li>
        </ul>

        <?php if ($user): ?>
            <div class="upload-section">
                <?php if (isset($success)): ?>
                    <div class="message success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="message error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <button class="btn" onclick="window.location.href='logout.php'">
            <b>Log Out</b>
        </button>

        <!-- Upload profile picture -->
        <form method="POST" enctype="multipart/form-data">
            <div class="profile">
                <label class="up">Upload or Change Profile Picture:</label>
                <input type="file" class="upload" name="profile-picture" accept="image/*" >
            </div>
            <div class="dub">
                <button type="submit" class="save">Save</button>
            </div>
        </form>

    </nav>

    <script src="header.js"></script>

</body>
</html>