<?php

// function cloud_messaging($title,$message){
//     $api_key  = "AIzaSyCqOmHnTckJcyJarZd1pHwdPJSZa7OACPk";
//     $base_url = "https://fcm.googleapis.com/fcm/send";

//     // toに指定しているのはトピック名:testに対して一括送信するという意味
//     // 個別に送信したい場合はここに端末に割り振られたトークンIDを指定する
//     $data = array(
//         "to"           => "/topics/test"
//         ,"priority"     => "high"
//         ,"notification" => array(
//                                 "title" => $title
//                                 ,"body"  => $message
//         )
//     );
//     $header = array(
//         "Content-Type:application/json"
//         ,"Authorization:key=".$api_key
//     );
//     $context = stream_context_create(array(
//         "http" => array(
//             'method' => 'POST'
//             ,'header' => implode("\r\n",$header)
//             ,'content'=> json_encode($data)
//         )
//     ));
//     file_get_contents($base_url,false,$context);
// }


?>