<?php
session_start(); 

include '../db.connect.php';
include '../db.php';

// Check if the user is logged iin
if (!isset($_SESSION['user_id'])) {
    header("Location: /Project/TechTala/Authentication/homepage.html");
    exit();
}

$table  = 'post';

if (isset($_POST['publish']) || isset($_POST['draft'])) {
    $title = $_POST['title'];
    $content = $_POST['format'] ?? $_POST['content'];
    $username = $_SESSION['username'] ?? 'guest'; 
    $users_id = $_SESSION['user_id'] ?? 0;
    $status = isset($_POST['publish']) ? 'published' : 'draft'; // Set post status

    $post = [
        'title' => $title,
        'content' => $content, 
        'username' => $username,
        'users_id' => $users_id,
        'status' => $status
    ];

    if (!empty($_FILES['fileUpload']['name'])) {
        $file_extension = pathinfo($_FILES['fileUpload']['name'], PATHINFO_EXTENSION);
        $image_name = $users_id . '_' . $title . '.' . $file_extension;
        $destination = 'images/' . $image_name;

        if (move_uploaded_file($_FILES['fileUpload']['tmp_name'], $destination)) {
            $post['image'] = $image_name;
        } else {
            $errors[] = "Failed to upload image.";
        }
    } elseif ($status === 'published') {
        $errors[] = "Post image is required for published posts";
    }

    if (empty($errors)) {
        $post_id = create($table, $post);
        if ($post_id) {
            if ($status === 'draft') {
                header('Location: http://localhost/TechTala/Author/drafts.php');
            } else {
                header('Location: http://localhost/TechTala/Author/homepage.php');
            }
            exit();
        } else {
            echo "Error: Failed to create post";
        }
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
    <link rel="stylesheet" href="create_newblogs.css">
</head>
<body>
    
    <?php include ("header.php")?>

    <div class="container">

        <form action="create_newblog.php" method="post" enctype="multipart/form-data" id="publish-form">
            
            <label for="title">Title</label>
            <input type="text" id="title" name="title" placeholder="Enter post title" required>

            <label>Content</label>
            <div class="buttons">
                <button type="button" id="bold">B</button>
                <button type="button" id="italic">I</button>
                <button type="button" id="underline">U</button>
            </div>

            <textarea id="content" name="content" placeholder="Write your content here..." required></textarea>
            
            <!-- Hidden field to store formatted HTML content -->
            <input type="hidden" id="format" name="format">
            
            <div id="preview" class="preview"></div>

            <label for="fileUpload">Upload Photo</label>
            <input type="file" id="fileUpload" name="fileUpload" accept="image/*" required>

            <div class="upload-box" onclick="document.getElementById('fileUpload').click()">
                <p>Click to choose photo</p>
                <img id="previewImage" src="" alt="Preview">
            </div>

            <div class="actions">
                <button type="submit" name="publish" class="publish">Publish Post</button>
                <button type="submit" name="draft" class="draft">Save as Draft</button>
            </div>

        </form>
    </div>

    <script src="create_newblog.js"></script>

</body>
</html>