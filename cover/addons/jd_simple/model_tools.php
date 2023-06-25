<?php 

$id = intval($_GET['id']);

$placesId = getplaces($db, $prefix);
$menuId = getMenuItems($db, $prefix);


?>

<div class="col-md-12">
    <div class="row d-flex">
    <form id="update-addon-form" method="POST" action="../../cover/addons/jd_simple/action/add_addon.php" enctype="multipart/form-data" class="col-md-9">
    <input type="hidden" class="form-control" name="id" value="<?php echo $id; ?>">
            <div class="row">
                <div class="col-md-8">
   
                <div class="form-group row">
                    <div class="col-md-5 mb-3">
                            <label for="addon_place_id"><?php echo t('Place');?></label>
                            <select class="form-control" id="addon_place_id" name="addon_place_id" required>
                                <?php foreach ($placesId as $place) : ?>
                                    <option value="<?php echo $place['id']; ?>">
                                        <?php echo htmlspecialchars($place['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col-md-6 mb-3">
                            <label for="addon_menu_id"><?php echo t('Menu');?></label>
                            <select class="form-control" id="addon_menu_id" name="addon_menu_id" required>
                                <?php foreach ($menuId as $menu) : ?>
                                    <option value="<?php echo $menu['id']; ?>">
                                        <?php echo htmlspecialchars($menu['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="simpleFormControlInput" class="form-label"><?php echo t('Title');?></label>
                        <input type="text" class="form-control" name="title" id="simpleFormControlInput" placeholder="Title" required>
                    </div>
                    <div class="mb-3">
                        <label for="simpleFormControlTextarea" class="form-label"><?php echo t('Description');?></label>
                        <textarea class="form-control" name="description" id="simpleFormControlTextarea" rows="3" required></textarea>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary"><?php echo t('Submit');?></button>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="file_id" class="form-label"><?php echo t('Image');?></label>
                        <input class="form-control form-control-sm" name="file_id" id="file_id" type="file" onchange="previewFile(this)">
                    </div>
                  
					<div id="image_container">
                        <img id="preview_image"  style="max-width: 100%;">
                    </div>

                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><?php echo t('files Library');?></button>
                        <div class="offcanvas offcanvas-end" style="background-color: #494f55fa;" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="offcanvasRightLabel">Offcanvas right</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body" id="offcanvasBody" style="background-color: #494f55fa;">
                            </div>
                            <div class="offcanvas-footer">
                                <button class="btn file-left prev"><i class="fas fa-angle-left"></i></button>
                                <button class="btn file-right next"><i class="fas fa-angle-right"></i></button>
                            </div>
                        </div>
                </div>
            </div>
        </form>
        <div class="col-md-12">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th scope="col" width="10%"><?php echo t("Image");?></th>
                        <th scope="col" width="20%"><?php echo t("Title");?></th>
                        <th scope="col" width="40%"><?php echo t("Description");?></th>
                        <th scope="col" width="15%"><?php echo t("Menu");?></th>
                        <th scope="col" width="10%"><?php echo t("Place");?></th>
                        <th scope="col" width="8%"><?php echo t("Actions");?></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $get_params = $_GET;
                unset($get_params['page']);
                
                $get_param_str = '';
                if (!empty($get_params)) {
                    $get_param_str = http_build_query($get_params) . '&';
                }
                    $addons_per_page = 5;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($page > 1) ? ($page - 1) * $addons_per_page : 0;

                    $stmt = $db->prepare("SELECT COUNT(*) FROM " . $prefix['table_prefix'] . "_jd_simple");
                    $stmt->execute();
                    $total_addons = $stmt->fetchColumn();
                    $stmt = $db->prepare("SELECT addons.id, addons.title, addons.description, addons.img_url, menu.name as menu_name, places.name as place_name FROM " . $prefix['table_prefix'] . "_jd_simple as addons LEFT JOIN " . $prefix['table_prefix'] . "_flussi_menu as menu ON addons.menu_id = menu.id LEFT JOIN " . $prefix['table_prefix'] . "_flussi_places as places ON addons.place_id = places.id ORDER BY addons.id DESC LIMIT :limit OFFSET :offset");

                    //$stmt = $db->prepare("SELECT id, title, description, img_url FROM " . $prefix['table_prefix'] . "_jd_simple ORDER BY id DESC LIMIT :limit OFFSET :offset");
                    $stmt->bindParam(':limit', $addons_per_page, PDO::PARAM_INT);
                    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($results as $result) {

                        echo '<tr>';
                        if($result['img_url'] != '') {
                            echo '<td><img src="' . $result['img_url'] . '" alt="Addon Image" style="width: 35px; height: 35px;"></td>';
                        } else {
                            echo '<td>No Image</td>';
                        }
                        echo '<td>' . $result['title'] . '</td>';
                        // Limit description to 30 characters
                        $short_description = mb_substr($result['description'], 0, 60);
                        $short_menu = $result['menu_name'];
                        $short_place = $result['place_name'];
                        echo '<td>' . $short_description . '...</td>';
                        echo '<td>' . $short_menu . '</td>';
                        echo '<td>' . $short_place . '</td>';
                        echo '<td>';
                        echo '<a href="../../cover/addons/jd_simple/action/edit_addon_post.php?name=jd_simple&id=' . $_GET['id'] . '&addon_post_id=' . $result['id'] . '"><i class="fa fa-trash"></i></a>';
                        echo '<a href="../../cover/addons/jd_simple/action/delete_addon_post.php?name=jd_simple&id=' . $_GET['id'] . '&addon_post_id=' . $result['id'] . '"><i class="fa fa-trash"></i></a>';
                        echo '</td>';
                        echo '</tr>';
                    } ?>
                </tbody>
            </table>
                <?php 
                $total_pages = ceil($total_addons / $addons_per_page);

                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $page) {
                        echo $i . " ";
                    } else {
                        echo "<a class='btn btn-primary' href='?" . $get_param_str . "page=" . $i . "'>" . $i . "</a> ";
                    }
                }
                ?>
        </div>
    </div>
</div>

<script>
    $('#file_id').change(function(event) {
    var file = this.files[0];
    var imgElement = document.getElementById('preview_image');
    var imgContainer = document.getElementById('image_container');

    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            imgElement.src = e.target.result;
            imgContainer.style.display = 'block'; // Show image container
        }
        reader.readAsDataURL(file);
    } else {
        imgContainer.style.display = 'none'; // Hide image container
    }
});

 $(document).on('click', '.brand_icone_id', function() {
    var selectedFileId = $(this).val();
    $('#selected_file_id').val(selectedFileId);
});

$(document).ready(function() {
    $('.overlay').hover(
    function() {
      $(this).append('<span class="select-overlay">Select</span>');
    },
    function() {
      $(this).find('.select-overlay').remove();
    }
  );
});

$(document).on('click', 'input[name="brand_icone_id"]', function() {
    var selectedImageUrl = $(this).siblings('img').attr('src');
    $('#preview_image').attr('src', selectedImageUrl);
});

var index = 0;
var loadImages = function() {
    $.ajax({
        url: 'get_images.php',
        method: 'GET',
        data: {
            index: index
        },
        success: function(data) {
            $('#offcanvasBody').html(data);
        }
    });
};

</script>