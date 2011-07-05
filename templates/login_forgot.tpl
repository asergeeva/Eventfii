{include file="header.tpl"}

<body>
{include file="home_header.tpl"}
<div id="fb-root"></div>
<div id="container">
	<div id="login_container">
    <h3>Forgot Password</h3>
    <form id="login_forgot_form" action="{$CURHOST}/login/forgot/submit" method="post">
    <table id="forgot_password">
    <tr>
      <th>Email</th>
      <td><input type="text" name="login_forgot_email" id="login_forgot_email" /></td>
    </tr>
    <tr>
    <tr>
      <th></th>
      <td><input type="submit" name="login_forgot_reset" id="login_forgot_reset" value="Reset" /></td>
    </tr>
    </tr>
    </table>
    </form>
	</div>
</div>
{include file="global_js.tpl"}
{include file="footer.tpl"}

{include file="header.tpl" title="Jumpstart your social life"}