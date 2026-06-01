<?php
$file = 'C:\Users\jawsi\.gemini\antigravity-ide\brain\c5bb9a1c-14b9-4191-96ed-4b3d01be223d\dashboard_ui_mockup_1780244591097.png';
if (file_exists($file)) {
    header('Content-Type: image/png');
    readfile($file);
} else {
    header("HTTP/1.0 404 Not Found");
}
