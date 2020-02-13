<?php

require "../init.php";
require "../logics/shift_release.php";

$shift_id = 3;
$users = LOGIC_get_member_by_shift_id($conn,$shift_id);
$days = get_days_by_shift_month($conn,$shift_id);
$is_finished = get_shift_element($conn,$shift_id)['is_finished'];
$group_data = get_all_group_by_user_id($conn,1);
$shift_data = get_all_shift_element_by_group_id($conn,$group_data[0]['group_id']);
if(!$_POST){
  require "./templates/shift_release_input.php";
}else{
  require "./templates/shift_release_input.php";
}
?>
