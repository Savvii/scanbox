<?php
// Secure PHP code demonstrating safe database interaction using PDO

// Database configuration (use environment variables in production)
$host = 'localhost';
$dbname = 'testdb';
$username = 'root';
$password = ''; // In production, use a secure password

try {
    // Create a PDO instance with prepared statements enabled
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Use real prepared statements

    // Example: Safe query with prepared statement to prevent SQL injection
    if (isset($_GET['username'])) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $_GET['username'], PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Output results safely (assuming no XSS here, but in real code, escape output)
        foreach ($results as $row) {
            echo htmlspecialchars($row['username']) . "<br>";
        }
    }

    // Example: Safe insert with prepared statement
    if (isset($_POST['new_username']) && isset($_POST['new_email'])) {
        $stmt = $pdo->prepare("INSERT INTO users (username, email) VALUES (:username, :email)");
        $stmt->bindParam(':username', $_POST['new_username'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $_POST['new_email'], PDO::PARAM_STR);
        $stmt->execute();
        echo "User added successfully.";
    }

} catch (PDOException $e) {
    // Log error instead of displaying in production
    echo "Database error: " . $e->getMessage();
}
?>
