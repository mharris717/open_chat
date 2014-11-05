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
  $all_messages = sprintf("select c.id,c.msg,c.sendid, 
    p1.reddit p1name, p2.reddit p2name, 
    case when c.p1id = c.sendid then p1.reddit else p2.reddit end sendname
    from hs_oppchat c
    INNER JOIN hs_users p1 on p1.id = c.p1id
    INNER JOIN hs_users p2 on p2.id = c.p2id
    where c.p1id = %s 
    AND c.p2id = %s
    order by c.id desc
    LIMIT 20",
    mysql_real_escape_string($p1id),
    mysql_real_escape_string($p2id));

  $query = "select all_messages.* 
  from (" . $all_messages . ") as all_messages 
  order by all_messages.id asc";
  $query_result_handle = mysql_query($query);

  $messages = array();

  for ($count = 0; $row = mysql_fetch_row($query_result_handle); ++$count) {
    $a = array('id' => $row[0], 'msg' => $row[1], 'sendid' => $row[2], 'p1name' => $row[3], 'p2name' => $row[4], 'sendname' => $row[5]);
    array_push($messages,$a);
  }

  $res = array('messages' => $messages);
  return $res;
}

if ($action == "getMessages") {
  $messages = getMessages($p1id,$p2id);
  echo json_encode($messages);
}
else if ($action == "sendMessage") {
  sendMessage($p1id,$p2id,$sendid,$msg);

  $messages = getMessages($p1id,$p2id);
  echo json_encode($messages);
}
else {
  PAGEHERE
}




?>