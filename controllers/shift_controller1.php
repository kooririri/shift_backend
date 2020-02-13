<?php
const DB_SERVER_NAME = 'localhost';
const USERNAME = 'root';
const PASSWORD = '';
const DB_NAME = 'shift';

// jsonを返す準備は整いました。
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// クライアントからのリクエストデータ(json)をデコードして、phpのオブジェクトに格納
$request_json = file_get_contents('php://input');
$request_data = json_decode($request_json, TRUE);

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];



// mysqlのコネクション
$conn = new mysqli(DB_SERVER_NAME, USERNAME, PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$group_id = $request_data['groupId'];
$user_id = $request_data['userId'];
$next_shift_month = date('Y-m-01', strtotime(date('Y-m-01').'+1 month'));
if($request_method === 'POST'){

    $shift_element_datas = get_shift_id_from_shift_element($conn,$next_shift_month,$group_id);
    $shift_type_datas = get_shift_type_by_group_id($conn,$group_id);
   
    //当这个月的shift已创建
    if(count($shift_element_datas) > 0){
        $shift_request_datas = get_shift_request($conn,$shift_element_datas['shift_id'],$user_id);
       
        //当这个月的shift没有做成
        if($shift_element_datas['is_finished'] == 0){     
            //已提交希望
            if(count($shift_request_datas)>0){
                echo json_encode(array('res_flg' => 1,'shift_id' => $shift_element_datas['shift_id'],'shift_type_datas' => $shift_type_datas,'shift_request_datas' => $shift_request_datas));
            }//没提交希望
            else{
                echo json_encode(array('res_flg' => 0,'shift_id' => $shift_element_datas['shift_id'],'shift_type_datas' => $shift_type_datas));
            }
        }//当这个月shift一时完成
        elseif($shift_element_datas['is_finished'] == 1){
            $black_datas = get_black_list($conn,$user_id);
            $sql = "SELECT * FROM shift_request WHERE shift_id = ? AND user_id = ? AND (selected_flag = 1 OR selected_flag = 9)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii",$shift_element_datas['shift_id'],$user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            $my_shift_request = [];
            while ($row = $result->fetch_assoc()) {
                $my_shift_request[] = [
                    'id' => $row['id'],
                    'user_id' => $row['user_id'],
                    'date' => $row['date'],
                    'shift_id' => $row['shift_id'],
                    'type_id' => $row['type_id'],
                    'selected_flag' => $row['selected_flag'],
                ];
            }
            $same_creation = array();
            $my_shift_creations = get_shift_creation_by_user_id($conn,$shift_element_datas['shift_id'],$user_id);
            for($i=0;$i<count($shift_request_datas);$i++){
                $date = $shift_request_datas[$i]['date'];
                $type_id = $shift_request_datas[$i]['type_id'];
                foreach($my_shift_creations as $val2){
                    if($val2['date'] == $date && $val2['type_id'] == $type_id){
                        $shift_request_datas[$i]['selected_flag'] = 8;//用于android，表示shift_creation第一次发表的shift
                    }
                }
            }
            for($i=0;$i<count($shift_request_datas);$i++){
                $date = $shift_request_datas[$i]['date'];
                $type_id = $shift_request_datas[$i]['type_id'];
                foreach($black_datas as $black_data){
                    $temp_val_by_date = get_shift_creation_by_user_id_and_date($conn,$shift_element_datas['shift_id'],$black_data['black_user_id'],$date);
                    $temp_val = get_shift_creation_by_user_id_and_date_and_type($conn,$shift_element_datas['shift_id'],$black_data['black_user_id'],$date,$type_id);
                    foreach($temp_val_by_date as $val){
                        if($date == $val['date']){
                            $shift_request_datas[$i]['kaburu_flag'] = 1;//日にちだけ被る場合
                        }
                    }
                    foreach($temp_val as $val){
                        if($date == $val['date']&&$type_id == $val['type_id']){
                            $shift_request_datas[$i]['kaburu_flag'] = 2;//日にちとタイプ完全に被る場合
                        }
                    }
                }
            }


            echo json_encode(array('res_flg' => 2,'shift_id' => $shift_element_datas['shift_id'],'same_creation' => $same_creation,'shift_type_datas' => $shift_type_datas,'black_list' => $black_datas,'shift_request_datas' => $shift_request_datas));
        }//完全確定
        elseif($shift_element_datas['is_finished'] == 2){
            $responsed_data = get_shift_request($conn,$shift_element_datas['shift_id'],$user_id);
            $test_datas = get_shift_creation_by_user_id($conn,$shift_element_datas['shift_id'],$user_id);
            for($i=0;$i<count($responsed_data);$i++){
                $shift_id = $responsed_data[$i]['shift_id'];
                $user_id = $responsed_data[$i]['user_id'];
                $type_id = $responsed_data[$i]['type_id'];
                $date = $responsed_data[$i]['date'];
                foreach($test_datas as $val){
                    if($type_id == $val['type_id'] && $date == $val['date']){
                        $responsed_data[$i]['selected_flag'] = 7;
                    }
                }
            }
            echo json_encode(array('res_flg' => 3,'shift_id' => $shift_element_datas['shift_id'],'shift_type_datas' => $shift_type_datas,'shift_request_datas' => $responsed_data));

        }
    }else{
        echo json_encode(array('res_flg' => 4));
    }
}

$conn->close();

function get_shift_id_from_shift_element($conn,$next_shift_month,$group_id){
    $sql = "SELECT * FROM shift_element WHERE shift_month = ? AND group_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si",$next_shift_month,$group_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $shift_element_datas = [];
    while ($row = $result->fetch_assoc()) {
        $shift_element_datas = [
            'shift_id' => $row['shift_id'],
            'is_finished' => $row['is_finished'],
        ];
    }
    return $shift_element_datas;
}

function get_shift_type_by_group_id($conn,$group_id){
    $sql = "SELECT * FROM shift_type WHERE group_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$group_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $shift_type_datas = [];
    while ($row = $result->fetch_assoc()) {
        $shift_type_datas[] = [
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
    return $shift_type_datas;
}

function get_shift_request($conn,$shift_id,$user_id){
    $sql = "SELECT * FROM shift_request WHERE shift_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii",$shift_id,$user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $shift_request_datas = [];
    while ($row = $result->fetch_assoc()) {
        $shift_request_datas[] = [
            'id' => $row['id'],
            'user_id' => $row['user_id'],
            'date' => $row['date'],
            'shift_id' => $row['shift_id'],
            'type_id' => $row['type_id'],
            'selected_flag' => $row['selected_flag'],
            'kaburu_flag' => $row['kaburu_flag'],
        ];
    }
    return $shift_request_datas;
}

function get_black_list($conn,$user_id){
    $sql = "SELECT * FROM black_list WHERE user_id = ? AND black_rank != 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $black_datas = [];
    while ($row = $result->fetch_assoc()) {
        $black_datas[] = [
            'black_user_id' => $row['black_user_id'],
            'black_rank' => $row['black_rank'],
        ];
    }
    return $black_datas;
}

function get_shift_creation_by_user_id($conn,$shift_id,$user_id){
    $sql = "SELECT * FROM shift_creation WHERE shift_id = ? AND user_id = ? AND selected_flag = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii",$shift_id,$user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $shift_creation_datas = [];
    while ($row = $result->fetch_assoc()) {
        $shift_creation_datas[] = [
            'id' => $row['id'],
            'user_id' => $row['user_id'],
            'date' => $row['date'],
            'shift_id' => $row['shift_id'],
            'type_id' => $row['type_id'],
            'selected_flag' => $row['selected_flag'],
        ];
    }
    return $shift_creation_datas;
}

function get_shift_creation_by_user_id_and_date_and_type($conn,$shift_id,$user_id,$date,$type_id){
    $sql = "SELECT * FROM shift_creation WHERE shift_id = ? AND user_id = ? AND date = ? AND type_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisi",$shift_id,$user_id,$date,$type_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $shift_creation_datas = [];
    while ($row = $result->fetch_assoc()) {
        $shift_creation_datas[] = [
            'id' => $row['id'],
            'user_id' => $row['user_id'],
            'date' => $row['date'],
            'shift_id' => $row['shift_id'],
            'type_id' => $row['type_id'],
            'selected_flag' => $row['selected_flag'],
        ];
    }
    return $shift_creation_datas;
}

function get_shift_creation_by_user_id_and_date($conn,$shift_id,$user_id,$date){
    $sql = "SELECT * FROM shift_creation WHERE shift_id = ? AND user_id = ? AND date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis",$shift_id,$user_id,$date);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $shift_creation_datas = [];
    while ($row = $result->fetch_assoc()) {
        $shift_creation_datas[] = [
            'id' => $row['id'],
            'user_id' => $row['user_id'],
            'date' => $row['date'],
            'shift_id' => $row['shift_id'],
            'type_id' => $row['type_id'],
            'selected_flag' => $row['selected_flag'],
        ];
    }
    return $shift_creation_datas;
}

function get_shift_request_by_user_id_selected_flag9($conn,$shift_id,$user_id){
    $sql = "SELECT * FROM shift_request WHERE shift_id = ? AND user_id = ? AND selected_flag = 9";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii",$shift_id,$user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $shift_creation_datas = [];
    while ($row = $result->fetch_assoc()) {
        $shift_creation_datas[] = [
            'id' => $row['id'],
            'user_id' => $row['user_id'],
            'date' => $row['date'],
            'shift_id' => $row['shift_id'],
            'type_id' => $row['type_id'],
            'selected_flag' => $row['selected_flag'],
            'kaburu_flag' => $row['kaburu_flag'],
        ];
    }
    return $shift_creation_datas;
}

