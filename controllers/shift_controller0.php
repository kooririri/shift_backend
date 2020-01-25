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


$user_id = $request_data['user_id'];
if($request_method === 'POST'){
    $sql = "SELECT * FROM group_member gm LEFT JOIN shift_group sg ON sg.group_id = gm.group_id WHERE gm.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $response_data = [];
    while ($row = $result->fetch_assoc()) {
        $response_data[] = [
            'group_id' => $row['group_id'],
            'name' => $row['name'],
        ];
    }

    echo json_encode(array('list' => $response_data));
}




$conn->close();