<!DOCTYPE html>
<html>
  <?php require "./templates/head.php" ?>
  <body>
    <div class="page">
      <!-- Main Navbar-->
      <?php require "./templates/header.php" ?>
      <div class="page-content d-flex align-items-stretch"> 
        <!-- Side Navbar -->
        <nav class="side-navbar">
        <!-- Sidebar Header-->
          <div class="sidebar-header d-flex align-items-center">
            <div class="avatar"><img src="img/avatar-1.jpg" alt="..." class="img-fluid rounded-circle"></div>
            <div class="title">
              <h1 class="h4">管理者様</h1>
              <p>おはよう</p>
            </div>
          </div>
          <!-- Sidebar Navidation Menus-->
          <span class="heading">Main</span>
          <ul class="list-unstyled" id="test">
            <li><a href="index.php"> <i class="icon-home"></i>Home </a></li>
            <li><a href="shift_creation.php"> <i class="icon-grid"></i>シフト確認と調整 </a></li>
            <li><a href="shift_detail.php"> <i class="fa fa-bar-chart"></i>毎日必要人数登録 </a></li>
            <li><a href="shift_element.php"> <i class="icon-interface-windows"></i>シフト作成 </a></li>
            <li><a href="shift_type.php"> <i class="icon-padnote"></i>シフトタイプ作成 </a></li>
            <li class="active"><a href="shift_type_display.php"> <i class="icon-page"></i>シフトタイプ確認 </a></li>
            <li><a href="staff.php"> <i class="icon-user"></i>人員管理 </a></li>
            <li><a href="#exampledropdownDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-user"></i>グループ </a>
              <ul id="exampledropdownDropdown" class="collapse list-unstyled ">
                <li><a href="shift_group.php">グループ作成</a></li>
                <li><a href="group_member.php">グループメンバ追加</a></li>
              </ul>
            </li>
          </ul>
        </nav>
        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">シフトタイプ確認</h2>
            </div>
          </header>
          <!-- Breadcrumb-->
          <div class="breadcrumb-holder container-fluid">
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">シフトタイプ確認            </li>
            </ul>
          </div>
          <!-- Forms Section-->
          <section class="forms"> 
            <div class="container-fluid">
              <div class="row">
                <!-- Basic Form-->
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-control-label">シフト名</label>
                            <select class="form-control mb-3" name="group_id" id = "group_id">
                                <?php foreach ($group_data as $val): ?>
                                <option value="<?php echo $val['group_id']; ?>">
                                    <?php echo $val['name']; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php 
                        $i = 0;
                        foreach ($shift_types as $shift_type): 
                        ?>
                        <?php if($i % 2 == 0): ?>
                        <div class = "row">
                        <?php endif; ?>
                        <div class="col-lg-6">
                            <div class="card">
                              <div class="card-close">
                                <div class="dropdown">
                                <form method="post" action="./shift_type_display.php" id="close_form">
                                  <input type="hidden" name="type_id" value="<?php echo $shift_type['type_id'] ?>" >
                                  <input type="submit" name="button" value="X" id="closeCard1"  aria-haspopup="true" aria-expanded="false" class="dropdown-toggle">
                                </form>
                                </div>
                              </div>
                                <div class="card-header d-flex align-items-center">
                                <h3 class="h4"><?php echo $shift_type['type_name']; ?></h3>
                                </div>
                                <div class="card-body"> 
                                <form class="form-horizontal" action="./shift_type_display.php" method="post" id = "input_form">
                                    <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">タイプ名</label>
                                    <div class="col-sm-9">
                                        <input type="hidden" name="type_id" value="<?php echo $shift_type['type_id'] ?>" >
                                        <input type="hidden" name="version" value="<?php echo $shift_type['version'] ?>" >
                                        <input id="type_name" type="text" name="type_name" value="<?php echo $shift_type['type_name']; ?>"  class="form-control form-control-success">
                                    </div>
                                    </div>
                                    <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">開始時間</label>
                                    <div class="col-sm-9">
                                        <input id="begin_time" type="time" name="begin_time" value="<?php echo substr($shift_type['begin_time'],0,5); ?>"  class="form-control form-control-success">
                                    </div>
                                    </div>
                                    <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">終了時間</label>
                                    <div class="col-sm-9">
                                        <input id="end_time" type="time" name="end_time" value="<?php echo substr($shift_type['end_time'],0,5); ?>" class="form-control form-control-success">
                                    </div>
                                    </div>
                                    <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">タイプ色</label>
                                    <div class="col-sm-6">
                                    <input type="color" name="type_color" id = "type_color" value="<?php echo $shift_type['type_color']; ?>" class="form-control form-control-success"/>
                                    </div>
                                    </div>
                                    <div class="form-group row">
                                    <label class="col-sm-3 form-control-label">コメント</label>
                                    <div class="col-sm-9">
                                        <input id="comment" type="text" name="comment" value="<?php echo $shift_type['comment']; ?>" class="form-control form-control-success">
                                    </div>
                                    </div>
                                    <div class="form-group row">       
                                    <div class="col-sm-9 offset-sm-3">
                                        <input type="submit" name="button" value="更新" class="btn btn-primary">
                                    </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                        <?php if($i % 2 == 1): ?>
                        </div>              
                        <?php 
                            endif;
                            $i++;
                            endforeach;
                        ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/popper.js/umd/popper.min.js"> </script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>
    <!-- Main File-->
    <script src="js/front.js"></script>
  </body>
</html>