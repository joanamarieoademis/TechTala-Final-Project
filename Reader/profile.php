<?php
session_start();

include '../db.connect.php';
include '../db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /Project/TechTala/Authentication/homepage.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';
$error = '';

// Fetch current user data 
$user = selectOne('users', ['id' => $user_id]);

// Handle submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $gender = $_POST['gender'] ?? '';

    if (empty($username)) {
        $error = "Username is required.";
    } else {
        $existing_user = selectAll('users', ['username' => $username]);
        $username_taken = false;

        foreach ($existing_user as $existing) {
            if ($existing['id'] != $user_id) {
                $username_taken = true;
                break;
            }
        }

        if ($username_taken) {
            $error = "Username is already taken.";
        } else {
            $profile_picture = null;
            if (isset($_FILES['profile-picture']) && $_FILES['profile-picture']['error'] === UPLOAD_ERR_OK) {
                $file_extension = pathinfo($_FILES['profile-picture']['name'], PATHINFO_EXTENSION);
                $current_date = date('Y-m-d');
                $profile_picture = $user_id . '_' . $current_date . '.' . $file_extension;
                $upload_path = '../profiles/' . $profile_picture;

                if (!move_uploaded_file($_FILES['profile-picture']['tmp_name'], $upload_path)) {
                    $error = "Failed to upload profile picture.";
                }
            }

            if (empty($error)) {
                $update_data = [
                    'username' => $username,
                    'gender' => $gender
                ];

                if ($profile_picture) {
                    $update_data['profile_picture'] = $profile_picture;
                }

                $affected_rows = update('users', $user_id, $update_data);

                if ($affected_rows > 0) {
                    $message = "Updated successfully!";
                    $user = selectOne('users', ['id' => $user_id]); // Refresh user data
                } else {
                    $message = "No changes were made.";
                }
            }
        }
    }
}

$date_joined = isset($user['created_at']) ? date('F j, Y', strtotime($user['created_at'])) : 'Unknown';
$profile_pic_path = !empty($user['profile_picture']) ? '../profiles/' . $user['profile_picture'] : 'image/profile.png';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTala</title>
    <link rel="icon" href="image/logo.png">
    <link rel="stylesheet" href="profile.css">
</head>
<body>

    <?php include ("header.php")?>

    <main class="main-content">
        <section>
            <h1>Profile</h1>
            
            <?php if ($message): ?>
                <div class="message success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="message error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form class="edit-profile-form" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" class="form-input" 
                           value="<?php echo is_array($user) && isset($user['username']) ? htmlspecialchars($user['username']) : ''; ?>" readonly>
                           <small>Username cannot be changed</small>
                </div>

                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" class="form-input" 
                           value="<?php echo is_array($user) && isset($user['email']) ? htmlspecialchars($user['email']) : ''; ?>" readonly>
                    <small>Email cannot be changed</small>
                </div>

                <div class="form-group">
                    <label>Gender:</label>
                    <div class="gender-options">
                        <?php if (is_array($user) && array_key_exists('gender', $user)): ?>
                            <label><input type="radio" name="gender" value="male" <?php echo ($user['gender'] == 'male') ? 'checked' : ''; ?>> Male</label>
                            <label><input type="radio" name="gender" value="female" <?php echo ($user['gender'] == 'female') ? 'checked' : ''; ?>> Female</label>
                            <label><input type="radio" name="gender" value="not-specified" <?php echo ($user['gender'] == 'not-specified' || empty($user['gender'])) ? 'checked' : ''; ?>> Not Specified</label>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="profile-picture">Profile Picture:</label>
                    <input type="file" id="profile-picture" name="profile-picture" class="form-input" accept="image/*" 
                           <?php echo !is_array($user) || !array_key_exists('profile_picture', $user) ? 'disabled' : ''; ?>>
                    
                </div>
                <div class="form-actions">
                    <button type="submit" class="save-button">Save</button>
                </div>
            </form>
        </section>

        <section>
            <h1>Preview</h1>
            <div class="profile-details">
                <img src="<?php echo htmlspecialchars($profile_pic_path); ?>" alt="Profile Picture" class="profile-details-picture">
                <ul class="details-list">
                    <li><strong>Username:</strong> <?php echo is_array($user) && isset($user['username']) ? htmlspecialchars($user['username']) : 'Unknown'; ?></li>
                    <li><strong>Email:</strong> <?php echo is_array($user) && isset($user['email']) ? htmlspecialchars($user['email']) : 'Unknown'; ?></li>
                    <li><strong>Gender:</strong> <?php echo is_array($user) && isset($user['gender']) ? htmlspecialchars($user['gender']) : 'Not specified'; ?></li>
                    <li><strong>Role:</strong> <?php echo is_array($user) && isset($user['role']) ? htmlspecialchars($user['role']) : 'User'; ?></li>
                    <li><strong>Date Joined:</strong> <?php echo htmlspecialchars($date_joined); ?></li>
                </ul>
            </div>
        </section>

    </main>

</body>
</html>