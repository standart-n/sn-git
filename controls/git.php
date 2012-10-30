<?php class git extends sn {
	
public static $status;
public static $uchet_id;
public static $sql;
public static $project_id;
public static $point_id;
public static $user_id;
public static $user_email;
public static $user;
public static $commit_hash;
public static $commit_time;
public static $commit_comment;
public static $parent_hash;

function __construct() {
		
}

function commit() {
	if (self::parseLine()) {
		if (self::getProjectId()) {
			if (self::getUserId()) {
				if (self::getPointId()) {
			
					if (self::genUchetId()) {
						if (self::insertIntoUchet()) {
							if (self::addUserIntoMessage()) {
								if (self::updateTextOfMessage(1)) {
									if (self::updateTextOfMessage(2)) {
										echo "запись успешно добавлена";
									} else { echo "не удалось заполнить текст решения"; }
								} else { echo "не удалось заполнить текст проблемы"; }
							} else { echo "не удалось вставить первоначальное сообщение"; }
						} else { echo "не удалось вставить первоначальную запись"; }
					} else { echo "не сгенерирован id сообщения"; }
				
				} else { echo "не найден id компании"; }
			} else { echo "не найден id пользователя"; }
		} else { echo "не найден id проекта"; }
	} else { echo "некорректные данные"; }
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
				"where m.uchet_id=".self::$uchet_id." and m.type_m=".$id.";"
		)))
	{
		return true;
	}
	return false;	
}

function getAnswerAfterBuildPacket($id=1) { $rg="";
	switch ($id) {
	case 1:
		return toWIN("".
					 self::$commit_comment.
					 "");
	break;
	case 2:
		return toWIN("".
					 "https://github.com/".
					 "standart-n"."/".
					 start::$url->project."/".
					 "commit"."/".
					 self::$commit_hash.
					 "");
	break;
	}
}


function addUserIntoMessage() {
	if (query(array(
		"sql"=>"insert into uchet_detail_send ".
				"(uchet_id, user_id) values ".
				"(".self::$uchet_id.",".self::$user_id.")"
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
				"(".self::$uchet_id.",".self::$point_id.",0,1,".self::$user_id.",".self::$project_id.")"
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

function parseLine() {
	$line_ms=explode("\t",start::$url->line);
	if (sizeof($line_ms)>0) {
		$line_info_ms=explode(" ",$line_ms[0]);
		if (sizeof($line_info_ms)>4) {
			self::$parent_hash=$line_info_ms[0];
			self::$commit_hash=$line_info_ms[1];
			self::$user=$line_info_ms[2];
			self::$user_email=$line_info_ms[3];
			self::$commit_time=$line_info_ms[4];
		}
		self::$commit_comment=str_replace("commit: ","",$line_ms[1]);
		if (self::$commit_comment!="") { return true; }
	}
	return false;
}

function getUserId() {
	switch (self::$user) {
		case "aleksnick": self::$user_id=18; return true; break;
	}
	return false;
}

function getPointId() {
	switch (start::$url->company) {
		case "standart-n": self::$point_id=463; return true; break;
		case "mauric": self::$point_id=631; return true; break;
		case "micro": self::$point_id=682; return true; break;
		case "072": self::$point_id=746; return true; break;
		case "maestro": self::$point_id=605; return true; break;
	}
	return false;
}

function getProjectId() {
	switch (start::$url->project) {
		case "sn-oz-client": self::$project_id=9; return true; break;
		case "sn-oz-upload": self::$project_id=10; return true; break;
		case "sn-oz-server": self::$project_id=11; return true; break;
		
		case "micro-api": self::$project_id=12; return true; break;
		case "micro-calls": self::$project_id=13; return true; break;
		case "micro-monitor": self::$project_id=14; return true; break;		

		case "mau-lite": self::$project_id=15; return true; break;
		case "mau-site": self::$project_id=16; return true; break;
		case "mau-buildings": self::$project_id=17; return true; break;
		case "mau-maps": self::$project_id=18; return true; break;
		case "mau-upload": self::$project_id=19; return true; break;
		case "mau-export": self::$project_id=20; return true; break;
		case "mau-reports": self::$project_id=21; return true; break;

		case "shop-maestro": self::$project_id=22; return true; break;

		case "sn-system": self::$project_id=23; return true; break;
		case "sn-project": self::$project_id=24; return true; break;
		case "sn-git": self::$project_id=25; return true; break;
		case "sn-cashier": self::$project_id=26; return true; break;
		case "sn-claims": self::$project_id=27; return true; break;
	}
	return false;
}

} ?>
