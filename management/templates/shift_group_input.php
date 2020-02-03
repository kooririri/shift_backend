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
            <div class="avatar"><img src="img/piao.jpg" alt="..." class="img-fluid rounded-circle"></div>
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
            <li><a href="shift_type_display.php"> <i class="icon-page"></i>シフトタイプ確認 </a></li>
            <li><a href="staff.php"> <i class="icon-user"></i>人員管理 </a></li>
            <li><a href="#exampledropdownDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-user"></i>グループ </a>
              <ul id="exampledropdownDropdown" class="collapse list-unstyled ">
                <li class="active"><a href="shift_group.php">グループ作成</a></li>
                <li><a href="group_member.php">グループメンバ追加</a></li>
              </ul>
            </li>
          </ul>
        </nav>
        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">グループ作成</h2>
            </div>
          </header>
          <!-- Breadcrumb-->
          <div class="breadcrumb-holder container-fluid">
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">グループ作成            </li>
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
                      <form method="post" action="./shift_group.php">
                        <div class="form-group">
                          <label class="form-control-label">グループ名</label>
                          <input type="text" placeholder="グループ名" class="form-control" id="group_name" name="group_name">
                        </div>
                        <div class="form-group">       
                          <input type="submit" value="送信" class="btn btn-primary" id="send" name="send">
                        </div>
                      </form>
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