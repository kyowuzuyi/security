<?php
class Message {
	public $id;
	public $to_user_id;
	public $from_user_id;
	public $title;
	public $message;
	public $timestamp;
	public function __construct($row = NULL) {
		if (! is_null ( $row )) {
			$this->id = $row ['id'];
			$this->to_user_id = $row ['to_user_id'];
			$this->from_user_id = $row ['from_user_id'];
			$this->title = $row ['title'];
			$this->message = $row ['message'];
			$this->file = $row ['file'];
			$this->timestamp = $row ['timestamp'];
			}
	}
	public function from_user() {
		global $db;
		$statement = $db->query ( "SELECT * FROM users WHERE id={$this->from_user_id}" );
		$row = $statement->fetch ();
		if (isset ( $row )) {
			$user = new User ( $row );
			return $user;
		}
		return null;
	}
	
	/**
	 * 内部IDを指定して、メッセージ情報を取得する。
	 *
	 * @param unknown $id
	 *        	内部のID
	 * @return Message|NULL 存在する場合はMessage、ない場合はnull
	 */
	public static function getMessage($id) {
		global $db;
		$statement = $db->query ( "SELECT * FROM messages WHERE id='{$id}'" );
		$row = $statement->fetch ();
		if (isset ( $row )) {
			$message = new Message ( $row );
			return $message;
		}
		return null;
	}
	public function save() {
		global $db;
		if(!$this->file) $this->file = "";
		if ($this->id) {
			$statement = $db->prepare ( "UPDATE messages SET to_user_id={$this->to_user_id}, from_user_id={$this->from_user_id}, title='{$this->title}', message='{$this->message}', file='{$this->file}' WHERE id={$this->id}" );
			$statement->execute ();
		} else {
			$statement = $db->prepare ( "INSERT INTO messages(to_user_id,from_user_id,title,message,file) VALUES({$this->to_user_id}, {$this->from_user_id}, '{$this->title}', '{$this->message}', '{$this->file}')" );
			$statement->execute ();
			$statement = $db->query ( "SELECT last_insert_id() FROM messages" );
			$row = $statement->fetch ();
			$this->id = $row [0];
		}
		
	}
}