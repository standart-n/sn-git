<?php class uchet extends sn {
	
public static $status;
public static $uchet_id;
public static $sql;
public static $project_id;
public static $point_id;

function __construct() {
		
}

function commit() {
	if (self::genUchetId()) {
		if (self::insertIntoUchet()) {
			if (self::addUserIntoMessage()) {
				if (self::updateTextOfMessage(1)) {
					if (self::updateTextOfMessage(2)) {
						return true;
					}
				}
			}
		}
	}
	return false;
}

function addMessage() {
	if (self::genUchetId()) {
		if (self::insertIntoUchet()) {
			if (self::addUserIntoMessage()) {
				if (self::updateTextOfMessage(1)) {
					if (self::updateTextOfMessage(2)) {
						return true;
					}
				}
			}
		}
	}
	return false;
}


function updateTextOfMessage($id=1) {
	if (query(array(
		"sql"=>"update messages m ".
				"set m.answer='".self::getAnswerAfterBuildPacket($id)."' ".
				"where m.uchet_id=".self::$uchet_id." and m.type_m=".$id.";",
		"connection"=>"spacepro"
		)))
	{
		return true;
	}
	return false;	
}

function getAnswerAfterBuildPacket($id=1) { $rg="";
	switch ($id) {
	case 1:
		return toWIN("Общий Заказ: пакет ".
						"для ".$rg." ".
					"");
	break;
	case 2:
		return toWIN("№".packets::$newPacket->packet.", ".
					 "id:".packets::$newPacket->id.", ".
					 "http://oz.st-n.ru/".packets::$zipName.
					 "");
	break;
	}
}


function addUserIntoMessage() {
	if (query(array(
		"sql"=>"insert into uchet_detail_send ".
				"(uchet_id, user_id) values ".
				"(".self::$uchet_id.",18)"
		)))
	{
		return true;
	}
	return false;	
}

function insertIntoUchet() {
	if (query(array(
		"sql"=>"insert into uchet ".
				"(id,point_id,session_id,status_id,user_id,project_id) values ".
				"(".self::$uchet_id.",463,0,1,18,8)"
		)))
	{
		return true;
	}
	return false;	
}

function genUchetId() {
	if (query(array(
		"sql"=>"select gen_id(GEN_UCHET_ID,1) as ID  from rdb\$database"
		),$ms))
	{
		foreach ($ms as $r) {
			if (isset($r->ID)) {
				self::$uchet_id=$r->ID;
				return true;
			}
		}
	}
	return false;
}

function getUserId() {
	switch (self::$url->company) {
		case "standart-n": self::$point_id=463; break;
		default: self::$point_id=463;
	}	
}

function getPointId() {
	switch (start::$url->company) {
		case "standart-n": self::$point_id=463; break;
		default: self::$point_id=463;
	}	
}

} ?>
