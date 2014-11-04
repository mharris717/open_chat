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
        Chat.addMessageToPage(message.id,message.sendid,message.msg);
      })
    };

    pollOnce();
    setInterval(pollOnce,2000);
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