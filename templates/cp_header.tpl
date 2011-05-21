<div id="logo_container"><a href="{$CURHOST}"><img src="{$IMG_PATH}/logosmall.png" id="ef_logo" /></a></div>
<div id="profile_container">
	<h3 id="current_user">Welcome, <a href="{$CURHOST}/user/{$currentUser['id']}" id="user-{$userInfo['id']}">{$currentUser['fname']}</a></h3>
  <ul class="top_nav">
  	<li><a href="{$CURHOST}/logout" id="signoff_link">Sign off</a></li>
  </ul>
</div>