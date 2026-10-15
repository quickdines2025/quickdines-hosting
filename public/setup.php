<?php
// Simple script to run storage link
$target = __DIR__ . '/../storage/app/public';
$link = __DIR__ . '/storage';

if (file_exists($link)) {
    echo "<h2 style='color: orange;'>Symlink already exists. Deleting it and recreating...</h2><br>";
    if (is_link($link)) {
        unlink($link);
    } else {
        exec("rm -rf " . escapeshellarg($link));
    }
}

if (symlink($target, $link)) {
    echo "<div style='font-family: sans-serif; padding: 50px; text-align: center; background: #f0fdf4; color: #166534; border-radius: 12px; max-width: 600px; margin: 40px auto; border: 2px solid #22c55e;'>
            <h2>✅ Server Setup Complete!</h2>
            <p>The storage links have been fixed successfully using the setup.php script.</p>
            <p>Your images should now load perfectly. You can close this tab and return to your website.</p>
          </div>";
} else {
    echo "<div style='font-family: sans-serif; padding: 50px; text-align: center; background: #fef2f2; color: #991b1b; border-radius: 12px; max-width: 600px; margin: 40px auto; border: 2px solid #ef4444;'>
            <h2>❌ Error</h2>
            <p>Could not create symlink automatically via PHP.</p>
          </div>";
}
