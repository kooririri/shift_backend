<?php
//同じ名前のグループがあるかどうかを確認
function check_group_name($conn,$name){
    $sql = "SELECT * FROM shift_group WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s",$name);
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

//shift_groupマスタに書き込む
function insert_shift_group($conn,$name){
    $stmt = $conn->prepare("INSERT INTO shift_group (name) VALUES (?)");
    $stmt->bind_param("s",$name);
    $stmt->execute();

    $stmt->close();
}