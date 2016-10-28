<?php
require_once "./libs.php";
$user = stateUser ();
if (! $user) {
	header ( "Location: ./login.php" );
	exit ();
}

printHeader ( "メッセージ送信" );


if (isset ( $_POST ["to_id"] ) && tokencheck()) {
	$to_user = User::getUser ( $_POST ["to_id"] );
	$transaction = false;
	if ($to_user->id >= 1) {
		if($_FILES["file"]["name"] != ""){
			$prefix = md5(time() . $user->id);
			$tname = $prefix . basename($_FILES["file"]["name"]);
			if (move_uploaded_file($_FILES['file']['tmp_name'], "./tmp/".$tname) && preg_match("/^[^.]+\.jpg$/",$tname)) {
				$transaction = $user->sendMessage ( $to_user, $_POST ["title"], $_POST ["message"],$tname);
			} else {
				$transaction = false;
			}
		} else {
			$transaction = $user->sendMessage ( $to_user, $_POST ["title"], $_POST ["message"]);
		}
	}
	if ($transaction) {
		?>
<h1><?php echo h($to_user->name); ?> にメッセージを送信しました。</h1>
<br />
<a href="./index.php">戻る</a>
<?php
	} else {
		?>
<h1>メール送信に失敗しました。　原因：ユーザが存在しない・フレンドでは無い・JPEG以外のファイルを指定した</h1>
<br />
<a href="./index.php" class="pure-button">戻る</a>
<?php
	}
} else {

	?>
<div id="main">
	<div class="header">
		<h1>メッセージ送信フォーム</h1>
	</div>
	<div class="content">
<span style="color: red">メッセージはフレンド同士でしかやり取り出来ません。</span>
		<form enctype="multipart/form-data"  class="pure-form" method="POST">
			<fieldset class="pure-group">
				<label>宛先フレンド：</label> <select name="to_id">
<?php
$lst = $user->getFriends ();
	foreach ( $lst as $tmp ) {
		print '        <option value="' . h ( $tmp->id ) . '">' . h ( $tmp->name ) . '</option>';
	}
	?>
        </select>
			</fieldset>
			<fieldset class="pure-group">
				<input type="text" class="pure-input-1-3" name="title"
					placeholder="Title">
				<textarea class="pure-input-1-2" name="message"
					placeholder="Message"></textarea>
			</fieldset>
			<fieldset class="pure-group">
				<label>添付ファイル（.jpgのみ許可）：</label><input type="file" name="file">
			</fieldset>
								<input type="hidden" name="csrf_token"
						value="<?php echo $_SESSION["csrf_token"]; ?>">
			<input type="submit" value="Send" class="btn4" />
		</form>
	</div>
</div>
<?php
}
printFooter ();
?>
