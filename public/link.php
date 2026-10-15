<?php

// Target folder is storage/app/public in the project root
$targetFolder = __DIR__.'/../storage/app/public';

// Link folder is public/storage
$linkFolder = __DIR__.'/storage';

if (file_exists($linkFolder)) {
    echo "Symlink already exists at $linkFolder<br>";
    // Optional: remove existing to recreate
    // unlink($linkFolder);
} else {
    try {
        symlink($targetFolder, $linkFolder);
        echo 'Symlink process successfully completed.<br>';
        echo 'Target: ' . $targetFolder . '<br>';
        echo 'Link: ' . $linkFolder . '<br>';
    } catch (\Exception $e) {
        echo 'Error creating symlink: ' . $e->getMessage();
    }
}
?>
