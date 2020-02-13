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
            <li class="active"><a href="shift_creation.php"> <i class="icon-grid"></i>シフト確認と調整 </a></li>
            <li><a href="shift_detail.php"> <i class="fa fa-bar-chart"></i>毎日必要人数登録 </a></li>
            <li><a href="shift_element.php"> <i class="icon-interface-windows"></i>シフト作成 </a></li>
            <li><a href="shift_type.php"> <i class="icon-padnote"></i>シフトタイプ作成 </a></li>
            <li><a href="shift_type_display.php"> <i class="icon-page"></i>シフトタイプ確認 </a></li>
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
                          <div class="card-header d-flex align-items-center"　>
                            <h3 class="h4"><?php echo $shift_each_data['shift_name']; ?></h3>
                          </div>
                        <div class="table-responsive">
                          <?php if($flag === 1): ?>
                            <table class = "table">
                              <thead>
                                <tr>
                                  <th></th>
                                  <th colspan="<?php echo count($users)/2; ?>">
                                    <form action = "./shift_creation.php" method="post" >
                                      <input type = "hidden" name="shift_id" value="<?php echo $shift_id; ?>">
                                      <input type="submit"  class="btn btn-light" name="button" value="一時確定">
                                    </form>
                                  </th>
                                  <th colspan="<?php echo count($users)/2; ?>">
                                    <form action = "./shift_creation.php" method="post" >
                                      <input type = "hidden" name="shift_id" value="<?php echo $shift_id; ?>">
                                      <input type="submit"  class="btn btn-warning" name="button" value="最終確定">
                                    </form>
                                  </th>
                                </tr>
                                <tr>
                                  <th>
                                    <form action = "./shift_creation.php" method="post">
                                      <input type = "hidden" name="shift_id" value="<?php echo $shift_id; ?>">
                                      <input type="submit"  class="btn btn-primary" name="button" value="データクリア">
                                    </form>
                                  </th>
                                  <?php foreach ($users as $user): ?>
                                    <th><?php echo $user['nickname']."(".$user['rank'].")"; ?></th>
                                  <?php endforeach; ?>
                                </tr>
                              </thead>
                              <tbody>
                                <?php foreach ($days as $day):
                                   $staff_requirement = get_staff_number_requirement_by_date($conn,$shift_id,$day);
                                   $staff_number = get_staff_number_by_date($conn,$shift_id,$day); ?>
                                  <tr>
                                    <?php if($staff_requirement['total_number'] > $staff_number['count']): ?>
                                    <th>
                                        <input class="btn btn-light" type="submit"  style="background-color: red ;color:white" name = "button" value="<?php echo $day."(".getweek($day).")"." [".$staff_requirement['total_number']."/".$staff_number['count']."]"; ?>">
                                    </th>
                                    <?php else: ?>
                                      <th>
                                        <input class="btn btn-light" type="submit"  name = "button" value="<?php echo $day."(".getweek($day).")"." [".$staff_requirement['total_number']."/".$staff_number['count']."]"; ?>">
                                    </th>
                                    <?php endif; ?>
                                    <?php foreach ($users as $user): ?>
                                      <?php 
                                        $shift_for_eachday = get_shift_for_eachday($conn,$shift_id,$user['user_id'],$day);
                                        $abled_datas = find_abled_staff_by_date($conn,$user['user_id'],$day,-1);
                                        if(isset($shift_for_eachday['type_name'])){
                                          ?>
                                          
                                            <?php if($shift_for_eachday['selected_flag'] == 9): ?>
                                              <th style = "background-color:#EA0000">
                                              <button type="button" data-toggle="modal" data-target= "<?php echo "#pop".$shift_for_eachday['id']; ?>" class="btn btn-link" style="color:#ffffff;"><?php echo '取消済'; ?></button>
                                            <?php elseif($shift_for_eachday['selected_flag'] == 1): ?>
                                              <th style = "background-color:<?php echo $shift_for_eachday['type_color']; ?>">
                                              <button type="button" data-toggle="modal" data-target= "<?php echo "#pop".$shift_for_eachday['id']; ?>" class="btn btn-link"><?php echo $shift_for_eachday['type_name']; ?></button>
                                            <?php endif; ?>
                                            <div id="<?php echo "pop".$shift_for_eachday['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
                                                <div role="document" class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h4 id="exampleModalLabel" class="modal-title">シフト調整</h4>
                                                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="./shift_creation.php" method="post">
                                                        <label class="form-control-label">タイプ</label>
                                                        <select class="form-control mb-3" name="type_id" id = "type_id">
                                                        <?php foreach ($shift_types as $shift_type): ?>
                                                          <option value="<?php echo $shift_type['type_id']; ?>">
                                                              <?php echo $shift_type['type_name']; ?>
                                                          </option>
                                                        <?php endforeach; ?>
                                                        <option value="-1">
                                                          休み
                                                        </option>
                                                        </select>
                                                        <table class = "table">
                                                          <thead>
                                                            <tr>
                                                              <?php foreach ($shift_types as $shift_dt): ?>
                                                                <th style = "background-color:<?php echo $shift_dt['type_color']; ?>"><?php echo $shift_dt['type_name']; ?></th>
                                                              <?php endforeach; ?>
                                                            </tr>
                                                          </thead>
                                                          <tbody>
                                                            <tr>
                                                            <?php 
                                                            $a = 0;
                                                            foreach ($shift_types as $shift_dt){
                                                              
                                                              $shift_type_id = $shift_dt['type_id'];
                                                              foreach($abled_datas as $abled_dt){
                                                                // var_dump($abled_dt['type_id']);
                                                                if($abled_dt['type_id'] == $shift_type_id){
                                                                  $a = 1;
                                                                }
                                                              }
                                                              if($a == 1){
                                                                ?>
                                                                <td>〇</td>
                                                                <?php
                                                              }else{
                                                                ?>
                                                                <td>×</td>
                                                                <?php
                                                              }
                                                            } 
                                                            ?>
                                                            </tr>
                                                          </tbody>
                                                        </table>
                                                        <input type = "hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                                        <input type = "hidden" name="creation_id" value="<?php echo $shift_for_eachday['id']; ?>">
                                                        <input type = "hidden" name="shift_id" value="<?php echo $shift_id; ?>">
                                                        <input type = "hidden" name="date" value="<?php echo $day; ?>">
                                                        <input type = "hidden" name="flg" value="yes">
                                                        <div class="modal-footer">
                                                            <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                                                            <input type="submit" class="btn btn-primary" name="button" value="調整確定">
                                                        </div>
                                                        </form>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                          </th>
                                          <?php
                                        }else{
                                              $checked_data = check_cancel_by_black_list($conn,$shift_id,$day);
                                              $abled_data = find_abled_staff_by_date($conn,$user['user_id'],$day,-1);
                                            ?>
                                            <?php if(count($checked_data)>0): ?>      
                                              <th>                  
                                            <button type="button" data-toggle="modal" data-target= "<?php echo "#pop".$day.$user['user_id']; ?>" class="btn btn-link"><?php echo "Cancelled"; ?></button>
                                            <?php elseif(count($abled_data)>0): ?>
                                              <th> 
                                            <button type="button" data-toggle="modal" data-target= "<?php echo "#pop".$day.$user['user_id']; ?>" class="btn btn-link"><?php echo "可能"; ?></button>
                                            <?php else: ?>
                                              <th style="background-color: #808080"> 
                                            <button style="color:#ffffff;" type="button" data-toggle="modal" data-target= "<?php echo "#pop".$day.$user['user_id']; ?>" class="btn btn-link"><?php echo "休み"; ?></button>
                                            <?php endif; ?>
                                            <div id="<?php echo "pop".$day.$user['user_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
                                                <div role="document" class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h4 id="exampleModalLabel" class="modal-title">シフト調整</h4>
                                                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="./shift_creation.php" method="post">
                                                        <label class="form-control-label">タイプ</label>
                                                        <select class="form-control mb-3" name="type_id" id = "type_id">
                                                        <?php foreach ($shift_types as $shift_type): ?>
                                                          <option value="<?php echo $shift_type['type_id']; ?>">
                                                              <?php echo $shift_type['type_name']; ?>
                                                          </option>
                                                        <?php endforeach; ?>
                                                        </select>
                                                        <table class = "table">
                                                          <thead>
                                                            <tr>
                                                              <?php foreach ($shift_types as $shift_dt): ?>
                                                                <th style = "background-color:<?php echo $shift_dt['type_color']; ?>"><?php echo $shift_dt['type_name']; ?></th>
                                                              <?php endforeach; ?>
                                                            </tr>
                                                          </thead>
                                                          <tbody>
                                                            <tr>
                                                            <?php 
                                                            
                                                            foreach ($shift_types as $shift_dt){
                                                              $a = 0;
                                                              $shift_type_id = $shift_dt['type_id'];
                                                              foreach($abled_data as $abled_dt){
                                                                // var_dump($abled_dt['type_id']);
                                                                if($abled_dt['type_id'] == $shift_type_id){
                                                                  $a = 1;
                                                                }
                                                              }
                                                              if($a == 1){
                                                                ?>
                                                                <td>〇</td>
                                                                <?php
                                                              }else{
                                                                ?>
                                                                <td>×</td>
                                                                <?php
                                                              }
                                                            } 
                                                            ?>
                                                            </tr>
                                                          </tbody>
                                                        </table>
                                                          <input type = "hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                                          <input type = "hidden" name="shift_id" value="<?php echo $shift_id; ?>">
                                                          <input type = "hidden" name="date" value="<?php echo $day; ?>">
                                                          <input type = "hidden" name="flg" value="no">
                                                          <div class="modal-footer">
                                                              <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                                                              <input type="submit" class="btn btn-primary" name="button" value="調整確定">
                                                          </div>
                                                        </form>
                                                    </div>
                                                    
                                                    </div>
                                                </div>
                                            </div>
                                          </th>
                                          <?php
                                        }
                                      ?>
                                    
                                    <?php endforeach; ?>
                                  </tr>
                                <?php endforeach; ?>
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