<?php
include("../textparse/textparse.php");
// The Google Plus hashtag to flash on, without the # prefix
define("GPLUS_HASHTAG","xmaspilights");
// Google Plus API key
define("GPLUS_API_KEY","");

$lasttime = 1;
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://www.googleapis.com/plus/v1/activities?query=%23'.GPLUS_HASHTAG.'&maxResults=1&key='.GPLUS_API_KEY
));
//date_default_timezone_set('Europe/Berlin');
while (true) {
	$time = time();
	$plus = curl_exec($curl);
	$plus = json_decode($plus);
	$item = $plus->items[0];
	$pub = strtotime($item->published);
	if ($pub > $lasttime) {
		$lasttime = $pub;
		if ($pub > ($time - 10)) {
			$content = str_replace("\xef\xbb\xbf","",trim(strtolower(strip_tags($item->object->content))));
			$content = explode(" ",$content);
			foreach ($content as $k=>$v) {
				if ($v === "#".GPLUS_HASHTAG) parseCommand("star5");
			}
		}
	}
	sleep(10);
}
