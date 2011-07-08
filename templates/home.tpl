{include file="header.tpl"}
<body>

{include file="home_header.tpl"}
<div id="container">
	<div class="start">
		<h1>trueRSVP</h1>
		<h2>Make every event a success</h2>
		<form id="create_event_home" name="create_event_home" method="post" action="home">
    	<fieldset>
			<input id="create_event_textarea" name="title" value="I'm planning..." class="inputbox" />
      <p class="btn-med"><input id="event_submit_btn" type="submit" value="Do it!" name=""></p>
      </fieldset>
    </form>
	</div>
	<div class="site-info">
		<div class="site-list">
			<h3>trueRSVP is...</h3>
			<ul>
				<li>a no BS prediction of how many people are actually going to show</li>
				<li>data driven on your end, interactive for the guests</li>
				<li>the new generation of virtual invitations</li>
		</div>
		<div class="site-media">
			<h3>Why use trueRSVP?</h3>
			<div class="video">
				<img src="images/video.jpg" alt="Video" />
			</div>
		</div>
	</div>
</div>
{include file="footer.tpl"}

{include file="global_js.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/home_event.js"></script>

</body>
</html>