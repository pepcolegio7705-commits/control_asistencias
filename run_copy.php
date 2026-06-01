<?php
$source = 'C:\Users\jawsi\.gemini\antigravity-ide\brain\c5bb9a1c-14b9-4191-96ed-4b3d01be223d\dashboard_ui_mockup_1780244591097.png';
$dest = __DIR__ . '/landing/dashboard_mockup.png';

if (file_exists($source)) {
    if (copy($source, $dest)) {
        echo "Done copying image to $dest";
    } else {
        echo "Failed to copy";
    }
} else {
    echo "Source not found: $source";
}
?>
