<?php
include("../textparse/textparse.php");
// The Instagram hashtag to flash on, without the # prefix
define("INSTA_HASHTAG","xmaspilights");
// Instagram API key
define("INSTA_API_KEY","");

$lasttime = 1;
$curl = curl_init();
//date_default_timezone_set('Europe/Berlin');
$tag = false;
while (true) {
	curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => 'https://api.instagram.com/v1/tags/'.INSTA_HASHTAG.'/media/recent?client_id='.INSTA_API_KEY.'&count=1'.(is_int($tag)?"&min_tag_id=".$tag:"")
	));
	$time = time();
	$plus = curl_exec($curl);
	$plus = json_decode($plus);
	if (count($plus->data) == 0) {
		sleep(1);
		continue;
	}
	$item = $plus->data[0];
	$pub = $item->created_time;
	if ($pub > $lasttime) {
		$lasttime = $pub;
		$tag = $plus->pagination->next_min_id;
		if ($pub > ($time - 10)) {
			$content = str_replace("\xef\xbb\xbf","",trim(strtolower(strip_tags($item->caption->text))));
			$content = explode(" ",$content);
			foreach ($content as $k=>$v) {
				if ($v === "#".INSTA_HASHTAG) parseCommand("star5");
			}
		}
	}
	sleep(1);
}
