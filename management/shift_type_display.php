<?php
require "../init.php";
require "../logics/shift_type_display.php";
if(!$_POST){
    $group_data = get_all_group_by_user_id($conn,11);
    $shift_data = get_all_shift_element_by_group_id($conn,$group_data[0]['group_id']);
    $shift_types = get_all_shift_type($conn,$group_data[0]['group_id']);
    
    require "./templates/shift_type_display_input.php";
}else{
    $group_data = get_all_group_by_user_id($conn,11);
    $shift_data = get_all_shift_element_by_group_id($conn,$group_data[0]['group_id']);
    $shift_types = get_all_shift_type($conn,$group_data[0]['group_id']);
    if($_POST['button'] == "X"){
        var_dump($_POST);
        delete_type($conn,$_POST['type_id']);
    }
    if($_POST['button'] == "更新"){
        var_dump($_POST);
        update_shift_type($conn,$_POST);
    }
    $shift_types = get_all_shift_type($conn,$group_data[0]['group_id']);
    require "./templates/shift_type_display_output.php";
}