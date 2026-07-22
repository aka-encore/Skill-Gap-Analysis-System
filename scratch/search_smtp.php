<?php
header('Content-Type: text/plain');
echo "=== SEARCHING PHP FILES FOR SMTP PATTERNS ===" . PHP_EOL;

$dir = new RecursiveDirectoryIterator(__DIR__ . '/..');
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        if (str_contains($path, 'vendor')) continue;
        $content = file_get_contents($path);
        if (str_contains($path, 'search_smtp.php')) continue;
        
        $matches = [];
        if (preg_match_all('/SMTP_[A-Z_]+|smtp\.[a-z\.]+|[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}/i', $content, $m)) {
            $matches = array_unique($m[0]);
        }
        
        if (!empty($matches)) {
            echo "File: " . str_replace(__DIR__ . '/..', '', $path) . PHP_EOL;
            foreach ($matches as $match) {
                echo "  - Token: " . $match . PHP_EOL;
            }
        }
    }
}
