<html>
  <head>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />

    <style>
      /* style sheets */
      .ui-chatbox {
          position: fixed;
          bottom:0;
          padding: 2px;
          background:  #CCCCCC;
      }

      .ui-chatbox-titlebar {
          padding: 3px;
          height: 20px;
          cursor: pointer;
      }

      .ui-chatbox-content {
          padding: 0px;
          margin: 0px;
          border: 0px;
      }

      .ui-chatbox-log {
          padding: 3px;
          height: 250px;
          overflow-y: auto;
          overflow-x: hidden;
          background: #FFFFFF;
      }

      .ui-chatbox-input {
          padding: 3px;
          border-top: 1px solid grey;
          overflow: hidden;
      }

      .ui-chatbox-input-box {
          margin: 5px;
          border: 2px solid lightgrey;/* #6699FF */
          padding: 2px;
          height: 50px;
      }

      .ui-chatbox-icon {
          float: right;
      }

      .ui-chatbox-input-focus {
          border-color: #6699FF;
      }

      .ui-chatbox-msg {
          margin-top: 10px;
          float: left;
          clear: both;
          /* Source: http://snipplr.com/view/10979/css-cross-browser-word-wrap */
          white-space: pre-wrap;      /* CSS3 */
          white-space: -moz-pre-wrap; /* Firefox */
          white-space: -pre-wrap;     /* Opera <7 */
          white-space: -o-pre-wrap;   /* Opera 7 */
          word-wrap: break-word;      /* IE */
      }
    </style>

    <script type="text/javascript" src="http://magma.cs.uiuc.edu/wenpu1/js/jquery.ui.chatbox.js"></script>

    <script type="text/javascript">
      JSHERE
    </script>

  </head>

  <body>
    <h1>Hello</h1>

    <div id="debug"></div>

    <div id="chat" data-p1id="1" data-p2id="2" data-myid="1" data-oppid="2"></div>
  </body>
</html>

