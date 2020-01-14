<?php
require "../init.php";
require "../logics/shift_detail.php";
$shift_id = 0;
$flag = 0;

$month = date("m",strtotime("1 month"));
$year = date("Y");
$day = date("t",mktime(0,0,0,$month,'1',$year));
$moneday = date("w",mktime(0,0,0,$month,'1',$year));
$weekarr = array("日曜日","月曜日","火曜日","水曜日","木曜日","金曜日","土曜日");

if(!$_POST){
    $group_data = get_all_group_by_user_id($conn,11);
    $shift_data = get_all_shift_element_by_group_id($conn,$group_data[0]['group_id']);
    
   
    require "./templates/shift_detail_input.php";
}else{
    if(isset($_POST['shift_id'])){
        $shift_id = $_POST['shift_id'];
    }
    $group_data = get_all_group_by_user_id($conn,11);
    $shift_data = get_all_shift_element_by_group_id($conn,$group_data[0]['group_id']);
    $type_datas = get_all_shift_type($conn,$group_data[0]['group_id']); 
    $shift_month = get_date_by_shift_id($conn,$shift_id); 
    $month = date("m",strtotime($shift_month['shift_month']));
    $year = date("Y");
    $day = date("t",mktime(0,0,0,$month,'1',$year));
    $moneday = date("w",mktime(0,0,0,$month,'1',$year));
   

    //ポップアップで入力された場合
    if(isset($_POST['button'])&&$_POST['button']=="登録"){
        $shift_types = get_all_shift_type($conn,$group_data[0]['group_id']);
        $total_number = 0;
        foreach($shift_types as $shift_type){
            $total_number += intval($_POST[$shift_type['type_id'].'rank1'])+intval($_POST[$shift_type['type_id'].'rank2'])+intval($_POST[$shift_type['type_id'].'rank3'])+intval($_POST[$shift_type['type_id'].'rank4'])+intval($_POST[$shift_type['type_id'].'rank5']);
        }
        foreach($shift_types as $shift_type){
            for($i = 1;$i<=5;$i++){
                $all_input_data = array();
                $all_input_data = [
                    'shift_id' => $shift_id,
                    'date' => $_POST['date'],
                    'total_number' => $total_number,
                    'type_id' => $shift_type['type_id'],
                    'shift_id' => $shift_id,
                    'rank' => $i,
                    'number' => $_POST[$shift_type['type_id'].'rank'.$i],
                ];
                $check_data = check_shift_detail($conn,$shift_id,$_POST['date'],$shift_type['type_id'],$i);
                if(isset($check_data['id'])){
                    $all_input_data =  $all_input_data = [
                        'shift_id' => $shift_id,
                        'date' => $_POST['date'],
                        'total_number' => $total_number,
                        'type_id' => $shift_type['type_id'],
                        'shift_id' => $shift_id,
                        'rank' => $i,
                        'number' => $_POST[$shift_type['type_id'].'rank'.$i],
                        'id' => $check_data['id'], 
                    ];
                    update_shift_detail($conn,$all_input_data);           
                }else{                  
                    insert_shift_detail($conn,$all_input_data);           
                }
            }     
        }
    }
    if(isset($_POST['button'])&&$_POST['button']=="すべて登録"){
        $shift_types = get_all_shift_type($conn,$group_data[0]['group_id']);
        $total_number = 0;
        foreach($shift_types as $shift_type){
            $total_number += intval($_POST[$shift_type['type_id'].'rank1'])+intval($_POST[$shift_type['type_id'].'rank2'])+intval($_POST[$shift_type['type_id'].'rank3'])+intval($_POST[$shift_type['type_id'].'rank4'])+intval($_POST[$shift_type['type_id'].'rank5']);
        }
        foreach($shift_types as $shift_type){
            for($i = 1;$i<=5;$i++){
                $all_input_data = array();
                $all_input_data = [
                    'shift_id' => $shift_id,
                    'date' => $_POST['date'],
                    'total_number' => $total_number,
                    'type_id' => $shift_type['type_id'],
                    'shift_id' => $shift_id,
                    'rank' => $i,
                    'number' => $_POST[$shift_type['type_id'].'rank'.$i],
                ];
                $check_data = check_shift_detail($conn,$shift_id,$_POST['date'],$shift_type['type_id'],$i);
                if(isset($check_data['id'])){
                    $all_input_data =  $all_input_data = [
                        'shift_id' => $shift_id,
                        'date' => $_POST['date'],
                        'total_number' => $total_number,
                        'type_id' => $shift_type['type_id'],
                        'shift_id' => $shift_id,
                        'rank' => $i,
                        'number' => $_POST[$shift_type['type_id'].'rank'.$i],
                        'id' => $check_data['id'], 
                    ];
                    update_shift_detail($conn,$all_input_data);           
                }else{                  
                    insert_shift_detail($conn,$all_input_data);           
                }
            }     
        }
    }
    require "./templates/shift_detail_output.php";
}