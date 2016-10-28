<?php
class FriendList {
	public $id;
	public $from_user_id;
	public $to_user_id;
	public $state;
	public function __construct($row = null) {
		if (! is_null ( $row )) {
			$this->id = $row ['id'];
			$this->from_user_id = $row ['from_user_id'];
			$this->to_user_id = $row ['to_user_id'];
			$this->state = $row ['state'];
		}
	}
	public static function _getFriendRequestUsers($user) {
		global $db;
		$ret = array ();
		$statement = $db->query ( "SELECT * FROM friends WHERE to_user_id={$user->id} AND state=0" );
		while ( $row = $statement->fetch () ) {
			$ret [] = User::getUser ( $row ['from_user_id'] );
		}
		return $ret;
	}
	public static function _getfriends($user) {
		global $db;
		$ret = array ();
		$statement = $db->query ( "SELECT * FROM friends WHERE state = 1 AND (to_user_id = {$user->id} OR from_user_id = {$user->id})" );
		while ( $row = $statement->fetch () ) {
			$ret [] = new FriendList ( $row );
		}
		return $ret;
	}
	
	/**
	 * $user1と$user2が友達かどうか返す
	 *
	 * @param User $user1        	
	 * @param User $user2        	
	 * @return boolean
	 */
	public static function _isFriend($user1, $user2) {
		global $db;
	$statement = $db->query ( "SELECT id FROM friends WHERE state = 1 AND ((to_user_id = {$user1->id} AND from_user_id = {$user2->id}) OR (to_user_id = {$user2->id} AND from_user_id = {$user1->id}))" );
		return $statement->fetch () ? true : false;
	}
	public static function makeFriendRequest($to_user, $from_user) {
		global $db;
		$statement = $db->query ( "SELECT * FROM friends WHERE to_user_id = {$to_user->id} AND from_user_id = {$from_user->id}" );
		if ($statement->fetch ()) {
			return false;
		}
		$tmp = new FriendList ();
		$tmp->from_user_id = $from_user->id;
		$tmp->to_user_id = $to_user->id;
		$tmp->state = 0;
		$tmp->save ();
		return true;
	}
	public static function forceFriend($to_user, $from_user) {
		global $db;
		$statement = $db->query ( "SELECT * FROM friends WHERE state = 0 AND ((to_user_id = {$to_user->id} AND from_user_id = {$from_user->id}) OR (to_user_id = {$from_user->id} AND from_user_id = {$to_user->id}))" );
		while ( $row = $statement->fetch () ) {
			$tmp = new FriendList ( $row );
			$tmp->state = 1;
			$tmp->save ();
		}
		return true;
	}
	public static function deleteFriend($user1, $user2) {
		global $db;
		$statement = $db->query ( "DELETE FROM friends WHERE (to_user_id = {$user1->id} AND from_user_id = {$user2->id}) OR (to_user_id = {$user2->id} AND from_user_id = {$user1->id})" );
		$statement->execute ();
	}
	public function save() {
		global $db;
		if ($this->id) {
			$statement = $db->prepare ( "UPDATE friends SET from_user_id={$this->from_user_id}, to_user_id={$this->to_user_id}, state={$this->state} WHERE id={$this->id}" );
			$statement->execute ();
		} else {
			$statement = $db->prepare ( "INSERT INTO friends(from_user_id,to_user_id,state) VALUES({$this->from_user_id}, {$this->to_user_id}, {$this->state})" );
			$statement->execute ();
			$statement = $db->query ( "SELECT last_insert_id() FROM friends" );
			$row = $statement->fetch ();
			$this->id = $row [0];
		}
	}
}