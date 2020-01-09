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
              <h2 class="no-margin-bottom">シフト確認と調整</h2>
            </div>
          </header>
          <!-- Breadcrumb-->
          <div class="breadcrumb-holder container-fluid">
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active">シフト確認と調整</li>
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
                        <h3 class="h4">シフト確認</h3> 
                      </div>
                    <div class="card-body">                    
                      <div class="form-group">
                        <label class="form-control-label">グループ</label>
                        <select class="form-control mb-3" name="group_id" id = "group_id">
                        <?php foreach ($group_data as $val): ?>
                          <option value="<?php echo $val['group_id']; ?>">
                              <?php echo $val['name']; ?>
                          </option>
                        <?php endforeach; ?>
                        </select>
                        <form action="./shift_creation.php" method="post" id= "shift_form">      
                          <label class="form-control-label">シフト名</label>
                          <div class="form-group">
                            <div class="input-group">
                              <select class="form-control" name="shift_id" id = "shift_id">
                              <?php foreach ($shift_data as $val2): ?>
                                <option value="<?php echo $val2['shift_id']; ?>">
                                    <?php echo $val2['shift_name']; ?>
                                </option>
                              <?php endforeach; ?>
                              </select>
                              <div class="input-group-append">
                                <input type="submit" form="shift_form" class="btn btn-primary" name="button" value="確定">
                              </div>
                            </div>
                          </div>
                        </form>                       
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