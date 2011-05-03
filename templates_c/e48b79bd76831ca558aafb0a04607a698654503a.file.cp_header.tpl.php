<?php /* Smarty version Smarty-3.0.7, created on 2011-05-03 08:59:22
         compiled from "templates/cp_header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17844563434dbfc3ea717687-97847687%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e48b79bd76831ca558aafb0a04607a698654503a' => 
    array (
      0 => 'templates/cp_header.tpl',
      1 => 1303544885,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17844563434dbfc3ea717687-97847687',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div id="logo_container"><a href="<?php echo $_smarty_tpl->getVariable('CURHOST')->value;?>
"><img src="<?php echo $_smarty_tpl->getVariable('IMG_PATH')->value;?>
/logosmall.png" id="ef_logo" /></a></div>
<div id="profile_container">
	<h3 id="current_user">Welcome, <a href="#" id="user-<?php echo $_smarty_tpl->getVariable('userInfo')->value['id'];?>
"><?php echo $_smarty_tpl->getVariable('userInfo')->value['fname'];?>
</a></h3>
  <ul class="top_nav">
  	<li><a href="<?php echo $_smarty_tpl->getVariable('CURHOST')->value;?>
/logout" id="signoff_link">Sign off</a></li>
  </ul>
</div>