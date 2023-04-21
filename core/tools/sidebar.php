<div class="card left-nav-items flex-shrink-0 admin-sidebar">
  <button class="btn btn-primary" id="toggleSidebarBtn"  style="float: right;"><i class="fas fa-bars"></i></button>
  
  <ul class="list-group list-group-flush">
  <li class="list-group-item"><a class="nav-link" href="<?php echo getFullUrl('/admin.php'); ?>"><i class="fas fa-tachometer-alt"></i> <span class="nav-text"><?php echo t("Dashboard");?></span></a></li>
    <li class="list-group-item"><a class="nav-link" href="<?php echo getFullUrl('/core/tools/users.php'); ?>"><i class="fas fa-users"></i> <span class="nav-text"><?php echo t("Users");?></span></a></li>
    <li class="list-group-item"><a class="nav-link" href="<?php echo getFullUrl('/core/tools/categories.php'); ?>"><i class="fas fa-tags"></i> <span class="nav-text"><?php echo t("Categories");?></span></a></li>
    <li class="list-group-item"><a class="nav-link" href="<?php echo getFullUrl('/core/tools/posts.php'); ?>"><i class="fas fa-newspaper"></i> <span class="nav-text"><?php echo t("Posts");?></span></a></li>
    <li class="list-group-item"><a class="nav-link" href="<?php echo getFullUrl('/core/tools/menu.php'); ?>"><i class="fas fa-bars"></i> <span class="nav-text"><?php echo t("Menu");?></span></a></li>
    <li class="list-group-item"><a class="nav-link" href="<?php echo getFullUrl('/core/tools/customBlock.php'); ?>"><i class="fas fa-shapes"></i> <span class="nav-text"><?php echo t("Block");?></span></a></li>
    <li class="list-group-item"><a class="nav-link" href="<?php echo getFullUrl('/core/tools/contact_form.php'); ?>"><i class="fas fa-message"></i> <span class="nav-text"><?php echo t("Contact Form");?></span></a></li>
    <li class="list-group-item"><a class="nav-link" href="<?php echo getFullUrl('/core/tools/files.php'); ?>"><i class="fas fa-folder"></i> <span class="nav-text"><?php echo t("Files");?></span></a></li>
     <li class="list-group-item"><a class="nav-link" href="<?php echo getFullUrl('/core/tools/language.php'); ?>"><i class="fas fa-language"></i> <span class="nav-text"><?php echo t("Language");?></span></a></li>
    <li class="list-group-item"><a class="nav-link" href="<?php echo getFullUrl('/core/tools/settings.php'); ?>"><i class="fas fa-cog"></i> <span class="nav-text"><?php echo t("Settings");?></span></a></li>
  </ul>
</div>