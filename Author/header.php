<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once '../db.connect.php';
require_once '../db.php';

// Get user profile picture
$profile_pic_path = 'image/profile.png'; 

if (isset($_SESSION['user_id'])) {
    $user = selectOne('users', ['id' => $_SESSION['user_id']]);
    if ($user && !empty($user['profile_picture'])) {
        $profile_pic_path = '../profiles/' . $user['profile_picture'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTala</title>
    <link rel="icon" href="image/logo.png">
    <link rel="stylesheet" href="header.css">
</head>
<body>
    <nav>
        <img src="image/logo.png" class="logo">
        <h3>TechTala</h3>
        <ul>
            <li><a href="homepage.php">Home</a></li>
            <li><a href="creations.php">Edit Creations</a></li>
            <li><a href="create_newblog.php">Create New Blog</a></li>
            <li><div class="drop">
            <div class="menu">
                <img class="profile" src="<?php echo htmlspecialchars($profile_pic_path); ?>" alt="Profile Picture"?>
            </div>
                    <div class="sub-menu">
                        <a href="profile.php">Profile</a>
                        <a href="drafts.php">Drafts</a>
                        <a href="comments.php">Comments</a>
                        <a href="logout.php">Logout</a>
                    </div>
            </div></div></li>
            <li id="prof_hide"><a href="profile.php"><span>Profile</span></a></li>
            <li id="log"><a href="logout.php">Logout</a></li>
        </ul>
        
        <div class="hamburger">
            <span></span>
        </div>
    </nav>

    <script src="header.js"></script>

</body>
</html>