<!DOCTYPE html>
<html>
  <?php require "./templates/head.php" ?>
  <body>
    <div class="page">
      <!-- Main Navbar-->
      <?php require "./templates/header.php" ?>
      <div class="page-content d-flex align-items-stretch"> 
        <!-- Side Navbar -->
        <?php require "./templates/nav.php" ?>
        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">シフトタイプ作成</h2>
            </div>
          </header>
          <!-- Breadcrumb-->
          <div class="breadcrumb-holder container-fluid">
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">シフトタイプ作成            </li>
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
                      <form method="post" action="./shift_type.php">
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
                        <div class="form-group">
                            <label class="form-control-label">タイプ名</label>
                            <input type="text" placeholder="タイプ名" class="form-control" id="type_name" name="type_name">
                        </div>
                        <div class="form-group">       
                            <label class="form-control-label">開始時間</label>
                            <input type="time" placeholder="開始時間" class="form-control" id="begin_time" name="begin_time">
                        </div>
                        <div class="form-group">       
                            <label class="form-control-label">終了時間</label>
                            <input type="time" placeholder="終了時間" class="form-control" id="end_time" name="end_time">
                        </div>
                        <div class="form-group">       
                            <label class="form-control-label">タイプ色</label>
                            <input type="color" name="type_color" id = "type_color" class="col-sm-3 form-control"/>    
                        </div>
                        <div class="form-group">       
                            <label class="form-control-label">コメント</label>
                            <textarea  placeholder="コメント" rows="10" cols="60" class="form-control" id="comment" name="comment"></textarea>
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