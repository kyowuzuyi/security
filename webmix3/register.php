<?php
require_once "./libs.php";
$user = stateUser ();
if ($user) {
	header ( "Location: ./index.php" );
	exit ();
}
function printRegisterHeader($name = null) {
	$title = $name ? (" - " . $name) : "";
	?>
<!doctype html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="./css/pure.css" type="text/css" />
<link rel="stylesheet" href="./css/marketing.css">
<link rel="stylesheet" href="./css/main.css" />
<script src="./js/ui.js"></script>
<title>MBSD SNS<?php print h($title); ?></title>
</head>
</head>
<body>
	<div class="header">
		<div class="home-menu pure-menu pure-menu-horizontal pure-menu-fixed">
			<a class="pure-menu-heading">MBSD SNS</a>
			<ul class="pure-menu-list">
				<li class="pure-menu-item"><a href="./login.php"
					class="pure-menu-link">Log in</a></li>
				<li class="pure-menu-item"><a href="./register.php"
					class="pure-menu-link">Sign Up</a></li>
			</ul>
		</div>
	</div>
	<div class="content-wrapper" style="top: 0;">
		<div class="content">
			<h2 class="content-head is-center"><?php print h($name); ?></h2>
			<div class="pure-g">
				<div class="l-box-lrg pure-u-1 pure-u-md-2-5"
					style="padding-top: 0;">
<?php
}
function printRegisterFooter() {
	?>
          </div>
			</div>
		</div>
	</div>
</body>
</html>
<?php
}

if ($_SERVER ["REQUEST_METHOD"] == "POST" && isset ( $_POST ["mode"] )) {
	$errors = array ();
	if ($_POST ["mode"] == "commit") {
		$hash = md5 ( $_SESSION ["secret_key"] . ':' . $_POST ["loginid"] . ":" . $_POST ["password"] . ":" . $_POST ["birthday"] );
		if ($hash != $_POST ["hash"]) {
			// ハッシュに異常があった場合
			$errors [] = "不正な操作が行われました。";
		} else {
			// データベースに登録する
			$tmp = new User ();
			$tmp->name = $_POST ["name"];
			$tmp->gender = $_POST ["gender"];
			$tmp->loginid = $_POST ["loginid"];
			$tmp->password = password_hash ( $_POST ["password"], PASSWORD_DEFAULT );
			$tmp->prof = $_POST ["birthday"];
			$tmp->privilege = 0;
			$tmp->save ();

			// 登録完了メッセージを表示
			printRegisterHeader ( "新規登録 - 完了" );
			?>
<h1>新規アカウントを作成しました。</h1>
<form action="./login.php" autocomplete="off">
	<button type="submit" class="pure-button btn1">ログイン</button>
</form>
<?php
			printRegisterFooter ();
			return;
		}
	} else if ($_POST ["mode"] == "confirm") {
		// 入力値のチェックを行う
		// loginidは英数字３文字以上
		if (! preg_match ( "/^[0-9a-zA-Z]{3,}$/", $_POST ["loginid"] )) {
			$errors [] = "loginidは英数字３文字以上で入力してください。";
		}
		// 名前が空か記号が含まれている場合はエラー
		if (! isset ( $_POST ["name"] ) || !(preg_match('/^(?:[A-Zａ-ｚぁ-んァ-ヶーa-zA-Z0-9０-９、。\n\r]|[\p{Han}][\x{E0100}-\x{E01EF}\x{FE00}-\x{FE02}]?)+$/u',$_POST ["name"] )) ) {
			$errors [] = "名前が入力されていないか、記号が含まれています。";
		}
		// 性別が空の場合はエラー
		if (! isset ( $_POST ["gender"] ) || ($_POST ["gender"] != "M" && $_POST ["gender"] != "F")) {
			$errors [] = "性別を選択してください。";
		}
		// パスワードが空の場合はエラー
		if (! isset ( $_POST ["password"] ) || ! isset ( $_POST ["password2"] )) {
			$errors [] = "パスワードが入力されていません。";
		}
		// パスワード１とパスワード２は一致
		if ($_POST ["password"] != $_POST ["password2"]) {
			$errors [] = "パスワードが一致しません。";
		}
		// 誕生日はYYYY/MM/DD形式
		if (! preg_match ( "|^[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}$|", $_POST ["birthday"] )) {
			$errors [] = "誕生日は、YYYY/MM/DD形式で入力してください。";
		}

		if (count ( $errors ) == 0) {
			// 改竄チェックハッシュ用の秘密鍵を作成
			$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';
			$key = '';
			for($i = 0; $i < 32; ++ $i) {
				$key .= $chars [mt_rand ( 0, 61 )];
			}
			// 秘密鍵はセッションに入れておく
			$_SESSION ["secret_key"] = $key;
			// 改ざんチェック用のハッシュを計算
			$hash = md5 ( $key . ':' . $_POST ["loginid"] . ":" . $_POST ["password"] . ":" . $_POST ["birthday"] );

			// 確認画面を表示する
			printRegisterHeader ( "新規登録 - 確認" );
			?>
<form class="pure-form pure-form-aligned" method="POST">
	<fieldset>
		<div class="pure-control-group">
			<label for="name">名前:</label>
			<?php print h($_POST ["name"]) ?> <input type="hidden" name="name"
				value="<?php print h($_POST ["name"]) ?>">
		</div>
		<div class="pure-control-group">
			<label for="gender">性別:</label>
			<?php print h($_POST ["gender"]) ?> <input type="hidden"
				name="gender" value="<?php print h($_POST ["gender"]) ?>">
		</div>
		<div class="pure-control-group">
			<label for="loginid">ログインID:</label>
			<?php print h($_POST ["loginid"]) ?> <input type="hidden"
				name="loginid" value="<?php print h($_POST ["loginid"]) ?>">
		</div>
		<div class="pure-control-group">
			<label for="password">パスワード:</label>
			<?php print h($_POST ["password"]) ?> <input type="hidden"
				name="password" value="<?php print h($_POST ["password"]) ?>">
		</div>
		<div class="pure-control-group">
			<label for="birthday">誕生日:</label>
			<?php print h($_POST ["birthday"]) ?> <input type="hidden"
				name="birthday" value="<?php print h($_POST ["birthday"]) ?>">
		</div>
		<button type="submit" class="pure-button pure-button-primary">送信</button>
		<input type="hidden" name="mode" value="commit"> <input type="hidden"
			name="hash" value="<?php print h($hash); ?>">
	</fieldset>
</form>
<?php
			printRegisterFooter ();
			return;
		}
	}
}

