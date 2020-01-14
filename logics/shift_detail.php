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

function get_all_shift_detail_by_date($conn,$shift_id,$date){
    $sql = "SELECT SUM(sd.number) AS count,st.type_name,st.type_color FROM shift_detail sd LEFT JOIN shift_type st ON sd.type_id = st.type_id WHERE sd.shift_id = ? AND sd.date = ? GROUP BY sd.type_id";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is",$shift_id,$date);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
  

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'count' => $row['count'],
            'type_name' => $row['type_name'],
            'type_color' => $row['type_color'],
        ];
    }
    return $data;
}

function check_shift_detail($conn,$shift_id,$date,$type_id,$rank){
    $sql = "SELECT * FROM shift_detail WHERE shift_id = ? AND date = ? AND type_id = ? AND rank = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isii",$shift_id,$date,$type_id,$rank);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
  
    
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data = [
            'id' => $row['id'],
        ];
    }
    return $data;
}

function update_shift_detail($conn,$all_input_data){
    $stmt = $conn->prepare("UPDATE shift_detail SET shift_id = ?,date=?,total_number=?,type_id=?,rank=?,number =? WHERE id =?");
    $stmt->bind_param("isiiiii", $all_input_data['shift_id'],$all_input_data['date'],$all_input_data['total_number'],$all_input_data['type_id'],$all_input_data['rank'],$all_input_data['number'],$all_input_data['id']);
    $stmt->execute();

    $stmt->close();
}


function insert_shift_detail($conn,$all_input_data){
    $stmt = $conn->prepare("INSERT INTO shift_detail (shift_id,date,total_number,type_id,rank,number) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("isiiii", $all_input_data['shift_id'],$all_input_data['date'],$all_input_data['total_number'],$all_input_data['type_id'],$all_input_data['rank'],$all_input_data['number']);
    $stmt->execute();

    $stmt->close();
}

function get_date_by_shift_id($conn,$shift_id){
    $sql = "SELECT shift_month FROM shift_element WHERE shift_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$shift_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
  

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data = [
            'shift_month' => $row['shift_month'],
        ];
    }
    return $data;
}