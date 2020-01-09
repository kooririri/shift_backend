<?php
// require './env.php';
// require './controllers/shift_controller.php';

// header("Content-Type: application/json; charset=UTF-8");
// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// // クライアントからのリクエストデータ(json)をデコードして、phpのオブジェクトに格納
// $request_json = file_get_contents('php://input');
// $request_data = json_decode($request_json, TRUE);

// $request_uri = $_SERVER['REQUEST_URI'];
// $request_method = $_SERVER['REQUEST_METHOD'];
// $shift_type_id = 1;


// // mysqlのコネクション
// $conn = new mysqli(DB_SERVER_NAME, USERNAME, PASSWORD, DB_NAME);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }


// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }


// if (preg_match('/^\/shift$/', $request_uri)) {
//     $shift_id = 1;
//     switch ($request_method) {
//         case 'GET':
//             echo get_shift_type($conn,$shift_id);
//             break;
//         case 'POST':
//             echo "POST";
//             break;
//     }

// }
// // ここでクローズしておけば、間違いないよね
// $conn->close();
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
$shift_type_id = 1;


// mysqlのコネクション
$conn = new mysqli(DB_SERVER_NAME, USERNAME, PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if($request_method === 'GET'){
    $sql = "SELECT * FROM shift_element se LEFT JOIN shift_type st ON se.shift_id = st.shift_id WHERE se.shift_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $shift_type_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $response_data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response_data[] = [
                'type_id' => $row['type_id'],
                'shift_id' => $row['shift_id'],
                'begin_time' => $row['begin_time'],
                'end_time' => $row['end_time'],
                'type_name' => $row['type_name'],
                'comment' => $row['comment'],
                'shift_name' => $row['shift_name'],
                'shift_month' => $row['shift_month'],
                'submit_time' => $row['submit_time'],
                'modify_time' => $row['modify_time'],
                'release_time' => $row['release_time'],
                'modify_version' => $row['modify_version'],
                'version' => $row['version']
            ];
        }
    }
    $stmt->close();
    echo json_encode(array('list' => $response_data));
}elseif($request_method === 'POST'){
    $shift_request_data = $request_data['submitJson'];
    // var_dump($shift_request_data);
}