<?php class start extends sn {
	
public static $conf;
public static $options;
public static $url;

function __construct() {
	if (self::getControls()) {
		//if (self::getSettings()) {
			//if (self::getOptions()) {
				if (self::getUrl()) {
					switch (self::$url->action){
						case "commit":
							uchet::commit();
						break;
					}
				}
			//}
		//}
	}
}

function getControls() {
	foreach (array("uchet","dt") as $key) {
		if (!file_exists(project."/controls/".$key.".php")) return false;
		require_once(project."/controls/".$key.".php");
		//sn::cl($key);
	}
	return true;	
}

function getSettings() {
	if (!file_exists(project."/conf/main.json")) return false;
	$f=file_get_contents(project."/conf/main.json");
	self::$conf=json_decode($f);
	return true;
}

function getOptions() {
	if (!file_exists(project."/conf/options.json")) return false;
	$f=file_get_contents(project."/conf/options.json");
	self::$options=json_decode($f);
	define("zcom",self::$options->tables->zcom);
	define("upload",self::$options->folders->upload);
	define("publish",self::$options->folders->publish);
	define("packets",self::$options->folders->packets);
	return true;
}

function getUrl() {
	self::$url=new def;
	if (self::checkParams(array("action"))) {
		switch (self::$url->action){
		case "commit":
			return self::checkParams(array("company","project","commit","line"));
		break;
		default: return false;
		}
	}
	return true;
}

function checkParams($ms) {
	foreach ($ms as $key) {
		if (!isset($_REQUEST[$key])) return false;
		self::$url->$key=trim(strval($_REQUEST[$key]));
		if (self::$url->$key=="") return false;
	}
	return true;
}


} ?>
