<?php
require_once "./libs.php";
if (isset ( $_POST ["loginid"] )) {
	$user = User::login ( $_POST ["loginid"], $_POST ["password"] );
	if ($user) {
		session_start ();
		$_SESSION ["user_id"] = $user->id;
		$_SESSION ["csrf_token"] = sha1 ( time () . $user->id );
		header ( "Location: ./index.php" );
		exit ();
	}
}
?>

<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MBSD SNS - Login</title>
<link rel="stylesheet" href="./css/pure.css" />
<link rel="stylesheet" href="./css/marketing.css">
<link rel="stylesheet" href="./css/main.css" />
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

	<div class="splash-container" style="height: 100%; overflow: scroll;">
		<div class="splash" style="top: 0">
			<h1 class="splash-head">MBSD SNS</h1>
			<p class="splash-subhead">
				MBSD SNSへようこそ！<br />ログインするか、右上のメニューから新規登録してください。


			<div class="pure-g">
				<div class="l-box-lrg pure-u-1 pure-u-md-2-5"
					style="padding-top: 0;">
              <?php if(isset($_POST["loginid"])){ ?>
              	<div class="error">
						<span class="error">IDかパスワードが違います</span>
					</div>
              <?php } ?>
              <form class="pure-form pure-form-stacked" method="POST">
						<fieldset>
							<label for="loginid">LoginID</label> <input id="loginid"
								name="loginid" type="text" placeholder="LoginID"
								required="required"> <label for="password">Password</label> <input
								id="password" name="password" type="password"
								placeholder="Password" required="required">
							<button type="submit" class="pure-button btn1">ログイン</button>
						</fieldset>
					</form>
				</div>
			</div>
			</p>
		</div>
	</div>
</body>
</html>
