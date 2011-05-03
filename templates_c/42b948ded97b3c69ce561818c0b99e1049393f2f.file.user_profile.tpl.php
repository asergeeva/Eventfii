<?php /* Smarty version Smarty-3.0.7, created on 2011-05-03 08:59:22
         compiled from "templates/user_profile.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16629141724dbfc3ea8ec9b3-04518612%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '42b948ded97b3c69ce561818c0b99e1049393f2f' => 
    array (
      0 => 'templates/user_profile.tpl',
      1 => 1303531106,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16629141724dbfc3ea8ec9b3-04518612',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<h2><?php echo $_smarty_tpl->getVariable('userInfo')->value['fname'];?>
 <?php echo $_smarty_tpl->getVariable('userInfo')->value['lname'];?>
</h2>