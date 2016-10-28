<?php
require_once "./libs.php";
$user = stateUser ();
if (! $user) {
	header ( "Location: ./login.php" );
	exit ();
}
$to_user = false;

if ($_SERVER ["REQUEST_METHOD"] == "POST" && tokencheck ()) {
	if ($_POST ["type"] == 1 && (( int ) $_POST ["to_id"] != $user->id)) {
		$user->sendFriendRequest ( User::getUser ( $_POST ["to_id"] ) );
	} else {
		$user->deleteFriend ( User::getUser ( $_POST ["to_id"] ) );
	}
	$to_user = User::getUser ( $_POST ["to_id"] );
} else {
	$to_user = User::getUser ( $_GET ["id"] );
}

if (! $to_user) {
	header ( "Location: ./index.php" );
	exit ();
}

if ($to_user->id < 1) {
	header ( "Location: ./index.php" );
	exit ();
}

if ($_SERVER ["REQUEST_METHOD"] == "POST" && tokencheck ()) {
	printHeader ( $to_user->loginid );
	if ($_POST ["type"] == 1) {
		echo '  <h1>' . h ( $to_user->loginid ) . ' にフレンド申請をしました。</h1>';
	} else {
		echo '  <h1>' . h ( $to_user->loginid ) . ' をフレンドから外しました。</h1>';
	}

	echo '<p><a href="./userlist.php">ユーザ一覧に戻る</a></p>';
	printFooter ();
	exit ();
}

// JSON形式でデータを返す
$ret = array (
		id => $to_user->id,
		loginid => $to_user->loginid,
		name => $to_user->name,
		gender => $to_user->gender,
		birthday => $to_user->prof,
		is_friend => $user->isFriend ( $to_user ) ? true : false
);

// 共通の友達を探す
$friends = array ();
foreach ( $to_user->getFriends () as $tmp ) {
	if ($user->isFriend ( $tmp ) && $tmp->privilege == 0) {
		$friends [] = array (
				id => $tmp->id,
				loginid => $tmp->loginid,
				name => $tmp->name,
				gender => $tmp->gender
		);
	}
}
$ret ['friends'] = $friends;

// 日記一覧
$diaries = array ();
foreach ( $to_user->getDiaries () as $tmp ) {
    if($tmp->isReadable($user)){
		$diaries [] = array (
			id => $tmp->id,
			title => $tmp->title,
			timestamp => $tmp->timestamp
		);
	}
}
$ret ['diaries'] = $diaries;

header ( "Content-type: application/json; charset=UTF-8" );
echo json_encode ( $ret );
?>
