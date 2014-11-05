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
  $query = sprintf("select c.id,c.msg,c.sendid, 
    p1.reddit p1name, p2.reddit p2name, 
    case when c.p1id = c.sendid then p1.reddit else p2.reddit end sendname
    from hs_oppchat c
    INNER JOIN hs_users p1 on p1.id = c.p1id
    INNER JOIN hs_users p2 on p2.id = c.p2id
    where c.p1id = %s 
    AND c.p2id = %s 
    LIMIT 500",
    mysql_real_escape_string($p1id),
    mysql_real_escape_string($p2id));
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
  ?>
<html>
  <head>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />

    <link type="text/css" href="http://magma.cs.uiuc.edu/wenpu1/css/jquery.ui.chatbox.css" rel="stylesheet" />
    <script type="text/javascript" src="http://magma.cs.uiuc.edu/wenpu1/js/jquery.ui.chatbox.js"></script>

    <script type="text/javascript">
      var pageName = "index.php";
var messageIds = {};

var global_p1Id = null;
var global_p2Id = null;
var global_myId = null;
var global_oppId = null

function loadIds() {
  var chatDiv = $('#chat');
  global_p1Id = chatDiv.data('p1id');
  global_p2Id = chatDiv.data('p2id');
  global_myId = chatDiv.data('myid');
  global_oppId = chatDiv.data('oppid');

  console.debug([global_p1Id,global_p2Id,global_myId,global_oppId]);
}

function safeParseInt(a) {
  if (a == null || a == undefined) throw "bad";
  var res = parseInt(a);
  if (isNaN(res)) throw "nan";
  return res;
}

function fixIds(a,b) {
  a = safeParseInt(a);
  b = safeParseInt(b);
  if (a == b) {
    throw "bad";
  }

  if (a > b) {
    return {p1id: b, p2id: a};
  }
  else {
    return {p1id: a, p2id: b};
  }
}

var Chat = {
  sendMessage: function(sender,receiver,msg) {
    console.log("sending message "+msg);
    var ids = fixIds(sender,receiver);

    var ops = {p1id: ids.p1id, p2id: ids.p2id, sendid: sender, msg: msg, action: "sendMessage"};
    console.debug(ops);

    $.post(pageName, ops, function(data) {
      console.log("chat post success");
    });
  },

  getMessages: function(sender,receiver,cb) {
    var ids = fixIds(sender,receiver);
    $.get(pageName, {p1id: ids.p1id, p2id: ids.p2id, action: "getMessages"}, function(data) {
      data = JSON.parse(data);
      console.log("getMessages success " + data);
      var messages = data.messages;

      for(var i=0;i<messages.length;i++) {
        var message = messages[i];
        cb(message);
      }
    });
  },

  startMessagePolling: function(p1Id,p2Id) {
    var pollOnce = function() {
      Chat.getMessages(p1Id,p2Id,function(message) {
        Chat.addMessageToPage(message.id,message.sendname,message.msg);
      })
    };

    pollOnce();
    setInterval(pollOnce,10000);
  },

  addMessageToPage: function(msgId,sender,msg) {
    if (!messageIds[msgId]) {
      $("#chat").chatbox("option", "boxManager").addMsg(sender, msg);
      messageIds[msgId] = true;
    }
    else {
      console.debug("dup message");
    }
  }
};

function setupChat(p1Id,p2Id,myId,oppId) {
  $("#chat").chatbox({id : "chat",
                      title : "Title",
                      user : "can be anything",
                      offset: 200,
                      messageSent: function(id, user, msg){
                           console.log("DOM " + id + " just typed in " + msg);
                           Chat.sendMessage(myId,oppId,msg)
                      }});

  Chat.startMessagePolling(p1Id,p2Id);
}

$(function() {
  loadIds();
  setupChat(global_p1Id,global_p2Id,global_myId,global_oppId);
});
    </script>

  </head>

  <body>
    <h1>Hello</h1>

    <div id="debug"></div>

    <div id="chat" data-p1id="1" data-p2id="2" data-myid="1" data-oppid="2"></div>
  </body>
</html>


<?php
}




?>