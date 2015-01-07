<?php

// IRC settings
define("IRC_SERVER","irc.ringoflightning.net"); // Server
define("IRC_NICK","XmasPiLights_"); // Nickname
define("IRC_IDENT","lightsbot"); // Ident
define("IRC_REALNAME","lightsbot"); // Realname
define("IRC_CHANNEL","#XmasPiLights"); // Channel
define("IRC_NS_PASS",""); // NickServ password
// Your command to listen for. The bot will listen for the command with or without hash prefix, traditional IRC trigger prefix (!), and space suffix.
define("IRC_COMMAND","xmaspilights");

include("Net_SmartIRC/SmartIRC.php");
include("../textparse/textparse.php");

class IRCBot {
	private $irc;
	private $actionids;
	
	public function __construct($irc) {
		$this->irc = $irc;
		$this->actionids = array(
			$irc->registerActionHandler(SMARTIRC_TYPE_CHANNEL,'^.*',$this,"cmdHandler")
		);
	}
	
	public function cmdHandler($irc,$data) {
		if (substr($data->message,0,1) == "!") {
			if ($data->messageex[0] == "!seconds") {
				if (!$irc->isOpped($data->channel,$data->nick)) return;
				global $secs;
				if ((int)$data->messageex[1] < 1) return;
				$secs = (int)$data->messageex[1];
				return;
			}
			 parseCommand(substr(str_replace(array(IRC_COMMAND." ",IRC_COMMAND),"star",strtolower($data->message)),1));
		} else parseCommand(str_replace(array("#".IRC_COMMAND,"#".IRC_COMMAND." ",IRC_COMMAND." ",IRC_COMMAND),"star",strtolower($data->message)));
	}
}

$irc = new Net_SmartIRC(array(
    'DebugLevel' => SMARTIRC_DEBUG_NONE,
));
$bot = new IRCBot($irc);
while (true) {
	$irc->connect(IRC_SERVER);
	$irc->login(IRC_NICK,IRC_IDENT,0,IRC_REALNAME);
	$irc->message(SMARTIRC_TYPE_QUERY,"nickserv","identify ".IRC_NS_PASS);
	$irc->join(IRC_CHANNEL);
	$irc->listen();
	$irc->disconnect();
}
