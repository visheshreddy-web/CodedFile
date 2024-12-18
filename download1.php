<?php
// Check if a file is provided for download
if (isset($_GET['file'])) {
    $file = basename($_GET['file']); // Sanitize file input to prevent directory traversal attacks
    $filePath = "uploads/" . $file;

    if (file_exists($filePath)) {
        // Extract the unique code from the filename
        // Assuming the unique code is a number followed by an underscore
        $uniqueCode = substr($file, 0, strpos($file, '_'));
        
        // Remove the unique code (if present) from the filename
        $fileName = (strpos($file, $uniqueCode . '_') === 0) 
                    ? substr($file, strlen($uniqueCode) + 1) // Remove the unique code prefix
                    : $file;

        // Force file download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        echo "Error: The requested file does not exist.";
    }
} else {
    echo "Error: No file specified for download.";
}
