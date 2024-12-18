<?php
// Check if the code and file details are provided
if (isset($_GET['file'])) {
    $file = basename($_GET['file']); // Sanitize filename
    $filePath = "uploads/" . $file;

    if (file_exists($filePath)) {
        // Extract the unique code from the filename
        // Assuming the unique code is a number followed by an underscore
        $uniqueCode = substr($file, 0, strpos($file, '_'));
        
        // Remove the unique code (if present) from the filename
        $fileName = (strpos($file, $uniqueCode . '_') === 0) 
                    ? substr($file, strlen($uniqueCode) + 1) // Remove the unique code prefix
                    : $file;
    } else {
        $error = "The requested file does not exist.";
    }
} else {
    $error = "No file specified for download.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodedFile - Download Your File</title>
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
        <h1>CodedFile - Download Your File</h1>
    </header>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-body">
                <h4 class="card-title text-center">Your File Details</h4>

                <!-- Display Success or Error Message -->
                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger text-center">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php } else { ?>
                    <p class="text-center text-muted">File Name: <strong><?= htmlspecialchars($fileName) ?></strong></p>
                    <div class="text-center mt-4">
                        <a href="download1.php?file=<?= urlencode($file) ?>" class="btn btn-primary w-100">
                            <i class="bi bi-download"></i> Download File
                        </a>
                    </div>
                    <!-- Home Button -->
                    <div class="text-center mt-3">
                        <a href="index.htm" class="btn btn-secondary w-100">Go to Home</a>
                    </div>
                    <!-- Delete Button -->
                    
<form method="get" action="delete.php" class="mt-3">
    <input type="hidden" name="file" value="<?= urlencode($file) ?>">
    <button type="submit" class="btn btn-danger w-100">Delete File</button>
</form>

                <?php } ?>
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
