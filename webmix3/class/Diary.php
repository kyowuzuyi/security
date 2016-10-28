<?php
class Diary {
	public $id;
	public $write_user_id;
	public $title;
	public $content;
	public $mode;
	public $timestamp;
	public function __construct($row = null) {
		if (! is_null ( $row )) {
			$this->id = $row ['id'];
			$this->write_user_id = $row ['write_user_id'];
			$this->title = $row ['title'];
			$this->content = $row ['content'];
			$this->mode = $row ['mode'];
			$this->timestamp = $row ['timestamp'];
		}
	}
	public function write_user() {
		return User::getUser ( $this->write_user_id );
	}
	public function isReadable($target) {
		$writer = $this->write_user ();
		// 下書き
		if ($this->mode == 0) {
			return $writer->id == $target->id;
		} else if ($this->mode == 1) { // 友達のみ
			return $writer->isFriend ( $target ) || $writer->id == $target->id;
		} else if ($this->mode == 2) { // public
			return true;
		} else {
			return false;
		}
	}
	public function isWritable($target) {
		return $target->id == $this->write_user_id;
	}
	public static function _getDiaries($target) {
		global $db;
		$ret = array ();

		$statement = $db->query ( "SELECT * FROM diaries WHERE write_user_id={$target->id}" );
		while ( $row = $statement->fetch () ) {
			$ret [] = new Diary ( $row );
		}
		return $ret;
	}

	/**
	 * 内部IDを指定して、日記情報を取得する。
	 *
	 * @param unknown $id
	 *        	内部のID
	 * @return Diary|NULL 存在する場合はDiary、しない場合はnull
	 */
	public static function getDiary($id) {
		global $db;
		$statement = $db->query ( "SELECT * FROM diaries WHERE id='{$id}'" );
		$row = $statement->fetch ();
		if (isset ( $row )) {
			$diary = new Diary ( $row );
			return $diary;
		}
		return null;
	}
	public function save() {
		global $db;
		if ($this->id) {
			$statement = $db->prepare ( "UPDATE diaries SET write_user_id={$this->write_user_id}, title='{$this->title}', content='{$this->content}', mode={$this->mode} , timestamp=NOW() WHERE id={$this->id}" );
			$statement->execute ();
		} else {
			$statement = $db->prepare ( "INSERT INTO diaries(write_user_id,title,content,mode) VALUES({$this->write_user_id}, '{$this->title}', '{$this->content}', {$this->mode})" );
			$statement->execute ();
			$statement = $db->query ( "SELECT last_insert_id() FROM diaries" );
			$row = $statement->fetch ();
			$this->id = $row [0];
		}
	}
	public function delete(){
		global $db;
		if($this->id){
			$statement = $db->prepare ( "DELETE FROM diaries WHERE id={$this->id};" );
			$statement->execute();
		}
	}
}
