<?php
const DB_SERVER_NAME = 'localhost';
const USERNAME = 'root';
const PASSWORD = '';
const DB_NAME = 'shift';

// jsonを返す準備は整いました。本番環境だと、この辺の設定がもう少し増えますね。藤原もあんま分かってない。
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

if($request_method === 'POST'){
    foreach($request_data as $val){
        $user_id = $val['UserId'];
        $date = $val['Date'];
        $id = $val['Id'];
        $shift_id = $val['ShiftId'];
        $type_id = $val['ShiftTypeId'];
        $selected_flag = $val['SelectedFlag'];
        $sql = "INSERT INTO shift_request(id,user_id,date,shift_id,type_id,selected_flag)VALUES(?,?,?,?,?,?)ON DUPLICATE KEY UPDATE user_id =?,date=?,shift_id=?,type_id=?,selected_flag=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisiiiisiii",$id,$user_id,$date,$shift_id,$type_id,$selected_flag,$user_id,$date,$shift_id,$type_id,$selected_flag);
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