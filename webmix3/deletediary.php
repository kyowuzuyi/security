<?php
require_once "./libs.php";
$user = stateUser ();
if (! $user) {
	header ( "Location: ./login.php" );
	exit ();
}

if (isset ( $_POST ["csrf_token"] ) && tokencheck ()) {
	
	$diary = Diary::getDiary ( $_POST ["id"] );
	if (! $diary) {
		header ( "location: ./index.php" );
	}
	printHeader ( "日記削除 - " . $diary->title );
	if ($diary->isWritable ( $user )) {
		$diary->delete ();
		print "\n    <h1>削除しました</h1><br />";
	} else {
		print "\n    <h1>削除に失敗しました</h1><br />";
	}
} else {
	printHeader ( "エラー" );
	print "\n    <h1>不正な画面遷移を検知しました</h1><br />";
}

printFooter ();
