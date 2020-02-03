<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="./vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="./vendor/font-awesome/css/font-awesome.min.css">
    <!-- Fontastic Custom icon font-->
    <link rel="stylesheet" href="./css/fontastic.css">
    <!-- Google fonts - Poppins -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,700">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="./css/style.default.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="./css/custom.css">
    <!-- Favicon-->
    <link rel="shortcut icon" href="./img/favicon.ico">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
</head>   
  <body>
    <div class="page">
      <div class="page-content d-flex align-items-stretch"> 
        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">WELCOME</h2>
            </div>
          </header>
              <div class="row">
                <div class="col-lg-12">
                  <div class="card">
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
                        <form action="./shift_release.php" method="post" id= "shift_form">      
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
          <?php if($is_finished == 2): ?>
          <div class="table-responsive">
                <table class = "table">
                  <thead>
                    <tr>
                      <th>
                      </th>
                      <?php foreach ($days as $day): ?>
                        <th>
                            <?php echo substr($day,8,2)."日"; ?>
                        </th>
                      <?php endforeach; ?>
                    
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($users as $user): ?>
                      <tr>
                      <th><?php echo $user['nickname']; ?></th>
                      <?php foreach ($days as $day): ?>
                        
                          <?php 
                            $shift_for_eachday = get_shift_for_eachday($conn,$shift_id,$user['user_id'],$day);
                            // $abled_datas = find_abled_staff_by_date($conn,$user['user_id'],$day,-1);
                            if(isset($shift_for_eachday['type_name'])): ?> 
                              <th style = "background-color:<?php echo $shift_for_eachday['type_color']; ?>">
                                <?php echo $shift_for_eachday['type_name']; ?>     
                              </th>
                            <?php else: ?>
                              <th>
                                休み
                              </th>
                            <?php endif; ?>
                        
                        <?php endforeach; ?>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>                    
            </div>
            <?php else: ?>
              <div class="card">
                <div class="card-body" style="margin: auto; margin-top:20%; margin-bottom:20%">
                  <h1>まだ発表していない</h1>
                </div>
              </div>
            <?php endif; ?>
          </section>
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