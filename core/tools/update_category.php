<?php
session_start();
define('IS_ADMIN', true);

define('ROOT_PATH', realpath(dirname(__FILE__) . '/../../') . '/');

require_once ROOT_PATH . 'security/config.php';
require_once ROOT_PATH . 'core/functions/functions.php';
secureSession();
// Duomenų gavimas iš duomenų bazės
$db = getDBConnection($config);

if (defined('IS_ADMIN') && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_category' && isset($_POST['category_id']) && isset($_POST['category_name'])) {
    $categoryId = $_POST['category_id'];
    $categoryName = $_POST['category_name'];
    $result = updateCategory($db, $categoryId, $categoryName);

    $response = array();
    if ($result) {
        $_SESSION['success_message'] = 'Kategorija sėkmingai atnaujinta.';
    } else {
        $_SESSION['error_message'] = 'Klaida atnaujinant kategoriją. Bandykite dar kartą.';
    }
    echo json_encode($response);
    exit;
}
?>