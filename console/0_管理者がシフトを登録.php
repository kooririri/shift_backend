<?php

require "../init.php";
require "../logics/shift_logic_output.php";

echo "シフト内容を入力してください";

i("グループID",$group_id);
i("シフト名",$shift_name);
i("何月のシフト",$shift_month);
i("提出期間",$submit_time);
i("修正期間",$modify_time);
i("発表期間",$release_time);

$all_input_data[] = [
    'group_id' => $group_id,
    'shift_name' => $shift_name,
    'shift_month' => $shift_month,
    'submit_time' => $submit_time,
    'modify_time' => $modify_time,
    'release_time' => $release_time,
];