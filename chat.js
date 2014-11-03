var Chat = {
  sendMessage: function(sender,receiver,msg) {
    console.log("sending message "+msg);
    $.post("chat.php", {sender: sender, receiver: receiver, message: msg, action: "sendMessage"}, function(data) {
      console.log("chat post success");
    });
  },

  getMessages: function(sender,receiver,cb) {
    $.getJSON("chat.php", {sender: sender, receiver: receiver, action: "getMessages"}, function(data) {
      console.log("getMessages success " + data);
      var messages = data.messages;

      for(var i=0;i<messages.length;i++) {
        var message = messages[i];
        var body = message.message;
        console.log(body);
        cb(message);
      }
    });
  },

  startMessagePolling: function(sender,receiver) {
    var pollOnce = function() {
      Chat.getMessages(sender,receiver,function(message) {
        Chat.addMessageToPage(sender,receiver,message.message);
      })
    };

    pollOnce();
    setInterval(pollOnce,2000);
  },

  addMessageToPage: function(sender,receiver,msg) {
    $("#chat").chatbox("option", "boxManager").addMsg("Mr. Foo", msg);
  }
};

// function addMessage(msg) {
//   $("#debug").append(msg+"<br>");
//   $("#chat").chatbox("option", "boxManager").addMsg("Mr. Foo", msg);
// }

function setupChat() {
  $("#chat").chatbox({id : "chat",
                      title : "Title",
                      user : "can be anything",
                      offset: 200,
                      messageSent: function(id, user, msg){
                           console.log("DOM " + id + " just typed in " + msg);
                           Chat.sendMessage("me","them",msg)
                      }});

  Chat.startMessagePolling("me","them");
}

$(function() {
  setupChat();

  // addMessage("Hello Friend");

  // setTimeout(function() {
  //   addMessage('More');
  // },1000);
});