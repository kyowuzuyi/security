<?php
require_once "./libs.php";

$user = stateUser ();
if (isset ( $user )) {
	$message = Message::getMessage ( $_GET ["id"] ? $_GET ["id"] : - 1 );
	if ($message) {
		if ($user->id != $message->to_user_id) {
			header ( "Location: ./index.php" );
			exit ();
		}
		$fromuser = $message->from_user ();
		printHeader ( $message->title );
		?>
<div id="main">
	<div class="header">
		<h1><?php echo h($message->title); ?></h1>
	</div>
	<div class="content">

		<div class="pure-g">
			<div class="pure-u-1">
				<h2 class="pure-u-2-1">送信者名:<?php echo h($fromuser->name); ?></h2>
			</div>
			<div class="pure-u-1">
				<pre
					style="border: solid 1px; height: 5em; width: 100%; padding: 1em;">
<?php echo $message->message; ?>
    </pre>
			</div>

<?php if($message->file){ ?>
			<img src="./tmp/<?php print h($message->file); ?>" alt="image">
<?php } ?>
		</div>
		<hr>
<?php    if($user->isFriend($fromuser)){ ?>
    <input type="button" id="reply_button" class="btn4" value="返信">
		<div id="reply_form" style="display: none;">
			<h2>返信フォーム</h2>
			<form method="POST" action="./sendmessage.php"
				class="pure-form pure-form-stacked "
				enctype="multipart/form-data">
				<fieldset>
					<input type="hidden" name="to_id"
						value="<?php echo h($fromuser->id); ?>" /> <label for="title">Title</label>
					<input type="text" name="title" id="title"
						value="Re:<?php echo h($message->title); ?>" size="20" /> <label
						for="message">Message</label>
					<textarea name="message" id="message" rows="5" cols="50"></textarea>
					<label>添付ファイル（.jpgのみ許可）：</label><input type="file" name="file">
					<input type="hidden" name="csrf_token"
						value="<?php echo $_SESSION["csrf_token"]; ?>"> <input
						type="submit" class="btn4" value="送信" />
				</fieldset>
			</form>
		</div>
	</div>
</div>
<script>
$(function(){
	$('#reply_button').click(function(){
		console.log(this);
		$('#reply_form').css('display', 'block');
		$(this).css('display', 'none');
	});
});

</script>
<?php
		}
		printFooter ();
	} else {
		header ( "Location: ./index.php" );
		exit ();
	}
} else {
	header ( "Location: ./login.php" );
	exit ();
}

