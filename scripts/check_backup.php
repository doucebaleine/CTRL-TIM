<?php
// Simple WP bootstrap to check if the backup table exists.
// Run from theme folder: php scripts\check_backup.php

// Adjust path to WordPress load
require_once __DIR__ . '/../../../../wp-load.php';

global $wpdb;
$table = $wpdb->prefix . 'ctrltim_projets_annee_backup';
$res = $wpdb->get_results($wpdb->prepare("SHOW TABLES LIKE %s", $table));
if (!empty($res)) {
    echo "FOUND\n";
} else {
    echo "NOT_FOUND\n";
}
