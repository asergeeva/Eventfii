{include file="header.tpl"}

<body>
{include file="home_header.tpl"}
<div id="fb-root"></div>
<div id="container">
	<div id="login_container">
    <h3>Reset Password</h3>
    <form id="login_forgot_form" action="{$CURHOST}/login/reset/submit"  method="post">
    <input type="hidden" name="login_forgot_ref" value="{$ref}" />
    <div id="login_forgot_error">{$errorMsg}</div>
    <table id="forgot_password">
    <tr>
        <th>New Password:</th>
        <td><input type="password" name="login_forgot_newpass" id="login_forgot_newpass" /></td>
    </tr>
    <tr>
    	<th>Confirm new password:</th>
      <td><input type="password" name="login_forgot_newpass_conf" id="login_forgot_newpass_conf" /></td>
    </tr>
    <tr>
      <th></th>
        <td>
          <button name="login_forgot_reset" id="login_forgot_reset" value="submit">Submit</button>
        </td>
    </tr>
    </table>
    </form>
	</div>
</div>
{include file="global_js.tpl"}
{include file="footer.tpl"}