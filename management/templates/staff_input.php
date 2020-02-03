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
            <li class="active"><a href="staff.php"> <i class="icon-user"></i>人員管理 </a></li>
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
              <h2 class="no-margin-bottom">人員管理</h2>
            </div>
          </header>
          <!-- Breadcrumb-->
          <div class="breadcrumb-holder container-fluid">
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">人員管理</li>
            </ul>
          </div>
          <!-- Forms Section-->
          <section class="forms"> 
            <div class="container-fluid">
              <div class="row">
                <!-- Basic Form-->
                <div class="col-lg-12">
                  <div class="card">
                  <div class="card-header d-flex align-items-center">
                        <h3 class="h4">人員管理</h3> 
                      </div>
                    <div class="card-body">                    
                      <div class="form-group">
                        <form action="./staff.php" method="post" id= "shift_form">   
                          <label class="form-control-label">グループ</label>
                          <div class="form-group">
                            <div class="input-group">
                                <select class="form-control" name="group_id" id = "group_id">
                                <?php foreach ($group_data as $val): ?>
                                    <option value="<?php echo $val['group_id']; ?>">
                                        <?php echo $val['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                                </select>
                                <div class="input-group-append">
                                    <input type="submit" form="shift_form" class="btn btn-primary" name="send" value="確定">
                                </div>
                            </div>
                          </div>
                        </form>                       
                      </div>
                      <div class="card-body">
                      <div class="table-responsive">
                        <form method="post" action="./staff.php">
                        <table class = "table table-striped table-hover">
                            <thead>
                            <tr>
                                <td>スタッフ名</td>
                                <td>ランキング</td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($users as $user): ?>
                                <tr>
                                    <td><?php echo $user['nickname']; ?></td>
                                    <td>
                                        <select class="form-control mb-3" name="<?php echo $user['user_id']; ?>">
                                            <?php for($i = 1 ;$i <=5;$i++): ?>
                                                <option value="<?php echo $i; ?>" <?php if($user['rank']==$i){echo 'selected';} ?>><?php echo $i; ?></option>
                                            <?php endfor; ?>
                                        </select>   
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="form-group">       
                             <input type="submit" value="送信" class="btn btn-primary" id="send" name="send">
                        </div>
                        </form>
                      </div>
                      </div>
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