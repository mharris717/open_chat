<?php

function sendMessage($p1id,$p2id,$sendid,$msg) {
  $query = sprintf("INSERT into hs_oppchat (p1id,p2id,sendid,msg) VALUES (%s,%s,%s,'%s')",
    mysql_real_escape_string($p1id),
    mysql_real_escape_string($p2id),
    mysql_real_escape_string($sendid),
    mysql_real_escape_string($msg));
  $query_result_handle = mysql_query($query);
}

function getMessages($p1id,$p2id) {
  $query = sprintf("select id,msg from hs_oppchat where p1id = %s AND p2id = %s",
    mysql_real_escape_string($p1id),
    mysql_real_escape_string($p2id));
  $query_result_handle = mysql_query($query);

  $res = array();

  for ($count = 0; $row = mysql_fetch_row($query_result_handle); ++$count) {
    $msg = $row[1];
    $a = array('msg' => $msg);
    array_push($res,$a);
  }

  return $res;
}

$action = $_GET["action"];

if ($action == "getMessages") {
  $message = array('message' => 'hello', 'id' => 1);
  $a = array($message);
  $arr = array('messages' => $a);

  echo json_encode($arr);
}
else if ($action == "sendMessage") {
  sendMessage(1,2,1,"Hello");
}
else {
  include "page.php";
}




?>