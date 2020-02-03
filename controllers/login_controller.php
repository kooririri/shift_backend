<?php
const DB_SERVER_NAME = 'localhost';
const USERNAME = 'root';
const PASSWORD = '';
const DB_NAME = 'shift';

// jsonを返す準備は整いました。
// header("Content-Type: application/json; charset=UTF-8");
// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// クライアントからのリクエストデータ(json)をデコードして、phpのオブジェクトに格納
// $request_json = file_get_contents('php://input');
// $request_data = json_decode($request_json, TRUE);

// $request_uri = $_SERVER['REQUEST_URI'];
// $request_method = $_SERVER['REQUEST_METHOD'];
// mysqlのコネクション
$conn = new mysqli(DB_SERVER_NAME, USERNAME, PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['mail'];
$password = $_POST['password'];
// $email = $request_data['user_name'];
// $password = $request_data['password'];

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode([
        'message' => 'メールアドレスとパスワードは必須です'
    ]);
}
$row = array(); 
$stmt = $conn->prepare("SELECT * FROM user WHERE email = ? AND password =? LIMIT 1");
$stmt->bind_param("ss", $email,$password);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $row['status'] = 1;
}
$stmt->close();

if(sizeof($row) > 0){
    echo json_encode($row);
}else{
    echo json_encode([
        'status' => 2
    ]);
}



$conn->close();
