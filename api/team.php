
<?php
require_once 'config.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

try {
    $stmt = $pdo->prepare("SELECT * FROM team ORDER BY id ASC");
    $stmt->execute();
    $team = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($team, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>
