
<?php
// Database configuration
$host = 'localhost';
$dbname = 'rocket_agency';
$username = 'root';
$password = '';

// Create database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set character set to UTF-8
    $pdo->exec("SET NAMES 'utf8'");
} catch(PDOException $e) {
    // If connection fails
    die("CONNECTION ERROR: " . $e->getMessage());
}

// Helper functions
function response($data, $status = 200) {
    header("Content-Type: application/json; charset=UTF-8");
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function errorResponse($message, $status = 400) {
    response(['success' => false, 'message' => $message], $status);
}

function successResponse($data = null, $message = 'Operation successful') {
    $response = ['success' => true, 'message' => $message];
    if ($data !== null) {
        $response['data'] = $data;
    }
    response($response);
}
?>
