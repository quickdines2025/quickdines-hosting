<?php
$files = [
    'c:/xampp/htdocs/quick/quickdines/resources/views/frontend/restaurant.blade.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    $styleStart = strpos($content, '<style>');
    $styleEnd = strpos($content, '</style>');
    
    if ($styleStart !== false && $styleEnd !== false) {
        $styleContent = substr($content, $styleStart, $styleEnd - $styleStart);
        
        $mediaStart = strpos($styleContent, '@media (max-width: 768px)');
        
        if ($mediaStart !== false) {
            $braceCount = 0;
            $mediaEnd = -1;
            for ($i = $mediaStart + 25; $i < strlen($styleContent); $i++) {
                if ($styleContent[$i] === '{') $braceCount++;
                if ($styleContent[$i] === '}') {
                    $braceCount--;
                    if ($braceCount === 0) {
                        $mediaEnd = $i;
                        break;
                    }
                }
            }
            
            if ($mediaEnd !== -1) {
                $mediaBlock = substr($styleContent, $mediaStart, $mediaEnd - $mediaStart + 1);
                $newStyleContent = str_replace($mediaBlock, '', $styleContent);
                $newStyleContent .= "\n\n    /* Mobile Styles Moved to Bottom */\n    " . $mediaBlock . "\n";
                $newContent = str_replace($styleContent, $newStyleContent, $content);
                file_put_contents($file, $newContent);
                echo "Fixed " . basename($file) . "\n";
            }
        }
    }
}
