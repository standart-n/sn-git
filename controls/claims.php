<?php class claims extends sn {
	
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
					 "Новая заявка на сайте!!!".
					 "");
	break;
	case 2:
		return toWIN("".
					 start::$url->msg.
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

function getUserId() {
	self::$user_id=7; return true;
}

function getPointId() {
	self::$point_id=463; return true;
}

function getProjectId() {
	self::$project_id=27; return true;
}

} ?>
