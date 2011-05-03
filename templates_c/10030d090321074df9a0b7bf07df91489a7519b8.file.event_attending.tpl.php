<?php /* Smarty version Smarty-3.0.7, created on 2011-05-03 08:59:22
         compiled from "templates/event_attending.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1042508984dbfc3ea8b8151-19728327%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '10030d090321074df9a0b7bf07df91489a7519b8' => 
    array (
      0 => 'templates/event_attending.tpl',
      1 => 1303544679,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1042508984dbfc3ea8b8151-19728327',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div id="current_events">
<?php  $_smarty_tpl->tpl_vars['event'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('attendingEvents')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['event']->key => $_smarty_tpl->tpl_vars['event']->value){
?>
  <div class="event_created_item">
  	<a href="<?php echo $_smarty_tpl->tpl_vars['event']->value['url'];?>
" class="event_created_item_title"><h3><?php echo $_smarty_tpl->tpl_vars['event']->value['title'];?>
</h3></a>
    <?php echo $_smarty_tpl->tpl_vars['event']->value['days_left'];?>
 days left
  </div>
<?php }} ?>
</div>