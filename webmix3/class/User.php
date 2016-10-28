<?php
class User {
	public $id;
	public $loginid;
	public $password;
	public $prof;
	public $privilege;
	public $name;
	public $gender;
	public function __construct($row = null) {
		if (! is_null ( $row )) {
			$this->id = $row ['id'];
			$this->loginid = $row ['loginid'];
			$this->password = $row ['password'];
			$this->prof = $row ['prof'];
			$this->privilege = $row ['privilege'];
			$this->name = $row ['name'];
			$this->gender = $row ['gender'];
		}
	}
	
	/**
	 * 各パラメータを指定して新規ユーザを作成する
	 *
	 * @param string $loginid        	
	 * @param string $password        	
	 * @param string $prof        	
	 * @return User|NULL 作成したユーザ、または作成できなかった場合はnull
	 */
	public static function make($loginid, $password, $prof) {
		global $db;
		$statement = $db->query ( "SELECT * FROM users WHERE loginid='{$loginid}'" );
		if ($statement->rowCount () > 0) {
			return null;
		}
		
		$tmp = new User ();
		$tmp->loginid = $loginid;
		$tmp->password = password_hash ( $password, PASSWORD_DEFAULT );
		$tmp->prof = $prof;
		$tmp->privilege = 0;
		
		$tmp->save ();
		
		return $tmp;
	}
	
	/**
	 * ログインIDとパスワードを受け取り、ログイン試行をする。
	 *
	 * @param string $loginid
	 *        	ログインID
	 * @param string $password
	 *        	パスワード
	 * @return User|NULL ログイン成功した場合はUser、失敗はnull
	 */
	public static function login($loginid, $password) {
		global $db;
		$statement = $db->query ( "SELECT * FROM users WHERE loginid='{$loginid}'" );
		$row = $statement->fetch ();
		if (isset ( $row )) {
			$user = new User ( $row );
			if (password_verify ( $password, $user->password )) {
				return $user;
			}
		}
		return null;
	}
	
	/**
	 * 内部IDを指定して、ユーザ情報を取得する。
	 *
	 * @param unknown $id
	 *        	内部のID
	 * @return User|NULL 存在する場合はUser、しない場合はnull
	 */
	public static function getUser($id) {
		global $db;
		$statement = $db->query ( "SELECT * FROM users WHERE id='{$id}'" );
		$row = $statement->fetch ();
		if (isset ( $row )) {
			$user = new User ( $row );
			return $user;
		}
		return null;
	}
	public function sendMessage($to_user, $title, $text, $file = "") {
		$message = new Message ();
		$message->to_user_id = $to_user->id;
		$message->from_user_id = $this->id;
		$message->title = $title;
		$message->message = $text;
		if ($file != "") {
			$message->file = $file;
		}
		$message->save ();
		return $message->id;
	}
	
	/**
	 * 自分宛に届いたメッセージのリストを返す
	 *
	 * @return Message[] メッセージのリスト
	 */
	public function readMessages() {
		global $db;
		
		$ret = array ();
		
		$statement = $db->query ( "SELECT * FROM messages WHERE to_user_id={$this->id}" );
		while ( $row = $statement->fetch () ) {
			$ret [] = new Message ( $row );
		}
		return $ret;
	}
	public function sendFriendRequest($to_user) {
		if ($this->isFriend ( $to_user )) {
			return false;
		}
		FriendList::makeFriendRequest ( $to_user, $this );
	}
	public function getFriends() {
		$list = FriendList::_getfriends ( $this );
		
		$ret = array ();
		foreach ( $list as $v ) {
			$vect = $v->to_user_id == $this->id ? $v->from_user_id : $v->to_user_id;
			$ret [] = User::getUser ( $vect );
		}
		return $ret;
	}
	public function getFriendRequestUsers() {
		return FriendList::_getFriendRequestUsers ( $this );
	}
	public function approvalFrindRequest($form_user) {
		FriendList::forceFriend ( $this, $form_user );
	}
	/**
	 * $targetと友達かどうか返す
	 *
	 * @param User $target        	
	 * @return boolean
	 */
	public function isFriend($target) {
		return FriendList::_isFriend ( $this, $target ) ? true : false;
	}
	/**
	 * 友達を削除する
	 * @param User $target        	
	 */
	public function deleteFriend($target) {
		FriendList::deleteFriend ( $this, $target );
	}
	/**
	 * 全一般ユーザの情報を取得する
	 * @return User
	 */
	public static function getAllUsers() {
		global $db;
		$statement = $db->query ( "SELECT * FROM users WHERE privilege=0" );
		$ret = array ();
		while ( $row = $statement->fetch () ) {
			$ret [] = new User ( $row );
		}
		return $ret;
	}
	
	/**
	 * ユーザ名を検索する
	 * @return User
	 */
	public static function searchUsers($word) {
		global $db;
		$ret = array ();
		
		if (isset ( $word ) && strlen ( $word ) > 0) {
			$statement = $db->query ( "SELECT * FROM users WHERE privilege=0 AND (name LIKE '%{$word}%' OR loginid LIKE '%{$word}%')" );
			while ( $row = $statement->fetch () ) {
				$ret [] = new User ( $row );
			}
		}
		return $ret;
	}
	
	public function getDiaries() {
		return Diary::_getDiaries ( $this );
	}
	/**
	 * データベースに保存する
	 *
	 */
	public function save() {
		global $db;
		if ($this->id) {
			$statement = $db->prepare ( "UPDATE users SET loginid='{$this->loginid}', password='{$this->password}', prof='{$this->prof}', privilege={$this->privilege}, name='{$this->name}', gender='{$this->gender}' WHERE id={$this->id}" );
			$statement->execute ();
		} else {
			$statement = $db->prepare ( "INSERT INTO users(loginid,password,prof,privilege,name,gender) VALUES('{$this->loginid}', '{$this->password}', '{$this->prof}', {$this->privilege}, '{$this->name}', '{$this->gender}')" );
			$statement->execute ();
			$statement = $db->query ( "SELECT last_insert_id() FROM users" );
			$row = $statement->fetch ();
			$this->id = $row [0];
		}
	}
}
