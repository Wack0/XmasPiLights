<?php
// The Twitter hashtag to flash on, without the # prefix
define("TWITTER_HASHTAG","xmaspilights");
error_reporting(0);
ini_set("show_errors",false);
require_once('phirehose/lib/Phirehose.php');
require_once('phirehose/lib/OauthPhirehose.php');
include("../textparse/textparse.php");
class FilterTrackConsumer extends OauthPhirehose
{
  public function enqueueStatus($status)
  {
    $data = json_decode($status, true);
    if (is_array($data) && isset($data['user']['screen_name'])) {
	  print $data['user']['screen_name'] . ': ' . urldecode($data['text']) . "\n";
      $tweet = $data['text'];
	  $tweet = explode(" ",$tweet);
	  foreach ($tweet as $k=>$v)
		if (strtolower($v) == "#".TWITTER_HASHTAG) {
			parseCommand("star5");
		}
    }
  }
}
// The OAuth credentials you received when registering your app at Twitter
define("TWITTER_CONSUMER_KEY", "");
define("TWITTER_CONSUMER_SECRET", "");
// The OAuth data for the twitter account
define("OAUTH_TOKEN", "");
define("OAUTH_SECRET", "");
// Start streaming
$sc = new FilterTrackConsumer(OAUTH_TOKEN, OAUTH_SECRET, Phirehose::METHOD_FILTER);
$sc->setTrack(array(TWITTER_HASHTAG));
$sc->consume();
