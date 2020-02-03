<?php

function get_all_group_by_user_id($conn,$user_id){
    $sql = "SELECT * FROM shift_group sg LEFT JOIN group_member gm ON sg.group_id = gm.group_id WHERE gm.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$user_id);
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

function get_all_shift_element_by_group_id($conn,$group_id){
    $sql = "SELECT * FROM shift_element WHERE group_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$group_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
  

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'shift_id' => $row['shift_id'],
            'shift_name' => $row['shift_name'],
            'shift_month' => $row['shift_month'],
            'submit_time' => $row['submit_time'],
            'modify_time' => $row['modify_time'],
            'release_time' => $row['release_time'],
        ];
    }
    return $data;
}

function get_shift_element($conn,$shift_id){
  $sql = "SELECT * FROM shift_element WHERE shift_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i",$shift_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();


  $data = [];
  while ($row = $result->fetch_assoc()) {
      $data = [
          'is_finished' => $row['is_finished'],
      ];
  }
  return $data;
}


function get_days_by_shift_month($conn,$shift_id){
  $sql = "SELECT * FROM shift_element WHERE shift_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $shift_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();
  $shift_month = [];
  while ($row = $result->fetch_assoc()) {
      $shift_month = [
          'shift_month' => $row['shift_month'],
      ];
  }

  $date = substr($shift_month['shift_month'],0,8);
  //シフト月の日にちのarray
  $shift_dates = array();
  $days = date('t', strtotime($shift_month['shift_month']));
  for($i = 1;$i<=$days;$i++){
      $str = $i;
      if($i < 10){
          $str = "0".$i;
      }
      $temp = $date.$str;
      $shift_dates[] = $temp;
  }
  return $shift_dates;
}
function LOGIC_get_member_by_shift_id($conn,$shift_id)
{
    $sql = "SELECT * FROM shift_element se LEFT JOIN group_member gm ON se.group_id = gm.group_id LEFT JOIN user u ON gm.user_id = u.user_id WHERE se.shift_id = ? AND u.authority = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $shift_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'user_id' => $row['user_id'],
            'nickname' => $row['nickname'],
            'rank' => $row['evaluation'],
        ];
    }
    return $data;
}
function get_staff_number_requirement_by_date($conn,$shift_id,$date){
  $sql = "SELECT total_number FROM shift_detail WHERE shift_id = ? AND date = ? limit 1";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("is",$shift_id,$date);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();


  $data = [];
  while ($row = $result->fetch_assoc()) {
      $data = [
          'total_number' => $row['total_number'],
      ];
  }
  return $data;
}

function get_staff_number_by_date($conn,$shift_id,$date){
  $sql = "SELECT COUNT(user_id) AS count FROM shift_creation WHERE shift_id = ? AND date = ? AND selected_flag = 1";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("is",$shift_id,$date);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();


  $data = [];
  while ($row = $result->fetch_assoc()) {
      $data = [
          'count' => $row['count'],
      ];
  }
  return $data;
}

function get_shift_for_eachday($conn,$shift_id,$user_id,$date)
{
    $sql = "SELECT * FROM shift_creation sc LEFT JOIN user u ON sc.user_id = u.user_id LEFT JOIN shift_type st ON sc.type_id = st.type_id WHERE sc.shift_id = ? AND sc.user_id = ? AND sc.date = ? AND (selected_flag = 1)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $shift_id,$user_id,$date);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data = [
            'id' => $row['id'],
            'user_id' => $row['user_id'],
            'nickname' => $row['nickname'],
            'rank' => $row['evaluation'],
            'type_id' => $row['type_id'],
            'type_name' => $row['type_name'],
            'type_color' => $row['type_color'],
            'selected_flag' => $row['selected_flag'],
            'date' => $row['date'],
        ];
    }

    return $data;
}
function find_abled_staff_by_date($conn,$user_id,$date,$selected_flag){
  if($selected_flag = -1){
      $sql = "SELECT * FROM shift_request sr LEFT JOIN shift_type st ON sr.type_id = st.type_id WHERE user_id = ? AND date = ? AND selected_flag != 0 AND selected_flag != 2";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("is",$user_id,$date);
      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();
  }else{
      $sql = "SELECT * FROM shift_request sr LEFT JOIN shift_type st ON sr.type_id = st.type_id WHERE user_id = ? AND date = ? AND selected_flag = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("isi",$user_id,$date,$selected_flag);
      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();
  }
 


  $data = [];
  while ($row = $result->fetch_assoc()) {
      $data[] = [
          'user_id' => $row['user_id'],
          'date' => $row['date'],
          'shift_id' => $row['shift_id'],
          'type_id' => $row['type_id'],
          'selected_flag' => $row['selected_flag'],
          'type_name' => $row['type_name'],
          'type_color' => $row['type_color'],
      ];
  }
  return $data;
}