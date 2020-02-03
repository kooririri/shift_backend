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


function LOGIC_make_shift($conn,$shift_id)
{
    //group_idとshift_monthによりshift_idを取得
    $sql = "SELECT * FROM shift_element WHERE shift_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $shift_id);
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
    
    
    $date = substr($shift_data['shift_month'],0,8);
    //シフト月の日にちのarray
    $shift_dates = array();
    $days = date('t', strtotime($shift_data['shift_month']));
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
                    change_selected_flag_when_arranged($conn,$temp_val['id'],$temp_val['user_id']);
                }              
            }elseif($count > $number){
                $line = $number;
                $countdown = $count;
                while($line > 0){
                    $random_number = rand(0,$countdown-1);
                    insert_shift_creation($conn,$temp_request[$random_number]['shift_id'],$temp_request[$random_number]['user_id'],$temp_request[$random_number]['type_id'],$temp_request[$random_number]['date']);
                    change_selected_flag_when_arranged($conn,$temp_request[$random_number]['id'],$temp_request[$random_number]['user_id']);
                    array_splice($temp_request,$random_number,1);
                    $line --;
                    $countdown--;
                }
            }
        }
    }
    $users = LOGIC_get_member_by_shift_id($conn,$shift_id);
    $days = get_days_by_shift_month($conn,$shift_id);
    foreach ($users as $user){
        foreach($days as $day){
            $sql = "SELECT * FROM shift_creation sc LEFT JOIN user u ON sc.user_id = u.user_id LEFT JOIN shift_type st ON sc.type_id = st.type_id WHERE sc.shift_id = ? AND sc.user_id = ? AND sc.date = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iis", $shift_id,$user['user_id'],$day);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data = [
                    'id' => $row['id'],
                    'user_id' => $row['user_id'],
                    'nickname' => $row['nickname'],
                    'rank' => $row['evaluation'],
                    'type_id' => $row['type_id'],
                    'type_name' => $row['type_name'],
                    'type_color' => $row['type_color'],
                ];
            }
            if(count($data) >0){
                $stmt = $conn->prepare("UPDATE shift_creation SET selected_flag = 1 WHERE id =?");
                $stmt->bind_param("i", $data['id']);
                $stmt->execute();
            
                $stmt->close();
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
            'id' => $row['id'],
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


//shift_idによりシフト組むかどうかをチェック
function LOGIC_check_shift($conn,$shift_id)
{
    $sql = "SELECT * FROM shift_creation WHERE shift_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $shift_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();


    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'user_id' => $row['user_id'],
            'type_id' => $row['type_id'],
            'date' => $row['date'],
        ];
    }
    $result = 0;
    if(count($data)>0){
        $result = 1;
    }
    return $result;
}

function LOGIC_shift_display($conn,$shift_id,$date){
    $sql = "SELECT * FROM shift_creation sc LEFT JOIN user u ON sc.user_id = u.user_id WHERE shift_id = ? AND sc.date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $shift_id,$date);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();


    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'user_id' => $row['user_id'],
            'type_id' => $row['type_id'],
            'date' => $row['date'],
            'rank' => $row['evaluation'],
            'nickname' => $row['nickname'],
        ];
    }
    $result = 0;
    if(count($data)>0){
        $result = 1;
    }
    return $result;
}

function LOGIC_get_member_by_shift_id($conn,$shift_id)
{
    $sql = "SELECT * FROM shift_element se LEFT JOIN group_member gm ON se.group_id = gm.group_id LEFT JOIN user u ON gm.user_id = u.user_id WHERE se.shift_id = ? AND u.authority = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $shift_id);
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

function get_days_by_shift_month($conn,$shift_id){
    $sql = "SELECT * FROM shift_element WHERE shift_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $shift_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $shift_month = [];
    while ($row = $result->fetch_assoc()) {
        $shift_month = [
            'shift_month' => $row['shift_month'],
        ];
    }

    $date = substr($shift_month['shift_month'],0,8);
    //シフト月の日にちのarray
    $shift_dates = array();
    $days = date('t', strtotime($shift_month['shift_month']));
    for($i = 1;$i<=$days;$i++){
        $str = $i;
        if($i < 10){
            $str = "0".$i;
        }
        $temp = $date.$str;
        $shift_dates[] = $temp;
    }
    return $shift_dates;
}

function get_shift_for_eachday($conn,$shift_id,$user_id,$date)
{
    $sql = "SELECT * FROM shift_creation sc LEFT JOIN user u ON sc.user_id = u.user_id LEFT JOIN shift_type st ON sc.type_id = st.type_id WHERE sc.shift_id = ? AND sc.user_id = ? AND sc.date = ? AND (selected_flag = 1 OR selected_flag = 9)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $shift_id,$user_id,$date);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data = [
            'id' => $row['id'],
            'user_id' => $row['user_id'],
            'nickname' => $row['nickname'],
            'rank' => $row['evaluation'],
            'type_id' => $row['type_id'],
            'type_name' => $row['type_name'],
            'type_color' => $row['type_color'],
            'selected_flag' => $row['selected_flag'],
        ];
    }

    return $data;
}

function get_shift_element_by_shift_id($conn,$shift_id){
    $sql = "SELECT * FROM shift_element WHERE shift_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$shift_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
  

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data = [
            'group_id' => $row['group_id'],
            'shift_name' => $row['shift_name'],
            'shift_month' => $row['shift_month'],
            'submit_time' => $row['submit_time'],
            'modify_time' => $row['modify_time'],
            'release_time' => $row['release_time'],
            'modify_version' => $row['modify_version'],
        ];
    }
    return $data;
}

