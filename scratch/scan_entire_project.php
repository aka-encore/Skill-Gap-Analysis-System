<?php
header('Content-Type: text/plain');
echo "========================================================" . PHP_EOL;
echo "  COMPREHENSIVE PROJECT-WIDE SMTP CREDENTIAL SEARCH     " . PHP_EOL;
echo "========================================================" . PHP_EOL;

$rootDir = realpath(__DIR__ . '/..');
echo "Scanning Root Directory: $rootDir" . PHP_EOL . PHP_EOL;

$searchTerms = [
    'SMTP_PASS',
    'SMTP_USER',
    'SMTP_FROM_EMAIL',
    'smtp.gmail.com',
    'Password',
    'Username',
    'setFrom'
];

$dir = new RecursiveDirectoryIterator($rootDir, RecursiveDirectoryIterator::SKIP_DOTS);
$iterator = new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::SELF_FIRST);

$foundCount = 0;

foreach ($iterator as $file) {
    if ($file->isDir()) continue;
    $path = $file->getPathname();
    
    // Skip vendor and git dirs to keep search relevant to project files
    if (str_contains($path, 'vendor') || str_contains($path, '.git')) continue;
    
    $content = @file_get_contents($path);
    if ($content === false) continue;
    
    $fileMatches = [];
    foreach ($searchTerms as $term) {
        if (stripos($content, $term) !== false) {
            $fileMatches[] = $term;
        }
    }
    
    if (!empty($fileMatches)) {
        $relPath = str_replace($rootDir, '', $path);
        echo "FILE: " . $relPath . PHP_EOL;
        echo "  Matched Terms: " . implode(', ', array_unique($fileMatches)) . PHP_EOL;
        
        $lines = explode("\n", $content);
        foreach ($lines as $num => $line) {
            foreach ($searchTerms as $term) {
                if (stripos($line, $term) !== false) {
                    echo sprintf("  Line %4d: %s", ($num + 1), trim($line)) . PHP_EOL;
                    $foundCount++;
                    break;
                }
            }
        }
        echo PHP_EOL;
    }
}

echo "Total Matches Found: $foundCount" . PHP_EOL;
