<?php
require "../init.php";
require "../logics/shift_group.php";
if(!$_POST){
    
    require "./templates/shift_group_input.php";
}else{
        $name = $_POST['group_name'];
    $data = check_group_name($conn,$name);
    if(empty($data)){
        insert_shift_group($conn,$name);
        $msg = "グループを作成しました。";
        require "./templates/shift_group_output.php";
    }else{
        $msg = "グループ名がすでに存在しています。";
        require "./templates/shift_group_output.php";
    }
    
}