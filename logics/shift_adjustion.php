<?php

function get_shift_detail_by_date($conn,$shift_id,$date){
    $sql = "SELECT * FROM shift_detail sd LEFT JOIN shift_type st ON sd.type_id = st.type_id WHERE sd.shift_id = ? AND sd.date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is",$shift_id,$date);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
  

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'count' => $row['count'],
            'type_name' => $row['type_name'],
            'type_color' => $row['type_color'],
        ];
    }
    return $data;
}