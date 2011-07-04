{include file="header.tpl" title="Jumpstart your social life"}

<body>
<div id="fb-root"></div>
<div id="container">
  <div id="header">
	{include file="home_header.tpl"}
  </div>
	<div id="login_forgot">
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
<script type="text/javascript" language="javascript" src="{$JS_PATH}/md5-min.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/fb.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/login.js"></script>
{include file="footer.tpl"}