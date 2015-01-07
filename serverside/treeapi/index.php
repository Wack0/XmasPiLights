<?php
// MySQL details
define("DB_HOST","localhost");
define("DB_USER","");
define("DB_PASS","");
define("DB_NAME","tree");

function notEmpty($array,$key) {
        if (!array_key_exists($key,$array)) return false;
        if ($array[$key] == "") return false;
        return true;
}

//function TreeAPI_info() { phpinfo();die(); }

function TreeAPI_get() {
        global $s;
        if (!notEmpty($_REQUEST,"name")) die();
        $name = $s->real_escape_string($_REQUEST['name']);
        $now = time();
        $r = $s->query("select group_concat(codes separator ';') as output from gates where name='".$name.
                "' and time < ".$now);
        if (!$r) die();
        while ($res = $r->fetch_object()) echo $res->output;
        $s->query("delete from gates where name='".$name."' and time < ".$now);
        die();
}

function TreeAPI_add() {
        global $s;
        if (!notEmpty($_REQUEST,"name")) die();
        if (!notEmpty($_REQUEST,"codes")) die();
        $name = $s->real_escape_string($_REQUEST['name']);
        $codes = explode(';',$_REQUEST['codes']);
        $now = time();
        // build our sql statement
        $sql = "insert into gates (name,time,codes) values ";
        foreach ($codes as $code) {
                $sql .= "('".$name."',".$now.",'".$s->real_escape_string($code)."'),";
        }
        $sql = substr($sql,0,-1);
        die((string)((int)($s->query($sql))));
}

$api = explode('?',$_SERVER['REQUEST_URI']);
$api = explode('/',$api[0]);
if (end($api) == "") array_pop($api);
reset($api);
$api = 'TreeAPI_'.strtolower(end($api));

if (!function_exists($api)) die();

$s = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
if ($s->connect_error) die();

$api();
