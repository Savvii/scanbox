

<?php
// Secure PHP code demonstrating input validation, sanitization, and safe file upload

// Function to sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Example: Validate and sanitize user input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Validate name (only letters and spaces)
    if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        echo "Only letters and spaces allowed in name.";
        exit;
    }

    echo "Name: " . $name . "<br>Email: " . $email;
}

// Example: Safe file upload
if (isset($_FILES['uploaded_file'])) {
    $file = $_FILES['uploaded_file'];

    // Check for errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo "Upload error.";
        exit;
    }

    // Validate file type (only images)
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed_types)) {
        echo "Invalid file type.";
        exit;
    }

    // Validate file size (max 2MB)
    if ($file['size'] > 2 * 1024 * 1024) {
        echo "File too large.";
        exit;
    }

    // Generate a unique filename to prevent overwrites
    $upload_dir = 'uploads/';
    $filename = uniqid() . '_' . basename($file['name']);
    $target_path = $upload_dir . $filename;

    // Move the file
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        echo "File uploaded successfully: " . $filename;
    } else {
        echo "Upload failed.";
    }
}
?>

<!-- Simple HTML form for demonstration -->
<form method="post" enctype="multipart/form-data">
    Name: <input type="text" name="name"><br>
    Email: <input type="email" name="email"><br>
    <input type="submit" value="Submit">
</form>

<form method="post" enctype="multipart/form-data">
    File: <input type="file" name="uploaded_file"><br>
    <input type="submit" value="Upload">
</form>
