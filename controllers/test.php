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


$success_flag = false;

if(is_array($request_data)){
    foreach($request_data as $val){
        $black_user_id = $val['UserId'];
        $nick_name = $val['NickName'];
        $color_code = $val['ColorCode'];
        $black_rank = $val['BlackRank'];
        $user_id = $val['MyId'];
        $group_id = $val['GroupId'];
        $sql = "INSERT INTO black_list(user_id,black_user_id,group_id,black_rank,color_code)VALUES(?,?,?,?,?)ON DUPLICATE KEY UPDATE user_id =?,black_user_id=?,group_id=?,black_rank=?,color_code=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiisiiiis",$user_id,$black_user_id,$group_id,$black_rank,$color_code,$user_id,$black_user_id,$group_id,$black_rank,$color_code);
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