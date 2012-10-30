<?php class start extends sn {
	
public static $conf;
public static $options;
public static $url;

function __construct() {
	if (self::getControls()) {
		if (self::getUrl()) {
			switch (self::$url->action){
				case "commit":
					git::commit();
				break;
				case "addClaim":
					claims::addClaim();
				break;
			}
		}
	}
}

function getControls() {
	foreach (array("git","claims","dt") as $key) {
		if (!file_exists(project."/controls/".$key.".php")) return false;
		require_once(project."/controls/".$key.".php");
		//sn::cl($key);
	}
	return true;	
}

function getUrl() {
	self::$url=new def;
	if (self::checkParams(array("action"))) {
		switch (self::$url->action){
		case "commit":
			return self::checkParams(array("company","project","line"));
		break;
		case "addClaim":
			return self::checkParams(array("msg"));
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
