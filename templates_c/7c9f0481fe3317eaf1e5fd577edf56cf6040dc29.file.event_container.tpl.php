<?php /* Smarty version Smarty-3.0.7, created on 2011-05-03 09:05:52
         compiled from "templates/event_container.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8545265184dbfc570a99807-94774654%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7c9f0481fe3317eaf1e5fd577edf56cf6040dc29' => 
    array (
      0 => 'templates/event_container.tpl',
      1 => 1303491268,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8545265184dbfc570a99807-94774654',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div id="cp_container">
  <div id="header">
  <?php $_template = new Smarty_Internal_Template("cp_header.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
  </div>
  <div id="middle">
  <?php $_template = new Smarty_Internal_Template("event_middle.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
  </div>
  <div id="footer"></div>
</div>