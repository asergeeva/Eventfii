<?php /* Smarty version Smarty-3.0.7, created on 2011-05-03 08:59:17
         compiled from "templates/login_form.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3050327634dbfc3e50a4bb8-62781076%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5fe0e1967977d30b40ed54949e31711f38e788b8' => 
    array (
      0 => 'templates/login_form.tpl',
      1 => 1303526771,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3050327634dbfc3e50a4bb8-62781076',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div id="login_container">
	<div id="existing_user_login_form">
    <h3>Existing</h3>
    <table>
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
            <td><button name="ef_login_btn" id="ef_login_btn" value="login" onclick="LOGIN_FORM.existingUserLogin()">Login</button></td>
        </tr>
    </table>
    </div>
    
    <div id="new_user_login_form">
    <h3>New User</h3>
    <table>
    	<tr>
        	<th>First name</th>
            <td><input type="text" name="ef_fname" id="ef_fname_new" /></td>
        </tr>
    	<tr>
        	<th>Last name</th>
            <td><input type="text" name="ef_lname" id="ef_lname_new" /></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><input type="text" name="ef_login_email" id="ef_login_email_new" /></td>
        </tr>
        <tr>
            <th>Password</th>
            <td><input type="password" name="ef_login_pass" id="ef_login_pass_new" /></td>
        </tr>
        <tr>
        	<th></th>
            <td><button name="ef_create_user_btn" id="ef_create_user_btn" value="submit" onclick="LOGIN_FORM.newUserLogin()">Create</button></td>
        </tr>
    </table>
    </div>
</div>