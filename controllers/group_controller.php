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
$user_id = 0;
if(isset($request_data['userId'])){
    $user_id = $request_data['userId'];
}
$post_no = 0;
if(isset($request_data['postNo'])){
    $post_no = $request_data['postNo'];
}
$group_id = 0;
if(isset($request_data['groupId'])){
    $group_id = $request_data['groupId'];
}
$list = array();
if(isset($request_data['list'])){
    $list = $request_data['list'];
}


if($request_method === 'POST'&&$post_no ===1){
    $sql = "SELECT sg.name AS group_name,sg.group_id AS group_id FROM group_member gm LEFT JOIN shift_group sg ON gm.group_id = sg.group_id WHERE gm.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $response_data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response_data[] = [
                'group_name' => $row['group_name'],
                'group_id' => $row['group_id'],
            ];
        }
    }
    $stmt->close();
    echo json_encode(array('result' => $response_data));
}




if($request_method === 'POST'&&$post_no ===2){
    $stmt = $conn->prepare("SELECT * FROM black_list b LEFT JOIN user u ON b.black_user_id = u.user_id WHERE b.user_id = ? AND b.group_id = ? AND u.authority = 0");
    $stmt->bind_param("ii", $user_id,$group_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $response_data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response_data[] = [
                'user_id' => $row['user_id'],
                'group_member_id' => $row['black_user_id'],
                'black_rank' => $row['black_rank'],
                'nickname' => $row['nickname'],
                'color_code' => $row['color_code'],
            ];
        }
    }
    if(!empty($response_data)){
        echo json_encode(array('result' => $response_data));
    }else{
        $sql = "SELECT u.nickname AS nickname,u.user_id AS group_member_id FROM group_member gm LEFT JOIN user u ON gm.user_id = u.user_id WHERE gm.group_id = ? AND u.authority = 0 AND gm.user_id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $group_id,$user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $response_data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $response_data[] = [
                    'nickname' => $row['nickname'],
                    'group_member_id' => $row['group_member_id'],
                    'black_rank' => 0,
                    'color_code' => $row['color_code'],
                ];
            }
        }
        $stmt->close();
        echo json_encode(array('result' => $response_data));
    }
}



// $post_no = 0;
// if(array_key_exists("postNo",$request_data)){
//     $post_no = $request_data['postNo'];
// }
// $logined_id = 0;
// if(array_key_exists("userId",$request_data)){
//     $logined_id = $request_data['userId'];
// }
$success_flag = false;
if($request_method === 'POST'&&$post_no ===3){
    foreach($list as $val){
        $black_user_id = $val['UserId'];
        $nick_name = $val['NickName'];
        $color_code = $val['ColorCode'];
        $black_rank = $val['BlackRank'];
        $id = $val['Id'];
        $sql = "INSERT INTO black_list(user_id,black_user_id,group_id,black_rank)VALUES(?,?,?,?)ON DUPLICATE KEY UPDATE user_id =?,black_user_id=?,group_id=?,black_rank=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiiiiii",$user_id,$black_user_id,$group_id,$black_rank,$user_id,$black_user_id,$group_id,$black_rank);
        if($stmt->execute()){
            $success_flag = true;
        }
        $stmt->close();
    }
    if($success_flag){
        echo json_encode([
            'status' => 'ok'
        ]);
    }else{
        echo json_encode([
            'status' => 'ng'
        ]);
    }
}



$conn->close();