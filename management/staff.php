<?php
require "../init.php";
require "../logics/staff.php";
if(!$_POST){
    $group_data = get_all_group_by_user_id($conn,11);
    $shift_data = get_all_shift_element_by_group_id($conn,$group_data[0]['group_id']);
    $users = get_member_by_group_id($conn,$group_data[0]['group_id']);
    require "./templates/staff_input.php";
}else{
    $group_data = get_all_group_by_user_id($conn,11);
    $shift_data = get_all_shift_element_by_group_id($conn,$group_data[0]['group_id']);
    $users = get_member_by_group_id($conn,$group_data[0]['group_id']);
    if($_POST['send'] == "確定"){
        $shift_data = get_all_shift_element_by_group_id($conn,$_POST['group_id']);
    }
    if($_POST['send'] == "送信"){
        foreach($users as $user){
            update_users($conn,$user['user_id'],$_POST[$user['user_id']]);
        }
    }
    $users = get_member_by_group_id($conn,$group_data[0]['group_id']);
    require "./templates/staff_input.php";
}