<?php
require "../init.php";
require "../logics/shift_element.php";
if(!$_POST){
    $group_data = get_all_group_by_user_id($conn,11);
    require "./templates/shift_element_input.php";
}else{
    $input_data = [];
    $input_data = [
        'shift_name' => $_POST['shift_name'],
        'shift_month' => $_POST['shift_month'],
        'submit_time' => $_POST['submit_time'],
        'modify_time' => $_POST['modify_time'],
        'release_time' => $_POST['release_time'],
        'group_id' => $_POST['group_id'],
    ];
    var_dump($_POST);
    insert_shift_element($conn,$input_data);
    require "./templates/shift_element_output.php";
}