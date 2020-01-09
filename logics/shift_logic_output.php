<?php



function LOGIC_get_member_by_group($conn,$group_id)
{
    $sql = "SELECT * FROM group_member gm LEFT JOIN user u ON gm.user_id = u.user_id WHERE gm.group_id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $group_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

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
    
    
    $date = substr($shift_month,0,8);
    //シフト月の日にちのarray
    $shift_dates = array();
    $days = date('t', strtotime($shift_month));
    for($i = 1;$i<=$days;$i++){
        $str = $i;
        if($i < 10){
            $str = "0".$i;
        }
        $temp = $date.$str;
        $shift_dates[] = $temp;
    }
    
    foreach($shift_dates as $temp_date){
        $shift_detail = get_shift_detail($conn,$shift_data['shift_id'],$temp_date);
        foreach($shift_detail as $each_detail){
            $total_number = $each_detail['total_number'];
            $number = $each_detail['number'];
            $type_id = $each_detail['type_id'];
            $rank = $each_detail['rank'];
            $shift_id = $each_detail['shift_id'];

            $temp_request = get_shift_request($conn,$shift_id,$temp_date,$type_id,$rank,1);
            // var_dump($temp_date);
            // var_dump($temp_request);
            $count = count($temp_request);
            if($count <= $number){
                foreach($temp_request as $temp_val){
                    insert_shift_creation($conn,$temp_val['shift_id'],$temp_val['user_id'],$temp_val['type_id'],$temp_val['date']);
                }              
            }elseif($count > $number){
                $line = $count;
                while($line > 0){
                    $random_number = rand(0,$line-1);
                    insert_shift_creation($conn,$temp_request[$random_number]['shift_id'],$temp_request[$random_number]['user_id'],$temp_request[$random_number]['type_id'],$temp_request[$random_number]['date']);
                    array_splice($temp_request,$random_number,1);
                    $line --;
                }
            }
        }
    }
}


//shift_idによりシフトの情報を取得（1月1日10人要るとか）
function get_shift_detail($conn,$shift_id,$date){
     $sql = "SELECT * FROM shift_detail WHERE shift_id = ? AND date = ?";
     $stmt = $conn->prepare($sql);
     $stmt->bind_param("is", $shift_id,$date);
     $stmt->execute();
     $result = $stmt->get_result();
     $stmt->close();
 
 
     $shift_detail_data = [];
     while ($row = $result->fetch_assoc()) {
         $shift_detail_data[] = [
             'shift_id' => $row['shift_id'],
             'date' => $row['date'],
             'type_id' => $row['type_id'],
             'rank' => $row['rank'],
             'number' => $row['number'],
             'total_number' => $row['total_number'],
         ];
     }
     return $shift_detail_data;
}


//group_idによりグループに所属されているメンバを取得
// function get_user_by_group_id($conn,$group_id){
   
//     $sql = "SELECT u.user_id AS user_id ,u.evaluation AS rank , u.nickname AS nickname FROM group_member gm LEFT JOIN user u ON gm.user_id = u.user_id WHERE gm.group_id = ? ";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("i", $group_id);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $stmt->close();

//     if ($result->num_rows === 0) {
//         echo "データなし";
//     }

//     $data = [];
//     while ($row = $result->fetch_assoc()) {
//         $data[] = [
//             'user_id' => $row['user_id'],
//             'rank' => $row['rank'],
//             'nickname' => $row['nickname'],
//         ];
//     }
//     return $data;
// }

function get_shift_request($conn,$shift_id,$temp_date,$type_id,$rank,$selected_flag){
    
    $sql = "SELECT * FROM shift_request sr LEFT JOIN user u ON sr.user_id = u.user_id WHERE shift_id = ? AND date = ? AND type_id = ? AND selected_flag = ? AND evaluation = ? AND authority = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isiii", $shift_id,$temp_date,$type_id,$selected_flag,$rank);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();


    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'user_id' => $row['user_id'],
            'date' => $row['date'],
            'shift_id' => $row['shift_id'],
            'type_id' => $row['type_id'],
            'selected_flag' => $row['selected_flag'],
            'rank' => $row['evaluation'],
        ];
    }
    return $data;
}

function insert_shift_creation($conn,$shift_id,$user_id,$type_id,$date){
    $stmt = $conn->prepare("INSERT INTO shift_creation (shift_id,user_id,type_id,date) VALUES (?,?,?,?)");
    $stmt->bind_param("iiis", $shift_id,$user_id,$type_id,$date);
    $stmt->execute();
    $stmt->close();
}
