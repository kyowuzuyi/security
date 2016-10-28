<?php
require_once "./libs.php";
$user = stateUser ();
if (! $user) {
	// ログインしていない場合はログインフォームへリダイレクト
	header ( "Location: ./login.php" );
	exit ();
}

if (isset ( $_POST ["from_id"] )) {
	$fromUser = User::getUser ( $_POST ["from_id"] );
	if ($_POST ["type"] == 1) {
		$user->approvalFrindRequest ( $fromUser );
	} else {
		$user->deleteFriend ( $fromUser );
	}
}

printHeader ();
$lst = $user->getFriendRequestUsers ();

?>
<div id="main">
	<div class="header">
		<h1>Hello, <?php echo h($user->name); ?>!</h1>
	</div>
	<div class="content">
<?php
echo getWelcomeText();
if (count ( $lst ) > 0) {
	?>
    <h2 class="content-subhead">友達申請(<?php print count($lst); ?>件)</h2>
<?php
	foreach ( $lst as $tmp ) {
		?>
    <div class="pure-g">
			<div class="pure-u-1-2">
        <?php print h($tmp->loginid)?>からフレンド申請が来ています。承認しますか？
      </div>
			<div class="pure-u-1-4">
				<div class="pure-g">
					<div class="pure-u-1-2">
						<form method="POST">
							<input type="hidden" name="type" value="1"> <input type="hidden"
								name="from_id" value="<?php print h($tmp->id)?>"> <input
								type="submit" value="承認" class="button-success pure-button">
						</form>
					</div>
					<div class="pure-u-1-2">
						<form method="POST">
							<input type="hidden" name="type" value="0"> <input type="hidden"
								name="from_id" value="<?php print h($tmp->id)?>"> <input
								type="submit" value="拒否" class="button-error pure-button">
						</form>
					</div>
				</div>
			</div>
		</div>
<?php
	}
	?>

            </fieldset>

<?php
}
$diaries = $user->getDiaries ();
$messages = $user->readMessages ();
?>
    <h2 class="content-subhead">書いた記事一覧(<?php print count($diaries); ?>件)</h2>
		<p>
		
		
		<table class="pure-table pure-table-horizontal" style="width: 100%;">
			<thead>
				<tr>
					<th>Title</th>
					<th style="width: 10em;">Timestamp</th>
				</tr>
			</thead>
			<tbody>
<?php

for($i = count ( $diaries ) - 1; $i >= 0; -- $i) {
	?>
        <tr>
					<td><a
						href="./readdiary.php?id=<?php print h($diaries[$i]->id); ?>"
						class="pure-menu-link"><?php print h($diaries[$i]->title=="" ? "(無題)" : $diaries[$i]->title); ?></a></td>
					<td><?php print h($diaries[$i]->timestamp);?></td>
				</tr>
<?php
}
?>
        </tbody>
		</table>
		</p>

		<h2 class="content-subhead">受信メッセージ一覧(<?php print count($messages); ?>件)</h2>
		<p>
		
		
		<table class="pure-table pure-table-horizontal" style="width: 100%;">
			<thead>
				<tr>
					<th>Title</th>
					<th>From</th>
					<th style="width: 10em;">Timestamp</th>
				</tr>
			</thead>
			<tbody>
<?php

for($i = count ( $messages ) - 1; $i >= 0; -- $i) {
	?>
        <tr>
					<td><a href="./read.php?id=<?php print h($messages[$i]->id); ?>"
						class="pure-menu-link"><?php print h($messages[$i]->title); ?></a></td>
					<td><?php print h($messages[$i]->from_user()->name); ?></td>
					<td><?php print h($messages[$i]->timestamp);?></td>
				</tr>
<?php
}
?>
        </tbody>
		</table>
		</p>
	</div>
</div>
<?php
printFooter ();
