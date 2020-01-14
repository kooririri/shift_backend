<?php
require "../init.php";
require "../logics/shift_creation.php";
$shift_id = 0;
$flag = 0;
if(!$_POST){
    $group_data = get_all_group_by_user_id($conn,11);
    $shift_data = get_all_shift_element_by_group_id($conn,$group_data[0]['group_id']);
    require "./templates/shift_creation_input.php";
}else{
    if(isset($_POST['shift_id'])){
        $shift_id = $_POST['shift_id'];
    }
    $group_data = get_all_group_by_user_id($conn,11);
    $shift_data = get_all_shift_element_by_group_id($conn,$group_data[0]['group_id']);
    $users = LOGIC_get_member_by_shift_id($conn,$shift_id);
    $days = get_days_by_shift_month($conn,$shift_id);
    $shift_each_data = get_shift_element_by_shift_id($conn,$shift_id);
    // $flag = 0;
    if($_POST['button'] == "今から組みます"){
        LOGIC_make_shift($conn,$shift_id);
        require "./templates/shift_creation_input.php";
    }elseif($_POST['button'] == "確定"){
        $flag = LOGIC_check_shift($conn,$shift_id);
    }
    // var_dump($flag);
    require "./templates/shift_creation_output.php";
}