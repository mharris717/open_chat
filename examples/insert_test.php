<?php

$query = 'INSERT into hs_oppchat (p1id,p2id,sendid,msg) VALUES (1,2,1,"Hello")';
$query_result_handle = mysql_query($query);

function sendMessage($p1id,$p2id,$sendid,$msg) {
  $query = sprintf("INSERT into hs_oppchat (p1id,p2id,sendid,msg) VALUES (%s,%s,%s,'%s')",
    mysql_real_escape_string($p1id),
    mysql_real_escape_string($p2id),
    mysql_real_escape_string($sendid),
    mysql_real_escape_string($msg));
  $query_result_handle = mysql_query($query);
}

sendMessage(1,2,1,"Hello");

$query = "SELECT count(*) cnt FROM hs_oppchat";
$query_result_handle = mysql_query($query);

for ($count = 0; $row = mysql_fetch_row($query_result_handle); ++$count) {
  $cnt = $row[0];
  echo $cnt;
}

echo "<br>";

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

$a = getMessages(1,2);
$c = count($a);
echo $c;
echo "<br>";

for ($i = 0; $i < count($a); $i++) {
  $row = $a[$i];
  echo $row['msg'];
  echo "<br>";
}

?>