// 登録フォームを表示する
printRegisterHeader ( "新規登録" );
?>
<div class="error">
<?php for($i=0;$i<count($errors);$i++){ ?>
<span class="error"><?php print h($errors[$i]);?></span><br />
<?php } ?>
</div>
<style>
.radio-form {width:auto;}
</style>
<form class="pure-form pure-form-stacked" method="POST">
	<fieldset>
		<label for="name">名前</label> <input id="name" name="name" type="text"
			placeholder="名前" value="<?php print h($_POST ["name"]) ?>"
			required="required" /> <label for="gender" class="pure-radio">性別</label>
		<label for="gender_M" class="pure-radio"><input style="width:auto;" id="gender_M" type="radio" name="gender" value="M">男</label>
		<label for="gender_F" class="pure-radio"> <input style="width:auto;" id="gender_F" type="radio" name="gender" value="F">女</label>
		<label for="loginid">ログインID(英数字３文字以上)</label> <input id="loginid"
			name="loginid" type="text" placeholder="ログインID"
			value="<?php print h($_POST ["loginid"]) ?>" required="required" /> <label
			for="password">パスワード</label> <input id="password" name="password"
			type="password" placeholder="パスワード"
			value="<?php print h($_POST ["password"]) ?>" required="required" />

		<label for="password2">パスワード(再入力)</label> <input id="password2"
			name="password2" type="password" placeholder="パスワード(再入力)"
			value="<?php print h($_POST ["password2"]) ?>" required="required" />

		<label for="birthday">誕生日</label> <input id="birthday" name="birthday"
			type="text" placeholder="YYYY/MM/DD"
			value="<?php print h($_POST ["birthday"]) ?>" />
		<button type="submit" class="pure-button">Sign Up</button>
	</fieldset>
	<input type="hidden" name="mode" value="confirm" />
</form>
<?php
printRegisterFooter ();
?>