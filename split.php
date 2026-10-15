<?php
$file = __DIR__ . '/resources/views/welcome.blade.php';
$lines = file($file);

$header = array_slice($lines, 0, 942); // lines 1 to 942
$content = array_slice($lines, 942, 560); // 1502 - 942 = 560 lines. Wait. 
// let's exact:
// 0 to 941 (942 lines) -> header
// 942 to 1501 (560 lines) -> content
// 1502 to end -> footer

$footer = array_slice($lines, 1502);

$layout = implode("", $header) . "\n    @yield('content')\n" . implode("", $footer);
file_put_contents(__DIR__ . '/resources/views/layouts/frontend.blade.php', $layout);

$newContent = "@extends('layouts.frontend')\n@section('content')\n" . implode("", $content) . "\n@endsection\n";
file_put_contents(__DIR__ . '/resources/views/welcome.blade.php', $newContent);

echo "Done.";
