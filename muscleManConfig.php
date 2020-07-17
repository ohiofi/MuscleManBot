<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// muscleManConfig.php
// by Justin Riley
// 2017.04.28

$consumer_key = "xxx";
$consumer_secret = "xxx";
$access_key = "xxx";
$access_secret = "xxx";

function punctCut($inputString)
{
  if( ($stringPosition = strpos($inputString, "U.S. ")) !== FALSE )// Convert " U.S. " to " US "
  	$status = str_replace("U.S. ","US ",$inputString);
  if( ($stringPosition = strpos($inputString, "?")) !== FALSE )// Cut at "?"
    $inputString = strstr($inputString,"?",true);
  if( ($stringPosition = strpos($inputString, ".")) !== FALSE )// Cut at "."
    $inputString = strstr($inputString,".",true);
  if( ($stringPosition = strpos($inputString, "!")) !== FALSE )// Cut at "!"
    $inputString = strstr($inputString,"!",true);
  if( ($stringPosition = strpos($inputString, "|")) !== FALSE )// Cut at "|"
    $inputString = strstr($inputString,"|",true);
  if( ($stringPosition = strpos($inputString, "http")) !== FALSE )// Cut at "!"
    $inputString = strstr($inputString,"http",true);
  if( ($stringPosition = strpos($inputString, "www")) !== FALSE )// Cut at "!"
    $inputString = strstr($inputString,"www",true);
  if( ($stringPosition = strpos($inputString, ":")) !== FALSE )// Cut at ":"
    $inputString = strstr($inputString,":",true);
  if( ($stringPosition = strpos($inputString, " himself ")) !== FALSE )// Convert " his " to " her "
    $inputString = str_replace(" himself "," herself ",$inputString);
  if( ($stringPosition = strpos($inputString, " his ")) !== FALSE )// Convert " his " to " her "
  	$inputString = str_replace(" his "," her ",$inputString);
  if( ($stringPosition = strpos($inputString, " he ")) !== FALSE )// Convert " he " to " she "
    $inputString = str_replace(" he "," she ",$inputString);
  if( ($stringPosition = strpos($inputString, " man ")) !== FALSE )// Convert " he " to " she "
    $inputString = str_replace(" man "," woman ",$inputString);

  $inputString = trim($inputString);
  if(strlen($inputString) > 280)
    $inputString = substr($inputString, 0, 275);// If tweet is too long, shorten it.
  return $inputString;
}



require_once('twitteroauth.php');// Use the twitteroauth library
$lastTweetScreenName=NULL;
$twitter = new TwitterOAuth ($consumer_key ,$consumer_secret , $access_key , $access_secret );// Connect
$myUserInfo = $twitter->get('account/verify_credentials');// Get the authorized user's info
$myLastTweet = $twitter->get('statuses/user_timeline', array('user_id' => $myUserInfo->id_str, 'count' => 1));// Use user info to get user's last tweet
$timeline = $twitter->get('statuses/home_timeline', ["count" => 20, "exclude_replies" => true, 'since_id' => $myLastTweet[0]->id_str]);// Get most recent 20 posts from users I follow

