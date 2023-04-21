<?php



function getFullUrl($relativePath) {
    $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
    return $base_url . $relativePath;
}

function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function getDBConnection($config) {
    try {
        $dsn = 'mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'] . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        return new PDO($dsn, $config['db_user'], $config['db_password'], $options);
    } catch (PDOException $e) {
        die('Nepavyko prisijungti prie duomenų bazės: ' . $e->getMessage());
    }
}

    function findNameById($id, $items) {
        foreach ($items as $item) {
            if ($item['id'] == $id) {
                return $item['name'];
            }
        }
        return null;
    }

    
    function getCurrentPageUrl($db) {
        $current_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $menus = getMenus($db);
    
        // Nustato numatytąjį URL pavadinimą
        $default_url_name = 'index';
    
        foreach ($menus as $menu) {
            $menu_url = "http://" . $_SERVER['HTTP_HOST'] . '/?page=' . $menu['page_url'];
            if ($current_url == $menu_url) {
                return $menu['page_url'];
            }
        }
    
        // Jei nerandamas joks kitas URL pavadinimas, grąžina numatytąjį
        return $default_url_name;
    }
    
    
    function getTemplates($dir) {
        $templateFiles = glob($dir . "/template_*.php");
        $templates = [];
    
        foreach ($templateFiles as $file) {
            $templates[] = basename($file, ".php");
        }
    
        return $templates;
    }

    function getSettings($db) {
        $stmt = $db->prepare("SELECT * FROM settings");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    function updateSettings($db, $site_title, $meta_description, $footer_text) {
        $stmt = $db->prepare("UPDATE settings SET site_title = :site_title, meta_description = :meta_description, footer_text = :footer_text");
        $stmt->bindParam(':site_title', $site_title, PDO::PARAM_STR);
        $stmt->bindParam(':meta_description', $meta_description, PDO::PARAM_STR);
        $stmt->bindParam(':footer_text', $footer_text, PDO::PARAM_STR);
        return $stmt->execute();
    }
    function createBackupFilename($db) {
    $names = [
        ['Jonas', 'Darius', 'Petras', 'Antanas', 'Juozas', 'Mantas'],
        ['Baltrūnas', 'Jakaitis', 'Jankauskas', 'Kazlauskas', 'Mockus', 'Razma']
    ];
    $firstName = $names[0][array_rand($names[0])];
    $lastName = $names[1][array_rand($names[1])];
    $randomCode = rand(1000, 9999);
    $timestamp = date('Y-m-d_H-i-s');
    $filename = "{$firstName}_{$lastName}_{$randomCode}_{$timestamp}.sql";
    return $filename;
}

function createDatabaseBackup($db, $backupFilename) {
    $tables = [];
    $result = $db->query("SHOW TABLES");
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }

    $backupFileContent = '';
    foreach ($tables as $table) {
        $result = $db->query("SELECT * FROM $table");
        $numFields = $result->columnCount();

        $backupFileContent .= "DROP TABLE IF EXISTS $table;";
        $row2 = $db->query("SHOW CREATE TABLE $table")->fetch(PDO::FETCH_NUM);
        $backupFileContent .= "\n\n" . $row2[1] . ";\n\n";

        for ($i = 0; $i < $numFields; $i++) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $backupFileContent .= "INSERT INTO $table VALUES(";
                for ($j = 0; $j < $numFields; $j++) {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = preg_replace("/\n/", "\\n", $row[$j]);
                    if (isset($row[$j])) {
                        $backupFileContent .= '"' . $row[$j] . '"';
                        } else {
                        $backupFileContent .= '""';
                        }
                        if ($j < ($numFields - 1)) {
                        $backupFileContent .= ',';
                        }
                        }
                        $backupFileContent .= ");\n";
                        }
                        }
                        $backupFileContent .= "\n\n\n";
                        }
                        $backupFolder = 'backups/';
                        if (!is_dir($backupFolder)) {
                            mkdir($backupFolder, 0777, true);
                        }
                        
                        $backupFilepath = $backupFolder . $backupFilename;
                        if (file_put_contents($backupFilepath, $backupFileContent)) {
                            return true;
                        } else {
                            return false;
                        }
}                        
    
    function getBackupFilesList($backupDir) {
        $files = array_diff(scandir($backupDir), array('..', '.'));
        $backupFiles = [];
        foreach ($files as $file) {
            if (is_file($backupDir . $file)) {
                $backupFiles[] = $file;
            }
        }
        return $backupFiles;
    }
        
    function getContactFormSettings($db) {
        $stmt = $db->prepare("SELECT * FROM `contact_form_settings`");
        $stmt->execute();
        $settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $settings;
    }

    function getContactFormSetting($db, $key) {
        $stmt = $db->prepare("SELECT `setting_value` FROM `contact_form_settings` WHERE `setting_key` = :key");
        $stmt->bindParam(':key', $key);
        $stmt->execute();
        $setting = $stmt->fetch(PDO::FETCH_ASSOC);
        return $setting['setting_value'];
    }

    require_once 'f_users.php';
    require_once 'f_posts.php';
    require_once 'f_menu.php';
    require_once 'f_customblock.php';
    require_once 'f_files.php';
    require_once 'f_categories.php';
    require_once 'f_translations.php';
    
   