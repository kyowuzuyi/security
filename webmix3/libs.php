<?php
// DB接続
$db = new PDO('mysql:host=localhost;dbname=webmix3;charset=utf8','webmix3','61cb5b3e0808d74564e3e5aa15b3f48c');
$db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
header ( "X-XSS-Protection: 0" );

require_once dirname ( __FILE__ ) . "/class/class.php";
function h($str, $flags = ENT_COMPAT, $charset = "UTF-8") {
	return htmlspecialchars ( $str, $flags, $charset );
}

/**
 * CSRF対策トークンが正しいかどうかチェックする
 *
 * @return boolean
 */
function tokencheck() {
	return $_POST ["csrf_token"] == $_SESSION ["csrf_token"];
}

/**
 * ログインしているユーザ情報をセッションから取得する
 *
 * @return User|NULL ログイン中のUserまたはnull
 */
function stateUser() {
	session_start ();
	if (! isset ( $_SESSION ["user_id"] )) {
		return null;
	} else {
		$user = User::getUser ( $_SESSION ["user_id"] );
		if (! isset ( $user )) {
			return null;
		}
	}
	return $user;
}
function printHeader($name = null) {
	$title = "MBSD SNS " . ($name ? ("-" . htmlspecialchars ( $name )) : "");
	?>
<!doctype html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="./css/pure.css" type="text/css" />
<link rel="stylesheet" href="./css/side-menu.css" type="text/css" />
<link rel="stylesheet" href="./css/main.css" type="text/css" />
<script src="./js/jquery-3.1.0.min.js"></script>
<script src="./js/ui.js"></script>
<title><?php print h($title); ?></title>
</head>
<body>
	<div id="layout">
		<a href="#menu" id="menuLink" class="menu-link">
			<span></span>
		</a>
		<div id="menu">
			<div class="pure-menu">
				<a class="pure-menu-heading">MBSD SNS</a>
				<ul class="pure-menu-list">
					<li class="pure-menu-item"><a href="./index.php"
						class="pure-menu-link">Home</a></li>
					<li class="pure-menu-item"><a href="./userlist.php"
						class="pure-menu-link">UserList</a></li>
					<li class="pure-menu-item"><a href="./sendmessage.php"
						class="pure-menu-link">SendMessage</a></li>
					<li class="pure-menu-item"><a href="./writediary.php"
						class="pure-menu-link">WriteDiary</a></li>
					<li class="pure-menu-item"><a href="./changeprofile.php"
						class="pure-menu-link">Profile</a></li>
					<li class="pure-menu-item"><a href="./help.php"
						class="pure-menu-link">Help</a></li>
					<li class="pure-menu-item"><a href="./logout.php"
						class="pure-menu-link">Logout</a></li>
				</ul>
			</div>
		</div>
<?php
}
function printFooter() {
	?>
   </div>
</body>
</html>
<?php
}
function getWelcomeText() {
	$message = <<<EOM
<h1>MBSD SNSへようこそ！ </h1>
<br />
メッセージを送信するには、<font color="red">相手ユーザと友達になる</font>必要があります。 <br />
友達で無いユーザとはメッセージできません<br />
友達になるには、相手ユーザの承認が必要です。
<br />
EOM;

	return $message;
}
?>
