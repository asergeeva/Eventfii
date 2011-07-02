<div id="login_container">
	<div id="existing_user_login_form">
      <h3>Existing</h3>
      <table id="regular_login">
          <tr>
              <th>Email</th>
              <td><input type="text" name="ef_login_email_exist" id="ef_login_email_exist" /></td>
          </tr>
          <tr>
              <th>Password</th>
              <td><input type="password" name="ef_login_pass_exist" id="ef_login_pass_exist" /></td>
          </tr>
          <tr>
          	<th></th>
            <td><a href="{$CURHOST}/login/forgot">Forgot your password?</a></td>
          </tr>
          <tr>
            <th></th>
              <td>
              	<button name="ef_login_btn" id="ef_login_btn" value="login" onclick="LOGIN_FORM.existingUserLogin()">Login</button>
              	<fb:login-button perms="email,publish_stream,create_event" id="fb-login-button" onlogin="FBCON.onlogin()">Login with Facebook</fb:login-button>
              </td>
          </tr>
      </table>
    </div>
    
    <div id="new_user_login_form">
    <h3>New User</h3>
    <table>
			<tr>
        <th>First name</th>
        <td><input type="text" name="ef_fname" id="ef_fname_new" value="{$smarty.post.fname}" /></td>
		<td><p><span style="color:red; top:0px; font-weight:bold;">{$user_create_fname}</span></p></td>
      </tr>
      <tr>
        <th>Last name</th>
        <td><input type="text" name="ef_lname" id="ef_lname_new" value="{$smarty.post.lname}" /></td>
		<td><p><span style="color:red; top:0px; font-weight:bold;">{$user_create_lname}</span></p></td>
      </tr>
      <tr>
        <th>Email</th>
        <td><input type="text" name="ef_login_email" id="ef_login_email_new" value="{$smarty.post.email}" /></td>
		<td><p><span style="color:red; top:0px; font-weight:bold;">{$user_create_email}</span></p></td>
      </tr>
      <tr>
        <th>Cell #</th>
        <td><input type="text" name="ef_login_phone_new" id="ef_login_phone_new" value="{$smarty.post.phone}" />
        		XXX-XXX-XXXX</td>
		<td><p><span style="color:red; top:0px; font-weight:bold;">{$user_create_phone}</span></p></td>
      </tr>
      <tr>
        <th>Password</th>
        <td><input type="password" name="ef_login_pass" id="ef_login_pass_new" value="{$smarty.post.pass}" /></td>
		<td><p><span style="color:red; top:0px; font-weight:bold;">{$user_create_pass}</span></p></td>
      </tr>
      <tr>
        <th></th>
        <td><button name="ef_create_user_btn" id="ef_create_user_btn" value="submit" onclick="LOGIN_FORM.newUserLogin()">Create</button></td>
      </tr>
    </table>
    </div>
</div>