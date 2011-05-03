<?php /* Smarty version Smarty-3.0.7, created on 2011-05-03 08:59:22
         compiled from "templates/cp_middle.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4014421284dbfc3ea74a1f4-37953515%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5df858f20316a73a71c4980722bb0f03c9724e09' => 
    array (
      0 => 'templates/cp_middle.tpl',
      1 => 1303593278,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4014421284dbfc3ea74a1f4-37953515',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div class="create_event_form_overlay" id="create_event_form_overlay">
	<?php $_template = new Smarty_Internal_Template("create_event_form.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
</div>
<div class="update_event_form_overlay" id="update_event_form_overlay">
	<?php $_template = new Smarty_Internal_Template("update_event_form.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
</div>
<div id="event_created_container">
	<h2>Event created</h2>
	<?php $_template = new Smarty_Internal_Template("event_created.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
</div>
<div id="event_attended_container">
	<h2>Events attending</h2>
	<?php $_template = new Smarty_Internal_Template("event_attending.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
</div>
<div id="user_profile_container">
	<h2>Profile</h2>
  <?php $_template = new Smarty_Internal_Template("user_profile.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
  
  <a href="#"><img src="<?php echo $_smarty_tpl->getVariable('UP_IMG_PATH')->value;?>
/startnew.png" id="create_new_event" rel="#create_event_form_overlay" /></a>
</div>