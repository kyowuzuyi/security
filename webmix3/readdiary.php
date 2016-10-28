<?php
require_once "./libs.php";

$user = stateUser ();

if (isset ( $user )) {
	$diary = Diary::getDiary ( $_GET ["id"] );
	$writer = $diary->write_user ();
	if ($diary) {
		$title = $diary->title == "" ? "(無題)" : $diary->title;
		
		printHeader ();
		?>

<div id="main">
	<div class="header">
		<h1><?php echo h($title); ?>  -- <?php echo h($diary->write_user()->name); ?>の日記</h1>
		<h2><?php echo $diary->timestamp; ?></h2>
	</div>
	<div class="content">
		<pre class="center">
      <?php echo $diary->content; ?>
    </pre>
	</div>

<?php
		if ($diary->isWritable ( $user )) {
			?>
  <div class="pure-u-1-4">
		<div class="pure-g">
			<div class="pure-u-1-2">
				<form method="POST" action="./writediary.php"
					class="pure-form pure-form-stacked ">
					<input type="hidden" name="id"
						value="<?php echo h($_GET["id"]); ?>"> <input type="submit"
						value="編集する" class="btn2">
				</form>
			</div>
			<div class="pure-u-1-2">
				<form method="POST" action="./deletediary.php"
					class="pure-form pure-form-stacked ">
					<input type="hidden" name="id"
						value="<?php echo h($_GET["id"]); ?>"> <input type="hidden"
						name="csrf_token" value="<?php print $_SESSION["csrf_token"]; ?>">
					<input type="submit" value="削除する" class="btn3" />
				</form>
			</div>
		</div>
	</div>
</div>
<?php
		}
		printFooter ();
		exit ();
	}
}
header ( "Location: ./index.php" );
exit ();
?>
