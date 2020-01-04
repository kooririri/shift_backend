<?php
//user_idにより全てのグループを取得
function get_all_group_by_user_id($conn,$user_id){
    $sql = "SELECT * FROM shift_group sg LEFT JOIN group_member gm ON sg.group_id = gm.group_id WHERE gm.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if ($result->num_rows === 0) {
        echo "データなし";
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'group_id' => $row['group_id'],
            'name' => $row['name'],
        ];
    }
    return $data;
} 

//shift_elementマスタに書き込む
function insert_shift_element($conn,$all_input_data){
    $shift_month = date('Y-m-01', strtotime(date($all_input_data['shift_month'])));
    $stmt = $conn->prepare("INSERT INTO shift_element (group_id,shift_name,shift_month,submit_time,modify_time,release_time) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("isssss", $all_input_data['group_id'],$all_input_data['shift_name'],$shift_month,$all_input_data['submit_time'],$all_input_data['modify_time'],$all_input_data['release_time']);
    $stmt->execute();

    $stmt->close();
}