//将被排上班的希望的selected_flag变成9
function change_selected_flag_when_arranged($conn,$id,$user_id){
    $stmt = $conn->prepare("UPDATE shift_request SET selected_flag = 9 WHERE id =? AND user_id =?");
    $stmt->bind_param("ii", $id,$user_id);
    $stmt->execute();

    $stmt->close();
}

//获取当天上班人数
function get_staff_number_by_date($conn,$shift_id,$date){
    $sql = "SELECT COUNT(user_id) AS count FROM shift_creation WHERE shift_id = ? AND date = ? AND selected_flag = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is",$shift_id,$date);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
  

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data = [
            'count' => $row['count'],
        ];
    }
    return $data;
}

//获取当天需要人数
function get_staff_number_requirement_by_date($conn,$shift_id,$date){
    $sql = "SELECT total_number FROM shift_detail WHERE shift_id = ? AND date = ? limit 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is",$shift_id,$date);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
  

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data = [
            'total_number' => $row['total_number'],
        ];
    }
    return $data;
}

//判断是不是因为讨厌的人而取消shift
function check_cancel_by_black_list($conn,$shift_id,$date){
    $sql = "SELECT * FROM shift_request WHERE shift_id = ? AND date = ? AND selected_flag = 2";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is",$shift_id,$date);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
  

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => $row['id'],
            'user_id' => $row['user_id'],
            'date' => $row['date'],
            'shift_id' => $row['shift_id'],
            'type_id' => $row['type_id'],
            'selected_flag' => $row['selected_flag'],
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

function find_abled_staff_by_date($conn,$user_id,$date,$selected_flag){
    if($selected_flag = -1){
        $sql = "SELECT * FROM shift_request sr LEFT JOIN shift_type st ON sr.type_id = st.type_id WHERE user_id = ? AND date = ? AND selected_flag != 0 AND selected_flag != 2";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is",$user_id,$date);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    }else{
        $sql = "SELECT * FROM shift_request sr LEFT JOIN shift_type st ON sr.type_id = st.type_id WHERE user_id = ? AND date = ? AND selected_flag = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isi",$user_id,$date,$selected_flag);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    }
   
  

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'user_id' => $row['user_id'],
            'date' => $row['date'],
            'shift_id' => $row['shift_id'],
            'type_id' => $row['type_id'],
            'selected_flag' => $row['selected_flag'],
            'type_name' => $row['type_name'],
            'type_color' => $row['type_color'],
        ];
    }
    return $data;
}

function change_shift_creation_by_id($conn,$creation_id,$type_id){
    if($type_id == "-1"){
        $stmt = $conn->prepare("DELETE FROM shift_creation WHERE id =?");
        $stmt->bind_param("i",$creation_id);
        $stmt->execute();
        $stmt->close();
    }else{
        $stmt = $conn->prepare("UPDATE shift_creation SET type_id = ?,selected_flag = 1 WHERE id =?");
        $stmt->bind_param("ii", $type_id,$creation_id);
        $stmt->execute(); 
        $stmt->close();
    }
}

function change_shift_creation_from_none($conn,$shift_id,$user_id,$type_id,$date){
    $sql = "SELECT * FROM shift_creation WHERE shift_id = ? AND user_id = ? AND type_id = ? AND date= ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis",$shift_id,$user_id,$type_id,$date);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data = [
            'id' => $row['id'],
        ];
    }
   
    if(isset($data['id'])){
        $stmt = $conn->prepare("UPDATE shift_creation SET selected_flag = 1 WHERE id =?");
        $stmt->bind_param("i", $data['id']);
        $stmt->execute(); 
        $stmt->close();
    }else{
        
        $stmt = $conn->prepare("INSERT INTO shift_creation (shift_id,user_id,type_id,date,selected_flag) VALUES (?,?,?,?,1)");
        $stmt->bind_param("iiis", $shift_id, $user_id, $type_id, $date);
        $stmt->execute(); 
        $stmt->close();
    }
}

function data_clear($conn,$shift_id){
    $stmt = $conn->prepare("DELETE FROM shift_creation WHERE shift_id = ?");
    $stmt->bind_param("i", $shift_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("UPDATE shift_request SET selected_flag = 1 WHERE (selected_flag = 9 OR selected_flag =8) AND shift_id = ?");
    $stmt->bind_param("i", $shift_id);
    $stmt->execute(); 
    $stmt->close();

    $stmt = $conn->prepare("UPDATE shift_request SET kaburu_flag = 0 WHERE selected_flag = 1 AND shift_id = ?");
    $stmt->bind_param("i", $shift_id);
    $stmt->execute(); 
    $stmt->close();

    $stmt = $conn->prepare("UPDATE shift_element SET is_finished = 0 WHERE shift_id = ?");
    $stmt->bind_param("i", $shift_id);
    $stmt->execute(); 
    $stmt->close();
}

function shift_submit($conn,$shift_id){
    $stmt = $conn->prepare("UPDATE shift_element SET is_finished = 1 WHERE shift_id = ?");
    $stmt->bind_param("i", $shift_id);
    $stmt->execute(); 
    $stmt->close();
}

function shift_release($conn,$shift_id){
    $stmt = $conn->prepare("UPDATE shift_element SET is_finished = 2 WHERE shift_id = ?");
    $stmt->bind_param("i", $shift_id);
    $stmt->execute(); 
    $stmt->close();
}