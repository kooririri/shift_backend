<?php
require "../init.php";
require "../logics/shift_type.php";
if(!$_POST){
    $group_data = get_all_group_by_user_id($conn,11);
    $shift_data = get_all_shift_element_by_group_id($conn,$group_data[0]['group_id']);
    require "./templates/shift_type_input.php";
}else{
    $group_data = get_all_group_by_user_id($conn,11);
    $shift_data = get_all_shift_element_by_group_id($conn,$group_data[0]['group_id']);
    // var_dump($_POST);
    insert_shift_type($conn,$_POST);
    require "./templates/shift_type_output.php";
}