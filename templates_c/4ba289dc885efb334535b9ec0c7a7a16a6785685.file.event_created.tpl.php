<?php /* Smarty version Smarty-3.0.7, created on 2011-05-03 08:59:22
         compiled from "templates/event_created.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12880470974dbfc3ea818709-12445174%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4ba289dc885efb334535b9ec0c7a7a16a6785685' => 
    array (
      0 => 'templates/event_created.tpl',
      1 => 1303594594,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12880470974dbfc3ea818709-12445174',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div id="current_events">
<?php  $_smarty_tpl->tpl_vars['event'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('createdEvents')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['event']->key => $_smarty_tpl->tpl_vars['event']->value){
?>
  <div class="event_created_item">
  	<a href="<?php echo $_smarty_tpl->tpl_vars['event']->value['url'];?>
" class="event_created_item_title"><h3><?php echo $_smarty_tpl->tpl_vars['event']->value['title'];?>
</h3></a>
    <p><?php echo $_smarty_tpl->tpl_vars['event']->value['days_left'];?>
 days left</p>
    <a href="#" class="edit_event_created" id="event-<?php echo $_smarty_tpl->tpl_vars['event']->value['id'];?>
">
    	<img src="<?php echo $_smarty_tpl->getVariable('UP_IMG_PATH')->value;?>
/edit.png" rel="#update_event_form_overlay" onclick="CP_EVENT.openUpdateEvent('event-<?php echo $_smarty_tpl->tpl_vars['event']->value['id'];?>
')" />
    </a>
  </div>
<?php }} ?>
</div>