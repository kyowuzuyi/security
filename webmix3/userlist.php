<?php
require_once "./libs.php";
$user = stateUser ();
if (! $user) {
	header ( "Location: ./login.php" );
	exit ();
}
$word="";
if(isset($_GET["name"])){
    //事前にエスケープ
    $word = h($_GET["name"]);
}

$users = User::searchUsers ($word);
$users = array_merge($users, $user->getFriends());
printHeader ( "フレンド" );

?>
<link rel="stylesheet" href="./css/email.css" type="text/css" />
<script>
function getFriend(){
	var friend_id = $(this).attr('friend_id');
	$.getJSON( './user.php', {id:friend_id}, function(data){
		$('#friend_name').text(data.name);
		$('#friend_loginid').text(data.loginid);
		$('#friend_birthday').html("");
		if(data.is_friend){
			$('#friend_birthday').append($("<span>").html(data.birthday));
		}
		$('#common_friends').empty();
		for(var i=0; i<data.friends.length; i++){
			var td = $('<td>').addClass('friends').attr('friend_id',data.friends[i].id).text(data.friends[i].name);
			td.click(getFriend);
			$('#common_friends').append($('<tr>').append($('<img>').attr('height',32).attr('width',32).attr('src','./image/'+data.friends[i].gender+'.png')).append(td));

		}

		$('#friend_diaries').empty();
		for(var i=0; i<data.diaries.length; i++){
			$('#friend_diaries')
				.append($('<tr>')
					.append($('<td>')
						.append($('<a>')
							.attr('href', './readdiary.php?id='+data.diaries[i].id)
							.addClass('pure-menu-link')
							.text(data.diaries[i].title)
						)
					)
					.append($('<td>')
						.text(data.diaries[i].timestamp)
					)
				);
		}
		$('#to_id').attr('value', data.id);
		if(data.is_friend){
			$('#become_friend').css('display', 'none');
			$('#stop_friend').css('display', 'inline');
			$('#type').attr('value', 0);
		} else {
			$('#become_friend').css('display', 'inline');
			$('#stop_friend').css('display', 'none');
			$('#type').attr('value', 1);
		}
	});
}
$(function(){
	$('.friends').click(getFriend);
    //$('.ward').val('<?php print $word; ?>');
});
</script>

<div id="list" class="pure-u-1">
<form method="GET">
    <input type="text" name="name" value="" id="ward">
    <input type="submit" value="Search">
</form>
<?php
foreach ( $users as $tmp ) {
	if ($tmp->privilege == 0) {
		?>
        <div class="email-item pure-g">
		<div class="pure-u">
			<img class="email-avatar" height="64" width="64"
				src="./image/<?php print h($tmp->gender);?>.png">
		</div>

		<div class="pure-u-3-4">
			<h4 class="email-name friends" friend_id="<?php print h($tmp->id);?>">
                  <?php if($user->isFriend($tmp)) print '<span style="color:yellow">★</span>'; ?><?php if($user->id == $tmp->id) print '<span style="color:red">●</span>'; ?>
                  <?php print h($tmp->name);?>
                </h4>
			<h5 class="email-subject"><?php print h($tmp->loginid);?></h5>
		</div>
	</div>

<?php
	}
}
?>
    </div>
<div id="main" class="pure-u-1" style="width: 100%;">
	<div class="email-content">
		<div class="email-content-header pure-g">
			<div class="pure-u-1-2">
				<h1 id="friend_name" class="email-content-title"></h1>
				<p id="friend_loginid" class="email-content-subtitle"></p>
				<p id="friend_birthday" class="email-content-subtitle"></p>
			</div>

			<div class="email-content-controls pure-u-1-2">
				<form method="POST" action="./user.php">
					<input type="hidden" id="type" name="type" value="1"> <input
						type="hidden" id="to_id" name="to_id" value="" /> <input
						type="hidden" name="csrf_token"
						value="<?php echo $_SESSION["csrf_token"]; ?>">
					<button class="secondary-button pure-button" id="stop_friend"
						style="display: none;">フレンドから外す</button>
					<button class="secondary-button pure-button" id="become_friend"
						style="display: none;">フレンドになる</button>
				</form>
			</div>
		</div>

		<div class="email-content-body">
			<h2 class="content-subhead" style="margin-top: 0;">共通のフレンド</h2>
			<table class="pure-table pure-table-horizontal">
				<tbody id="common_friends"></tbody>
			</table>

			<h2 class="content-subhead" style="margin-top: 0;">日記一覧</h2>
			<table class="pure-table pure-table-horizontal" style="width: 100%;">
				<thead>
					<tr>
						<th>Title</th>
						<th style="width: 10em;">Timestamp</th>
					</tr>
				</thead>
				<tbody id="friend_diaries">
<?php if(isset($diaries)){for($i = count ( $diaries ) - 1; $i >= 0; -- $i) { ?>
					<tr>
						<td><a
							href="./readdiary.php?id=<?php print h($diaries[$i]->id); ?>"
							class="pure-menu-link"><?php print h($diaries[$i]->title); ?></a></td>
						<td><?php print date("Y/m/d H:i:s", $diaries[$i]->timestamp);?></td>
					</tr>
<?php }} ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php
printFooter ();
?>
