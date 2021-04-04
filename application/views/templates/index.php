<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title_dr; ?></h1>

    <div class="row">

        <!-- Bus -->
        <?php
        $queryBus = "SELECT COUNT(*) FROM `t_bus` WHERE `t_bus`.`status` != 3";

        $bus = $this->db->query($queryBus)->result_array();
        ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">BUS</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                                                                foreach ($bus as $b) :
                                                                                    echo $b['COUNT(*)'];
                                                                                endforeach
                                                                                ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bus-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trayek -->
        <?php
        $queryTrayek = "SELECT COUNT(*) FROM `t_trayek`";

        $trayek = $this->db->query($queryTrayek)->result_array();
        ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">TRAYEK</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                                                                foreach ($trayek as $t) :
                                                                                    echo $t['COUNT(*)'];
                                                                                endforeach
                                                                                ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-map-marked-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kru Jalan -->
        <?php
        $queryKru = "SELECT COUNT(*) FROM `t_user` WHERE `t_user`.`role_id` = 31 OR `t_user`.`role_id` = 32 OR `t_user`.`role_id` = 33";

        $kru = $this->db->query($queryKru)->result_array();
        ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">KRU JALAN</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                                                                foreach ($kru as $k) :
                                                                                    echo $k['COUNT(*)'];
                                                                                endforeach
                                                                                ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Petugas Kontrol -->
        <?php
        $queryKontrol = "SELECT COUNT(*) FROM `t_user` WHERE `t_user`.`role_id` = 4";

        $kontrol = $this->db->query($queryKontrol)->result_array();
        ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">PETUGAS KONTROL</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php
                                                                                foreach ($kontrol as $k) :
                                                                                    echo $k['COUNT(*)'];
                                                                                endforeach
                                                                                ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-search fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content Row -->




</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->