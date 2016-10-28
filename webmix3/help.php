<?php
require_once "./libs.php";

$user = stateUser ();
printHeader ( "Help" );

?>
<div class="pure-g">
	<div class="pure-u-1-4">
		<ul class="pure-menu-list">
			<li class="pure-menu-heading">Help Menu</li>
			<li class="pure-menu-item"><a
				href="<?php print $_SERVER["PHP_SELF"] ?>#s0" class="pure-menu-link">Step0
					SNS紹介</a></li>
			<li class="pure-menu-item"><a
				href="<?php print $_SERVER["PHP_SELF"] ?>#s1" class="pure-menu-link">Step1
					フレンド作り</a></li>
			<li class="pure-menu-item"><a
				href="<?php print $_SERVER["PHP_SELF"] ?>#s2" class="pure-menu-link">Step2
					メッセージ送信・受信</a></li>
			<li class="pure-menu-item"><a
				href="<?php print $_SERVER["PHP_SELF"] ?>#s3" class="pure-menu-link">Step3
					日記書き込み</a></li>
			<li class="pure-menu-item"><a
				href="<?php print $_SERVER["PHP_SELF"] ?>#s4" class="pure-menu-link">Step4
					その他</a></li>
		</ul>
	</div>
	<div class="pure-u-3-4">
		<h2 id="s0">Step0 SNS紹介</h2>
		<p>
			MBSD SNSはユーザの緩いつながりを促進するためのSNSです。<br /> メッセージや日記といった機能があります。
		</p>

		<h2 id="s1">Step1 フレンド作り</h2>
		<p>
			まずは誰かとフレンドになるところから始めましょう。<br /> <br /> ログイン後のメニューから<a href="./userlist.php">UserList</a>を選んでください。
			既にフレンドがいる場合は、表示されているはずです。その他のユーザを検索するには、検索フォームに名前を入れ検索してください。<br />
			表示されたユーザをクリックするとユーザ詳細情報が表示されます。<br />
			すると、そのユーザとの共通のフレンド一覧や公開されている日記の一覧が見れます。<br />
			気に入ったユーザが居たら、「フレンドになる」ボタンを押してフレンド申請をしましょう。<br /> <span
				style="color: red">そのユーザがフレンド申請を承認してくれたら、</span>二人はフレンドとなります。<br />
			フレンド同士ではメッセージのやり取りが行えたり、限定公開の日記の閲覧などが行えます。<br /> <br /> UserListで黄色い<span
				style="color: yellow">★</span>のついているユーザはフレンドです。<br /> 赤い<span
				style="color: red">●</span>は自分を表します。<br /> <br />
			フレンドになったユーザの詳細ページでは、そのユーザにメッセージを送ったり、ユーザがフレンド限定で公開している日記を閲覧する等が出来ます。<br />
			また、気が変わってフレンドから外したくなった場合もそのユーザの詳細ページから外す処理を行えます。この処理は相手の承認なく行えます。<br />
			間違って外してしまうと再度申請しなければならないので注意してください
		</p>

		<h2 id="s2">Step2 メッセージ送信・受信</h2>
		<p>
			次はメッセージを送ってみましょう。<br /> <br /> メニューから<a href="./sendmessage.php">SendMessage</a>を選び、宛先フレンドを選択します。<br />
			<span style="color: red">なお、メッセージはフレンド同士でしかやり取り出来ません。</span><br />
			まだ誰もフレンドが居ない場合、送信先ユーザ名は何も選べない状態になっていると思います。<br />
			フレンド申請していても、相手が承認してくれるまではメッセージを送れませんので注意してください。<br /> <br />
			しばらくしてユーザが返信をくれると、<a href="./">Home</a>の受信メッセージ一覧にそのメッセージが表示されます。<br />
			クリックして閲覧してみましょう。<br /> もし返信したい場合は、「返信」ボタンを押して表示される返信フォームを使うと便利です。<br />
			<br /> なお、メッセージを送ってからフレンドを解除した場合でも、送ったメッセージ・受け取ったメッセージは消えません。
		</p>


		<h2 id="s3">Step3 日記書き込み</h2>
		<p>
			続いて日記を書いてみましょう。<br /> <br /> メニューから<a href="./writediary.php">WriteDiary</a>を選び、タイトルと内容を入力し、公開状態を選びます。<br />
			公開状態と公開範囲は、以下の通りとなります。
		</p>

		<table class="pure-table pure-table-bordered">
			<tr>
				<td>下書き</td>
				<td><span style="color: red">自分だけしか見れない</span></td>
			</tr>
			<tr>
				<td>フレンドにのみ公開</td>
				<td><span style="color: red">フレンドと自分に見える</span></td>
			</tr>
			<tr>
				<td>公開</td>
				<td>このSNSに登録しているユーザ全てに見える</td>
			</tr>
		</table>

		<p>
			適切な公開範囲を選んだら、Writeボタンを押して書き込みます。<br /> 成功したら<a href="./">Home</a>に戻って書いた記事一覧を確認しましょう。<br />
			先ほど書いた記事が一覧に入っていると思います。<br /> <br />
			クリックすると内容を閲覧できます。編集したい場合は編集を、削除したい場合は削除を押してください。<br /> <span
				style="color: red">一度削除すると元に戻せないので注意してください。</span><br />
		</p>


		<h2 id="s4">Step4 その他</h2>
		<p>
			氏名・性別・誕生日を変更したい場合は、<a href="/changeprofile.php">Profile</a>機能をご利用ください。<br />
			なお誕生日は、<span style="color: red">YYYY/MM/DD以外の書式の誕生日データは受け付けません。</span><br />
			<br /> <br /> MBSD SNSには<span style="color: red">一般ユーザからは見ることが出来ませんが、</span>実は管理ユーザが居ます。<br />
			masterというIDのユーザが管理ユーザです。<br /> この管理ユーザが皆さんにフレンド申請を出したり、<span
				style="color: red">フレンド申請を承認することはありません。</span><br />
			また、一般ユーザにメッセージを送ることもありません。<br /> 運営の名を語るフィッシング詐欺等に注意してください。
		</p>



	</div>
</div>
<?php
printFooter ();
?>
