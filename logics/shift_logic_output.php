<?php



function LOGIC_get_member_by_group($conn,$group_id)
{
    $sql = "SELECT * FROM group_member gm LEFT JOIN user u ON gm.user_id = u.user_id WHERE gm.group_id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $group_id);
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
            'user_id' => $row['user_id'],
            'nickname' => $row['nickname'],
            'evaluation' => $row['evaluation'],
        ];
    }
    return $data;

}

function LOGIC_make_shift($conn,$group_id,$shift_month)
{
    //group_idとshift_monthによりshift_idを取得
    $sql = "SELECT * FROM shift_element WHERE group_id = ? AND shift_month = ? limit 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $group_id,$shift_month);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    
    if ($result->num_rows === 0) {
        echo "データなし";
    }

    $shift_data = [];
    while ($row = $result->fetch_assoc()) {
        $shift_data = [
            'shift_id' => $row['shift_id'],
            'group_id' => $row['group_id'],
            'shift_name' => $row['shift_name'],
            'shift_month' => $row['shift_month'],
            'submit_time' => $row['submit_time'],
            'modify_time' => $row['modify_time'],
            'release_time' => $row['release_time'],
            'modify_version' => $row['modify_version'],
        ];
    }
    
    $members = get_user_by_group_id($conn,$group_id);
    

    //shift_idによりシフトの情報を取得（1月1日10人要るとか）
    $sql = "SELECT * FROM shift_detail WHERE shift_id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $shift_data['shift_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows === 0) {
        echo "データなし";
    }

    $shift_detail_data = [];
    while ($row = $result->fetch_assoc()) {
        $shift_detail_data[] = [
            'shift_id' => $row['shift_id'],
            'date' => $row['date'],
            'type_id' => $row['type_id'],
            'rank' => $row['rank'],
            'total_number' => $row['total_number'],
        ];
    }
    return $shift_detail_data;
    foreach($shift_detail_data as $val){
        $date = $val['date'];

    }

}


//group_idによりグループに所属されているメンバを取得
function get_user_by_group_id($conn,$group_id){
   
    $sql = "SELECT u.user_id AS user_id ,u.evaluation AS rank , u.nickname AS nickname FROM group_member gm LEFT JOIN user u ON gm.user_id = u.user_id WHERE gm.group_id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $group_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows === 0) {
        echo "データなし";
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'user_id' => $row['user_id'],
            'rank' => $row['rank'],
            'nickname' => $row['nickname'],
        ];
    }
    return $data;
}

function get_shift_request_by_date($conn,$date,$user_id){
    $sql = "SELECT * FROM shift_request sr LEFT JOIN user u ON sr.user_id = u.user_id WHERE sr.date = ? AND sr.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $date,$user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows === 0) {
        echo "データなし";
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'user_id' => $row['user_id'],
            'date' => $row['date'],
            'shift_id' => $row['shift_id'],
            'type_id' => $row['type_id'],
            'selected_flag' => $row['selected_flag'],
            'rank' => $row['evaluation'],
            'nickname' => $row['nickname'],
        ];
    }
    return $data;
}
