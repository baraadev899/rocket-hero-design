
<?php
require_once 'api/config.php';

header("Content-Type: text/plain");

try {
    // Get robots.txt content from SEO settings
    $stmt = $pdo->prepare("SELECT robots_txt FROM seo_settings WHERE id = 1");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result && !empty($result['robots_txt'])) {
        echo $result['robots_txt'];
    } else {
        // Default robots.txt content
        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo "Sitemap: " . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/sitemap.xml");
    }
} catch (PDOException $e) {
    // Default robots.txt if there's an error
    echo "User-agent: *\n";
    echo "Allow: /\n";
}
?>
