<?php
// Database connection parameters
$host = 'localhost';
$username = 'root';
$password = '';  // Replace with your MySQL password
$dbname = 'codedfile';  // Replace with your database name

// Connect to MySQL database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the code is passed via the URL
if (isset($_GET['code'])) {
    $code = htmlspecialchars($_GET['code']); // Sanitize code input

    // If the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
            $file_name = basename($_FILES['file']['name']);
            $file_tmp = $_FILES['file']['tmp_name'];
            $upload_dir = 'uploads/';
            $file_path = $upload_dir . $code . '_' . $file_name;

            // Ensure the uploads directory exists
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Move the file to the uploads directory
            if (move_uploaded_file($file_tmp, $file_path)) {
                // Insert the file details into the database
                $sql = "INSERT INTO file_codes (code, file_path) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ss', $code, $file_path);

                if ($stmt->execute()) {
                    $success_message = "Your file has been uploaded successfully!";
                    $redirect = true; // Set a flag for redirection
                    $hide_form = true; // Flag to hide the form
                } else {
                    $error_message = "Database error: Unable to save file details.";
                }
            } else {
                $error_message = "There was an error moving the uploaded file.";
            }
        } else {
            $error_message = "No file was selected or an error occurred during the upload.";
        }
    }
} else {
    die("No code provided. Please use a valid code to upload files.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File - CodedFile</title>
    <?php if (!empty($redirect)): ?>
        <meta http-equiv="refresh" content="2;url=index.htm"> <!-- Redirect only if the file upload was successful -->
    <?php endif; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* General Styles */
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
        <h1>CodedFile - Secure File Upload</h1>
    </header>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-body">
                <h4 class="card-title text-center">Upload Your File</h4>
                <p class="text-muted text-center">Using Code: <strong><?php echo $code; ?></strong></p>

                <!-- Display Success or Error Message -->
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success text-center">
                        <?php echo $success_message; ?>
                    </div>
                    <p class="text-center text-muted mt-3">Redirecting to the home page in <span id="countdown">2</span> seconds...</p>
                    <script>
                        let countdown = 2;
                        const countdownElement = document.getElementById('countdown');
                        const interval = setInterval(() => {
                            countdown--;
                            countdownElement.textContent = countdown;
                            if (countdown <= 0) {
                                clearInterval(interval);
                                window.location.href = 'index.htm';
                            }
                        }, 1000);
                    </script>
                <?php elseif (!empty($error_message)): ?>
                    <div class="alert alert-danger text-center">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <!-- File Upload Form (Hidden if successful) -->
                <?php if (empty($hide_form)): ?>
                    <form action="upload.php?code=<?php echo $code; ?>" method="POST" enctype="multipart/form-data" class="mt-4">
                        <div class="mb-3">
                            <label for="file" class="form-label">Choose a File</label>
                            <input type="file" name="file" id="file" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Upload File</button>
                    </form>
                <?php endif; ?>
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
