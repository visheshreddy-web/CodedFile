<?php
// Check if the file parameter is provided
if (isset($_GET['file'])) {
    $file = basename($_GET['file']); // Sanitize filename
    $filePath = "uploads/" . $file;

    // Connect to the database
    $servername = "localhost"; // Change to your server details
    $username = "root";        // Change to your database username
    $password = "";            // Change to your database password
    $dbname = "codedfile";     // Database name

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        $error = "Database connection failed: " . $conn->connect_error;
    } else {
        // Extract the unique code from the filename
        $uniqueCode = substr($file, 0, strpos($file, '_'));

        if (file_exists($filePath)) {
            // Delete the file from the uploads directory
            unlink($filePath);

            // Delete the unique key from the database
            $stmt = $conn->prepare("DELETE FROM file_codes WHERE code = ?");
            $stmt->bind_param("s", $uniqueCode);
            $stmt->execute();
            $stmt->close();

            $success = "Your file has been deleted successfully.";
        } else {
            $error = "The requested file does not exist.";
        }

        // Close the database connection
        $conn->close();
    }
} else {
    $error = "No file specified for deletion.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodedFile - File Deletion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        header {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 20px 0;
        }
        .card {
            max-width: 500px;
            margin: 30px auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: white;
            overflow: hidden;
        }
        .card-body {
            padding: 20px;
        }
        .alert {
            margin-top: 20px;
        }
        footer {
            margin-top: 50px;
            font-size: 0.9rem;
            color: #6c757d;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <h1>CodedFile - File Deletion</h1>
    </header>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-body">
                <h4 class="card-title text-center">File Deletion Status</h4>

                <!-- Display Success or Error Message -->
                <?php if (isset($success)) { ?>
                    <div class="alert alert-success text-center">
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php } elseif (isset($error)) { ?>
                    <div class="alert alert-danger text-center">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php } ?>

                <!-- Countdown and Redirect Notice -->
                <p class="text-center text-muted mt-4">
                    Redirecting to home page in <span id="countdown">2</span> seconds...
                </p>
                <script>
                    let countdown = 2; // Starting countdown
                    const countdownElement = document.getElementById('countdown');
                    const interval = setInterval(() => {
                        countdown--;
                        countdownElement.textContent = countdown; // Update countdown text
                        if (countdown <= 0) {
                            clearInterval(interval); // Stop the countdown
                            window.location.href = 'index.htm'; // Redirect to home page
                        }
                    }, 1000); // Decrease every 1 second
                </script>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 CodedFile. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
