<?php
//user_idにより全てのグループを取得
function get_all_group_by_user_id($conn,$user_id){
    $sql = "SELECT * FROM shift_group sg LEFT JOIN group_member gm ON sg.group_id = gm.group_id WHERE gm.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
  
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'group_id' => $row['group_id'],
            'name' => $row['name'],
        ];
    }
    return $data;
} 

function get_all_shift_element_by_group_id($conn,$group_id){
    $sql = "SELECT * FROM shift_element WHERE group_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$group_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
  

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'shift_id' => $row['shift_id'],
            'shift_name' => $row['shift_name'],
            'shift_month' => $row['shift_month'],
            'submit_time' => $row['submit_time'],
            'modify_time' => $row['modify_time'],
            'release_time' => $row['release_time'],
        ];
    }
    return $data;
}

function insert_shift_type($conn,$all_input_data){
    $stmt = $conn->prepare("INSERT INTO shift_type (group_id,begin_time,end_time,type_name,comment,type_color) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("isssss", $all_input_data['group_id'],$all_input_data['begin_time'],$all_input_data['end_time'],$all_input_data['type_name'],$all_input_data['comment'],$all_input_data['type_color']);
    $stmt->execute();

    $stmt->close();
}

function get_all_shift_type($conn,$group_id){
    $sql = "SELECT * FROM shift_type WHERE group_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$group_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
  

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'type_id' => $row['type_id'],
            'group_id' => $row['group_id'],
            'begin_time' => $row['begin_time'],
            'end_time' => $row['end_time'],
            'type_name' => $row['type_name'],
            'type_color' => $row['type_color'],
            'comment' => $row['comment'],
            'version' => $row['version'],
        ];
    }
    return $data;
}

function update_shift_type($conn,$all_input_data){
    $new_version = $all_input_data['version'] + 1;
    $stmt = $conn->prepare("UPDATE shift_type SET begin_time = ?,end_time=?,type_name=?,comment=?,type_color=?,version =? WHERE type_id =?");
    $stmt->bind_param("sssssii", $all_input_data['begin_time'],$all_input_data['end_time'],$all_input_data['type_name'],$all_input_data['comment'],$all_input_data['type_color'],$new_version,$all_input_data['type_id']);
    $stmt->execute();

    $stmt->close();
}

function delete_type($conn,$type_id){
    $stmt = $conn->prepare("DELETE FROM shift_type WHERE type_id = ?");
    $stmt->bind_param("i", $type_id);
    $stmt->execute();
    $stmt->close();
}