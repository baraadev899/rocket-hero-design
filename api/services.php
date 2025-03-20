
<?php
require_once 'config.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

try {
    $stmt = $pdo->prepare("SELECT * FROM services ORDER BY order_index, id ASC");
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Fix image paths if needed
    foreach ($services as &$service) {
        if (!empty($service['image']) && strpos($service['image'], 'http') !== 0) {
            $service['image'] = $service['image']; // Image path is already set correctly
        }
        
        // Ensure icons have 'fa-' prefix
        if (!empty($service['icon']) && strpos($service['icon'], 'fa-') !== 0) {
            $service['icon'] = 'fa-' . $service['icon'];
        }
    }
    
    echo json_encode($services, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>
