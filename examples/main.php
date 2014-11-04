<?php
  echo "<BR><BR>";


  echo "<TABLE border=0><TR>";
  echo "<TD valign=top>";

    if(($enddate < time()) && ($chkdiff > 0) && ($hsc != 1)) {

      echo "<blink><h2><span style=\"color: red;\">If you see this message, you are not checked in!</span></h2></blink>";

    }
    echo "<h1>Hearthstone Open Tournament News</h1>";

    if($loggedin != "true") {
      echo "<div class=\"signupbutton\" style=\"width:175;\"><a href=\"index.php?pr=5\" style=\"color: white;\">Register to play!</a></div><BR>";
    } else {

      echo "<DIV class=\"nexttourney\">";
      echo "<CENTER><h2>Next Tournament Information</h2></center>";

      echo "<table width=100%>";
        $nextnice = date("l, M jS. g:ia", $tstartdate);
        $nextchkin = date("l, M jS. g:ia", $enddate);
        if($restricteddeck == 0) {
          $ntourneyformat = "No deck submissions";
        } else if ($restricteddeck == 1) {
          $ntourneyformat = "Pre-submitted decks only";
        } else if ($restricteddeck == 2) {
          $ntourneyformat = "Arena style deck drafting";
        }

        echo "<TR><TD>Next tournament:</TD><TD>$nexttourneyname</TD><TD>Tournament Format:</TD><TD>$ntourneyformat</TD></TR>";

        if($restricteddeck == 0) {
          echo "<TR><TD>Start time:</TD><TD>$nextnice US/Eastern</TD><TD>Deck Builder:</TD><TD>Not for this tournament</TD></TR>";
        } else if ($restricteddeck == 1) {
          echo "<TR><TD>Start time:</TD><TD>$nextnice US/Eastern</TD><TD>Deck Builder:</TD><TD><a href=\"http://hearthstoneopen.com/index.php?pr=18&db=true\">Deck Builder</a></TD></TR>";
        } else if ($restricteddeck == 2) {
          echo "<TR><TD>Start time:</TD><TD>$nextnice US/Eastern</TD><TD>Deck Builder:</TD><TD><a href=\"http://hearthstoneopen.com/index.php?pr=18&ad=true&tid=$ntourneyid\">Arena Deck Builder</a></TD></TR>";
        }
        if(($diff < 0) && ($chkdiff > 0)) {
          echo "<TR><TD>Check-in begins:</TD><TD>$nextchkin US/Eastern (<a href=\"index.php?pr=77\">Check in!</a>)</TD><TD>Your check-in status:</td><TD>";
        } else {
          echo "<TR><TD>Check-in begins:</TD><TD>$nextchkin US/Eastern</TD><TD>Your check-in status:</td><TD>";
        }
        if($hsc == 1) {
          echo "You are checked in.";
        } else {
          echo "You are NOT checked in.";
        }
        echo "</TD></TR>";
        echo "<TR><TD colspan=4 align=center>Follow us on Twitter: <a href=\"https://twitter.com/HearthstoneOpen\" target=\"_blank\">@HearthstoneOpen</a></TD></TR>";

      echo "</table>";
      echo "</DIV><BR>";


    }

    $query = "SELECT * FROM hs_frontpage ORDER BY pubdate DESC LIMIT 5";
    $query_result_handle = mysql_query ($query);

    for ($count = 0; $row = mysql_fetch_row ($query_result_handle); ++$count) {
      $blogid = $row[0];
      $blogentry = $row[1];
      $nicedate = date("l, F jS. g:ia", $row[2]);
      $title = $row[3];

//      echo "<BR>";
//      echo "$nicedate ";
//      if($hsa >= 5) {
//        echo "[<a href=\"index.php?pr=3&be=true&editblog=true&editid=$blogid\">edit</a>]";
//      }
//      echo "<BR>";
//      echo "$blogentry";

      echo "<div class=\"blogpost\">";
      if($title != "") {
        echo "<h2>$title</h2>";
        echo "$nicedate<BR>";
        echo "$blogentry";
      } else {
        echo "$blogentry";
        echo "$nicedate<BR>";
      }
      echo "</div><BR>";
    }




  echo "</TD><TD valign=top width=400>";

    echo "<TABLE id=\"hor-minimalist-b\">";
    echo "<TR><TD><center><span style=\"font-size: 2em;\">Upcoming Tournaments</span></center></TD></TR>";
    echo "<TR><TD align=center>Tournament [region] (#players)<BR>Date (All times " . date("e") . ")</TD></TR>";

    if($diff < 0) {
      $numcheckedin = $numchk;
    }
    $displayed=0;

    $query = "SELECT * FROM hs_tsinfo";
    $query_result_handle = mysql_query ($query);
    for ($count = 0; $row = mysql_fetch_row ($query_result_handle); ++$count) {
      $tsinfo_list[$row[1]] = $row[2];
    }


    foreach($tseries_list as $upcoming) {
      if((($upcoming[3]) + (60 * 60 * 12)) > time()) {
        if(($displayed == 0)&&($numcheckedin > 0)&&($diff < 0)) {
//          if(!$tsinfo_list[$upcoming[2]]) {
//            echo "<TR><TD>$upcoming[1] [$upcoming[4]] ($numcheckedin)<BR>" . date("l, M jS. g:ia", $upcoming[3]) . "</TD></TR>";
//          } else {
            echo "<TR><TD><a href=\"index.php?pr=97&tsinfo=$upcoming[2]\">$upcoming[1]</a> [$upcoming[4]] ($numcheckedin)<BR>" . date("l, M jS. g:ia", $upcoming[3]) . "</TD></TR>";
//          }
          $displayed = 1;
        } else {
//          if(!$tsinfo_list[$upcoming[2]]) {
//            echo "<TR><TD>$upcoming[1] [$upcoming[4]]<BR>" . date("l, M jS. g:ia", $upcoming[3]) . "</TD></TR>";
//          } else {
            echo "<TR><TD><a href=\"index.php?pr=97&tsinfo=$upcoming[2]\">$upcoming[1]</a> [$upcoming[4]]<BR>" . date("l, M jS. g:ia", $upcoming[3]) . "</TD></TR>";
//          }
        }
      }
    }
    echo "<TR><TD align=right>";
    ?>
      <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
      <!-- Small-Banner -->
      <ins class="adsbygoogle"
         style="display:inline-block;width:320px;height:50px"
         data-ad-client="ca-pub-7323686333684602"
         data-ad-slot="5854475819"></ins>
      <script>
      (adsbygoogle = window.adsbygoogle || []).push({});
      </script>
    <?php
    echo "</TD></TR>";
    echo "</TABLE>";

    include 'quiz.php';

  echo "</TD></TR></TABLE>";
?>