$newTweetCount = 0;// Set the tweets count to zero
$keywordFound = FALSE;// Assume that the tweet DOESN'T contain one of the keywords
foreach($timeline as $tweet) {// Loop the following for each search result
  $myTweet = (string)$tweet->text;
  if( (($stringPosition = strpos($myTweet, " is ")) !== FALSE )){// if the tweet contains is

    $myTweet = "You know who else" . strstr($myTweet, " is ");
    $myTweet = punctCut($myTweet) . "?";
    $keywordFound = TRUE;

  } elseif((($stringPosition = strpos($myTweet, " isn't ")) !== FALSE )){// if the tweet contains isn't

        $myTweet = "You know who else" . strstr($myTweet, " isn't ");
        $myTweet = punctCut($myTweet) . "?";
        $keywordFound = TRUE;

  } elseif((($stringPosition = strpos($myTweet, " will ")) !== FALSE )){// if the tweet contains will

        $myTweet = "You know who else" . strstr($myTweet, " will ");
        $myTweet = punctCut($myTweet) . "?";
        $keywordFound = TRUE;

  } elseif((($stringPosition = strpos($myTweet, " won't ")) !== FALSE )){// if the tweet contains won't

        $myTweet = "You know who else" . strstr($myTweet, " won't ");
        $myTweet = punctCut($myTweet) . "?";
        $keywordFound = TRUE;

  } elseif((($stringPosition = strpos($myTweet, " wants ")) !== FALSE )){// if the tweet contains wants

        $myTweet = "You know who else" . strstr($myTweet, " wants ");
        $myTweet = punctCut($myTweet) . "?";
        $keywordFound = TRUE;

  } elseif((($stringPosition = strpos($myTweet, " can ")) !== FALSE )){// if the tweet contains can

        $myTweet = "You know who else" . strstr($myTweet, " can ");
        $myTweet = punctCut($myTweet) . "?";
        $keywordFound = TRUE;

  } elseif((($stringPosition = strpos($myTweet, " can't ")) !== FALSE )){// if the tweet contains can't

        $myTweet = "You know who else" . strstr($myTweet, " can't ");
        $myTweet = punctCut($myTweet) . "?";
        $keywordFound = TRUE;

  } elseif((($stringPosition = strpos($myTweet, " made ")) !== FALSE )){// if the tweet contains made

        $myTweet = "You know who else" . strstr($myTweet, " made ");
        $myTweet = punctCut($myTweet) . "?";
        $keywordFound = TRUE;

  } elseif((($stringPosition = strpos($myTweet, " got ")) !== FALSE )){// if the tweet contains just got

        $myTweet = "You know who else" . strstr($myTweet, " got ");
        $myTweet = punctCut($myTweet) . "?";
        $keywordFound = TRUE;

  } elseif((($stringPosition = strpos($myTweet, " had ")) !== FALSE )){// if the tweet contains just got

        $myTweet = "You know who else" . strstr($myTweet, " had ");
        $myTweet = punctCut($myTweet) . "?";
        $keywordFound = TRUE;

  } elseif((($stringPosition = strpos($myTweet, " went ")) !== FALSE )){// if the tweet contains went

        $myTweet = "You know who else" . strstr($myTweet, " went ");
        $myTweet = punctCut($myTweet) . "?";
        $keywordFound = TRUE;


  } else {// else exit
    echo "<br/>";echo "No keyword found?!?!?! :-(";echo "<br/>";
    $keywordFound = FALSE;
  }



  if($keywordFound == TRUE){
    //Profanity or offensive phrase checker
    //if contains suicide or murder or rape or etc
    $lower_tweet_str = strtolower((string)$myTweet);// make it lowercase, because strpos() is faster than stripos()
    if( (($stringPosition = strpos($lower_tweet_str, "kill")) == FALSE)
    AND (($stringPosition = strpos($lower_tweet_str, "suicide")) == FALSE)
    AND (($stringPosition = strpos($lower_tweet_str, "murder")) == FALSE)
    AND (($stringPosition = strpos($lower_tweet_str, "rape")) == FALSE)
    AND (($stringPosition = strpos($lower_tweet_str, "death")) == FALSE)
    AND (($stringPosition = strpos($lower_tweet_str, "dead")) == FALSE)
    AND (($stringPosition = strpos($lower_tweet_str, "abuse")) == FALSE)
    AND (($stringPosition = strpos($lower_tweet_str, "attack")) == FALSE)
    AND (($stringPosition = strpos($lower_tweet_str, "breathe")) == FALSE)
    AND (($stringPosition = strpos($lower_tweet_str, "covid")) == FALSE)
    AND (($stringPosition = strpos($lower_tweet_str, "virus")) == FALSE)
    AND (($stringPosition = strpos($lower_tweet_str, "gay")) == FALSE)
    AND (($stringPosition = strpos($lower_tweet_str, "lgbt")) == FALSE)
    AND (($stringPosition = strpos($lower_tweet_str, "trans")) == FALSE)
    AND (($stringPosition = strpos($lower_tweet_str, "protest")) == FALSE)
    AND (($stringPosition = strpos($lower_tweet_str, "racist")) == FALSE)
    AND (($stringPosition = strpos($lower_tweet_str, "racism")) == FALSE)
    AND (($stringPosition = strpos($lower_tweet_str, "nazi")) == FALSE)
    AND (($stringPosition = strpos($lower_tweet_str, "hitler")) == FALSE)
    AND (($stringPosition = strpos($lower_tweet_str, "black lives matter")) == FALSE)
    AND (($stringPosition = strpos($lower_tweet_str, "abortion")) == FALSE)){// Continue only if the tweet does NOT contain these blacklisted words
      // Output
  	  echo "<br/>";echo $myTweet;echo "<br/>";
      $replyTweet = $myTweet." https://twitter.com/".$tweet->user->screen_name."/status/".$tweet->id_str;// Append the URL of the @ mention
      $twitter->post('statuses/update', array('status' => $replyTweet,'in_reply_to_status_id' => $tweet->id_str));// Post tweet
  	  //$result = $twitter->post('statuses/update', array('status' => $myTweet));
  	  //echo $result;
  	  echo "<br/>";print_r($result);
      $newTweetCount++;
      break;
    }
  }
}
echo "Success! Check your twitter bot for ".$newTweetCount." new tweets.";
?>
