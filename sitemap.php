
<?php
require_once 'api/config.php';

header("Content-Type: application/xml; charset=utf-8");

// Start XML output
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Domain base URL
$domain = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];

// Add homepage
echo "  <url>\n";
echo "    <loc>{$domain}/</loc>\n";
echo "    <priority>1.0</priority>\n";
echo "    <changefreq>daily</changefreq>\n";
echo "  </url>\n";

// Add static pages
$static_pages = [
    'about.html' => 0.8,
    'services.html' => 0.8,
    'portfolio.html' => 0.8,
    'team.html' => 0.7,
    'contact.html' => 0.7
];

foreach ($static_pages as $page => $priority) {
    echo "  <url>\n";
    echo "    <loc>{$domain}/{$page}</loc>\n";
    echo "    <priority>{$priority}</priority>\n";
    echo "    <changefreq>weekly</changefreq>\n";
    echo "  </url>\n";
}

// Add projects
try {
    $stmt = $pdo->prepare("SELECT id, title, created_at FROM projects ORDER BY created_at DESC");
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($projects as $project) {
        $lastmod = date('Y-m-d', strtotime($project['created_at']));
        echo "  <url>\n";
        echo "    <loc>{$domain}/project.html?id={$project['id']}</loc>\n";
        echo "    <lastmod>{$lastmod}</lastmod>\n";
        echo "    <priority>0.6</priority>\n";
        echo "    <changefreq>monthly</changefreq>\n";
        echo "  </url>\n";
    }
} catch (PDOException $e) {
    // Ignore errors, just don't add projects
}

// Add services
try {
    $stmt = $pdo->prepare("SELECT id, title FROM services ORDER BY order_index, id ASC");
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($services as $service) {
        echo "  <url>\n";
        echo "    <loc>{$domain}/service.html?id={$service['id']}</loc>\n";
        echo "    <priority>0.6</priority>\n";
        echo "    <changefreq>monthly</changefreq>\n";
        echo "  </url>\n";
    }
} catch (PDOException $e) {
    // Ignore errors, just don't add services
}

// Close XML
echo '</urlset>';
?>
