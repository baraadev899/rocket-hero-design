
<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rocket_agency";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set
$conn->set_charset("utf8");

// Helper functions for responses
function jsonResponse($data, $status = 200) {
    header('Content-Type: application/json; charset=UTF-8');
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function errorJson($message, $status = 400) {
    jsonResponse(['success' => false, 'message' => $message], $status);
}

function successJson($data = null, $message = 'Operation successful') {
    $response = ['success' => true, 'message' => $message];
    if ($data !== null) {
        $response['data'] = $data;
    }
    jsonResponse($response);
}

// Database helper functions
function getAdminByUsername($conn, $username) {
    $query = "SELECT * FROM admins WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        return $result->fetch_assoc();
    }
    
    return null;
}
?>
