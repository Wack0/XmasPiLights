<?php
// MySQL details
define("DB_HOST","localhost");
define("DB_USER","");
define("DB_PASS","");
define("DB_NAME","tree");

$fwd_morse = array(
                'A' => '.-',
                'B' => '-...',
                'C' => '-.-.',
                'D' => '-..',
                'E' => '.',
                'F' => '..-.',
                'G' => '--.',
                'H' => '....',
                'I' => '..',
                'J' => '.---',
                'K' => '-.-',
                'L' => '.-..',
                'M' => '--',
                'N' => '-.',
                'O' => '---',
                'P' => '.--.',
                'Q' => '--.-',
                'R' => '.-.',
                'S' => '...',
                'T' => '-',
                'U' => '..-',
                'V' => '...-',
                'W' => '.--',
                'X' => '-..-',
                'Y' => '-.--',
                'Z' => '--..',
                '0' => '-----',
                '1' => '.----',
                '2' => '..---',
                '3' => '...--',
                '4' => '....-',
                '5' => '.....',
                '6' => '-....',
                '7' => '--...',
                '8' => '---..',
                '9' => '----.',
                '.' => '.-.-.-',
                ',' => '--..--',
                '?' => '..--..',
                ':' => '---...',
                "'" => '.----.',
                '"' => '.-..-.',
                '-' => '-....-',
                '/' => '-..-.',
                '(' => '-.--.',
                ')' => '-.--.-'
        );

        $rev_morse = array_flip($fwd_morse);

        function text_to_morse($msg){
                global $fwd_morse;

                $msg = StrToUpper($msg);
                $words = preg_split("/\s+/", $msg);

                $words_out = array();

                foreach($words as $word){

                        $bits = array();

                        for($i=0; $i<strlen($word); $i++){
                                $temp = $fwd_morse[substr($word,$i,1)];
                                if ($temp) $bits[] = $temp;
                        }
                        $words_out[] = implode(' ', $bits);
                }
                return implode(' / ', $words_out);

        }

$lastcmd = 1;
$secs = 5;

function parseCommand($cmd) {
        $cmd = explode(" ",$cmd);
        if (strtolower(substr($cmd[0],0,4)) == "star") {
                $cmd[0] = substr($cmd[0],4);
                while ($cmd[0] == "") {
                        if (count($cmd) == 1) return;
                        array_shift($cmd);
                }
		global $lastcmd;
		global $secs;
		if ($lastcmd > (time()-$secs)) return;
                parseStar($cmd[0]);
		$lastcmd = time();
                return;
        }
}

function parseStar($cmd) {
        if (((int)($cmd) < 1) || (strlen((int)($cmd)) < strlen($cmd)) || ((int)$cmd != 5)) {
                // morsetime!
                //if (isBadWord($cmd)) return;
		if (strlen($cmd) > 15) return;
                doAdd("star",text_to_ener($cmd));
                return;
        }
        // it's a number, flash that number of times.
	$number = mt_rand(1,4);
        doAdd("star",substr(str_repeat($number."1;w500;".$number."0;w500;",(int)($cmd)),0,-1));
}

function text_to_ener($text) {
	$number = mt_rand(1,4);
        return substr(str_replace(array('.','-',' / ',' '),array($number.'1;w100;'.$number.'0;',$number.'1;w500;'.$number.'0;','w1000;','w600;'),text_to_morse($text)),0,-1);
}

function doAdd($name,$codes) {
        $s = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
		if ($s->connect_error) return false;
        if (($name == "") || ($codes == "")) return false;
        $name = $s->real_escape_string($name);
        $codes = explode(';',$codes);
        $now = time();
        // build our sql statement
        $sql = "insert into gates (name,time,codes) values ";
        foreach ($codes as $code) {
                $sql .= "('".$name."',".$now.",'".$s->real_escape_string($code)."'),";
        }
        $sql = substr($sql,0,-1);
        $ret = $s->query($sql);
	$s->query("update counter set hits=hits+1");
		$s->close();
		return $ret;
}
