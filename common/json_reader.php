<?php

function readJsonFileToObject($filePath) {
    // Check if the file exists
    if (!file_exists($filePath)) {
        throw new Exception('File not found: ' . $filePath);
    }

    // Read the JSON file content
    $jsonContent = file_get_contents($filePath);

    // Decode the JSON content to a PHP object
    $object = json_decode($jsonContent);

    // Check for JSON errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON format: ' . json_last_error_msg());
    }

    return $object;
}

?>
