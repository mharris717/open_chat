var pageName = "index.php";
var messageIds = {};

function getChatDiv() {
  return $("#chat");
}

var global_p1Id = null;
var global_p2Id = null;
var global_myId = null;
var global_oppId = null

function myLog(str) {
  console.log(str);
}

function loadIds() {
  var chatDiv = getChatDiv();
  global_p1Id = chatDiv.data('p1id');
  global_p2Id = chatDiv.data('p2id');
  global_myId = chatDiv.data('myid');
  global_oppId = chatDiv.data('oppid');
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
    myLog("sending message "+msg);
    var ids = fixIds(sender,receiver);

    var ops = {p1id: ids.p1id, p2id: ids.p2id, sendid: sender, msg: msg, action: "sendMessage"};
    myLog(ops);

    $.post(pageName, ops, function(data) {
      myLog("chat post success");
      Chat.pollForMessagesOnce(ids.p1id,ids.p2id);
    });
  },

  getMessages: function(sender,receiver,cb) {
    var ids = fixIds(sender,receiver);
    $.get(pageName, {p1id: ids.p1id, p2id: ids.p2id, action: "getMessages"}, function(data) {
      data = JSON.parse(data);
      myLog("getMessages success " + data);
      var messages = data.messages;

      for(var i=0;i<messages.length;i++) {
        var message = messages[i];
        cb(message);
      }
    });
  },

  pollForMessagesOnce: function(p1Id,p2Id) {
    Chat.getMessages(p1Id,p2Id,function(message) {
      Chat.addMessageToPage(message.id,message.sendname,message.msg);
    });
  },

  startMessagePolling: function(p1Id,p2Id) {
    var pollOnce = function() {
      Chat.pollForMessagesOnce(p1Id,p2Id);
    };

    pollOnce();
    setInterval(pollOnce,10000);
  },

  addMessageToPage: function(msgId,sender,msg) {
    if (!messageIds[msgId]) {
      getChatDiv().chatbox("option", "boxManager").addMsg(sender, msg);
      messageIds[msgId] = true;
    }
  },

  setup: function(p1Id,p2Id,myId,oppId) {
    getChatDiv().chatbox({id : "chat",
                        title : "Chat with Opponent",
                        user : "can be anything",
                        offset: 200,
                        messageSent: function(id, user, msg){
                             myLog("DOM " + id + " just typed in " + msg);
                             Chat.sendMessage(myId,oppId,msg)
                        }});

    Chat.startMessagePolling(p1Id,p2Id);
  },

  shouldUse: function() {
    return (getChatDiv().length > 0);
  }
};

$(function() {
  if (Chat.shouldUse()) {
    loadIds();
    Chat.setup(global_p1Id,global_p2Id,global_myId,global_oppId);
  }
});