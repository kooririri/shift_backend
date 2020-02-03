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
    $shift_types = get_all_shift_type($conn,$group_data[0]['group_id']);
    // $flag = 0;
    if($_POST['button'] == "今から組みます"){
        LOGIC_make_shift($conn,$shift_id);
        $flag = LOGIC_check_shift($conn,$shift_id);
        require "./templates/shift_creation_output.php";
    }elseif($_POST['button'] == "確定"){
        $flag = LOGIC_check_shift($conn,$shift_id);
        require "./templates/shift_creation_output.php";
    }elseif($_POST['button'] == "調整確定"&&$_POST['flg'] == "yes"){  
        $flag = LOGIC_check_shift($conn,$shift_id);
        change_shift_creation_by_id($conn,$_POST['creation_id'],$_POST['type_id']);
        require "./templates/shift_creation_output.php";
    }elseif($_POST['button'] == "調整確定"&&$_POST['flg'] == "no"){
        $flag = LOGIC_check_shift($conn,$shift_id);
        change_shift_creation_from_none($conn,$_POST['shift_id'],$_POST['user_id'],$_POST['type_id'],$_POST['date']);
        require "./templates/shift_creation_output.php";
    }elseif($_POST['button'] == "データクリア"){
        
        data_clear($conn,$shift_id);
        $flag = LOGIC_check_shift($conn,$shift_id);
        require "./templates/shift_creation_output.php";
    }elseif($_POST['button'] == "一時確定"){
        shift_submit($conn,$shift_id);
        $flag = LOGIC_check_shift($conn,$shift_id);
        require "./templates/shift_creation_output.php";
    }elseif($_POST['button'] == "最終確定"){
        $flag = LOGIC_check_shift($conn,$shift_id);
        require "./templates/shift_creation_output.php";
    }
    // var_dump($flag);
   
}