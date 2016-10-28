<?php
require_once "./libs.php";
$user = stateUser ();
if (! $user) {
	header ( "Location: ./login.php" );
	exit ();
}

printHeader ( "Change Profile" );

if ($_SERVER ["REQUEST_METHOD"] == "POST") {
	if ($_POST ["mode"] == "changeProfile") {
        if (! isset ( $_POST ["name"] ) || !(preg_match('/^(?:[A-Zａ-ｚぁ-んァ-ヶーa-zA-Z0-9０-９、。\n\r]|[\p{Han}][\x{E0100}-\x{E01EF}\x{FE00}-\x{FE02}]?)+$/u',$_POST ["name"] )) ) {
            $errors [] = "名前が入力されていないか、記号が含まれています。";
        }
        // 性別が空の場合はエラー
        if (! isset ( $_POST ["gender"] ) || ($_POST ["gender"] != "M" && $_POST ["gender"] != "F")) {
            $errors [] = "性別を選択してください。";
        }
        if (! preg_match ( "|^[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}$|", $_POST ["birthday"] )) {
            $errors [] = "誕生日は、YYYY/MM/DD形式で入力してください。";
        }
        if(count($errors) == 0){
    		$user->prof = $_POST ["birthday"];
	   	    $user->name = $_POST ["name"];
	        $user->gender = $_POST ["gender"];
		    $user->save ();
?>
<h1>プロフィールを更新しました</h1>
<a href="./changeprofile.php">戻る</a>
<?php
    printFooter();
    exit ();

        } else {
            for($i=0;$i<count($errors);++$i){
?>
<h2><?php print h($errors[$i]); ?></h2>
<?php
            }
        }
	}
}
?>
<script>
  function checknum(){
    var ymd = document.getElementById("birthday").value.match(/^(\d{4})\/(\d{1,2})\/(\d{1,2})$/);
    if(ymd == null){
      alert("誕生日は正しい形式で入力して下さい。");
      return;
    }
    var tr = new Date(ymd[1],ymd[2]-1,ymd[3]);
    if(tr.getFullYear() == ymd[1] && (tr.getMonth()+1) == ymd[2] && tr.getDate() == ymd[3]){
      document.forms[0].submit();
    } else {
      alert("誕生日は正しい形式で入力して下さい。");
      return;
    }
  }

</script>
<div id="main">
	<div class="header">
		<h1>プロフィール変更</h1>
	</div>
	<div class="content">
		<form class="pure-form pure-form-stacked" method="POST">
			<input type="hidden" name="mode" value="changeProfile"
				class="kaetemo imiha nai"> <input type="hidden" name="crsf_tekon"
				value="<?php print sha1(time()); ?>">

			<fieldset>
				<label for="name">名前</label> <input id="name" name="name"
					type="text" placeholder="名前" value="<?php print h($user->name);?>"
					required="required" /> <label for="gender" class="pure-radio">性別</label>
				<input id="gender_M" type="radio" name="gender" value="M"
					<?php if($user->gender=="M"){echo ' checked';} ?>>男 <input
					id="gender_F" type="radio" name="gender" value="F"
					<?php if($user->gender=="F"){echo ' checked';} ?>>女 <label
					for="birthday">誕生日</label> <input id="birthday" name="birthday"
					type="text" placeholder="YYYY/MM/DD"
					value="<?php print h($user->prof);?>" />
		</form>
		<a class="secondary-button pure-button" onclick="checknum()">変更する</a>
	</div>
</div>

<?php
printFooter();
?>


