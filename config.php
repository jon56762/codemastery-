<?php
// Base configuration
define('SITE_NAME', 'CodeMastery');
define('SITE_EMAIL', 'somchiaanong68gmail.com');
define('UPLOAD_PATH', __DIR__ . '/uploads/');
define('DATA_PATH', __DIR__ . '/data/');

// Initialize data directory
if (!is_dir(DATA_PATH)) {
    mkdir(DATA_PATH, 0755, true);
}

// Initialize uploads directory
if (!is_dir(UPLOAD_PATH)) {
    mkdir(UPLOAD_PATH, 0755, true);
}
?>