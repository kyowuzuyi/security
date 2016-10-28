<?php
require_once "./libs.php";
$user = stateUser ();
if (! $user) {
	header ( "Location: ./login.php" );
	exit ();
}

printHeader ( "日記書き込み" );
if ($_SERVER ["REQUEST_METHOD"] == "POST" && isset ( $_POST ["mode"] )) {
	if ($_POST ["id"] == "") {
		$diary = new Diary ();
		$diary->write_user_id = $user->id;
	} else {
		$diary = Diary::getDiary ( $_POST ["id"] );
	}
	$diary->title = $_POST ["title"];
	$diary->content = $_POST ["content"];
	$diary->mode = $_POST ["mode"];
	$diary->timestamp = time ();
	$diary->save ();
	?>
<h1>書き込みに成功しました</h1>
<a href="./index.php">戻る</a>
<?php
} else {
	$diary = null;
	if (isset ( $_POST ["id"] )) {
		$diary = Diary::getDiary ( $_POST ["id"] );
	} else {
		$diary = new Diary ();
	}

	?>
<div id="main">
	<div class="header">
		<h1>日記編集</h1>
	</div>
	<div class="content">
		<form class="pure-form" method="POST">
			<input type="hidden" name="id"
				value="<?php if(isset($_POST["id"])) print h($_POST["id"]); ?>"> <input
				type="hidden" name="mode" value="write">

			<fieldset class="pure-group">
				<input type="text" class="pure-input-1-3" name="title"
					placeholder="Title" value="<?php print h($diary->title); ?>">
			</fieldset>
			<fieldset class="pure-group">
				<textarea class="pure-input-1-2" name="content"
					placeholder="Content"><?php print h($diary->content); ?></textarea>
			</fieldset>
			<fieldset class="pure-group">
				<label>公開状態</label> <select name="mode">
					<option value="0" <?php if($diary->mode == 0) print " selected"; ?>>下書き</option>
					<option value="1" <?php if($diary->mode == 1) print " selected"; ?>>フレンドにのみ公開</option>
					<option value="2" <?php if($diary->mode == 2) print " selected"; ?>>公開</option>
				</select>
			</fieldset>
			<fieldset class="pure-group">
				<input type="submit" value="Write"
					class="secondary-button pure-button" />
			</fieldset>
		</form>
	</div>
</div>

<?php
}
printFooter();
?>
