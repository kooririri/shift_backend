<?php
require "../init.php";
require "./templates/index.php";
if($_POST){
    data_clear($conn,3,1);
}

function data_clear($conn,$shift_id,$user_id){
    $stmt = $conn->prepare("DELETE FROM shift_request WHERE shift_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $shift_id,$user_id);
    $stmt->execute();
    $stmt->close();
}