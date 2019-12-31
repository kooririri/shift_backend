<?php

function LOGIC_shift_element_input($conn,$all_input_data)
{
    $stmt = $conn->prepare("INSERT INTO shift_element (group_id,shift_name,shift_month,submit_time,modify_time,release_time) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("isssss", $all_input_data['group_id'],$all_input_data['shift_name'],$all_input_data['shift_month'],$all_input_data['submit_time'],$all_input_data['modify_time'],$all_input_data['release_time']);
    $stmt->execute();

    $stmt->close();

}

function LOGIC_shift_detail_input($conn,$all_input_data)
{
    $stmt = $conn->prepare("INSERT INTO shift_detail (shift_id,date,total_number,type_id,rank,number) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("isiiii", $all_input_data['shift_id'],$all_input_data['date'],$all_input_data['total_number'],$all_input_data['type_id'],$all_input_data['rank'],$all_input_data['number']);
    $stmt->execute();

    $stmt->close();
}

function LOGIC_shift_type_input($conn,$all_input_data)
{
    $stmt = $conn->prepare("INSERT INTO shift_type (shift_id,begin_time,end_time,type_name,comment) VALUES (?,?,?,?,?)");
    $stmt->bind_param("issss", $all_input_data['shift_id'],$all_input_data['begin_time'],$all_input_data['end_time'],$all_input_data['type_name'],$all_input_data['comment']);
    $stmt->execute();

    $stmt->close();
}

