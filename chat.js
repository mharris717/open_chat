var pageName = "index.php";
var messageIds = {};

// var myId = 1;
// var oppId = 2;

var myId = null;
var oppId = null;

function loadIds() {
  var chatDiv = $('#chat');
  myId = chatDiv.data('p1id');
  oppId = chatDiv.data('p2id');
}

var Chat = {
  sendMessage: function(sender,receiver,msg) {
    console.log("sending message "+msg);
    $.post(pageName, {p1id: myId, p2id: oppId, sendid: myId, msg: msg, action: "sendMessage"}, function(data) {
      console.log("chat post success");
    });
  },

  getMessages: function(sender,receiver,cb) {
    $.get(pageName, {p1id: myId, p2id: oppId, action: "getMessages"}, function(data) {
      data = JSON.parse(data);
      console.log("getMessages success " + data);
      var messages = data.messages;

      for(var i=0;i<messages.length;i++) {
        var message = messages[i];
        cb(message);
      }
    });
  },

  startMessagePolling: function(sender,receiver) {
    var pollOnce = function() {
      Chat.getMessages(sender,receiver,function(message) {
        Chat.addMessageToPage(message.id,sender,receiver,message.msg);
      })
    };

    pollOnce();
    setInterval(pollOnce,2000);
  },

  addMessageToPage: function(msgId,sender,receiver,msg) {
    if (!messageIds[msgId]) {
      $("#chat").chatbox("option", "boxManager").addMsg(sender, msg);
      messageIds[msgId] = true;
    }
    else {
      console.debug("dup message");
    }
  }
};

function setupChat() {
  loadIds();
  $("#chat").chatbox({id : "chat",
                      title : "Title",
                      user : "can be anything",
                      offset: 200,
                      messageSent: function(id, user, msg){
                           console.logoo("DOM " + id + " just typed in " + msg);
                           Chat.sendMessage(myId,oppId,msg)
                      }});

  Chat.startMessagePolling(myId,oppId);
}

$(function() {
  setupChat();
});