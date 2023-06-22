<?php 
define('ROOT_PATH', realpath(dirname(__FILE__) . '/../../') . '/');

require_once ROOT_PATH . 'core/template/header-admin.php'; ?>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/core/template/admin-menu-horizontal.php';?>
  <button class="btn btn-primary position-fixed start-0 translate-middle-y d-md-none tools-settings" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
      <i class="fas fa-bars"></i>
  </button>
 <?php require_once  $_SERVER['DOCUMENT_ROOT'] . '/core/tools/sidebar.php';?>
<div class="container-fluid mt-4 main-content admin-layout">
    <div class="row">
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 content-up">

                <div class="col-sm-9">
                <div class="message"></div>
                    <?php  if (isset($_SESSION['success_message'])) {
                            echo "<div class='alert alert-success alert-dismissible fade show slow-fade'>
                                    " . htmlspecialchars($_SESSION['success_message']) . "
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>";
                            unset($_SESSION['success_message']);
                        }
                        if (isset($_SESSION['error_message'])) {
                            echo "<div class='alert alert-danger alert-dismissible fade show slow-fade'>
                                    " . htmlspecialchars($_SESSION['error_message']) . "
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>";
                            unset($_SESSION['error_message']);
                        } ?>
                </div>
<?php 
    $systemAddons = getAllSystemAddons();
    $installedAddons = getAllAddons($db, $prefix); 

    if (!isset($_GET['name']) || !isset($_GET['id'])) {
        // Čia galite apdoroti situaciją, kai nuoroda neturi reikiamų parametrų
        echo "Parametrų nėra.";
        return;
    }
    $name = htmlspecialchars($_GET['name']);
    $id = htmlspecialchars($_GET['id']);

    foreach ($systemAddons as $addon) {
        foreach($installedAddons as $installedAddon) {
            if ($installedAddon['name_addon'] !== $addon['name_addon']) {
                continue;
            }
            $isInstalled = true;
            $isActive = isActiveAddon($addon['name_addon'], $db, $prefix);
            $showFront = $installedAddon['show_front'];
            if(!$isInstalled || !$isActive || $showFront != 1) {
                continue;
            }
    
            $addonPath = $_SERVER['DOCUMENT_ROOT'] . "/cover/addons/$addon[name_addon]/model_tools.php";
            if (!file_exists($addonPath)) {
                echo "Addon'o '$addon[name_addon]' model_tools.php failas nerastas.";
                continue;
            }
            include $addonPath;
        }
    }
?>
    </main>
  </div>
</div>

<?php require_once ROOT_PATH . 'core/template/admin-footer.php';?>