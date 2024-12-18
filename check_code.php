<?php
// Database connection parameters
$host = 'localhost';
$username = 'root';
$password = '';  // Update with your MySQL password
$dbname = 'codedfile';  // Replace with your database name

// Connect to MySQL database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the code was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'];

    // Query to check if the code exists in the database
    $sql = "SELECT * FROM file_codes WHERE code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $code);  // Bind the code parameter
    $stmt->execute();
    $result = $stmt->get_result();

    // If the code exists, redirect to download page
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_path = $row['file_path'];
        // Redirect to the download page with the file path
        header("Location: download.php?file=$file_path");
        exit();
    } else {
        // If code doesn't exist, redirect to upload page
        header("Location: upload.php?code=$code");
        exit();
    }
}

$conn->close();
?>
