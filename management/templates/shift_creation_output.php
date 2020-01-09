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
                                <option value="<?php echo $val2['shift_id']; ?>" 
                                <?php if($shift_id == $val2['shift_id']){
                                  echo "selected";} ?>>
                                  <?php echo $val2['shift_name'];?>
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
                      <div class="card-body">
                        <div class="card-header d-flex align-items-center">
                          <h3 class="h4">All form elements</h3>
                        </div>
                        <div class="table-responsive">
                          <?php if($flag === 1): ?>
                            <table class = "table">
                              <thead>
                                <tr>
                                  <th></th>
                                  <?php foreach ($users as $user): ?>
                                    <th><?php echo $user['nickname']; ?></th>
                                  <?php endforeach; ?>
                                </tr>
                              </thead>
                              <tbody>
                                <?php foreach ($days as $day): ?>
                                  <tr>
                                    <th>
                                      <?php echo $day; ?>
                                    </th>
                                    <?php foreach ($users as $user): ?>
                                    
                                      <?php 
                                        $shift_for_eachday = get_shift_for_eachday($conn,$shift_id,$user['user_id'],$day);
                                        if(isset($shift_for_eachday['type_name'])){
                                          ?>
                                          <th style = "background-color:<?php echo $shift_for_eachday['type_color']; ?>">
                                            <?php echo $shift_for_eachday['type_name']; ?>
                                          </th>
                                          <?php
                                        }else{
                                          ?>
                                          <th>
                                            <?php echo "休み"; ?>
                                          </th>
                                          <?php
                                        }
                                      ?>
                                    
                                    <?php endforeach; ?>
                                  </tr>
                                <?php endforeach; ?>
                              </tbody>
                            </table>
                          <table class="table">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Username</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                              </tr>
                              <tr>
                                <th scope="row">2</th>
                                <td>Jacob</td>
                                <td>Thornton</td>
                                <td>@fat</td>
                              </tr>
                              <tr>
                                <th scope="row">3</th>
                                <td>Larry</td>
                                <td>the Bird</td>
                                <td>@twitter</td>
                              </tr>
                            </tbody>
                          </table>
                          <?php elseif($flag === 0): ?>                           
                            <div class="col-sm-4 offset-sm-3">
                              <form action="./shift_creation.php" method="post">     
                                <h3>シフトまだ組んでいない</h3>
                                <button class="btn btn-primary" type="submit" form="shift_form" name = "button" value="今から組みます">今から組みます</button></p>
                                <!-- <input type="submit" class="btn btn-primary" name = "button" value="今から組みます"> -->
                              </form>
                            </div>                            
                          <?php endif; ?>
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