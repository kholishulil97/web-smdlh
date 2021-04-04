<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href=<?= base_url('controlleruser/dashboard'); ?>>
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-bus"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Akas Mila</div>
    </a>

    <!-- Divider PENGATUR DINAS-->
    <hr class="sidebar-divider my-0">


    <!-- Divider PENGATUR DINAS 1-->
    <hr class="sidebar-divider">

    <!-- QUERY MENU -->
    <?php

    //mengambil data user dari session 
    $role_id = $this->session->userdata('role_id');

    //query untuk mengambil data menu
    $queryMenu = "SELECT `user_menu`.`id` , `menu`, `controller`
                    FROM `user_menu` JOIN `user_access_mn`
                    ON `user_menu`.`id` = `user_access_mn`.`menu_id`
                    WHERE `user_access_mn`.`role_id` = $role_id
                    ORDER BY `user_menu`.`menu_order` ASC
                ";

    $menu = $this->db->query($queryMenu)->result_array();

    ?>

    <!-- LOOPING MENU -->

    <!-- Heading -->
    <?php foreach ($menu as $m) : ?>
        <div class="sidebar-heading">
            <?= $m['menu']; ?>
        </div>

        <!-- MENYIAPKAN SUB-MENU BERDASARKAN MENU -->
        <?php
        $menuId = $m['id'];
        $querySubMenu = "SELECT *
        FROM `user_sub_menu` JOIN `user_menu`
        ON `user_sub_menu`.`menu_id` = `user_menu`.`id`
        WHERE `user_sub_menu`.`menu_id` = $menuId
              ";
        $subMenu = $this->db->query($querySubMenu)->result_array();
        ?>

        <!-- LOOPING SUB-MENU -->
        <?php foreach ($subMenu as $sm) : ?>
            <!-- Nav Item - Pages Collapse Menu PENGATUR DINAS -->
            <?php if ($title == $sm['title']) : ?>
                <li class="nav-item active">
                    <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapse<?= $sm['class'] ?>" aria-expanded="true" aria-controls="collapse<?= $sm['class'] ?>">
                        <i class="<?= $sm['icon'] ?>"></i>
                        <span><?= $sm['title'] ?></span>
                    </a>
                    <div id="collapse<?= $sm['class'] ?>" class="collapse show" aria-labelledby="heading<?= $sm['class'] ?>" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                        <?php else : ?>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse<?= $sm['class'] ?>" aria-expanded="true" aria-controls="collapse<?= $sm['class'] ?>">
                        <i class="<?= $sm['icon'] ?>"></i>
                        <span><?= $sm['title'] ?></span>
                    </a>
                    <div id="collapse<?= $sm['class'] ?>" class="collapse" aria-labelledby="heading<?= $sm['class'] ?>" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                        <?php endif; ?>

                        <!-- MENYIAPKAN SUB-MENU DROPDOWN BERDASARKAN SUB-MENU -->
                        <?php
                        $submenuId = $sm['id_sm'];
                        $querySubMenuDropdown = "SELECT *
                                        FROM `user_sub_menu_dropdown` JOIN `user_sub_menu` 
                                        ON `user_sub_menu_dropdown`.`sub_menu_id` = `user_sub_menu`.`id_sm` 
                                        WHERE `user_sub_menu_dropdown`.`sub_menu_id` = $submenuId 
                                  ";
                        $subMenuDropdown = $this->db->query($querySubMenuDropdown)->result_array();
                        ?>

                        <!-- LOOPING SUB-MENU DRPDOWN -->
                        <?php foreach ($subMenuDropdown as $smd) : ?>
                            <?php if (strpos($_SERVER['REQUEST_URI'], $m['controller']) == true) : ?>
                                <?php if ($title_dr == $smd['title_dr']) : ?>
                                    <a class="collapse-item active" href="<?= $smd['method'] ?>">
                                    <?php else : ?>
                                        <a class="collapse-item" href="<?= $smd['method'] ?>">
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <a class="collapse-item" href="<?= base_url($m['controller'] . $smd['method'])  ?>">
                                        <?php endif; ?>
                                        <i class="<?= $smd['icon_dr'] ?>"></i>
                                        <?= $smd['title_dr'] ?></a>
                                    <?php endforeach; ?>
                        </div>
                    </div>
                </li>
            <?php endforeach;   ?>
            <hr class="sidebar-divider">
        <?php endforeach;  ?>


        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

</ul>
<!-- End of Sidebar -->