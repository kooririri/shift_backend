<?php
require "../init.php";
require "../logics/shift_logic_output.php";



// require "../templates/shift_management.php";
//通过shift_id把每一天的要求取出
var_dump(LOGIC_make_shift($conn,1,202001));

