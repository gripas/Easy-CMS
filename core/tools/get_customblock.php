<?php
session_start();
define('IS_ADMIN', true);

require_once $_SERVER['DOCUMENT_ROOT'] . '/security/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/functions/functions.php';

secureSession();
// Duomenų gavimas iš duomenų bazės
$db = getDBConnection($config);
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_name = getUserNameById($db, $user_id);
} else {
    header("Location: 404.php");
    exit;
}

// Tikriname, ar vartotojas yra adminas ar moderatorius
if (!checkUserRole($user_id, 'admin', $db) && !checkUserRole($user_id, 'moderator', $db)) {
    header("Location: 404.php");
    exit;
}

// Gaukite customblock ID iš užklausos
$customBlockId = isset($_GET['customblock_id']) ? (int)$_GET['customblock_id'] : 0;

// Nustatykite režimą (redagavimas arba kūrimas) pagal customblock ID
$mode = $customBlockId > 0 ? 'edit' : 'create';

// Gaukite customblock duomenis iš duomenų bazės, jei režimas yra "edit"
$customBlock = $mode === 'edit' ? getCustomBlockById($db, $customBlockId) : null;

$categories = getCategories($db);
$menuId = getMenuItems($db);

if ($mode === 'create' || $customBlock) {
?>
<div id="customblock-form-content">
    <form id="customblock-form">
        <input type="hidden" name="mode" value="<?php echo $mode; ?>">
        <?php if ($mode === 'edit'): ?>
            <input type="hidden" name="customblock_id" value="<?php echo $customBlock['id']; ?>">
        <?php endif; ?>
   
        <div class="form-group">
            <label for="customblock_category_id">Kategorija</label>
            <select class="form-control" id="customblock_category_id" name="customblock_category_id" required>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo $mode === 'edit' && $customBlock['category_id'] === $category['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="customblock_menu_id">Meniu</label>
            <select class="form-control" id="customblock_menu_id" name="customblock_menu_id" required>
                <?php foreach ($menuId as $menu) : ?>
                    <option value="<?php echo $menu['id']; ?>" <?php echo $mode === 'edit' && $customBlock['menu_id'] === $menu['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($menu['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="customblock_name">Pavadinimas</label>
            <input type="text" class="form-control" id="customblock_name" name="customblock_name" value="<?php echo $mode === 'edit' ? htmlspecialchars($customBlock['name']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="customblock_html_code">Turinys</label>
            <textarea class="form-control" id="customblock_html_code" name="customblock_html_code" rows="10" required><?php echo $mode === 'edit' ? htmlspecialchars($customBlock['html_code']) : ''; ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary"><?php echo $mode === 'edit' ? 'Atnaujinti bloką' : 'Pridėti bloką'; ?></button>
        <button type="button" class="btn btn-secondary" id="cancel-customblock"><?php echo $mode === 'edit' ? 'Atšaukti' : 'Grįžti'; ?></button>
    </form>
</div>
<?php
} else {
    echo 'Blokas nerastas.';
}
?>
