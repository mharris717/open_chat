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
  $query = sprintf("select id,msg from hs_oppchat where p1id = %s AND p2id = %s LIMIT 500",
    mysql_real_escape_string($p1id),
    mysql_real_escape_string($p2id));
  $query_result_handle = mysql_query($query);

  $messages = array();

  for ($count = 0; $row = mysql_fetch_row($query_result_handle); ++$count) {
    $a = array('id' => $row[0], 'msg' => $row[1]);
    array_push($messages,$a);
  }

  $res = array('messages' => $messages);
  return $res;
}

$action = $_GET["action"];

if ($action == "getMessages") {
  $messages = getMessages(1,2);
  echo json_encode($messages);
}
else if ($action == "sendMessage") {
  sendMessage(1,2,1,"Hello");
}
else {
  PAGEHERE
}




?>