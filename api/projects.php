
<?php
require_once 'config.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

try {
    // Check if we're requesting a specific project by ID
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        $project = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($project) {
            // Handle image path
            if (strpos($project['image'], 'http') !== 0 && strpos($project['image'], 'assets/') !== 0) {
                $project['image'] = 'assets/uploads/' . $project['image'];
            }
            
            echo json_encode([
                'success' => true,
                'data' => $project
            ], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'المشروع غير موجود'
            ], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }
    
    // Check if we're requesting featured projects
    $featured = isset($_GET['featured']) ? (bool)$_GET['featured'] : false;
    
    if ($featured) {
        $stmt = $pdo->prepare("SELECT * FROM projects WHERE featured = 1 ORDER BY order_index, created_at DESC");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM projects ORDER BY order_index, created_at DESC");
    }
    
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Process image paths
    foreach ($projects as &$project) {
        if (strpos($project['image'], 'http') !== 0 && strpos($project['image'], 'assets/') !== 0) {
            $project['image'] = 'assets/uploads/' . $project['image'];
        }
    }
    
    echo json_encode([
        'success' => true,
        'data' => $projects
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>
