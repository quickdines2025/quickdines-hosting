<?php
$files = [
    'c:/xampp/htdocs/quick/quickdines/resources/views/restaurants/show.blade.php',
    'c:/xampp/htdocs/quick/quickdines/resources/views/checkout/index.blade.php'
];

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    // Find <style> and </style>
    $styleStart = strpos($content, '<style>');
    $styleEnd = strpos($content, '</style>');
    
    if ($styleStart !== false && $styleEnd !== false) {
        $styleContent = substr($content, $styleStart, $styleEnd - $styleStart);
        
        // Find @media (max-width: 768px) { ... }
        // We need to carefully extract the block. Assuming it's the only media query or the first one.
        $mediaStart = strpos($styleContent, '@media (max-width: 768px)');
        
        if ($mediaStart !== false) {
            // Find the matching closing brace
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
                
                // Remove the block from its original position
                $newStyleContent = str_replace($mediaBlock, '', $styleContent);
                
                // Append it to the end
                $newStyleContent .= "\n\n    /* Mobile Styles Moved to Bottom */\n    " . $mediaBlock . "\n";
                
                // Replace in the file
                $newContent = str_replace($styleContent, $newStyleContent, $content);
                file_put_contents($file, $newContent);
                echo "Fixed " . basename($file) . "\n";
            }
        }
    }
}
