<?php
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

function get_member_by_group_id($conn,$group_id){
    $sql = "SELECT * FROM group_member gm LEFT JOIN user u ON gm.user_id = u.user_id WHERE gm.group_id = ? AND authority = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $group_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'user_id' => $row['user_id'],
            'nickname' => $row['nickname'],
            'rank' => $row['evaluation'],
        ];
    }
    return $data;
}

function update_users($conn,$user_id,$rank){
    $stmt = $conn->prepare("UPDATE user SET evaluation = ? WHERE user_id =?");
    $stmt->bind_param("ii", $rank,$user_id);
    $stmt->execute(); 
    $stmt->close();
}
?>