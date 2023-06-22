<?php
$configurations = require $_SERVER['DOCUMENT_ROOT'] . '/security/config.php';

// Duomenų gavimas iš duomenų bazės
$prefix = $configurations['prefix'];

$databaseScript = "CREATE TABLE IF NOT EXISTS {$prefix['table_prefix']}_jd_simple (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    simple_image VARCHAR(255),
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    author VARCHAR(255) NOT NULL
)";

$databaseDropScript = "DROP TABLE IF EXISTS {$prefix['table_prefix']}_jd_simple";