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
                       
                        <form action="./shift_detail.php" method="post" id= "shift_form">    
                            <label class="form-control-label">グループ</label>
                            <select class="form-control mb-3" name="group_id" id = "group_id">
                            <?php foreach ($group_data as $val): ?>
                            <option value="<?php echo $val['group_id']; ?>">
                                <?php echo $val['name']; ?>
                            </option>
                            <?php endforeach; ?>
                            </select>  
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
                        <div class="table-responsive">
                        <table class = "table">
                            <thead>
                            <tr>
                                <th colspan="5" style="text-align:center"><h4><?php echo $year."年".$month."月"; ?></h4></th>
                                <th colspan="2" style="text-align:center">
                                <button type="button" data-toggle="modal" data-target= "#all_register" class="btn btn-primary">全体人数登録</button>
                                    <div id="all_register" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
                                        <div role="document" class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <h4 id="exampleModalLabel" class="modal-title">シフト人数登録</h4>
                                            <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="./shift_detail.php" method="post">
                                                <?php 
                                                foreach ($type_datas as $type_data): ?>
                                                    <h4><?php echo $type_data['type_name'] ?></h4>
                                                    <div class="form-group">
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 form-control-label">ランク1</label>
                                                        <div class="col-sm-9">
                                                            <input type="number" min="0" placeholder="人数を入力してください" class="form-control" name="<?php echo $type_data['type_id']."rank1"  ?>" value = 0>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 form-control-label">ランク2</label>
                                                        <div class="col-sm-9">
                                                            <input type="number" min="0" placeholder="人数を入力してください" class="form-control" name="<?php echo $type_data['type_id']."rank2"  ?>" value = 0>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 form-control-label">ランク3</label>
                                                        <div class="col-sm-9">
                                                            <input type="number" min="0" placeholder="人数を入力してください" class="form-control" name="<?php echo $type_data['type_id']."rank3"  ?>" value = 0>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 form-control-label">ランク4</label>
                                                        <div class="col-sm-9">
                                                            <input type="number" min="0" placeholder="人数を入力してください" class="form-control" name="<?php echo $type_data['type_id']."rank4"  ?>" value = 0>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 form-control-label">ランク5</label>
                                                        <div class="col-sm-9">
                                                            <input type="number" min="0" placeholder="人数を入力してください" class="form-control" name="<?php echo $type_data['type_id']."rank5"  ?>" value = 0>
                                                        </div>
                                                    </div>
                                                    <div class="line"></div>      
                                                <?php endforeach; ?>
                                                    <input type = "hidden" name="shift_id" value="<?php echo $shift_id; ?>">
                                                    <input type = "hidden" name="date" value="<?php echo $date; ?>">
                                                    <div class="modal-footer">
                                                        <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                                                        <input type="submit" class="btn btn-primary" name="button" value="すべて登録">
                                                    </div>
                                                </form>
                                            </div>
                                            
                                            </div>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                <?php foreach($weekarr as $weekday): ?>
                                    <th><?php echo $weekday; ?></th>
                                <?php endforeach; ?>
                            </tr>
                            </thead>
                            <tbody>
                                <?php  
                                $ed = 1;
                                while($ed <= $day){
                                ?>
                                <tr>
                                <?php for($i=0;$i<=6;$i++): ?>
                                    <?php if($ed <= $day && ($moneday <= $i || $ed != 1) ): ?>
                                        <td>
                                        <?php if($ed < 10){
                                            $ed = "0".$ed;
                                        }
                                        $date = $year."-".$month."-".$ed;
                                        $shift_details = get_all_shift_detail_by_date($conn,$shift_id,$date);
                                        ?>
                                        <h4><?php echo $ed; ?></h4>
                                        <?php foreach($shift_details as $shift_detail): ?>
                                            <div style = "background-color:<?php echo $shift_detail['type_color'];?>"><?php echo $shift_detail['type_name']; ?>:<?php echo $shift_detail['count']; ?>人</div>
                                        <?php endforeach; ?>
                                        
                                        <button type="button" data-toggle="modal" data-target= "<?php echo "#myModal".$ed; ?>" class="btn btn-primary">人数登録</button>
                                        <div id="<?php echo "myModal".$ed; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
                                            <div role="document" class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <h4 id="exampleModalLabel" class="modal-title">シフト人数登録</h4>
                                                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="./shift_detail.php" method="post">
                                                    <?php 
                                                    foreach ($type_datas as $type_data): ?>
                                                        <h4><?php echo $type_data['type_name'] ?></h4>
                                                        <div class="form-group">
                                                            <div class="form-group row">
                                                                <label class="col-sm-3 form-control-label">ランク1</label>
                                                            <div class="col-sm-9">
                                                                <input type="number" min="0" placeholder="人数を入力してください" class="form-control" name="<?php echo $type_data['type_id']."rank1"  ?>" value = 0>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-group row">
                                                                <label class="col-sm-3 form-control-label">ランク2</label>
                                                            <div class="col-sm-9">
                                                                <input type="number" min="0" placeholder="人数を入力してください" class="form-control" name="<?php echo $type_data['type_id']."rank2"  ?>" value = 0>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-group row">
                                                                <label class="col-sm-3 form-control-label">ランク3</label>
                                                            <div class="col-sm-9">
                                                                <input type="number" min="0" placeholder="人数を入力してください" class="form-control" name="<?php echo $type_data['type_id']."rank3"  ?>" value = 0>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-group row">
                                                                <label class="col-sm-3 form-control-label">ランク4</label>
                                                            <div class="col-sm-9">
                                                                <input type="number" min="0" placeholder="人数を入力してください" class="form-control" name="<?php echo $type_data['type_id']."rank4"  ?>" value = 0>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-group row">
                                                                <label class="col-sm-3 form-control-label">ランク5</label>
                                                            <div class="col-sm-9">
                                                                <input type="number" min="0" placeholder="人数を入力してください" class="form-control" name="<?php echo $type_data['type_id']."rank5"  ?>" value = 0>
                                                            </div>
                                                        </div>
                                                        <div class="line"></div>      
                                                    <?php endforeach; ?>
                                                        <input type = "hidden" name="shift_id" value="<?php echo $shift_id; ?>">
                                                        <input type = "hidden" name="date" value="<?php echo $date; ?>">
                                                        <div class="modal-footer">
                                                            <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                                                            <input type="submit" class="btn btn-primary" name="button" value="登録">
                                                        </div>
                                                    </form>
                                                </div>
                                                
                                            </div>
                                            </div>
                                        </div>
                                        </td>
                                        <?php $ed++; ?>
                                    <?php else: ?>
                                        <td></td>
                                    <?php endif; ?>
                                    <?php endfor;} ?>
                                </tr>
                            </tbody>
                        </table>  
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