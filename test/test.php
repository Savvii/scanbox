<?php
// This is highly unsafe PHP code demonstrating multiple security vulnerabilities.
// DO NOT use this in production. It's for educational purposes only.

// 1. SQL Injection vulnerability
$conn = mysqli_connect("localhost", "root", "", "testdb");
$query = "SELECT * FROM users WHERE username = '" . $_GET['username'] . "'";
$result = mysqli_query($conn, $query);

// 2. XSS vulnerability
echo "<h1>Welcome, " . $_GET['name'] . "!</h1>";

// 3. Command Injection
system("ls " . $_GET['dir']);

// 4. File Inclusion vulnerability
include($_GET['file']);

// 5. Eval vulnerability
eval($_POST['code']);

// 6. Weak password storage (no hashing)
$password = $_POST['password'];
$query = "INSERT INTO users (password) VALUES ('$password')";

// 7. Session fixation
session_start();
$_SESSION['user'] = $_GET['user'];

// 8. Arbitrary file upload without checks
move_uploaded_file($_FILES['file']['tmp_name'], "/uploads/" . $_FILES['file']['name']);

// 9. Direct object reference
$user_id = $_GET['id'];
$query = "SELECT * FROM users WHERE id = $user_id";

// 10. CSRF without token
if ($_POST['action'] == 'delete') {
    $query = "DELETE FROM posts WHERE id = " . $_POST['post_id'];
}

// 11. Insecure deserialization
$data = unserialize($_GET['data']);

// 12. Hardcoded credentials
$api_key = "supersecretkey123";

// 13. No input validation
$email = $_POST['email'];
mail($email, "Subject", "Body");

// 14. Race condition in file handling
$file = fopen("counter.txt", "r+");
$count = fread($file, 10);
$count++;
fseek($file, 0);
fwrite($file, $count);
fclose($file);

// 15. Buffer overflow simulation (though PHP handles it, conceptually unsafe)
$buffer = str_repeat("A", 1000000);

// 16. Use of deprecated functions
mysql_connect("localhost", "root", "");

// 17. No error reporting suppression, but exposing errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 18. Insecure random number generation
$token = rand(0, 1000);

// 19. Directory traversal
$file = file_get_contents("../" . $_GET['path']);

// 20. Open redirect
header("Location: " . $_GET['url']);
?>